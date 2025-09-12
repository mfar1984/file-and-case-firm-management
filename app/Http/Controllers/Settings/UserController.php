<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Firm;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Super Administrator can see all users, others see only their firm's users
        if (auth()->user()->hasRole('Super Administrator')) {
            $users = User::with(['roles', 'firm'])->get();
        } else {
            // Regular users see only their firm's users
            $firmId = session('current_firm_id') ?? auth()->user()->firm_id;
            $users = User::where('firm_id', $firmId)->with(['roles', 'firm'])->get();
        }

        // Get all firms for filtering (Super Administrator only)
        $firms = auth()->user()->hasRole('Super Administrator')
            ? Firm::orderBy('name')->get()
            : collect();

        return view('settings.user', compact('users', 'firms'));
    }

    public function create()
    {
        $roles = Role::all();

        // Get all firms for Super Administrator, current firm for others
        if (auth()->user()->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firms = collect([auth()->user()->firm]);
        }

        return view('settings.user-create', compact('roles', 'firms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'firm_id' => 'required|exists:firms,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'department' => $request->department,
                'notes' => $request->notes,
                'firm_id' => $request->firm_id,
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ]);

            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->assignRole($roles);
            }

            // Log user creation
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'username' => $user->username,
                    'email' => $user->email,
                    'firm_id' => $user->firm_id,
                    'roles_count' => $request->has('roles') ? count($request->roles) : 0
                ])
                ->log("User {$user->username} created");

            DB::commit();

            return redirect()->route('settings.user')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.user.create')
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::with('roles.permissions')->findOrFail($id);
        
        return view('settings.user-show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        // Get all firms for Super Administrator, current firm for others
        if (auth()->user()->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firms = collect([auth()->user()->firm]);
        }

        return view('settings.user-edit', compact('user', 'roles', 'firms'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
            'firm_id' => 'required|exists:firms,id',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'department' => $request->department,
                'notes' => $request->notes,
                'firm_id' => $request->firm_id,
            ];

            // Handle password update
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Handle email verification
            if ($request->has('email_verified') && !$user->email_verified_at) {
                $userData['email_verified_at'] = now();
            } elseif (!$request->has('email_verified') && $user->email_verified_at) {
                $userData['email_verified_at'] = null;
            }

            $user->update($userData);

            // Handle role assignment
            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }

            // Log user update
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'username' => $user->username,
                    'email' => $user->email,
                    'firm_id' => $user->firm_id,
                    'roles_count' => $request->has('roles') ? count($request->roles) : 0
                ])
                ->log("User {$user->username} updated");

            DB::commit();

            return redirect()->route('settings.user.show', $user->id)
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.user.edit', $user->id)
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deletion of admin user
        if ($user->hasRole('Administrator')) {
            return redirect()->route('settings.user')
                ->with('error', 'Cannot delete Administrator user.');
        }

        try {
            DB::beginTransaction();

            // Remove all roles
            $user->syncRoles([]);
            
            // Log user deletion
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'username' => $user->username,
                    'email' => $user->email,
                    'firm_id' => $user->firm_id
                ])
                ->log("User {$user->username} deleted");

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('settings.user')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('settings.user')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        try {
            // Generate new password
            $newPassword = \Illuminate\Support\Str::random(10);
            
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            // Send email with new password if email is configured
            if (\App\Services\EmailConfigurationService::isEmailConfigured()) {
                try {
                    // Configure email settings
                    \App\Services\EmailConfigurationService::configureEmailSettings();
                    
                    // Get firm name for email
                    $firmId = session('current_firm_id') ?? auth()->user()->firm_id;
                    $firm = \App\Models\Firm::find($firmId);
                    $firmName = $firm ? $firm->name : 'Naeelah Firm';

                    // Send email
                    \Mail::send('emails.password-reset', [
                        'user' => $user,
                        'newPassword' => $newPassword,
                        'firmName' => $firmName
                    ], function($message) use ($user, $firmName) {
                        $message->to($user->email, $user->name)
                                ->subject('Password Reset - ' . $firmName);
                    });
                    
                    $message = 'Password reset successfully. New password has been sent to user\'s email.';
                } catch (\Exception $e) {
                    \Log::error('Failed to send password reset email: ' . $e->getMessage());
                    $message = 'Password reset successfully. New password: ' . $newPassword . ' (Email notification failed)';
                }
            } else {
                $message = 'Password reset successfully. New password: ' . $newPassword . ' (Email not configured)';
            }

            // Log password reset
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'username' => $user->username,
                    'email' => $user->email
                ])
                ->log("Password reset for user {$user->username}");

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_password' => $newPassword
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmail($id)
    {
        $user = User::findOrFail($id);
        
        try {
            $user->update([
                'email_verified_at' => now()
            ]);

            // Log email verification
            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'username' => $user->username,
                    'email' => $user->email
                ])
                ->log("Email verified for user {$user->username}");

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify email: ' . $e->getMessage()
            ], 500);
        }
    }
}
