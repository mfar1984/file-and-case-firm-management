<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Firm;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Super Administrator can see all roles, others see only their firm's roles
        if ($user->hasRole('Super Administrator')) {
            $roles = Role::with('permissions')->get();
        } else {
            // Get roles for current firm context
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $roles = Role::where('firm_id', $firmId)->with('permissions')->get();
        }

        $permissions = Permission::all();

        // Get user count for each role
        $roleUserCounts = [];
        foreach ($roles as $role) {
            $roleUserCounts[$role->id] = User::role($role->name)->count();
        }

        // Get all firms for filtering (Super Administrator only)
        $firms = $user->hasRole('Super Administrator')
            ? Firm::orderBy('name')->get()
            : collect();

        return view('settings.role', compact('roles', 'permissions', 'roleUserCounts', 'firms'));
    }

    public function create()
    {
        $permissions = Permission::all();

        // Get all firms for Super Administrator, current firm for others
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        return view('settings.role-create', compact('permissions', 'firms'));
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $roleUserCount = User::role($role->name)->count();
        $users = User::role($role->name)->take(10)->get();

        // Get firm information if role has firm_id
        $firm = null;
        if ($role->firm_id) {
            $firm = Firm::find($role->firm_id);
        }

        return view('settings.role-show', compact('role', 'roleUserCount', 'users', 'firm'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'firm_id' => $user->hasRole('Super Administrator') ? 'required|exists:firms,id' : 'nullable'
        ]);

        // Determine firm ID
        $firmId = null;
        if ($user->hasRole('Super Administrator')) {
            $firmId = $request->firm_id;
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
        }

        // Check for unique role name within firm context
        $existingRole = Role::where('name', $request->name)
            ->where('firm_id', $firmId)
            ->first();

        if ($existingRole) {
            return back()->withErrors(['name' => 'Role name already exists for this firm.']);
        }

        try {
            DB::beginTransaction();

            // Set firm context for role creation
            if ($firmId) {
                setPermissionsTeamId($firmId);
            }

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'firm_id' => $firmId,
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
                    'firm_id' => $firmId,
                    'permissions_count' => $request->has('permissions') ? count($request->permissions) : 0
                ])
                ->log("Role {$role->name} created for firm");

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

        // Get all firms for Super Administrator, current firm for others
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        return view('settings.role-edit', compact('role', 'permissions', 'firms'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
            'firm_id' => $user->hasRole('Super Administrator') ? 'required|exists:firms,id' : 'nullable'
        ]);

        // Determine firm ID
        $firmId = null;
        if ($user->hasRole('Super Administrator')) {
            $firmId = $request->firm_id;
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
        }

        // Check for unique role name within firm context (excluding current role)
        $existingRole = Role::where('name', $request->name)
            ->where('firm_id', $firmId)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRole) {
            return back()->withErrors(['name' => 'Role name already exists for this firm.']);
        }

        try {
            DB::beginTransaction();

            // Set firm context for role update
            if ($firmId) {
                setPermissionsTeamId($firmId);
            }

            $role->update([
                'name' => $request->name,
                'description' => $request->description,
                'firm_id' => $firmId,
            ]);

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            // Log role update
            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'role_name' => $role->name,
                    'firm_id' => $firmId,
                    'permissions_count' => $request->has('permissions') ? count($request->permissions) : 0
                ])
                ->log("Role {$role->name} updated");

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
        $user = auth()->user();

        // Check if user has permission to delete this role
        if (!$user->hasRole('Super Administrator') && $role->firm_id !== ($user->firm_id ?? session('current_firm_id'))) {
            abort(403, 'Unauthorized to delete this role.');
        }

        // Check if role is assigned to any users
        $userCount = User::role($role->name)->count();

        if ($userCount > 0) {
            return redirect()->route('settings.role')
                ->with('error', "Cannot delete role. It is assigned to {$userCount} user(s).");
        }

        try {
            // Set firm context for role deletion
            if ($role->firm_id) {
                setPermissionsTeamId($role->firm_id);
            }

            // Log role deletion before deleting
            activity()
                ->performedOn($role)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'role_name' => $role->name,
                    'firm_id' => $role->firm_id,
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