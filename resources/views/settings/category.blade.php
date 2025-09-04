@extends('layouts.app')

@section('breadcrumb')
    Settings > Category
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto" x-data="{ 
    showTypeModal: false, 
    showStatusModal: false,
    showEditTypeModal: false,
    showEditStatusModal: false,
    showSpecializationModal: false,
    showEditSpecializationModal: false,
    showPayeeModal: false,
    showEditPayeeModal: false,
    typeForm: { code: '', description: '', status: 'active' },
    statusForm: { name: '', description: '', color: 'blue', status: 'active' },
    editTypeForm: { id: '', code: '', description: '', status: 'active' },
    editStatusForm: { id: '', name: '', description: '', color: 'blue', status: 'active' },
    specializationForm: { specialist_name: '', description: '', status: 'active' },
    editSpecializationForm: { id: '', specialist_name: '', description: '', status: 'active' },
            payeeForm: { name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
        editPayeeForm: { id: '', name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
    
    openEditTypeModal(id, code, description, status) {
        this.editTypeForm = { id: id, code: code, description: description, status: status };
        this.showEditTypeModal = true;
    },
    
    openEditStatusModal(id, name, description, color, status) {
        this.editStatusForm = { id: id, name: name, description: description, color: color, status: status };
        this.showEditStatusModal = true;
    },

    openEditSpecializationModal(id, specialist_name, description, status) {
        this.editSpecializationForm = { id: id, specialist_name: specialist_name, description: description, status: status };
        this.showEditSpecializationModal = true;
    },

    openEditPayeeModal(id, name, category, address, contact_person, phone, email, status) {
        this.editPayeeForm = { id: id, name: name, category: category, address: address, contact_person: contact_person, phone: phone, email: email, status: status };
        this.showEditPayeeModal = true;
    },

    submitTypeForm() {
        const formData = new FormData();
        formData.append('code', this.typeForm.code);
        formData.append('description', this.typeForm.description);
        formData.append('status', this.typeForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("settings.category.type.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case type created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the case type.');
        });

        this.showTypeModal = false;
        this.typeForm = { code: '', description: '', status: 'active' };
    },

    submitStatusForm() {
        const formData = new FormData();
        formData.append('name', this.statusForm.name);
        formData.append('description', this.statusForm.description);
        formData.append('color', this.statusForm.color);
        formData.append('status', this.statusForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("settings.category.status.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case status created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the case status.');
        });

        this.showStatusModal = false;
        this.statusForm = { name: '', description: '', color: 'blue', status: 'active' };
    },

    submitEditTypeForm() {
        const formData = new FormData();
        formData.append('code', this.editTypeForm.code);
        formData.append('description', this.editTypeForm.description);
        formData.append('status', this.editTypeForm.status);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/type") }}/' + this.editTypeForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case type updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the case type.');
        });

        this.showEditTypeModal = false;
    },

    submitEditStatusForm() {
        const formData = new FormData();
        formData.append('name', this.editStatusForm.name);
        formData.append('description', this.editStatusForm.description);
        formData.append('color', this.editStatusForm.color);
        formData.append('status', this.editStatusForm.status);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/status") }}/' + this.editStatusForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case status updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the case status.');
        });

        this.showEditStatusModal = false;
    },

    deleteType(id) {
        if (confirm('Are you sure you want to delete this case type?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/type") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Case type deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the case type.');
            });
        }
    },

    deleteStatus(id) {
        if (confirm('Are you sure you want to delete this case status?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/status") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Case status deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the case status.');
            });
        }
    },

    showFileTypeModal: false,
    showEditFileTypeModal: false,
    fileTypeForm: { code: '', description: '', status: 'active' },
    editFileTypeForm: { id: '', code: '', description: '', status: 'active' },

    openEditFileTypeModal(id, code, description, status) {
        this.editFileTypeForm = { id: id, code: code, description: description, status: status };
        this.showEditFileTypeModal = true;
    },

    submitFileTypeForm() {
        const formData = new FormData();
        formData.append('code', this.fileTypeForm.code);
        formData.append('description', this.fileTypeForm.description);
        formData.append('status', this.fileTypeForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("settings.category.file-type.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File type created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the file type.');
        });

        this.showFileTypeModal = false;
        this.fileTypeForm = { code: '', description: '', status: 'active' };
    },

    submitEditFileTypeForm() {
        const formData = new FormData();
        formData.append('code', this.editFileTypeForm.code);
        formData.append('description', this.editFileTypeForm.description);
        formData.append('status', this.editFileTypeForm.status);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/file-type") }}/' + this.editFileTypeForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File type updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the file type.');
        });

        this.showEditFileTypeModal = false;
    },

    deleteFileType(id) {
        if (confirm('Are you sure you want to delete this file type?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/file-type") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('File type deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the file type.');
            });
        }
    },

    submitSpecializationForm() {
        const formData = new FormData();
        formData.append('specialist_name', this.specializationForm.specialist_name);
        formData.append('description', this.specializationForm.description);
        formData.append('status', this.specializationForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("settings.specialization.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Specialization created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the specialization.');
        });

        this.showSpecializationModal = false;
        this.specializationForm = { specialist_name: '', description: '', status: 'active' };
    },

    submitEditSpecializationForm() {
        const formData = new FormData();
        formData.append('specialist_name', this.editSpecializationForm.specialist_name);
        formData.append('description', this.editSpecializationForm.description);
        formData.append('status', this.editSpecializationForm.status);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/specialization") }}/' + this.editSpecializationForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Specialization updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the specialization.');
        });

        this.showEditSpecializationModal = false;
    },

    submitPayeeForm() {
        const formData = new FormData();
        formData.append('name', this.payeeForm.name);
        formData.append('category', this.payeeForm.category);
        formData.append('address', this.payeeForm.address);
        formData.append('contact_person', this.payeeForm.contact_person);
        formData.append('phone', this.payeeForm.phone);
        formData.append('email', this.payeeForm.email);
        formData.append('status', this.payeeForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("payee.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payee created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the payee.');
        });

        this.showPayeeModal = false;
        this.payeeForm = { name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: 'active' };
    },

    submitEditPayeeForm() {
        const formData = new FormData();
        formData.append('name', this.editPayeeForm.name);
        formData.append('category', this.editPayeeForm.category);
        formData.append('address', this.editPayeeForm.address);
        formData.append('contact_person', this.editPayeeForm.contact_person);
        formData.append('phone', this.editPayeeForm.phone);
        formData.append('email', this.editPayeeForm.email);
        formData.append('status', this.editPayeeForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("payee.update", ["id" => ":id"]) }}'.replace(':id', this.editPayeeForm.id), {
            method: 'PUT',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payee updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the payee.');
        });

        this.showEditPayeeModal = false;
    },

    deletePayee(id) {
        if (confirm('Are you sure you want to delete this payee?')) {
            fetch('{{ route("payee.destroy", ["id" => ":id"]) }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payee deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the payee.');
            });
        }
    },

    deleteSpecialization(id) {
        if (confirm('Are you sure you want to delete this specialization?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/specialization") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Specialization deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the specialization.');
            });
        }
    }
}">
    <!-- Type of Case Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">category</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Type of Case</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage different types of legal cases and their codes.</p>
                </div>
                
                <!-- Add Type Button -->
                <button @click="showTypeModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Type
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Code</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($caseTypes as $type)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $type->code }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $type->description }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $type->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($type->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditTypeModal('{{ $type->id }}', '{{ $type->code }}', '{{ $type->description }}', '{{ $type->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteType({{ $type->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Mobile Card View for Type of Case -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Type Card 1 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">CR</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTypeModal('CR', 'Criminal', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Criminal</span>
                </div>
            </div>

            <!-- Type Card 2 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">CA</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTypeModal('CA', 'Civil Action', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Civil Action</span>
                </div>
            </div>

            <!-- Type Card 3 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">CVY</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTypeModal('CVY', 'Conveyancing', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Conveyancing</span>
                </div>
            </div>

            <!-- Type Card 4 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">PB</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTypeModal('PB', 'Probate/ Letter of Administration', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Probate/ Letter of Administration</span>
                </div>
            </div>

            <!-- Type Card 5 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">AGT</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTypeModal('AGT', 'Agreement', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Agreement</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Categories Section [temporarily removed on request] -->

    <!-- Category Status Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">flag</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Category Status</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage case status categories and their definitions.</p>
                </div>
                
                <!-- Add Status Button -->
                <button @click="showStatusModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Status
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Status Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Color</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($caseStatuses as $status)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $status->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $status->description }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-{{ $status->color }}-500 rounded mr-2"></div>
                                    <span class="text-xs">{{ ucfirst($status->color) }}</span>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $status->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($status->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditStatusModal('{{ $status->id }}', '{{ $status->name }}', '{{ $status->description }}', '{{ $status->color }}', '{{ $status->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteStatus({{ $status->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Mobile Card View for Category Status -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Status Card 1 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Consultation</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditStatusModal('Consultation', 'Initial consultation with client', 'blue', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-xs text-gray-600">Blue</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Initial consultation with client</span>
                </div>
            </div>

            <!-- Status Card 2 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Quotation</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditStatusModal('Quotation', 'Fee quotation provided to client', 'yellow', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-xs text-gray-600">Yellow</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Fee quotation provided to client</span>
                </div>
            </div>

            <!-- Status Card 3 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Open file</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditStatusModal('Open file', 'Case file opened and active', 'green', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-xs text-gray-600">Green</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Case file opened and active</span>
                </div>
            </div>

            <!-- Status Card 4 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Proceed</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditStatusModal('Proceed', 'Case proceeding with legal action', 'purple', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                        <span class="text-xs text-gray-600">Purple</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Case proceeding with legal action</span>
                </div>
            </div>

            <!-- Status Card 5 -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">Closed file</span>
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditStatusModal('Closed file', 'Case completed and file closed', 'gray', 'active')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-500 rounded mr-2"></div>
                        <span class="text-xs text-gray-600">Gray</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Case completed and file closed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- File Type Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-indigo-600">insert_drive_file</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">File Type</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage file types used in case files (e.g., PDF, DOCX, XLSX).</p>
                </div>
                <button @click="showFileTypeModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add File Type
                </button>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Code</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($fileTypes as $ft)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $ft->code }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $ft->description }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $ft->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($ft->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditFileTypeModal('{{ $ft->id }}', '{{ $ft->code }}', '{{ $ft->description }}', '{{ $ft->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteFileType({{ $ft->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Mobile Card View for File Types -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($fileTypes as $ft)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $ft->code }}</span>
                        <span class="inline-block {{ $ft->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">{{ ucfirst($ft->status) }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditFileTypeModal('{{ $ft->id }}', '{{ $ft->code }}', '{{ $ft->description }}', '{{ $ft->status }}')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button @click="deleteFileType({{ $ft->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">{{ $ft->description }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Add Type Modal -->
    <div x-show="showTypeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showTypeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Case Type</h3>
                    <button @click="showTypeModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitTypeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                        <input type="text" x-model="typeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., CR, CA, PB" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <input type="text" x-model="typeForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Criminal, Civil Action" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="typeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showTypeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                        Save Type
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Type Modal -->
    <div x-show="showEditTypeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditTypeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Case Type</h3>
                    <button @click="showEditTypeModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitEditTypeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                        <input type="text" x-model="editTypeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., CR, CA, PB" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <input type="text" x-model="editTypeForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Criminal, Civil Action" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editTypeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditTypeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">
                        Update Type
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Status Modal -->
    <div x-show="showStatusModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showStatusModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Status</h3>
                    <button @click="showStatusModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitStatusForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status Name *</label>
                        <input type="text" x-model="statusForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Consultation, Quotation" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <textarea x-model="statusForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Brief description of this status" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Color</label>
                        <select x-model="statusForm.color" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="yellow">Yellow</option>
                            <option value="red">Red</option>
                            <option value="purple">Purple</option>
                            <option value="gray">Gray</option>
                            <option value="orange">Orange</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="statusForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showStatusModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                        Save Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Status Modal -->
    <div x-show="showEditStatusModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditStatusModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Status</h3>
                    <button @click="showEditStatusModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitEditStatusForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status Name *</label>
                        <input type="text" x-model="editStatusForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Consultation, Quotation" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <textarea x-model="editStatusForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Brief description of this status" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Color</label>
                        <select x-model="editStatusForm.color" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="yellow">Yellow</option>
                            <option value="red">Red</option>
                            <option value="purple">Purple</option>
                            <option value="gray">Gray</option>
                            <option value="orange">Orange</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editStatusForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditStatusModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add File Type Modal -->
    <div x-show="showFileTypeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showFileTypeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New File Type</h3>
                    <button @click="showFileTypeModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            <form @submit.prevent="submitFileTypeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                        <input type="text" x-model="fileTypeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., PDF, DOCX" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <input type="text" x-model="fileTypeForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., PDF Document" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="fileTypeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showFileTypeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">Save File Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit File Type Modal -->
    <div x-show="showEditFileTypeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditFileTypeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit File Type</h3>
                    <button @click="showEditFileTypeModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            <form @submit.prevent="submitEditFileTypeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                        <input type="text" x-model="editFileTypeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., PDF, DOCX" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description *</label>
                        <input type="text" x-model="editFileTypeForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., PDF Document" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editFileTypeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditFileTypeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">Update File Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Specialization Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">psychology</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Specialization</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage legal specializations and areas of expertise.</p>
                </div>
                <button @click="showSpecializationModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Specialization
                </button>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Specialist Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($specializations as $spec)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $spec->specialist_name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $spec->description }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $spec->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($spec->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditSpecializationModal('{{ $spec->id }}', '{{ $spec->specialist_name }}', '{{ $spec->description }}', '{{ $spec->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteSpecialization({{ $spec->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Mobile Card View for Specializations -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($specializations as $spec)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $spec->specialist_name }}</span>
                        <span class="inline-block {{ $spec->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">{{ ucfirst($spec->status) }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditSpecializationModal('{{ $spec->id }}', '{{ $spec->specialist_name }}', '{{ $spec->description }}', '{{ $spec->status }}')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button @click="deleteSpecialization({{ $spec->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">{{ $spec->description }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Payee List Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">payment</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Payee List</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage payee information for payment vouchers.</p>
                </div>
                <button @click="showPayeeModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Payee
                </button>
            </div>
        </div>
        
        @php
            $payees = \App\Models\Payee::orderBy('name')->get();
        @endphp
        
        @if($payees->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Payee Name</th>
                            <th class="py-3 px-4 text-left">Category</th>
                            <th class="py-3 px-4 text-left">Contact Person</th>
                            <th class="py-3 px-4 text-left">Phone</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payees as $payee)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $payee->name }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-[10px]">
                                    {{ $payee->category }}
                                </span>
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $payee->contact_person ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $payee->phone ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $payee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ $payee->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditPayeeModal('{{ $payee->id }}', '{{ $payee->name }}', '{{ $payee->category }}', '{{ $payee->address ?? '' }}', '{{ $payee->contact_person ?? '' }}', '{{ $payee->phone ?? '' }}', '{{ $payee->email ?? '' }}', '{{ $payee->is_active ? '1' : '0' }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deletePayee({{ $payee->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Mobile Card View for Payees -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($payees as $payee)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $payee->name }}</span>
                        <span class="inline-block {{ $payee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">{{ $payee->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditPayeeModal('{{ $payee->id }}', '{{ $payee->name }}', '{{ $payee->category }}', '{{ $payee->address ?? '' }}', '{{ $payee->contact_person ?? '' }}', '{{ $payee->phone ?? '' }}', '{{ $payee->email ?? '' }}', '{{ $payee->is_active ? '1' : '0' }}')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button @click="deletePayee({{ $payee->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Category:</span>
                            <span class="text-xs font-medium text-blue-600">{{ $payee->category }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Contact:</span>
                            <span class="text-xs font-medium">{{ $payee->contact_person ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Phone:</span>
                            <span class="text-xs font-medium">{{ $payee->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">payment</span>
                <p class="text-sm text-gray-500">No payees available</p>
                <p class="text-xs text-gray-400">Add payees to manage payment vouchers</p>
            </div>
        </div>
        
        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">payment</span>
                <p class="text-sm text-gray-500">No payees available</p>
                <p class="text-xs text-gray-400">Add payees to manage payment vouchers</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Agency Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6" x-data="{
        showAgencyModal: false,
        showEditAgencyModal: false,
        showBulkAgencyModal: false,
        agencyForm: { name: '', status: 'active' },
        editAgencyForm: { id: '', name: '', status: 'active' },
        bulkText: '',
        agencies: [],
        
        fetchAgencies() {
            fetch('{{ route('settings.agency.index') }}')
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        this.agencies = d.data;
                    }
                })
                .catch(error => {
                    console.error('Error fetching agencies:', error);
                });
        },
        
        openEditAgency(a) {
            this.editAgencyForm = { id: a.id, name: a.name, status: a.status };
            this.showEditAgencyModal = true;
        },
        
        submitAgency() {
            console.log('submitAgency called');
            console.log('agencyForm:', this.agencyForm);
            
            if (!this.agencyForm.name.trim()) {
                alert('Please enter agency name');
                return;
            }
            
            const fd = new FormData();
            fd.append('name', this.agencyForm.name);
            fd.append('status', this.agencyForm.status);
            fd.append('_token', '{{ csrf_token() }}');
            
            console.log('Sending request to:', '{{ route('settings.agency.store') }}');
            
            fetch('{{ route('settings.agency.store') }}', {
                method: 'POST',
                body: fd
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    this.showAgencyModal = false;
                    this.agencyForm = {name: '', status: 'active'};
                    this.fetchAgencies();
                    alert('Agency saved successfully!');
                } else {
                    alert('Failed to save agency: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the agency.');
            });
        },
        
        submitEditAgency() {
            if (!this.editAgencyForm.name.trim()) {
                alert('Please enter agency name');
                return;
            }
            
            const fd = new FormData();
            fd.append('name', this.editAgencyForm.name);
            fd.append('status', this.editAgencyForm.status);
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('_method', 'PUT');
            
            fetch('{{ url('/settings/agency') }}/' + this.editAgencyForm.id, {
                method: 'POST',
                body: fd
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showEditAgencyModal = false;
                    this.fetchAgencies();
                    alert('Agency updated successfully!');
                } else {
                    alert('Failed to update agency: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the agency.');
            });
        },
        
        deleteAgency(id) {
            if (!confirm('Delete this agency?')) return;
            
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('_method', 'DELETE');
            
            fetch('{{ url('/settings/agency') }}/' + id, {
                method: 'POST',
                body: fd
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.fetchAgencies();
                    alert('Agency deleted successfully!');
                } else {
                    alert('Failed to delete: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the agency.');
            });
        },
        
        submitBulk() {
            const names = this.bulkText.split('\n').map(s => s.trim()).filter(Boolean).slice(0, 50);
            
            if (names.length === 0) {
                alert('Enter up to 50 lines.');
                return;
            }
            
            const fd = new FormData();
            names.forEach(n => fd.append('names[]', n));
            fd.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route('settings.agency.bulk') }}', {
                method: 'POST',
                body: fd
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showBulkAgencyModal = false;
                    this.bulkText = '';
                    this.fetchAgencies();
                    alert('Bulk import completed successfully!');
                } else {
                    alert('Bulk import failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during bulk import.');
            });
        }
    }" x-init="fetchAgencies()">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Agency</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage government agencies. Use bulk import to add up to 50 at once.</p>
                </div>
                <div class="flex gap-2">
                    <button @click="showBulkAgencyModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center"><span class="material-icons text-xs mr-1">file_upload</span>Bulk Import</button>
                    <button @click="showAgencyModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center"><span class="material-icons text-xs mr-1">add</span>Add Agency</button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Name</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="a in agencies" :key="a.id">
                            <tr class="hover:bg-gray-50">
                                <td class="py-1 px-4 text-[11px] font-medium" x-text="a.name"></td>
                                <td class="py-1 px-4 text-[11px]"><span class="inline-block" :class="a.status==='active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-1.5 py-0.5 rounded-full text-[10px]" x-text="a.status"></span></td>
                                <td class="py-1 px-4">
                                                                         <div class="flex justify-center space-x-1 items-center">
                                         <button @click="openEditAgency(a)" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit"><span class="material-icons text-base">edit</span></button>
                                         <button @click="deleteAgency(a.id)" class="p-0.5 text-red-600 hover:text-red-700" title="Delete"><span class="material-icons text-base">delete</span></button>
                                     </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Agency Modal -->
        <div x-show="showAgencyModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="showAgencyModal=false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Agency</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" x-model="agencyForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., JABATAN KEBAJIKAN MASYARAKAT">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="agencyForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button @click="showAgencyModal=false" class="px-3 py-1 bg-gray-500 text-white text-xs rounded">Cancel</button>
                        <button @click="submitAgency()" class="px-3 py-1 bg-blue-600 text-white text-xs rounded">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Agency Modal -->
        <div x-show="showEditAgencyModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="showEditAgencyModal=false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Agency</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" x-model="editAgencyForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editAgencyForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button @click="showEditAgencyModal=false" class="px-3 py-1 bg-gray-500 text-white text-xs rounded">Cancel</button>
                        <button @click="submitEditAgency()" class="px-3 py-1 bg-yellow-600 text-white text-xs rounded">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Import Modal -->
        <div x-show="showBulkAgencyModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="showBulkAgencyModal=false" class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Bulk Import Agencies (max 50 per import)</h3>
                <p class="text-xs text-gray-600 mb-2">Paste one agency name per line.</p>
                <textarea x-model="bulkText" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g.
JABATAN KEBAJIKAN MASYARAKAT
KETUA POLIS NEGERI PERAK
KETUA POLIS PAHANG
AADK - Agensi Anti Dadah Kebangsaan Daerah Jelebu"></textarea>
                <div class="flex justify-end gap-2 mt-4">
                    <button @click="showBulkAgencyModal=false" class="px-3 py-1 bg-gray-500 text-white text-xs rounded">Cancel</button>
                    <button @click="submitBulk()" class="px-3 py-1 bg-indigo-600 text-white text-xs rounded">Import</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Specialization Modal -->
    <div x-show="showSpecializationModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showSpecializationModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Specialization</h3>
                    <button @click="showSpecializationModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitSpecializationForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Specialist Name *</label>
                        <input type="text" x-model="specializationForm.specialist_name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Criminal Law, Family Law" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea x-model="specializationForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of the specialization"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="specializationForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showSpecializationModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                        Save Specialization
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Specialization Modal -->
    <div x-show="showEditSpecializationModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditSpecializationModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Specialization</h3>
                    <button @click="showEditSpecializationModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>
            
            <form @submit.prevent="submitEditSpecializationForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Specialist Name *</label>
                        <input type="text" x-model="editSpecializationForm.specialist_name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Criminal Law, Family Law" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea x-model="editSpecializationForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of the specialization"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editSpecializationForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditSpecializationModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">
                        Update Specialization
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Payee Modal -->
    <div x-show="showPayeeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showPayeeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Add New Payee</h3>
                <button @click="showPayeeModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            
            <form @submit.prevent="submitPayeeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payee Name *</label>
                        <input type="text" x-model="payeeForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., TNB Berhad" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category *</label>
                        <select x-model="payeeForm.category" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select category</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Rent">Rent</option>
                            <option value="Salary">Salary</option>
                            <option value="Internet">Internet</option>
                            <option value="Supplies">Supplies</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea x-model="payeeForm.address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full address"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                        <input type="text" x-model="payeeForm.contact_person" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contact person name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" x-model="payeeForm.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone number">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" x-model="payeeForm.email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email address">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select x-model="payeeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showPayeeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">
                        Save Payee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Payee Modal -->
    <div x-show="showEditPayeeModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditPayeeModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Edit Payee</h3>
                <button @click="showEditPayeeModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            
            <form @submit.prevent="submitEditPayeeForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payee Name *</label>
                        <input type="text" x-model="editPayeeForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., TNB Berhad" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category *</label>
                        <select x-model="editPayeeForm.category" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select category</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Rent">Rent</option>
                            <option value="Salary">Salary</option>
                            <option value="Internet">Internet</option>
                            <option value="Supplies">Supplies</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Insurance">Insurance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea x-model="editPayeeForm.address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full address"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                        <input type="text" x-model="editPayeeForm.contact_person" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contact person name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" x-model="editPayeeForm.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone number">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" x-model="editPayeeForm.email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email address">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select x-model="editPayeeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditPayeeModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-xs rounded-lg hover:bg-yellow-700 transition-colors">
                        Update Payee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitTypeForm() {
    const formData = new FormData();
    formData.append('code', this.typeForm.code);
    formData.append('description', this.typeForm.description);
    formData.append('status', this.typeForm.status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("settings.category.type.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Case type created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the case type.');
    });

    this.showTypeModal = false;
    this.typeForm = { code: '', description: '', status: 'active' };
}

function submitStatusForm() {
    const formData = new FormData();
    formData.append('name', this.statusForm.name);
    formData.append('description', this.statusForm.description);
    formData.append('color', this.statusForm.color);
    formData.append('status', this.statusForm.status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("settings.category.status.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Case status created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the case status.');
    });

    this.showStatusModal = false;
    this.statusForm = { name: '', description: '', color: 'blue', status: 'active' };
}

function submitEditTypeForm() {
    const formData = new FormData();
    formData.append('code', this.editTypeForm.code);
    formData.append('description', this.editTypeForm.description);
    formData.append('status', this.editTypeForm.status);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');

    fetch('{{ url("/settings/category/type") }}/' + this.editTypeForm.id, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Case type updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the case type.');
    });

    this.showEditTypeModal = false;
}

function submitEditStatusForm() {
    const formData = new FormData();
    formData.append('name', this.editStatusForm.name);
    formData.append('description', this.editStatusForm.description);
    formData.append('color', this.editStatusForm.color);
    formData.append('status', this.editStatusForm.status);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');

    fetch('{{ url("/settings/category/status") }}/' + this.editStatusForm.id, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Case status updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the case status.');
    });

    this.showEditStatusModal = false;
}

function deleteType(id) {
    if (confirm('Are you sure you want to delete this case type?')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');

        fetch('{{ url("/settings/category/type") }}/' + id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case type deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the case type.');
        });
    }
}

function deleteStatus(id) {
    if (confirm('Are you sure you want to delete this case status?')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');

        fetch('{{ url("/settings/category/status") }}/' + id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Case status deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the case status.');
        });
    }
}

function submitFileTypeForm() {
    const formData = new FormData();
    formData.append('code', this.fileTypeForm.code);
    formData.append('description', this.fileTypeForm.description);
    formData.append('status', this.fileTypeForm.status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("settings.category.file-type.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('File type created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the file type.');
    });

    this.showFileTypeModal = false;
    this.fileTypeForm = { code: '', description: '', status: 'active' };
}

function submitEditFileTypeForm() {
    const formData = new FormData();
    formData.append('code', this.editFileTypeForm.code);
    formData.append('description', this.editFileTypeForm.description);
    formData.append('status', this.editFileTypeForm.status);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');

    fetch('{{ url("/settings/category/file-type") }}/' + this.editFileTypeForm.id, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('File type updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the file type.');
    });

    this.showEditFileTypeModal = false;
}

function deleteFileType(id) {
    if (confirm('Are you sure you want to delete this file type?')) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');

        fetch('{{ url("/settings/category/file-type") }}/' + id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File type deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the file type.');
        });
    }
}

// Payee Functions
function submitPayeeForm() {
    const formData = new FormData();
    formData.append('name', document.querySelector('[x-data]').__x.$data.payeeForm.name);
    formData.append('category', document.querySelector('[x-data]').__x.$data.payeeForm.category);
    formData.append('address', document.querySelector('[x-data]').__x.$data.payeeForm.address);
    formData.append('contact_person', document.querySelector('[x-data]').__x.$data.payeeForm.contact_person);
    formData.append('phone', document.querySelector('[x-data]').__x.$data.payeeForm.phone);
    formData.append('email', document.querySelector('[x-data]').__x.$data.payeeForm.email);
    formData.append('status', document.querySelector('[x-data]').__x.$data.payeeForm.status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("payee.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payee created successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the payee.');
    });
}

function submitEditPayeeForm() {
    const formData = new FormData();
    formData.append('name', document.querySelector('[x-data]').__x.$data.editPayeeForm.name);
    formData.append('category', document.querySelector('[x-data]').__x.$data.editPayeeForm.category);
    formData.append('address', document.querySelector('[x-data]').__x.$data.editPayeeForm.address);
    formData.append('contact_person', document.querySelector('[x-data]').__x.$data.editPayeeForm.contact_person);
    formData.append('phone', document.querySelector('[x-data]').__x.$data.editPayeeForm.phone);
    formData.append('email', document.querySelector('[x-data]').__x.$data.editPayeeForm.email);
    formData.append('status', document.querySelector('[x-data]').__x.$data.editPayeeForm.status);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("payee.update", ["id" => ":id"]) }}'.replace(':id', document.querySelector('[x-data]').__x.$data.editPayeeForm.id), {
        method: 'PUT',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payee updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the payee.');
    });
}

function deletePayee(id) {
    if (confirm('Are you sure you want to delete this payee?')) {
        fetch('{{ route("payee.destroy", ["id" => ":id"]) }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payee deleted successfully!');
                location.reload();
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