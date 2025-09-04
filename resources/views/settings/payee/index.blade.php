@extends('layouts.app')

@section('breadcrumb')
    Settings > Payee Management
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">payment</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Payee Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage payee information for payment vouchers.</p>
                </div>
                
                <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <span class="material-icons text-sm mr-1">add</span>
                    Add Payee
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <table class="min-w-full border border-gray-200 rounded">
                <thead>
                    <tr class="bg-primary-light text-white uppercase text-xs">
                        <th class="py-3 px-4 text-left">Payee Name</th>
                        <th class="py-3 px-4 text-left">Category</th>
                        <th class="py-3 px-4 text-left">Contact Person</th>
                        <th class="py-3 px-4 text-left">Phone</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($payees as $payee)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-sm">{{ $payee->name }}</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                {{ $payee->category }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm">{{ $payee->contact_person ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">{{ $payee->phone ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $payee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $payee->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="editPayee({{ $payee->id }})" class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                            <button onclick="deletePayee({{ $payee->id }})" class="text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    alert('Add Payee functionality will be implemented here');
}

function editPayee(id) {
    alert('Edit Payee ' + id + ' functionality will be implemented here');
}

function deletePayee(id) {
    if (confirm('Are you sure you want to delete this payee?')) {
        fetch(`/settings/payee/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the payee.');
        });
    }
}
</script>
@endsection
