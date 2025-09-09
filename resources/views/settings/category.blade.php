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
    showEventStatusModal: false,
    showEditEventStatusModal: false,
    showPayeeModal: false,
    showEditPayeeModal: false,
    showExpenseCategoryModal: false,
    showEditExpenseCategoryModal: false,
    typeForm: { code: '', description: '', status: 'active' },
    statusForm: { name: '', description: '', color: 'blue', status: 'active' },
    editTypeForm: { id: '', code: '', description: '', status: 'active' },
    editStatusForm: { id: '', name: '', description: '', color: 'blue', status: 'active' },
    specializationForm: { specialist_name: '', description: '', status: 'active' },
    editSpecializationForm: { id: '', specialist_name: '', description: '', status: 'active' },
    eventStatusForm: { name: '', description: '', background_color: 'bg-blue-500', icon: 'circle', status: 'active', sort_order: 0 },
    editEventStatusForm: { id: '', name: '', description: '', background_color: 'bg-blue-500', icon: 'circle', status: 'active', sort_order: 0 },
            payeeForm: { name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
        editPayeeForm: { id: '', name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
    expenseCategoryForm: { name: '', description: '', status: 'active', sort_order: 0 },
    editExpenseCategoryForm: { id: '', name: '', description: '', status: 'active', sort_order: 0 },
    
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

    openEditEventStatusModal(id, name, description, background_color, icon, status, sort_order) {
        this.editEventStatusForm = { id: id, name: name, description: description, background_color: background_color, icon: icon, status: status, sort_order: sort_order };
        this.showEditEventStatusModal = true;
    },

    openEditPayeeModal(id, name, category, address, contact_person, phone, email, status) {
        this.editPayeeForm = { id: id, name: name, category: category, address: address, contact_person: contact_person, phone: phone, email: email, status: status };
        this.showEditPayeeModal = true;
    },

    openEditExpenseCategoryModal(id, name, description, status, sort_order) {
        this.editExpenseCategoryForm = { id: id, name: name, description: description, status: status, sort_order: sort_order };
        this.showEditExpenseCategoryModal = true;
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

    submitEventStatusForm() {
        const formData = new FormData();
        formData.append('name', this.eventStatusForm.name);
        formData.append('description', this.eventStatusForm.description);
        formData.append('background_color', this.eventStatusForm.background_color);
        formData.append('icon', this.eventStatusForm.icon);
        formData.append('status', this.eventStatusForm.status);
        formData.append('sort_order', this.eventStatusForm.sort_order);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("settings.category.event-status.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Event status created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the event status.');
        });

        this.showEventStatusModal = false;
        this.eventStatusForm = { name: '', description: '', background_color: 'bg-blue-500', icon: 'circle', status: 'active', sort_order: 0 };
    },

    submitEditEventStatusForm() {
        const formData = new FormData();
        formData.append('name', this.editEventStatusForm.name);
        formData.append('description', this.editEventStatusForm.description);
        formData.append('background_color', this.editEventStatusForm.background_color);
        formData.append('icon', this.editEventStatusForm.icon);
        formData.append('status', this.editEventStatusForm.status);
        formData.append('sort_order', this.editEventStatusForm.sort_order);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/event-status") }}/' + this.editEventStatusForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Event status updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the event status.');
        });

        this.showEditEventStatusModal = false;
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
    },

    deleteEventStatus(id) {
        if (confirm('Are you sure you want to delete this event status?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/event-status") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Event status deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the event status.');
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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageTypes" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageTypes" onchange="changePerPageTypes()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterTypes" placeholder="Search case types..."
                           onkeyup="filterTypes()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterTypes" onchange="filterTypes()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterTypes()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersTypes()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for Case Types -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoTypes">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnTypes" onclick="firstPageTypes()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnTypes" onclick="previousPageTypes()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersTypes" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnTypes" onclick="nextPageTypes()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnTypes" onclick="lastPageTypes()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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

    <!-- Expense Categories Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">category</span>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Expense Categories</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage expense categories for bills and vouchers</p>
                </div>
                <button @click="showExpenseCategoryModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Expense Category
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($expenseCategories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900 font-medium">
                            {{ $category->name }}
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            {{ $category->description ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-600">
                            {{ $category->sort_order }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->status_color }}">
                                {{ $category->status_display }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-xs font-medium">
                            <button @click="openEditExpenseCategoryModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}', '{{ $category->status }}', {{ $category->sort_order }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                <span class="material-icons text-sm">edit</span>
                            </button>
                            <button @click="deleteExpenseCategory({{ $category->id }})"
                                    class="text-red-600 hover:text-red-900">
                                <span class="material-icons text-sm">delete</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageStatus" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageStatus" onchange="changePerPageStatus()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterStatus" placeholder="Search status..."
                           onkeyup="filterStatus()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterStatus" onchange="filterStatus()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterStatus()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersStatus()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for Category Status -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoStatus">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnStatus" onclick="firstPageStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnStatus" onclick="previousPageStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersStatus" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnStatus" onclick="nextPageStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnStatus" onclick="lastPageStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageFileType" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageFileType" onchange="changePerPageFileType()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterFileType" placeholder="Search file types..."
                           onkeyup="filterFileType()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterFileType" onchange="filterFileType()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterFileType()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersFileType()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for File Types -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoFileType">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnFileType" onclick="firstPageFileType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnFileType" onclick="previousPageFileType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersFileType" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnFileType" onclick="nextPageFileType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnFileType" onclick="lastPageFileType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageSpecialization" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageSpecialization" onchange="changePerPageSpecialization()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterSpecialization" placeholder="Search specializations..."
                           onkeyup="filterSpecialization()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterSpecialization" onchange="filterSpecialization()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterSpecialization()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersSpecialization()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for Specializations -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoSpecialization">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnSpecialization" onclick="firstPageSpecialization()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnSpecialization" onclick="previousPageSpecialization()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersSpecialization" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnSpecialization" onclick="nextPageSpecialization()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnSpecialization" onclick="lastPageSpecialization()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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

    <!-- Event Status Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">event_note</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Event Status</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage timeline event statuses with custom colors and icons.</p>
                </div>

                <!-- Add Event Status Button -->
                <button @click="showEventStatusModal = true" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Event Status
                </button>
            </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <div class="min-w-full">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($eventStatuses as $eventStatus)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium text-gray-900">{{ $eventStatus->display_name }}</td>
                            <td class="py-1 px-4 text-[11px] text-gray-600">{{ $eventStatus->description ?? 'No description' }}</td>
                            <td class="py-1 px-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 {{ $eventStatus->background_color }} rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                                        <span class="material-icons text-white text-xs">{{ $eventStatus->icon }}</span>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $eventStatus->background_color }}</span>
                                </div>
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $eventStatus->sort_order }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $eventStatus->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($eventStatus->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditEventStatusModal('{{ $eventStatus->id }}', '{{ $eventStatus->name }}', '{{ $eventStatus->description }}', '{{ $eventStatus->background_color }}', '{{ $eventStatus->icon }}', '{{ $eventStatus->status }}', '{{ $eventStatus->sort_order }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteEventStatus({{ $eventStatus->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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
            @foreach($eventStatuses as $eventStatus)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 {{ $eventStatus->background_color }} rounded-full border-2 border-white shadow-sm flex items-center justify-center">
                            <span class="material-icons text-white text-xs">{{ $eventStatus->icon }}</span>
                        </div>
                        <span class="text-sm font-medium text-gray-800">{{ $eventStatus->display_name }}</span>
                        <span class="inline-block {{ $eventStatus->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">{{ ucfirst($eventStatus->status) }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditEventStatusModal('{{ $eventStatus->id }}', '{{ $eventStatus->name }}', '{{ $eventStatus->description }}', '{{ $eventStatus->background_color }}', '{{ $eventStatus->icon }}', '{{ $eventStatus->status }}', '{{ $eventStatus->sort_order }}')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button @click="deleteEventStatus({{ $eventStatus->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">{{ $eventStatus->description ?? 'No description' }}</span>
                    <div class="mt-1 text-xs text-gray-500">
                        Sort Order: {{ $eventStatus->sort_order }} | Color: {{ $eventStatus->background_color }} | Icon: {{ $eventStatus->icon }}
                    </div>
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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPagePayee" class="text-xs text-gray-700">Show:</label>
                    <select id="perPagePayee" onchange="changePerPagePayee()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterPayee" placeholder="Search payees..."
                           onkeyup="filterPayee()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterPayee" onchange="filterPayee()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterPayee()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersPayee()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for Payees -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoPayee">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnPayee" onclick="firstPagePayee()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnPayee" onclick="previousPagePayee()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersPayee" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnPayee" onclick="nextPagePayee()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnPayee" onclick="lastPagePayee()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageAgency" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageAgency" onchange="changePerPageAgency()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterAgency" placeholder="Search agencies..."
                           onkeyup="filterAgency()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterAgency" onchange="filterAgency()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterAgency()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersAgency()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
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

        <!-- Pagination Section for Agencies -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoAgency">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnAgency" onclick="firstPageAgency()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnAgency" onclick="previousPageAgency()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersAgency" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnAgency" onclick="nextPageAgency()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnAgency" onclick="lastPageAgency()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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

    <!-- Add Event Status Modal -->
    <div x-show="showEventStatusModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEventStatusModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Event Status</h3>
                    <button @click="showEventStatusModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitEventStatusForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status Name *</label>
                        <input type="text" x-model="eventStatusForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="e.g., Completed, Processing" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea x-model="eventStatusForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" rows="3" placeholder="Brief description of this status"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Background Color *</label>
                            <select x-model="eventStatusForm.background_color" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="bg-green-500">Green</option>
                                <option value="bg-blue-500">Blue</option>
                                <option value="bg-yellow-500">Yellow</option>
                                <option value="bg-red-500">Red</option>
                                <option value="bg-purple-500">Purple</option>
                                <option value="bg-orange-500">Orange</option>
                                <option value="bg-gray-500">Gray</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Icon *</label>
                            <select x-model="eventStatusForm.icon" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="check">check (✓)</option>
                                <option value="radio_button_checked">radio_button_checked (⚪)</option>
                                <option value="trending_up">trending_up (📈)</option>
                                <option value="schedule">schedule (🕐)</option>
                                <option value="cancel">cancel (❌)</option>
                                <option value="circle">circle (⚫)</option>
                                <option value="star">star (⭐)</option>
                                <option value="flag">flag (🚩)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" x-model="eventStatusForm.sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" min="0" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select x-model="eventStatusForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEventStatusModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors">
                        Save Event Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Event Status Modal -->
    <div x-show="showEditEventStatusModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditEventStatusModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Event Status</h3>
                    <button @click="showEditEventStatusModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitEditEventStatusForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status Name *</label>
                        <input type="text" x-model="editEventStatusForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" placeholder="e.g., Completed, Processing" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea x-model="editEventStatusForm.description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" rows="3" placeholder="Brief description of this status"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Background Color *</label>
                            <select x-model="editEventStatusForm.background_color" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="bg-green-500">Green</option>
                                <option value="bg-blue-500">Blue</option>
                                <option value="bg-yellow-500">Yellow</option>
                                <option value="bg-red-500">Red</option>
                                <option value="bg-purple-500">Purple</option>
                                <option value="bg-orange-500">Orange</option>
                                <option value="bg-gray-500">Gray</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Icon *</label>
                            <select x-model="editEventStatusForm.icon" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="check">check (✓)</option>
                                <option value="radio_button_checked">radio_button_checked (⚪)</option>
                                <option value="trending_up">trending_up (📈)</option>
                                <option value="schedule">schedule (🕐)</option>
                                <option value="cancel">cancel (❌)</option>
                                <option value="circle">circle (⚫)</option>
                                <option value="star">star (⭐)</option>
                                <option value="flag">flag (🚩)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" x-model="editEventStatusForm.sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" min="0" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select x-model="editEventStatusForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditEventStatusModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors">
                        Update Event Status
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
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
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
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
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

// Expense Category Functions
function submitExpenseCategoryForm() {
    const form = document.getElementById('expenseCategoryForm');
    const formData = new FormData(form);

    fetch('{{ route("settings.category.expense-category.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the expense category.');
    });
}

function submitEditExpenseCategoryForm() {
    const form = document.getElementById('editExpenseCategoryForm');
    const formData = new FormData(form);
    const id = document.getElementById('editExpenseCategoryId').value;

    fetch('{{ route("settings.category.expense-category.update", ["expenseCategory" => ":id"]) }}'.replace(':id', id), {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the expense category.');
    });
}

function deleteExpenseCategory(id) {
    if (confirm('Are you sure you want to delete this expense category?')) {
        fetch('{{ route("settings.category.expense-category.destroy", ["expenseCategory" => ":id"]) }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the expense category.');
        });
    }
}

// Pagination variables for Case Types
let currentPageTypes = 1;
let perPageTypes = 10;
let allTypes = [];
let filteredTypes = [];

// Initialize pagination for Case Types
function initializePaginationTypes() {
    // Only target the first table (Case Types) - skip other sections
    const caseTypeTable = document.querySelector('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (!caseTypeTable) return;

    const typeRows = caseTypeTable.querySelectorAll('tr');
    allTypes = Array.from(typeRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredTypes = [...allTypes];
    displayTypes();
    updatePaginationTypes();
}

function displayTypes() {
    const startIndex = (currentPageTypes - 1) * perPageTypes;
    const endIndex = startIndex + perPageTypes;

    allTypes.forEach(type => {
        if (type.element) type.element.style.display = 'none';
    });

    const typesToShow = filteredTypes.slice(startIndex, endIndex);
    typesToShow.forEach(type => {
        if (type.element) type.element.style.display = '';
    });
}

function updatePaginationTypes() {
    const totalItems = filteredTypes.length;
    const totalPages = Math.ceil(totalItems / perPageTypes);
    const startItem = totalItems === 0 ? 0 : (currentPageTypes - 1) * perPageTypes + 1;
    const endItem = Math.min(currentPageTypes * perPageTypes, totalItems);

    if (document.getElementById('pageInfoTypes')) {
        document.getElementById('pageInfoTypes').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnTypes');
    const nextBtn = document.getElementById('nextBtnTypes');
    const prevSingleBtn = document.getElementById('prevSingleBtnTypes');
    const nextSingleBtn = document.getElementById('nextSingleBtnTypes');

    if (prevBtn) prevBtn.disabled = currentPageTypes === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageTypes === 1;
    if (nextBtn) nextBtn.disabled = currentPageTypes === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageTypes === totalPages || totalPages === 0;

    updatePageNumbersTypes(totalPages);
}

function updatePageNumbersTypes(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersTypes');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPageTypes - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageTypes;
        pageHtml += `
            <button onclick="goToPageTypes(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageTypes() {
    const newPerPage = parseInt(document.getElementById('perPageTypes')?.value || 10);

    if (document.getElementById('perPageTypes')) document.getElementById('perPageTypes').value = newPerPage;

    perPageTypes = newPerPage;
    currentPageTypes = 1;
    displayTypes();
    updatePaginationTypes();
}

function filterTypes() {
    const searchTerm = (document.getElementById('searchFilterTypes')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterTypes')?.value || '');

    filteredTypes = allTypes.filter(type => {
        const matchesSearch = searchTerm === '' || type.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || type.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageTypes = 1;
    displayTypes();
    updatePaginationTypes();
}

function resetFiltersTypes() {
    if (document.getElementById('searchFilterTypes')) document.getElementById('searchFilterTypes').value = '';
    if (document.getElementById('statusFilterTypes')) document.getElementById('statusFilterTypes').value = '';

    filteredTypes = [...allTypes];
    currentPageTypes = 1;
    displayTypes();
    updatePaginationTypes();
}

function previousPageTypes() {
    if (currentPageTypes > 1) {
        currentPageTypes--;
        displayTypes();
        updatePaginationTypes();
    }
}

function nextPageTypes() {
    const totalPages = Math.ceil(filteredTypes.length / perPageTypes);
    if (currentPageTypes < totalPages) {
        currentPageTypes++;
        displayTypes();
        updatePaginationTypes();
    }
}

function firstPageTypes() {
    currentPageTypes = 1;
    displayTypes();
    updatePaginationTypes();
}

function lastPageTypes() {
    const totalPages = Math.ceil(filteredTypes.length / perPageTypes);
    currentPageTypes = totalPages;
    displayTypes();
    updatePaginationTypes();
}

function goToPageTypes(page) {
    currentPageTypes = page;
    displayTypes();
    updatePaginationTypes();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePaginationTypes();
    initializePaginationStatus();
    initializePaginationFileType();
    initializePaginationSpecialization();
    initializePaginationPayee();

    // Agency needs special handling due to Alpine.js
    setTimeout(() => {
        initializePaginationAgency();
    }, 1000);
});

// Category Status Pagination
let currentPageStatus = 1;
let perPageStatus = 10;
let allStatus = [];
let filteredStatus = [];

function initializePaginationStatus() {
    const statusTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (statusTables.length < 2) return;

    const statusRows = statusTables[1].querySelectorAll('tr');
    allStatus = Array.from(statusRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredStatus = [...allStatus];
    displayStatus();
    updatePaginationStatus();
}

function displayStatus() {
    const startIndex = (currentPageStatus - 1) * perPageStatus;
    const endIndex = startIndex + perPageStatus;

    allStatus.forEach(status => {
        if (status.element) status.element.style.display = 'none';
    });

    const statusToShow = filteredStatus.slice(startIndex, endIndex);
    statusToShow.forEach(status => {
        if (status.element) status.element.style.display = '';
    });
}

function updatePaginationStatus() {
    const totalItems = filteredStatus.length;
    const totalPages = Math.ceil(totalItems / perPageStatus);
    const startItem = totalItems === 0 ? 0 : (currentPageStatus - 1) * perPageStatus + 1;
    const endItem = Math.min(currentPageStatus * perPageStatus, totalItems);

    if (document.getElementById('pageInfoStatus')) {
        document.getElementById('pageInfoStatus').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnStatus');
    const nextBtn = document.getElementById('nextBtnStatus');
    const prevSingleBtn = document.getElementById('prevSingleBtnStatus');
    const nextSingleBtn = document.getElementById('nextSingleBtnStatus');

    if (prevBtn) prevBtn.disabled = currentPageStatus === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageStatus === 1;
    if (nextBtn) nextBtn.disabled = currentPageStatus === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageStatus === totalPages || totalPages === 0;

    updatePageNumbersStatus(totalPages);
}

function updatePageNumbersStatus(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersStatus');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPageStatus - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageStatus;
        pageHtml += `
            <button onclick="goToPageStatus(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageStatus() {
    const newPerPage = parseInt(document.getElementById('perPageStatus')?.value || 10);
    perPageStatus = newPerPage;
    currentPageStatus = 1;
    displayStatus();
    updatePaginationStatus();
}

function filterStatus() {
    const searchTerm = (document.getElementById('searchFilterStatus')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterStatus')?.value || '');

    filteredStatus = allStatus.filter(status => {
        const matchesSearch = searchTerm === '' || status.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageStatus = 1;
    displayStatus();
    updatePaginationStatus();
}

function resetFiltersStatus() {
    if (document.getElementById('searchFilterStatus')) document.getElementById('searchFilterStatus').value = '';
    if (document.getElementById('statusFilterStatus')) document.getElementById('statusFilterStatus').value = '';

    filteredStatus = [...allStatus];
    currentPageStatus = 1;
    displayStatus();
    updatePaginationStatus();
}

function previousPageStatus() {
    if (currentPageStatus > 1) {
        currentPageStatus--;
        displayStatus();
        updatePaginationStatus();
    }
}

function nextPageStatus() {
    const totalPages = Math.ceil(filteredStatus.length / perPageStatus);
    if (currentPageStatus < totalPages) {
        currentPageStatus++;
        displayStatus();
        updatePaginationStatus();
    }
}

function firstPageStatus() {
    currentPageStatus = 1;
    displayStatus();
    updatePaginationStatus();
}

function lastPageStatus() {
    const totalPages = Math.ceil(filteredStatus.length / perPageStatus);
    currentPageStatus = totalPages;
    displayStatus();
    updatePaginationStatus();
}

function goToPageStatus(page) {
    currentPageStatus = page;
    displayStatus();
    updatePaginationStatus();
}

// File Type Pagination
let currentPageFileType = 1;
let perPageFileType = 10;
let allFileTypes = [];
let filteredFileTypes = [];

function initializePaginationFileType() {
    const fileTypeTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (fileTypeTables.length < 3) return;

    const fileTypeRows = fileTypeTables[2].querySelectorAll('tr');
    allFileTypes = Array.from(fileTypeRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredFileTypes = [...allFileTypes];
    displayFileType();
    updatePaginationFileType();
}

function displayFileType() {
    const startIndex = (currentPageFileType - 1) * perPageFileType;
    const endIndex = startIndex + perPageFileType;

    allFileTypes.forEach(fileType => {
        if (fileType.element) fileType.element.style.display = 'none';
    });

    const fileTypesToShow = filteredFileTypes.slice(startIndex, endIndex);
    fileTypesToShow.forEach(fileType => {
        if (fileType.element) fileType.element.style.display = '';
    });
}

function updatePaginationFileType() {
    const totalItems = filteredFileTypes.length;
    const totalPages = Math.ceil(totalItems / perPageFileType);
    const startItem = totalItems === 0 ? 0 : (currentPageFileType - 1) * perPageFileType + 1;
    const endItem = Math.min(currentPageFileType * perPageFileType, totalItems);

    if (document.getElementById('pageInfoFileType')) {
        document.getElementById('pageInfoFileType').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnFileType');
    const nextBtn = document.getElementById('nextBtnFileType');
    const prevSingleBtn = document.getElementById('prevSingleBtnFileType');
    const nextSingleBtn = document.getElementById('nextSingleBtnFileType');

    if (prevBtn) prevBtn.disabled = currentPageFileType === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageFileType === 1;
    if (nextBtn) nextBtn.disabled = currentPageFileType === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageFileType === totalPages || totalPages === 0;

    updatePageNumbersFileType(totalPages);
}

function updatePageNumbersFileType(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersFileType');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPageFileType - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageFileType;
        pageHtml += `
            <button onclick="goToPageFileType(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageFileType() {
    const newPerPage = parseInt(document.getElementById('perPageFileType')?.value || 10);
    perPageFileType = newPerPage;
    currentPageFileType = 1;
    displayFileType();
    updatePaginationFileType();
}

function filterFileType() {
    const searchTerm = (document.getElementById('searchFilterFileType')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterFileType')?.value || '');

    filteredFileTypes = allFileTypes.filter(fileType => {
        const matchesSearch = searchTerm === '' || fileType.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || fileType.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageFileType = 1;
    displayFileType();
    updatePaginationFileType();
}

function resetFiltersFileType() {
    if (document.getElementById('searchFilterFileType')) document.getElementById('searchFilterFileType').value = '';
    if (document.getElementById('statusFilterFileType')) document.getElementById('statusFilterFileType').value = '';

    filteredFileTypes = [...allFileTypes];
    currentPageFileType = 1;
    displayFileType();
    updatePaginationFileType();
}

function previousPageFileType() {
    if (currentPageFileType > 1) {
        currentPageFileType--;
        displayFileType();
        updatePaginationFileType();
    }
}

function nextPageFileType() {
    const totalPages = Math.ceil(filteredFileTypes.length / perPageFileType);
    if (currentPageFileType < totalPages) {
        currentPageFileType++;
        displayFileType();
        updatePaginationFileType();
    }
}

function firstPageFileType() {
    currentPageFileType = 1;
    displayFileType();
    updatePaginationFileType();
}

function lastPageFileType() {
    const totalPages = Math.ceil(filteredFileTypes.length / perPageFileType);
    currentPageFileType = totalPages;
    displayFileType();
    updatePaginationFileType();
}

function goToPageFileType(page) {
    currentPageFileType = page;
    displayFileType();
    updatePaginationFileType();
}

// Specialization Pagination
let currentPageSpecialization = 1;
let perPageSpecialization = 10;
let allSpecializations = [];
let filteredSpecializations = [];

function initializePaginationSpecialization() {
    const specializationTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (specializationTables.length < 4) return;

    const specializationRows = specializationTables[3].querySelectorAll('tr');
    allSpecializations = Array.from(specializationRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredSpecializations = [...allSpecializations];
    displaySpecialization();
    updatePaginationSpecialization();
}

function displaySpecialization() {
    const startIndex = (currentPageSpecialization - 1) * perPageSpecialization;
    const endIndex = startIndex + perPageSpecialization;

    allSpecializations.forEach(specialization => {
        if (specialization.element) specialization.element.style.display = 'none';
    });

    const specializationsToShow = filteredSpecializations.slice(startIndex, endIndex);
    specializationsToShow.forEach(specialization => {
        if (specialization.element) specialization.element.style.display = '';
    });
}

function updatePaginationSpecialization() {
    const totalItems = filteredSpecializations.length;
    const totalPages = Math.ceil(totalItems / perPageSpecialization);
    const startItem = totalItems === 0 ? 0 : (currentPageSpecialization - 1) * perPageSpecialization + 1;
    const endItem = Math.min(currentPageSpecialization * perPageSpecialization, totalItems);

    if (document.getElementById('pageInfoSpecialization')) {
        document.getElementById('pageInfoSpecialization').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnSpecialization');
    const nextBtn = document.getElementById('nextBtnSpecialization');
    const prevSingleBtn = document.getElementById('prevSingleBtnSpecialization');
    const nextSingleBtn = document.getElementById('nextSingleBtnSpecialization');

    if (prevBtn) prevBtn.disabled = currentPageSpecialization === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageSpecialization === 1;
    if (nextBtn) nextBtn.disabled = currentPageSpecialization === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageSpecialization === totalPages || totalPages === 0;

    updatePageNumbersSpecialization(totalPages);
}

function updatePageNumbersSpecialization(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersSpecialization');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPageSpecialization - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageSpecialization;
        pageHtml += `
            <button onclick="goToPageSpecialization(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageSpecialization() {
    const newPerPage = parseInt(document.getElementById('perPageSpecialization')?.value || 10);
    perPageSpecialization = newPerPage;
    currentPageSpecialization = 1;
    displaySpecialization();
    updatePaginationSpecialization();
}

function filterSpecialization() {
    const searchTerm = (document.getElementById('searchFilterSpecialization')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterSpecialization')?.value || '');

    filteredSpecializations = allSpecializations.filter(specialization => {
        const matchesSearch = searchTerm === '' || specialization.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || specialization.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageSpecialization = 1;
    displaySpecialization();
    updatePaginationSpecialization();
}

function resetFiltersSpecialization() {
    if (document.getElementById('searchFilterSpecialization')) document.getElementById('searchFilterSpecialization').value = '';
    if (document.getElementById('statusFilterSpecialization')) document.getElementById('statusFilterSpecialization').value = '';

    filteredSpecializations = [...allSpecializations];
    currentPageSpecialization = 1;
    displaySpecialization();
    updatePaginationSpecialization();
}

function previousPageSpecialization() {
    if (currentPageSpecialization > 1) {
        currentPageSpecialization--;
        displaySpecialization();
        updatePaginationSpecialization();
    }
}

function nextPageSpecialization() {
    const totalPages = Math.ceil(filteredSpecializations.length / perPageSpecialization);
    if (currentPageSpecialization < totalPages) {
        currentPageSpecialization++;
        displaySpecialization();
        updatePaginationSpecialization();
    }
}

function firstPageSpecialization() {
    currentPageSpecialization = 1;
    displaySpecialization();
    updatePaginationSpecialization();
}

function lastPageSpecialization() {
    const totalPages = Math.ceil(filteredSpecializations.length / perPageSpecialization);
    currentPageSpecialization = totalPages;
    displaySpecialization();
    updatePaginationSpecialization();
}

function goToPageSpecialization(page) {
    currentPageSpecialization = page;
    displaySpecialization();
    updatePaginationSpecialization();
}

// Payee Pagination
let currentPagePayee = 1;
let perPagePayee = 10;
let allPayees = [];
let filteredPayees = [];

function initializePaginationPayee() {
    const payeeTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (payeeTables.length < 5) return;

    const payeeRows = payeeTables[4].querySelectorAll('tr');
    allPayees = Array.from(payeeRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredPayees = [...allPayees];
    displayPayee();
    updatePaginationPayee();
}

function displayPayee() {
    const startIndex = (currentPagePayee - 1) * perPagePayee;
    const endIndex = startIndex + perPagePayee;

    allPayees.forEach(payee => {
        if (payee.element) payee.element.style.display = 'none';
    });

    const payeesToShow = filteredPayees.slice(startIndex, endIndex);
    payeesToShow.forEach(payee => {
        if (payee.element) payee.element.style.display = '';
    });
}

function updatePaginationPayee() {
    const totalItems = filteredPayees.length;
    const totalPages = Math.ceil(totalItems / perPagePayee);
    const startItem = totalItems === 0 ? 0 : (currentPagePayee - 1) * perPagePayee + 1;
    const endItem = Math.min(currentPagePayee * perPagePayee, totalItems);

    if (document.getElementById('pageInfoPayee')) {
        document.getElementById('pageInfoPayee').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnPayee');
    const nextBtn = document.getElementById('nextBtnPayee');
    const prevSingleBtn = document.getElementById('prevSingleBtnPayee');
    const nextSingleBtn = document.getElementById('nextSingleBtnPayee');

    if (prevBtn) prevBtn.disabled = currentPagePayee === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPagePayee === 1;
    if (nextBtn) nextBtn.disabled = currentPagePayee === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPagePayee === totalPages || totalPages === 0;

    updatePageNumbersPayee(totalPages);
}

function updatePageNumbersPayee(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersPayee');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPagePayee - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPagePayee;
        pageHtml += `
            <button onclick="goToPagePayee(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPagePayee() {
    const newPerPage = parseInt(document.getElementById('perPagePayee')?.value || 10);
    perPagePayee = newPerPage;
    currentPagePayee = 1;
    displayPayee();
    updatePaginationPayee();
}

function filterPayee() {
    const searchTerm = (document.getElementById('searchFilterPayee')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterPayee')?.value || '');

    filteredPayees = allPayees.filter(payee => {
        const matchesSearch = searchTerm === '' || payee.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || payee.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPagePayee = 1;
    displayPayee();
    updatePaginationPayee();
}

function resetFiltersPayee() {
    if (document.getElementById('searchFilterPayee')) document.getElementById('searchFilterPayee').value = '';
    if (document.getElementById('statusFilterPayee')) document.getElementById('statusFilterPayee').value = '';

    filteredPayees = [...allPayees];
    currentPagePayee = 1;
    displayPayee();
    updatePaginationPayee();
}

function previousPagePayee() {
    if (currentPagePayee > 1) {
        currentPagePayee--;
        displayPayee();
        updatePaginationPayee();
    }
}

function nextPagePayee() {
    const totalPages = Math.ceil(filteredPayees.length / perPagePayee);
    if (currentPagePayee < totalPages) {
        currentPagePayee++;
        displayPayee();
        updatePaginationPayee();
    }
}

function firstPagePayee() {
    currentPagePayee = 1;
    displayPayee();
    updatePaginationPayee();
}

function lastPagePayee() {
    const totalPages = Math.ceil(filteredPayees.length / perPagePayee);
    currentPagePayee = totalPages;
    displayPayee();
    updatePaginationPayee();
}

function goToPagePayee(page) {
    currentPagePayee = page;
    displayPayee();
    updatePaginationPayee();
}

// Agency Pagination
let currentPageAgency = 1;
let perPageAgency = 10;
let allAgencies = [];
let filteredAgencies = [];

function initializePaginationAgency() {
    // Initialize with empty data first to show pagination controls
    allAgencies = [];
    filteredAgencies = [];
    updatePaginationAgency();

    // Agency section is the last table, wait for data to be loaded
    const checkForAgencies = () => {
        const allTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (allTables.length >= 6) {
            const agencyTbody = allTables[5]; // Agency is the 6th table (index 5)
            const agencyRows = agencyTbody.querySelectorAll('tr');

            if (agencyRows.length > 0 && agencyRows[0].textContent.trim() !== '') {
                allAgencies = Array.from(agencyRows).map((row, index) => ({
                    id: index,
                    element: row,
                    searchText: row.textContent.toLowerCase()
                }));

                filteredAgencies = [...allAgencies];
                displayAgency();
                updatePaginationAgency();
                console.log('Agency pagination initialized with', allAgencies.length, 'items');
            } else {
                // No rows yet or empty rows, try again
                setTimeout(checkForAgencies, 500);
            }
        } else {
            // Tables not loaded yet, try again
            setTimeout(checkForAgencies, 500);
        }
    };

    // Start checking after a short delay
    setTimeout(checkForAgencies, 1000);
}

function displayAgency() {
    const startIndex = (currentPageAgency - 1) * perPageAgency;
    const endIndex = startIndex + perPageAgency;

    allAgencies.forEach(agency => {
        if (agency.element) agency.element.style.display = 'none';
    });

    const agenciesToShow = filteredAgencies.slice(startIndex, endIndex);
    agenciesToShow.forEach(agency => {
        if (agency.element) agency.element.style.display = '';
    });
}

function updatePaginationAgency() {
    const totalItems = filteredAgencies.length;
    const totalPages = Math.ceil(totalItems / perPageAgency);
    const startItem = totalItems === 0 ? 0 : (currentPageAgency - 1) * perPageAgency + 1;
    const endItem = Math.min(currentPageAgency * perPageAgency, totalItems);

    if (document.getElementById('pageInfoAgency')) {
        document.getElementById('pageInfoAgency').textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnAgency');
    const nextBtn = document.getElementById('nextBtnAgency');
    const prevSingleBtn = document.getElementById('prevSingleBtnAgency');
    const nextSingleBtn = document.getElementById('nextSingleBtnAgency');

    if (prevBtn) prevBtn.disabled = currentPageAgency === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageAgency === 1;
    if (nextBtn) nextBtn.disabled = currentPageAgency === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageAgency === totalPages || totalPages === 0;

    updatePageNumbersAgency(totalPages);
}

function updatePageNumbersAgency(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersAgency');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPageAgency - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageAgency;
        pageHtml += `
            <button onclick="goToPageAgency(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageAgency() {
    const newPerPage = parseInt(document.getElementById('perPageAgency')?.value || 10);
    perPageAgency = newPerPage;
    currentPageAgency = 1;
    displayAgency();
    updatePaginationAgency();
}

function filterAgency() {
    const searchTerm = (document.getElementById('searchFilterAgency')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterAgency')?.value || '');

    filteredAgencies = allAgencies.filter(agency => {
        const matchesSearch = searchTerm === '' || agency.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || agency.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageAgency = 1;
    displayAgency();
    updatePaginationAgency();
}

function resetFiltersAgency() {
    if (document.getElementById('searchFilterAgency')) document.getElementById('searchFilterAgency').value = '';
    if (document.getElementById('statusFilterAgency')) document.getElementById('statusFilterAgency').value = '';

    filteredAgencies = [...allAgencies];
    currentPageAgency = 1;
    displayAgency();
    updatePaginationAgency();
}

function previousPageAgency() {
    if (currentPageAgency > 1) {
        currentPageAgency--;
        displayAgency();
        updatePaginationAgency();
    }
}

function nextPageAgency() {
    const totalPages = Math.ceil(filteredAgencies.length / perPageAgency);
    if (currentPageAgency < totalPages) {
        currentPageAgency++;
        displayAgency();
        updatePaginationAgency();
    }
}

function firstPageAgency() {
    currentPageAgency = 1;
    displayAgency();
    updatePaginationAgency();
}

function lastPageAgency() {
    const totalPages = Math.ceil(filteredAgencies.length / perPageAgency);
    currentPageAgency = totalPages;
    displayAgency();
    updatePaginationAgency();
}

function goToPageAgency(page) {
    currentPageAgency = page;
    displayAgency();
    updatePaginationAgency();
}
</script>

<!-- Add Expense Category Modal -->
<div x-show="showExpenseCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Expense Category</h3>
                <button @click="showExpenseCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form id="expenseCategoryForm" @submit.prevent="submitExpenseCategoryForm()">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" x-model="expenseCategoryForm.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" x-model="expenseCategoryForm.description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" x-model="expenseCategoryForm.sort_order" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" x-model="expenseCategoryForm.status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showExpenseCategoryModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Expense Category Modal -->
<div x-show="showEditExpenseCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Expense Category</h3>
                <button @click="showEditExpenseCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form id="editExpenseCategoryForm" @submit.prevent="submitEditExpenseCategoryForm()">
                <input type="hidden" id="editExpenseCategoryId" name="id" x-model="editExpenseCategoryForm.id">
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name *</label>
                    <input type="text" name="name" x-model="editExpenseCategoryForm.name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" x-model="editExpenseCategoryForm.description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" name="sort_order" x-model="editExpenseCategoryForm.sort_order" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" x-model="editExpenseCategoryForm.status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showEditExpenseCategoryModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.custom-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 8px center;
    background-repeat: no-repeat;
    background-size: 16px 16px;
    padding-right: 32px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}
</style>

@endsection