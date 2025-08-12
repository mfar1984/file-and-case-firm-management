@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management > User Details
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded shadow-md border border-gray-300">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="text-xl font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h1 class="text-lg md:text-xl font-bold text-gray-800">{{ $user->name }}</h1>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">User ID: {{ $user->id }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('settings.user.edit', $user->id) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit User
                    </a>
                    <a href="{{ route('settings.user') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1.5 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - User Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-blue-500 mr-2 text-sm">person</span>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Full Name</label>
                                <p class="text-xs text-gray-900 font-medium">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Email Address</label>
                                <p class="text-xs text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Account Status</label>
                                <div class="mt-1">
                                    @if($user->email_verified_at)
                                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Email Verified</span>
                                    @else
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Email Pending</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Member Since</label>
                                <p class="text-xs text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-purple-500 mr-2 text-sm">admin_panel_settings</span>
                            Role Information
                        </h3>
                        @if($user->roles->count() > 0)
                            <div class="space-y-3">
                                @foreach($user->roles as $role)
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-xs font-medium text-gray-900">{{ $role->name }}</h4>
                                            @if($role->description)
                                                <p class="text-xs text-gray-600 mt-1">{{ $role->description }}</p>
                                            @endif
                                        </div>
                                        <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </div>
                                    
                                    @if($role->permissions->count() > 0)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-500 mb-2">Key Permissions:</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($role->permissions->take(6) as $permission)
                                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">{{ $permission->name }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 6)
                                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">+{{ $role->permissions->count() - 6 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <span class="material-icons text-gray-400 text-2xl mb-2">warning</span>
                                <p class="text-gray-500 text-xs">No roles assigned</p>
                                <a href="{{ route('settings.user.edit', $user->id) }}" class="text-blue-600 hover:text-blue-700 text-xs">Assign roles now</a>
                            </div>
                        @endif
                    </div>

                    <!-- Activity Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <span class="material-icons text-green-500 mr-2 text-sm">schedule</span>
                            Activity Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Login</label>
                                <p class="text-xs text-gray-900 font-medium">
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}
                                </p>
                                @if($user->last_login_at)
                                    <div class="text-xs text-gray-500 mt-1">{{ $user->last_login_at->diffForHumans() }}</div>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Last Updated</label>
                                <p class="text-xs text-gray-900 font-medium">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                                <div class="text-xs text-gray-500 mt-1">{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Actions & Stats -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('settings.user.edit', $user->id) }}" 
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                                <span class="material-icons text-xs mr-1">edit</span>
                                Edit User
                            </a>
                            <button onclick="resetPassword({{ $user->id }})" 
                                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                                <span class="material-icons text-xs mr-1">lock_reset</span>
                                Reset Password
                            </button>
                            @if(!$user->email_verified_at)
                            <button onclick="verifyEmail({{ $user->id }})" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                                <span class="material-icons text-xs mr-1">verified</span>
                                Verify Email
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- User Statistics -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">User Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Roles:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $user->roles->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Permissions:</span>
                                <span class="text-xs font-medium text-gray-900">
                                    {{ $user->roles->sum(function($role) { return $role->permissions->count(); }) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Account Age:</span>
                                <span class="text-xs font-medium text-gray-900">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <h3 class="text-sm font-semibold text-red-700 mb-4">Danger Zone</h3>
                        <p class="text-xs text-red-600 mb-3">These actions cannot be undone.</p>
                        <button onclick="deleteUser({{ $user->id }})" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-xs mr-1">delete_forever</span>
                            Delete User
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetPassword(userId) {
    if (confirm('Are you sure you want to reset the password for this user? A new password will be generated and sent to their email.')) {
        // Implement password reset functionality
        alert('Password reset functionality to be implemented');
    }
}

function verifyEmail(userId) {
    if (confirm('Are you sure you want to mark this user\'s email as verified?')) {
        // Implement email verification functionality
        alert('Email verification functionality to be implemented');
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone and will remove all associated data.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/user/${userId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection 