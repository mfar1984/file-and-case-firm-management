<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        // Get user count for each role
        $roleUserCounts = [];
        foreach ($roles as $role) {
            $roleUserCounts[$role->id] = User::role($role->name)->count();
        }
        
        return view('settings.role', compact('roles', 'permissions', 'roleUserCounts'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('settings.role-create', compact('permissions'));
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $roleUserCount = User::role($role->name)->count();
        $users = User::role($role->name)->take(10)->get();
        
        return view('settings.role-show', compact('role', 'roleUserCount', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            // Log role creation
            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'role_name' => $role->name,
                    'permissions_count' => $request->has('permissions') ? count($request->permissions) : 0
                ])
                ->log("Role {$role->name} created");

            DB::commit();

            return redirect()->route('settings.role')
                ->with('success', 'Role created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.role')
                ->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        
        return view('settings.role-edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            return redirect()->route('settings.role')
                ->with('success', 'Role updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.role')
                ->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Check if role is assigned to any users
        $userCount = User::role($role->name)->count();
        
        if ($userCount > 0) {
            return redirect()->route('settings.role')
                ->with('error', "Cannot delete role. It is assigned to {$userCount} user(s).");
        }

        try {
            // Log role deletion before deleting
            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'role_name' => $role->name,
                    'description' => $role->description
                ])
                ->log("Role {$role->name} deleted");

            $role->delete();
            return redirect()->route('settings.role')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.role')
                ->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    public function getPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function getRoleUsers($roleId)
    {
        $role = Role::findOrFail($roleId);
        $users = User::role($role->name)->get(['id', 'name', 'email']);
        
        return response()->json($users);
    }
} 