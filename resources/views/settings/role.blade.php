@extends('layouts.app')

@section('breadcrumb')
    Settings > Role Management
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
                        <span class="material-icons mr-2 text-purple-600">admin_panel_settings</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Role Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage user roles and their permissions.</p>
                </div>
                
                <!-- Add Role Button -->
                <a href="{{ route('settings.role.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Role
                </a>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Role Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Permissions</th>
                            <th class="py-3 px-4 text-left">Users Count</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($roles as $role)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $role->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $role->description ?? 'No description' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex flex-wrap gap-1">
                                    @if($role->permissions->count() > 0)
                                        @foreach($role->permissions->take(3) as $permission)
                                            <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-[10px]">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 3)
                                            <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">+{{ $role->permissions->count() - 3 }} more</span>
                                        @endif
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-[10px]">No permissions</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $roleUserCounts[$role->id] ?? 0 }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">Active</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <a href="{{ route('settings.role.show', $role->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="Show">
                                        <span class="material-icons text-base">visibility</span>
                                    </a>
                                    <a href="{{ route('settings.role.edit', $role->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button onclick="deleteRole({{ $role->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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
            @foreach($roles as $role)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $role->name }}</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('settings.role.show', $role->id) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100">
                            <span class="material-icons text-blue-700 text-sm">visibility</span>
                        </a>
                        <a href="{{ route('settings.role.edit', $role->id) }}" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </a>
                        <button onclick="deleteRole({{ $role->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Description:</span>
                        <span class="text-xs text-gray-800">{{ $role->description ?? 'No description' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs font-medium text-gray-600">Users:</span>
                        <span class="text-xs text-gray-800">{{ $roleUserCounts[$role->id] ?? 0 }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex flex-wrap gap-1">
                            @if($role->permissions->count() > 0)
                                @foreach($role->permissions->take(3) as $permission)
                                    <span class="inline-block bg-green-100 text-green-800 px-1 py-0.5 rounded text-xs">{{ $permission->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="inline-block bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-xs">+{{ $role->permissions->count() - 3 }} more</span>
                                @endif
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-xs">No permissions</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>





<script>
function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/settings/role/${roleId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection 