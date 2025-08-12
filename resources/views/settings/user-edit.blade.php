@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management > Edit User
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-lg font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit User</h1>
                            <p class="text-xs text-gray-500 mt-1">{{ $user->name }} ({{ $user->email }})</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form action="{{ route('settings.user.update', $user->id) }}" method="POST" class="space-y-0">
                @csrf
                @method('PUT')
                
                <!-- User Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">User Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" required 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., Ahmad Firm, Siti Partner">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" required 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., ahmad@firm.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" 
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., +60 12-345 6789">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department" 
                                   value="{{ old('department', $user->department ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., Legal, HR, Finance">
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Password Change</h3>
                    <p class="text-xs text-gray-600 mb-4">Leave blank if you don't want to change the password.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Re-enter new password">
                        </div>
                    </div>
                </div>

                <!-- Role Assignment Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Role Assignment</h3>
                    <p class="text-xs text-gray-600 mb-4">Select the roles this user should have:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   id="role_{{ $role->id }}"
                                   {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="role_{{ $role->id }}" class="ml-2 text-xs text-gray-700">
                                <span class="font-medium">{{ $role->name }}</span>
                                @if($role->description)
                                    <br><span class="text-gray-500 text-xs">{{ $role->description }}</span>
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    @error('roles')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Settings Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Account Settings</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="email_verified" value="1" 
                                   id="email_verified"
                                   {{ old('email_verified', $user->email_verified_at ? '1' : '') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="email_verified" class="ml-2 text-xs text-gray-700">
                                Mark email as verified (user can login immediately)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="force_password_change" value="1" 
                                   id="force_password_change"
                                   {{ old('force_password_change') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="force_password_change" class="ml-2 text-xs text-gray-700">
                                Force password change on next login
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" 
                                   id="is_active"
                                   {{ old('is_active', '1') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="ml-2 text-xs text-gray-700">
                                User account is active
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Additional Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Additional notes about this user...">{{ old('notes', $user->notes ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Current User Information -->
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-700 mb-4">Current User Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div>
                            <span class="text-blue-600">Created:</span>
                            <div class="text-blue-800">{{ $user->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Last Updated:</span>
                            <div class="text-blue-800">{{ $user->updated_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Last Login:</span>
                            <div class="text-blue-800">
                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Never' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('settings.user.show', $user->id) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 