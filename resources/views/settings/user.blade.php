@extends('layouts.app')

@section('breadcrumb')
    Settings > User Management
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
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">people</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">User Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage system users, their roles, and access permissions.</p>
                </div>
                
                <!-- Add User Button -->
                <a href="{{ route('settings.user.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">person_add</span>
                    Add User
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">User</th>
                            <th class="py-3 px-4 text-left">Username</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Roles</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Last Login</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-[11px] font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">{{ $user->username }}</td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">{{ $user->email }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles->take(3) as $role)
                                            <span class="inline-block bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded text-[10px]">{{ $role->name }}</span>
                                        @endforeach
                                        @if($user->roles->count() > 3)
                                            <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">+{{ $user->roles->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">No roles</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($user->email_verified_at)
                                    <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Verified</span>
                                @else
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded-full text-[10px]">Pending</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px] text-gray-500">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('settings.user.show', $user->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('settings.user.edit', $user->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button onclick="deleteUser({{ $user->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($users as $user)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-600">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->username }}</div>
                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="flex space-x-1">
                        <a href="{{ route('settings.user.show', $user->id) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100">
                            <span class="material-icons text-blue-700 text-sm">visibility</span>
                        </a>
                        <a href="{{ route('settings.user.edit', $user->id) }}" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </a>
                        <button onclick="deleteUser({{ $user->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500">Roles:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @if($user->roles->count() > 0)
                                @foreach($user->roles->take(2) as $role)
                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">{{ $role->name }}</span>
                                @endforeach
                                @if($user->roles->count() > 2)
                                    <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">+{{ $user->roles->count() - 2 }} more</span>
                                @endif
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">No roles</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <div class="mt-1">
                            @if($user->email_verified_at)
                                <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Verified</span>
                            @else
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 text-xs text-gray-500">
                    Last login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
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