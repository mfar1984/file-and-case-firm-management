@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management > Add New User
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">person_add</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Add New User</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Create a new user account with specific roles and permissions.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form action="{{ route('settings.user.store') }}" method="POST" class="space-y-0">
                @csrf
                
                <!-- User Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">person</span>
                        User Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the basic user account details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., Ahmad Firm, Siti Partner">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Username *</label>
                            <input type="text" name="username" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., ahmad_firm, siti_partner">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., ahmad@firm.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Password *</label>
                            <input type="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Role Assignment Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">admin_panel_settings</span>
                        Role Assignment
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Select the roles this user should have</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   id="role_{{ $role->id }}"
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

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Account Settings Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-orange-600 text-base mr-2">settings</span>
                        Account Settings
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Configure account preferences and notifications</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="email_verified" value="1" 
                                   id="email_verified"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="email_verified" class="ml-2 text-xs text-gray-700">
                                Mark email as verified (user can login immediately)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="send_welcome_email" value="1" 
                                   id="send_welcome_email" checked
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="send_welcome_email" class="ml-2 text-xs text-gray-700">
                                Send welcome email with login credentials
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="force_password_change" value="1" 
                                   id="force_password_change"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="force_password_change" class="ml-2 text-xs text-gray-700">
                                Force password change on first login
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Additional Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-purple-600 text-base mr-2">info</span>
                        Additional Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter optional contact and organizational details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., +60 12-345 6789">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" name="department"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., Legal, HR, Finance">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Additional notes about this user..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4">
                    <a href="{{ route('settings.user') }}" class="w-full md:w-auto px-3 py-1.5 text-gray-600 border border-gray-300 rounded-sm text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 