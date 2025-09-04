<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        
        return view('settings.user', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        
        return view('settings.user-create', compact('roles'));
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
                'email_verified_at' => $request->has('email_verified') ? now() : null,
            ]);

            if ($request->has('roles')) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->assignRole($roles);
            }

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
        
        return view('settings.user-edit', compact('user', 'roles'));
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
                    
                    // Send email
                    \Mail::send('emails.password-reset', [
                        'user' => $user,
                        'newPassword' => $newPassword
                    ], function($message) use ($user) {
                        $message->to($user->email, $user->name)
                                ->subject('Password Reset - Naeelah Firm');
                    });
                    
                    $message = 'Password reset successfully. New password has been sent to user\'s email.';
                } catch (\Exception $e) {
                    \Log::error('Failed to send password reset email: ' . $e->getMessage());
                    $message = 'Password reset successfully. New password: ' . $newPassword . ' (Email notification failed)';
                }
            } else {
                $message = 'Password reset successfully. New password: ' . $newPassword . ' (Email not configured)';
            }
            
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
