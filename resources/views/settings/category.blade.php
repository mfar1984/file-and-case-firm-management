@extends('layouts.app')

@section('breadcrumb')
    Settings > Category
@endsection

@section('content')
    <script id="sectionTypesJson" type="application/json">@json($sectionTypes)</script>

<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto" x-data="{
    showTypeModal: false,
    showTaxCategoryModal: false,
    showStatusModal: false,
    showEditTypeModal: false,
    showEditTaxCategoryModal: false,
    showEditStatusModal: false,
    showSpecializationModal: false,
    showEditSpecializationModal: false,
    showEventStatusModal: false,
    showEditEventStatusModal: false,
    showPayeeModal: false,
    showEditPayeeModal: false,
    showExpenseCategoryModal: false,
    showEditExpenseCategoryModal: false,
    showSectionTypeModal: false,
    showEditSectionTypeModal: false,
    typeForm: { code: '', description: '', status: 'active' },
    taxCategoryForm: { name: '', tax_rate: 0, sort_order: 0, status: 'active' },
    statusForm: { name: '', description: '', color: 'blue', status: 'active' },
    editTypeForm: { id: '', code: '', description: '', status: 'active' },
    editTaxCategoryForm: { id: '', name: '', tax_rate: 0, sort_order: 0, status: 'active' },
    editStatusForm: { id: '', name: '', description: '', color: 'blue', status: 'active' },
    specializationForm: { specialist_name: '', description: '', status: 'active' },
    editSpecializationForm: { id: '', specialist_name: '', description: '', status: 'active' },
    eventStatusForm: { name: '', description: '', background_color: 'bg-blue-500', icon: 'circle', status: 'active', sort_order: 0 },
    editEventStatusForm: { id: '', name: '', description: '', background_color: 'bg-blue-500', icon: 'circle', status: 'active', sort_order: 0 },
            payeeForm: { name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
        editPayeeForm: { id: '', name: '', category: '', address: '', contact_person: '', phone: '', email: '', status: '1' },
    expenseCategoryForm: { name: '', description: '', status: 'active', sort_order: 0 },
    editExpenseCategoryForm: { id: '', name: '', description: '', status: 'active', sort_order: 0 },
    sectionTypeForm: {
        code: '',
        name: '',
        description: '',
        status: 'active',
        documents: [{ document_name: '', document_code: '' }],
        customFields: [{ field_name: '', field_type: 'text', placeholder: '', is_required: false, field_options: [], conditional_document_code: '' }]
    },
    editSectionTypeForm: {
        id: '',
        code: '',
        name: '',
        description: '',
        status: 'active',
        documents: [],
        customFields: []
    },

    openEditTypeModal(id, code, description, status) {
        this.editTypeForm = { id: id, code: code, description: description, status: status };
        this.showEditTypeModal = true;
    },

    openEditTaxCategoryModal(id, name, tax_rate, sort_order, status) {
        this.editTaxCategoryForm = { id: id, name: name, tax_rate: tax_rate, sort_order: sort_order, status: status };
        this.showEditTaxCategoryModal = true;
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

    openEditSectionTypeModal(id, code, name, description, status) {
        // Find the section type data from the existing data
        const sectionTypes = JSON.parse(document.getElementById('sectionTypesJson').textContent);
        const sectionType = sectionTypes.find(section => section.id == id);



        this.editSectionTypeForm = {
            id: id,
            code: code,
            name: name,
            description: description,
            status: status,
            documents: sectionType && sectionType.initiating_documents ?
                sectionType.initiating_documents.map(doc => ({
                    document_name: doc.document_name,
                    document_code: doc.document_code
                })) : [{ document_name: '', document_code: '' }],
            customFields: sectionType && sectionType.custom_fields ?
                sectionType.custom_fields.map(field => ({
                    field_name: field.field_name,
                    field_type: field.field_type,
                    placeholder: field.placeholder || '',
                    is_required: field.is_required || false,
                    field_options: field.field_options ? (Array.isArray(field.field_options) ? field.field_options : []) : [],
                    conditional_document_code: field.conditional_document_code || ''
                })) : [{ field_name: '', field_type: 'text', placeholder: '', is_required: false, field_options: [], conditional_document_code: '' }]
        };





        this.showEditSectionTypeModal = true;

        // Force refresh dropdown values after modal is shown
        setTimeout(() => {
            this.editSectionTypeForm.customFields.forEach((field, index) => {
                if (field.conditional_document_code) {
                    // Force re-assignment to trigger Alpine.js reactivity
                    const currentValue = field.conditional_document_code;
                    field.conditional_document_code = '';
                    setTimeout(() => {
                        field.conditional_document_code = currentValue;
                    }, 50);
                }
            });
        }, 100);
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

    submitTaxCategoryForm() {
        const formData = new FormData();
        formData.append('name', this.taxCategoryForm.name);
        formData.append('tax_rate', this.taxCategoryForm.tax_rate);
        formData.append('sort_order', this.taxCategoryForm.sort_order);
        formData.append('status', this.taxCategoryForm.status);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ url("/settings/category/tax-category") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tax category created successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while creating the tax category.');
        });

        this.showTaxCategoryModal = false;
        this.taxCategoryForm = { name: '', tax_rate: 0, sort_order: 0, status: 'active' };
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

    updateTaxCategoryForm() {
        const formData = new FormData();
        formData.append('name', this.editTaxCategoryForm.name);
        formData.append('tax_rate', this.editTaxCategoryForm.tax_rate);
        formData.append('sort_order', this.editTaxCategoryForm.sort_order);
        formData.append('status', this.editTaxCategoryForm.status);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ url("/settings/category/tax-category") }}/' + this.editTaxCategoryForm.id, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tax category updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the tax category.');
        });

        this.showEditTaxCategoryModal = false;
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

    deleteTaxCategory(id) {
        if (confirm('Are you sure you want to delete this tax category?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');

            fetch('{{ url("/settings/category/tax-category") }}/' + id, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tax category deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the tax category.');
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

        @if($caseTypes->count() > 0)
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
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">category</span>
                <p class="text-sm text-gray-500">No case types available</p>
                <p class="text-xs text-gray-400">Add case types to manage different legal case categories</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">category</span>
                <p class="text-sm text-gray-500">No case types available</p>
                <p class="text-xs text-gray-400">Add case types to manage different legal case categories</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Expense Categories Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6 mb-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-green-600">category</span>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Expense Categories</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage expense categories for bills and vouchers</p>
                </div>
                <button @click="showExpenseCategoryModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Expense Category
                </button>
            </div>
        </div>

        @if($expenseCategories->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageExpenseCategory" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageExpenseCategory" onchange="changePerPageExpenseCategory()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterExpenseCategory" placeholder="Search expense categories..."
                           onkeyup="filterExpenseCategory()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterExpenseCategory" onchange="filterExpenseCategory()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterExpenseCategory()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersExpenseCategory()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Name</th>
                            <th class="py-3 px-4 text-left">Description</th>
                            <th class="py-3 px-4 text-left">Sort Order</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($expenseCategories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $category->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $category->description ?? '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $category->sort_order }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $category->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($category->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditExpenseCategoryModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->description }}', '{{ $category->status }}', {{ $category->sort_order }})" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteExpenseCategory({{ $category->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Pagination Section for Expense Categories -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoExpenseCategory">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnExpenseCategory" onclick="firstPageExpenseCategory()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnExpenseCategory" onclick="previousPageExpenseCategory()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersExpenseCategory" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnExpenseCategory" onclick="nextPageExpenseCategory()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnExpenseCategory" onclick="lastPageExpenseCategory()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">category</span>
                <p class="text-sm text-gray-500">No expense categories available</p>
                <p class="text-xs text-gray-400">Add expense categories to manage bills and vouchers</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">category</span>
                <p class="text-sm text-gray-500">No expense categories available</p>
                <p class="text-xs text-gray-400">Add expense categories to manage bills and vouchers</p>
            </div>
        </div>
        @endif
    </div>
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

    <!-- Section Type Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mt-6 mb-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">account_tree</span>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800">Section Types</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage case section types (Civil, Criminal, Probate, Conveyancing)</p>
                </div>
                <button @click="showSectionTypeModal = true" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Section Type
                </button>
            </div>
        </div>

        @if($sectionTypes->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageSectionType" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageSectionType" onchange="changePerPageSectionType()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterSectionType" placeholder="Search section types..."
                           onkeyup="filterSectionType()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterSectionType" onchange="filterSectionType()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterSectionType()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersSectionType()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-medium uppercase tracking-wider">Code</th>
                            <th class="py-2 px-4 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                            <th class="py-2 px-4 text-left text-xs font-medium uppercase tracking-wider">Description</th>
                            <th class="py-2 px-4 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                            <th class="py-2 px-4 text-center text-xs font-medium uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sectionTypes as $sectionType)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium text-gray-900">{{ $sectionType->code }}</td>
                            <td class="py-1 px-4 text-[11px] text-gray-900">{{ $sectionType->name }}</td>
                            <td class="py-1 px-4 text-[11px] text-gray-500">{{ $sectionType->description ?? '-' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $sectionType->status_color }} px-1.5 py-0.5 rounded-full text-[10px]">{{ $sectionType->status_display }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditSectionTypeModal({{ $sectionType->id }}, '{{ $sectionType->code }}', '{{ $sectionType->name }}', '{{ $sectionType->description }}', '{{ $sectionType->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteSectionType({{ $sectionType->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Pagination Section for Section Types -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoSectionType">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnSectionType" onclick="firstPageSectionType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnSectionType" onclick="previousPageSectionType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersSectionType" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnSectionType" onclick="nextPageSectionType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnSectionType" onclick="lastPageSectionType()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">account_tree</span>
                <p class="text-sm text-gray-500">No section types available</p>
                <p class="text-xs text-gray-400">Add section types to categorize cases</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">account_tree</span>
                <p class="text-sm text-gray-500">No section types available</p>
                <p class="text-xs text-gray-400">Add section types to categorize cases</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Add Section Type Modal -->
    <div x-show="showSectionTypeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <!-- Modal Title with Icon -->
                <div class="flex items-center gap-x-2 mb-6 pl-1">
                    <span class="material-icons text-purple-600 text-xl">account_tree</span>
                    <h3 class="text-lg font-semibold text-gray-900">Add Section Type</h3>
                    <div class="ml-auto">
                        <button @click="showSectionTypeModal = false" class="text-gray-400 hover:text-gray-600">
                            <span class="material-icons">close</span>
                        </button>
                    </div>
                </div>
                <div class="border-b border-gray-200 mb-6"></div>

                <form id="sectionTypeForm" @submit.prevent="submitSectionTypeForm()" class="space-y-4">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                            <input type="text" name="code" x-model="sectionTypeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required maxlength="10" placeholder="e.g., CA, CR, CVY" style="border-radius: 2px !important;">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" x-model="sectionTypeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required style="border-radius: 2px !important;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" name="name" x-model="sectionTypeForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required maxlength="100" placeholder="e.g., Civil Action, Criminal, Conveyancing" style="border-radius: 2px !important;">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" x-model="sectionTypeForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" maxlength="500" placeholder="Detailed description of this section type" style="border-radius: 2px !important;"></textarea>
                        </div>
                    </div>

                    <!-- Case Initiating Documents -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-xs font-medium text-gray-600">Case Initiating Documents</label>
                            <button type="button" @click="sectionTypeForm.documents.push({ document_name: '', document_code: '' })" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Document
                            </button>
                        </div>
                        <template x-for="(doc, index) in sectionTypeForm.documents" :key="index">
                            <div class="flex space-x-2 mb-2 items-center">
                                <input type="text" x-model="doc.document_name" placeholder="Document Name (e.g., Writ of Summons)" class="w-1/2 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                <input type="text" x-model="doc.document_code" placeholder="Code (e.g., WOS)" class="w-1/2 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                <button type="button" @click="sectionTypeForm.documents.splice(index, 1)" x-show="sectionTypeForm.documents.length > 1" class="text-red-500 hover:text-red-700">
                                    <span class="material-icons text-sm">remove_circle_outline</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Custom Fields -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-xs font-medium text-gray-600">Custom Fields</label>
                            <button type="button" @click="sectionTypeForm.customFields.push({ field_name: '', field_type: 'text', placeholder: '', is_required: false, field_options: [], conditional_document_code: '' })" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Custom Field
                            </button>
                        </div>
                        <template x-for="(field, index) in sectionTypeForm.customFields" :key="index">
                            <div class="border border-gray-200 rounded p-3 mb-3" style="border-radius: 2px !important;">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                                    <input type="text" x-model="field.field_name" placeholder="Field Name (e.g., Total Claim)" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                    <select x-model="field.field_type" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="date">Date</option>
                                        <option value="time">Time</option>
                                        <option value="datetime">Date & Time</option>
                                    </select>
                                    <input type="text" x-model="field.placeholder" placeholder="Placeholder text" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                </div>
                                <!-- Conditional Document Code -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Show Only When Document Selected</label>
                                        <select x-model="field.conditional_document_code"
                                                x-init="$nextTick(() => { $el.value = field.conditional_document_code || ''; })"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                            <option value="">Always Show (No Condition)</option>
                                            <template x-for="doc in sectionTypeForm.documents" :key="doc.document_code">
                                                <option x-show="doc.document_code" :value="doc.document_code" x-text="doc.document_name + ' (' + doc.document_code + ')'"></option>
                                            </template>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Field will only appear when the selected document is chosen in case creation</p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <label class="flex items-center text-xs">
                                        <input type="checkbox" x-model="field.is_required" class="mr-1 rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        Required Field
                                    </label>
                                    <button type="button" @click="sectionTypeForm.customFields.splice(index, 1)" x-show="sectionTypeForm.customFields.length > 1" class="text-red-500 hover:text-red-700">
                                        <span class="material-icons text-sm">remove_circle_outline</span>
                                    </button>
                                </div>
                                <!-- Dropdown Options (only show for dropdown type) -->
                                <div x-show="field.field_type === 'dropdown'" class="mt-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Dropdown Options</label>
                                    <template x-for="(option, optIndex) in field.field_options" :key="optIndex">
                                        <div class="flex space-x-2 mb-1 items-center">
                                            <input type="text" x-model="field.field_options[optIndex]" placeholder="Option value" class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                            <button type="button" @click="field.field_options.splice(optIndex, 1)" class="text-red-500 hover:text-red-700">
                                                <span class="material-icons text-xs">remove</span>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="field.field_options.push('')" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center mt-1">
                                        <span class="material-icons text-xs mr-1">add</span> Add Option
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" @click="showSectionTypeModal = false" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-sm hover:bg-gray-200" style="border-radius: 2px !important;">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-purple-600 border border-transparent rounded-sm hover:bg-purple-700" style="border-radius: 2px !important;">
                            Create Section Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Type Modal -->
    <div x-show="showEditSectionTypeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <!-- Modal Title with Icon -->
                <div class="flex items-center gap-x-2 mb-6 pl-1">
                    <span class="material-icons text-purple-600 text-xl">edit</span>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Section Type</h3>
                    <div class="ml-auto">
                        <button @click="showEditSectionTypeModal = false" class="text-gray-400 hover:text-gray-600">
                            <span class="material-icons">close</span>
                        </button>
                    </div>
                </div>
                <div class="border-b border-gray-200 mb-6"></div>

                <form id="editSectionTypeForm" @submit.prevent="submitEditSectionTypeForm()" class="space-y-4">
                    <input type="hidden" name="section_type_id" x-model="editSectionTypeForm.id">
                    <input type="hidden" name="_method" value="PUT">

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Code *</label>
                            <input type="text" name="code" x-model="editSectionTypeForm.code" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required maxlength="10" placeholder="e.g., CA, CR, CVY" style="border-radius: 2px !important;">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" x-model="editSectionTypeForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required style="border-radius: 2px !important;">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                            <input type="text" name="name" x-model="editSectionTypeForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required maxlength="100" placeholder="e.g., Civil Action, Criminal, Conveyancing" style="border-radius: 2px !important;">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" x-model="editSectionTypeForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" maxlength="500" placeholder="Detailed description of this section type" style="border-radius: 2px !important;"></textarea>
                        </div>
                    </div>

                    <!-- Case Initiating Documents -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-xs font-medium text-gray-600">Case Initiating Documents</label>
                            <button type="button" @click="editSectionTypeForm.documents.push({ document_name: '', document_code: '' })" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Document
                            </button>
                        </div>
                        <template x-for="(doc, index) in editSectionTypeForm.documents" :key="index">
                            <div class="flex space-x-2 mb-2 items-center">
                                <input type="text" x-model="doc.document_name" placeholder="Document Name (e.g., Writ of Summons)" class="w-1/2 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                <input type="text" x-model="doc.document_code" placeholder="Code (e.g., WOS)" class="w-1/2 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                <button type="button" @click="editSectionTypeForm.documents.splice(index, 1)" x-show="editSectionTypeForm.documents.length > 1" class="text-red-500 hover:text-red-700">
                                    <span class="material-icons text-sm">remove_circle_outline</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Custom Fields -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-xs font-medium text-gray-600">Custom Fields</label>
                            <button type="button" @click="editSectionTypeForm.customFields.push({ field_name: '', field_type: 'text', placeholder: '', is_required: false, field_options: [], conditional_document_code: '' })" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add_circle_outline</span> Add Custom Field
                            </button>
                        </div>
                        <template x-for="(field, index) in editSectionTypeForm.customFields" :key="index">
                            <div class="border border-gray-200 rounded p-3 mb-3" style="border-radius: 2px !important;">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                                    <input type="text" x-model="field.field_name" placeholder="Field Name (e.g., Total Claim)" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                    <select x-model="field.field_type" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                        <option value="text">Text</option>
                                        <option value="number">Number</option>
                                        <option value="dropdown">Dropdown</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="date">Date</option>
                                        <option value="time">Time</option>
                                        <option value="datetime">Date & Time</option>
                                    </select>
                                    <input type="text" x-model="field.placeholder" placeholder="Placeholder text" class="px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                </div>
                                <!-- Conditional Document Code -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Show Only When Document Selected</label>
                                        <select x-model="field.conditional_document_code"
                                                x-init="$nextTick(() => { $el.value = field.conditional_document_code || ''; })"
                                                class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                            <option value="">Always Show (No Condition)</option>
                                            <template x-for="doc in editSectionTypeForm.documents" :key="doc.document_code">
                                                <option x-show="doc.document_code" :value="doc.document_code" x-text="doc.document_name + ' (' + doc.document_code + ')'"></option>
                                            </template>
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">Field will only appear when the selected document is chosen in case creation</p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <label class="flex items-center text-xs">
                                        <input type="checkbox" x-model="field.is_required" class="mr-1 rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                        Required Field
                                    </label>
                                    <button type="button" @click="editSectionTypeForm.customFields.splice(index, 1)" x-show="editSectionTypeForm.customFields.length > 1" class="text-red-500 hover:text-red-700">
                                        <span class="material-icons text-sm">remove_circle_outline</span>
                                    </button>
                                </div>
                                <!-- Dropdown Options (only show for dropdown type) -->
                                <div x-show="field.field_type === 'dropdown'" class="mt-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Dropdown Options</label>
                                    <template x-for="(option, optIndex) in field.field_options" :key="optIndex">
                                        <div class="flex space-x-2 mb-1 items-center">
                                            <input type="text" x-model="field.field_options[optIndex]" placeholder="Option value" class="flex-1 px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-purple-500" style="border-radius: 2px !important;">
                                            <button type="button" @click="field.field_options.splice(optIndex, 1)" class="text-red-500 hover:text-red-700">
                                                <span class="material-icons text-xs">remove</span>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="field.field_options.push('')" class="text-purple-600 hover:text-purple-700 text-xs font-medium flex items-center mt-1">
                                        <span class="material-icons text-xs mr-1">add</span> Add Option
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" @click="showEditSectionTypeModal = false" class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-sm hover:bg-gray-200" style="border-radius: 2px !important;">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-purple-600 border border-transparent rounded-sm hover:bg-purple-700" style="border-radius: 2px !important;">
                            Update Section Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tax Category Section -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Tax Category</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage tax categories for accounting purposes.</p>
                </div>

                <!-- Add Tax Category Button -->
                <button @click="showTaxCategoryModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Tax Category
                </button>
            </div>
        </div>

        @if(isset($taxCategories) && $taxCategories->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageTaxCategories" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageTaxCategories" onchange="changePerPageTaxCategories()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterTaxCategories" placeholder="Search tax categories..."
                           onkeyup="filterTaxCategories()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterTaxCategories" onchange="filterTaxCategories()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterTaxCategories()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersTaxCategories()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Name</th>
                            <th class="py-3 px-4 text-left">Tax Rate (%)</th>
                            <th class="py-3 px-4 text-left">Sort Order</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($taxCategories as $taxCategory)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $taxCategory->name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ number_format($taxCategory->tax_rate, 2) }}%</td>
                            <td class="py-1 px-4 text-[11px]">{{ $taxCategory->sort_order }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                <span class="inline-block {{ $taxCategory->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-1.5 py-0.5 rounded-full text-[10px]">{{ ucfirst($taxCategory->status) }}</span>
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center">
                                    <button @click="openEditTaxCategoryModal('{{ $taxCategory->id }}', '{{ $taxCategory->name }}', '{{ $taxCategory->tax_rate }}', '{{ $taxCategory->sort_order }}', '{{ $taxCategory->status }}')" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </button>
                                    <button @click="deleteTaxCategory({{ $taxCategory->id }})" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
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

        <!-- Pagination Section for Tax Categories -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoTaxCategories">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnTaxCategories" onclick="firstPageTaxCategories()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnTaxCategories" onclick="previousPageTaxCategories()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersTaxCategories" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnTaxCategories" onclick="nextPageTaxCategories()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnTaxCategories" onclick="lastPageTaxCategories()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Card View for Tax Categories -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($taxCategories as $taxCategory)
            <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-800">{{ $taxCategory->name }}</span>
                        <span class="inline-block {{ $taxCategory->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded-full text-xs">{{ ucfirst($taxCategory->status) }}</span>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="openEditTaxCategoryModal('{{ $taxCategory->id }}', '{{ $taxCategory->name }}', '{{ $taxCategory->tax_rate }}', '{{ $taxCategory->sort_order }}', '{{ $taxCategory->status }}')" class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                            <span class="material-icons text-yellow-700 text-sm">edit</span>
                        </button>
                        <button @click="deleteTaxCategory({{ $taxCategory->id }})" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                            <span class="material-icons text-red-600 text-sm">delete</span>
                        </button>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <span class="text-sm text-gray-700">Tax Rate: {{ number_format($taxCategory->tax_rate, 2) }}% | Sort Order: {{ $taxCategory->sort_order }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-6 text-center text-gray-500">
            <span class="material-icons text-4xl mb-2 block">receipt</span>
            <p class="text-sm">No tax categories found. Click "Add Tax Category" to create one.</p>
        </div>
        @endif
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

        @if($caseStatuses->count() > 0)
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
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">flag</span>
                <p class="text-sm text-gray-500">No case statuses available</p>
                <p class="text-xs text-gray-400">Add case statuses to manage case progress tracking</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">flag</span>
                <p class="text-sm text-gray-500">No case statuses available</p>
                <p class="text-xs text-gray-400">Add case statuses to manage case progress tracking</p>
            </div>
        </div>
        @endif
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

        @if($fileTypes->count() > 0)
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
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">insert_drive_file</span>
                <p class="text-sm text-gray-500">No file types available</p>
                <p class="text-xs text-gray-400">Add file types to manage document categories</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">insert_drive_file</span>
                <p class="text-sm text-gray-500">No file types available</p>
                <p class="text-xs text-gray-400">Add file types to manage document categories</p>
            </div>
        </div>
        @endif
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

    <!-- Add Tax Category Modal -->
    <div x-show="showTaxCategoryModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showTaxCategoryModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Add New Tax Category</h3>
                    <button @click="showTaxCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>

            <form @submit.prevent="submitTaxCategoryForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" x-model="taxCategoryForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., GST, SST, Service Tax" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tax Rate (%) *</label>
                        <input type="number" x-model="taxCategoryForm.tax_rate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" min="0" max="100" step="0.01" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" x-model="taxCategoryForm.sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0" min="0">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="taxCategoryForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showTaxCategoryModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                        Save Tax Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Tax Category Modal -->
    <div x-show="showEditTaxCategoryModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showEditTaxCategoryModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Tax Category</h3>
                    <button @click="showEditTaxCategoryModal = false" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">close</span>
                    </button>
                </div>
            </div>

            <form @submit.prevent="updateTaxCategoryForm()" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Name *</label>
                        <input type="text" x-model="editTaxCategoryForm.name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., GST, SST, Service Tax" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tax Rate (%) *</label>
                        <input type="number" x-model="editTaxCategoryForm.tax_rate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" min="0" max="100" step="0.01" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" x-model="editTaxCategoryForm.sort_order" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0" min="0">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <select x-model="editTaxCategoryForm.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="showEditTaxCategoryModal = false" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                        Update Tax Category
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

        @if($specializations->count() > 0)
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
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">psychology</span>
                <p class="text-sm text-gray-500">No specializations available</p>
                <p class="text-xs text-gray-400">Add specializations to manage legal expertise areas</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">psychology</span>
                <p class="text-sm text-gray-500">No specializations available</p>
                <p class="text-xs text-gray-400">Add specializations to manage legal expertise areas</p>
            </div>
        </div>
        @endif
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

        @if($eventStatuses->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Controls Above Table -->
            <div class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageEventStatus" class="text-xs text-gray-700">Show:</label>
                    <select id="perPageEventStatus" onchange="changePerPageEventStatus()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-xs text-gray-700">entries</span>
                </div>

                <!-- Right: Search and Filters -->
                <div class="flex gap-2 items-center">
                    <input type="text" id="searchFilterEventStatus" placeholder="Search event status..."
                           onkeyup="filterEventStatus()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterEventStatus" onchange="filterEventStatus()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="filterEventStatus()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button onclick="resetFiltersEventStatus()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
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
                            <th class="py-3 px-4 text-left">Preview</th>
                            <th class="py-3 px-4 text-left">Sort Order</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($eventStatuses as $eventStatus)
                        <tr class="hover:bg-gray-50">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $eventStatus->display_name }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $eventStatus->description ?? 'No description' }}</td>
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

        <!-- Pagination Section for Event Status -->
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span id="pageInfoEventStatus">Showing 1 to 10 of 100 records</span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1">
                    <button id="prevBtnEventStatus" onclick="firstPageEventStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button id="prevSingleBtnEventStatus" onclick="previousPageEventStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div id="pageNumbersEventStatus" class="flex items-center gap-1 mx-2">
                        <!-- Page numbers will be populated here -->
                    </div>

                    <button id="nextSingleBtnEventStatus" onclick="nextPageEventStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button id="nextBtnEventStatus" onclick="lastPageEventStatus()"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;&gt;
                    </button>
                </div>
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
        @else
        <div class="hidden md:block p-6">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">event_note</span>
                <p class="text-sm text-gray-500">No event statuses available</p>
                <p class="text-xs text-gray-400">Add event statuses to manage timeline events</p>
            </div>
        </div>

        <div class="md:hidden p-4">
            <div class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">event_note</span>
                <p class="text-sm text-gray-500">No event statuses available</p>
                <p class="text-xs text-gray-400">Add event statuses to manage timeline events</p>
            </div>
        </div>
        @endif
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
        allAgencies: [],
        filteredAgencies: [],
        currentPage: 1,
        perPage: 10,
        searchQuery: '',
        statusFilter: '',
        isLoading: true,

        fetchAgencies() {
            this.isLoading = true;
            fetch('{{ route('settings.agency.index') }}')
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        this.allAgencies = d.data;
                        this.applyFilters();
                    } else {
                        console.error('API returned error:', d);
                    }
                })
                .catch(error => {
                    console.error('Error fetching agencies:', error);
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        applyFilters() {
            this.filteredAgencies = this.allAgencies.filter(agency => {
                const matchesSearch = !this.searchQuery ||
                    agency.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchesStatus = !this.statusFilter ||
                    agency.status.toLowerCase() === this.statusFilter.toLowerCase();
                return matchesSearch && matchesStatus;
            });
            this.currentPage = 1;
            this.updatePaginatedAgencies();
        },

        updatePaginatedAgencies() {
            const startIndex = (this.currentPage - 1) * this.perPage;
            const endIndex = startIndex + this.perPage;
            this.agencies = this.filteredAgencies.slice(startIndex, endIndex);
        },

        get totalPages() {
            return Math.ceil(this.filteredAgencies.length / this.perPage);
        },

        get pageInfo() {
            const total = this.filteredAgencies.length;
            if (total === 0) return 'Showing 0 to 0 of 0 records';
            const start = (this.currentPage - 1) * this.perPage + 1;
            const end = Math.min(this.currentPage * this.perPage, total);
            return `Showing ${start} to ${end} of ${total} records`;
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.updatePaginatedAgencies();
            }
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updatePaginatedAgencies();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.updatePaginatedAgencies();
            }
        },

        changePerPage(newPerPage) {
            this.perPage = parseInt(newPerPage);
            this.currentPage = 1;
            this.updatePaginatedAgencies();
        },

        filterAgencies() {
            this.searchQuery = document.getElementById('searchFilterAgency').value;
            this.statusFilter = document.getElementById('statusFilterAgency').value;
            this.applyFilters();
        },

        resetFilters() {
            this.searchQuery = '';
            this.statusFilter = '';
            document.getElementById('searchFilterAgency').value = '';
            document.getElementById('statusFilterAgency').value = '';
            this.applyFilters();
        },

        openEditAgency(a) {
            this.editAgencyForm = { id: a.id, name: a.name, status: a.status };
            this.showEditAgencyModal = true;
        },

        submitAgency() {


            if (!this.agencyForm.name.trim()) {
                alert('Please enter agency name');
                return;
            }

            const fd = new FormData();
            fd.append('name', this.agencyForm.name);
            fd.append('status', this.agencyForm.status);
            fd.append('_token', '{{ csrf_token() }}');



            fetch('{{ route('settings.agency.store') }}', {
                method: 'POST',
                body: fd
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
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
            <!-- Loading State -->
            <div x-show="isLoading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="text-sm text-gray-500 mt-2">Loading agencies...</p>
            </div>

            <!-- Controls Above Table -->
            <div x-show="!isLoading && allAgencies.length > 0" class="flex justify-between items-center mb-2">
                <!-- Left: Show Entries -->
                <div class="flex items-center gap-2">
                    <label for="perPageAgency" class="text-xs text-gray-700">Show:</label>
                    <select @change="changePerPage($event.target.value)" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
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
                           @input="filterAgencies()"
                           class="border border-gray-300 rounded px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white w-64">

                    <select id="statusFilterAgency" @change="filterAgencies()" class="custom-select border border-gray-300 rounded pl-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <button @click="filterAgencies()" class="px-3 py-2 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                        🔍 Search
                    </button>

                    <button @click="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded text-xs hover:bg-gray-600 transition-colors">
                        🔄 Reset
                    </button>
                </div>
            </div>
            <div x-show="!isLoading && agencies.length > 0" class="overflow-visible border border-gray-200 rounded">
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

            <div x-show="!isLoading && allAgencies.length === 0" class="text-center py-8">
                <span class="material-icons text-gray-400 text-4xl mb-2">business</span>
                <p class="text-sm text-gray-500">No agencies available</p>
                <p class="text-xs text-gray-400">Add agencies to manage government departments</p>
            </div>
        </div>

        <!-- Pagination Section for Agencies -->
        <div class="p-6" x-show="!isLoading && filteredAgencies.length > 0">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <!-- Left: Page Info -->
                <div class="text-xs text-gray-600">
                    <span x-text="pageInfo"></span>
                </div>

                <!-- Right: Pagination -->
                <div class="flex items-center gap-1" x-show="totalPages > 1">
                    <button @click="goToPage(1)" :disabled="currentPage === 1"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;&lt;
                    </button>

                    <button @click="previousPage()" :disabled="currentPage === 1"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &lt;
                    </button>

                    <div class="flex items-center gap-1 mx-2">
                        <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1).filter(p => p === 1 || p === totalPages || (p >= currentPage - 2 && p <= currentPage + 2))" :key="page">
                            <button @click="goToPage(page)"
                                    :class="page === currentPage ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600'"
                                    class="px-2 py-1 text-xs rounded transition-colors">
                                <span x-text="page"></span>
                            </button>
                        </template>
                    </div>

                    <button @click="nextPage()" :disabled="currentPage === totalPages"
                            class="px-2 py-1 text-xs text-gray-600 hover:text-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        &gt;
                    </button>

                    <button @click="goToPage(totalPages)" :disabled="currentPage === totalPages"
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

// Section Type Functions
function submitSectionTypeForm() {
    // Get Alpine.js data
    const modal = document.querySelector('[x-show="showSectionTypeModal"]');
    const alpine = Alpine.$data(modal);

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('code', alpine.sectionTypeForm.code);
    formData.append('name', alpine.sectionTypeForm.name);
    formData.append('description', alpine.sectionTypeForm.description);
    formData.append('status', alpine.sectionTypeForm.status);

    // Add documents data
    alpine.sectionTypeForm.documents.forEach((doc, index) => {
        if (doc.document_name && doc.document_code) {
            formData.append(`documents[${index}][document_name]`, doc.document_name);
            formData.append(`documents[${index}][document_code]`, doc.document_code);
        }
    });

    // Add custom fields data
    alpine.sectionTypeForm.customFields.forEach((field, index) => {
        if (field.field_name && field.field_type) {
            formData.append(`custom_fields[${index}][field_name]`, field.field_name);
            formData.append(`custom_fields[${index}][field_type]`, field.field_type);
            formData.append(`custom_fields[${index}][placeholder]`, field.placeholder || '');
            formData.append(`custom_fields[${index}][is_required]`, field.is_required ? '1' : '0');
            formData.append(`custom_fields[${index}][conditional_document_code]`, field.conditional_document_code || '');

            // Add field options for dropdown type
            if (field.field_type === 'dropdown' && field.field_options) {
                field.field_options.forEach((option, optIndex) => {
                    if (option) {
                        formData.append(`custom_fields[${index}][field_options][${optIndex}]`, option);
                    }
                });
            }
        }
    });

    fetch('{{ route("settings.category.section-type.store") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset form
            alpine.sectionTypeForm = {
                code: '',
                name: '',
                description: '',
                status: 'active',
                documents: [{ document_name: '', document_code: '' }],
                customFields: [{ field_name: '', field_type: 'text', placeholder: '', is_required: false, field_options: [] }]
            };
            alpine.showSectionTypeModal = false;
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the section type.');
    });
}

function submitEditSectionTypeForm() {
    // Get Alpine.js data
    const modal = document.querySelector('[x-show="showEditSectionTypeModal"]');
    const alpine = Alpine.$data(modal);

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    formData.append('code', alpine.editSectionTypeForm.code);
    formData.append('name', alpine.editSectionTypeForm.name);
    formData.append('description', alpine.editSectionTypeForm.description);
    formData.append('status', alpine.editSectionTypeForm.status);

    // Add documents data
    alpine.editSectionTypeForm.documents.forEach((doc, index) => {
        if (doc.document_name && doc.document_code) {
            formData.append(`documents[${index}][document_name]`, doc.document_name);
            formData.append(`documents[${index}][document_code]`, doc.document_code);
            if (doc.id) {
                formData.append(`documents[${index}][id]`, doc.id);
            }
        }
    });

    // Add custom fields data
    alpine.editSectionTypeForm.customFields.forEach((field, index) => {
        if (field.field_name && field.field_type) {
            formData.append(`custom_fields[${index}][field_name]`, field.field_name);
            formData.append(`custom_fields[${index}][field_type]`, field.field_type);
            formData.append(`custom_fields[${index}][placeholder]`, field.placeholder || '');
            formData.append(`custom_fields[${index}][is_required]`, field.is_required ? '1' : '0');
            formData.append(`custom_fields[${index}][conditional_document_code]`, field.conditional_document_code || '');
            if (field.id) {
                formData.append(`custom_fields[${index}][id]`, field.id);
            }

            // Add field options for dropdown type
            if (field.field_type === 'dropdown' && field.field_options) {
                field.field_options.forEach((option, optIndex) => {
                    if (option) {
                        formData.append(`custom_fields[${index}][field_options][${optIndex}]`, option);
                    }
                });
            }
        }
    });

    const sectionTypeId = alpine.editSectionTypeForm.id;

    fetch(`/settings/category/section-type/${sectionTypeId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alpine.showEditSectionTypeModal = false;
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the section type.');
    });
}

function deleteSectionType(id) {
    if (confirm('Are you sure you want to delete this section type?')) {
        fetch(`/settings/category/section-type/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
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
            alert('An error occurred while deleting the section type.');
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

// Tax Category Pagination
let currentPageTaxCategories = 1;
let perPageTaxCategories = 10;
let allTaxCategories = [];
let filteredTaxCategories = [];

function initializePaginationTaxCategories() {
    // Find Tax Category section by looking for the h1 text
    const allH1s = document.querySelectorAll('h1');
    let taxCategoryTable = null;

    for (let h1 of allH1s) {
        if (h1.textContent.includes('Tax Category')) {
            // Find the table within this section
            const section = h1.closest('.bg-white.rounded.shadow-md.border.border-gray-300');
            if (section) {
                taxCategoryTable = section.querySelector('table tbody');
                break;
            }
        }
    }

    if (!taxCategoryTable) return;

    const taxCategoryRows = taxCategoryTable.querySelectorAll('tr');

    allTaxCategories = Array.from(taxCategoryRows).map((row, index) => ({
        element: row,
        searchText: row.textContent.toLowerCase(),
        status: row.querySelector('span').textContent.toLowerCase()
    }));

    filteredTaxCategories = [...allTaxCategories];
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function displayTaxCategories() {
    const startIndex = (currentPageTaxCategories - 1) * perPageTaxCategories;
    const endIndex = startIndex + perPageTaxCategories;

    allTaxCategories.forEach(taxCategory => {
        if (taxCategory.element) taxCategory.element.style.display = 'none';
    });

    const taxCategoriesToShow = filteredTaxCategories.slice(startIndex, endIndex);
    taxCategoriesToShow.forEach(taxCategory => {
        if (taxCategory.element) taxCategory.element.style.display = '';
    });
}

function updatePaginationTaxCategories() {
    const totalItems = filteredTaxCategories.length;
    const totalPages = Math.ceil(totalItems / perPageTaxCategories);
    const startItem = totalItems === 0 ? 0 : (currentPageTaxCategories - 1) * perPageTaxCategories + 1;
    const endItem = Math.min(currentPageTaxCategories * perPageTaxCategories, totalItems);

    const pageInfo = document.getElementById('pageInfoTaxCategories');
    if (pageInfo) {
        pageInfo.textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;
    }

    const prevBtn = document.getElementById('prevBtnTaxCategories');
    const prevSingleBtn = document.getElementById('prevSingleBtnTaxCategories');
    const nextBtn = document.getElementById('nextBtnTaxCategories');
    const nextSingleBtn = document.getElementById('nextSingleBtnTaxCategories');

    if (prevBtn) prevBtn.disabled = currentPageTaxCategories === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageTaxCategories === 1;
    if (nextBtn) nextBtn.disabled = currentPageTaxCategories === totalPages;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageTaxCategories === totalPages;

    updatePageNumbersTaxCategories(totalPages);
}

function updatePageNumbersTaxCategories(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersTaxCategories');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    const startPage = Math.max(1, currentPageTaxCategories - 2);
    const endPage = Math.min(totalPages, currentPageTaxCategories + 2);

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        const isActive = i === currentPageTaxCategories;
        pageHtml += `
            <button onclick="goToPageTaxCategories(${i})"
                    class="w-8 h-8 flex items-center justify-center text-xs transition-colors ${isActive ? 'bg-blue-500 text-white rounded-full' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full'}">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageTaxCategories() {
    const newPerPage = parseInt(document.getElementById('perPageTaxCategories')?.value || 10);
    perPageTaxCategories = newPerPage;
    currentPageTaxCategories = 1;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function filterTaxCategories() {
    const searchTerm = (document.getElementById('searchFilterTaxCategories')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterTaxCategories')?.value || '');

    filteredTaxCategories = allTaxCategories.filter(taxCategory => {
        const matchesSearch = searchTerm === '' || taxCategory.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || taxCategory.status === statusFilter.toLowerCase();
        return matchesSearch && matchesStatus;
    });

    currentPageTaxCategories = 1;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function resetFiltersTaxCategories() {
    if (document.getElementById('searchFilterTaxCategories')) document.getElementById('searchFilterTaxCategories').value = '';
    if (document.getElementById('statusFilterTaxCategories')) document.getElementById('statusFilterTaxCategories').value = '';

    filteredTaxCategories = [...allTaxCategories];
    currentPageTaxCategories = 1;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function previousPageTaxCategories() {
    if (currentPageTaxCategories > 1) {
        currentPageTaxCategories--;
        displayTaxCategories();
        updatePaginationTaxCategories();
    }
}

function nextPageTaxCategories() {
    const totalPages = Math.ceil(filteredTaxCategories.length / perPageTaxCategories);
    if (currentPageTaxCategories < totalPages) {
        currentPageTaxCategories++;
        displayTaxCategories();
        updatePaginationTaxCategories();
    }
}

function firstPageTaxCategories() {
    currentPageTaxCategories = 1;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function lastPageTaxCategories() {
    const totalPages = Math.ceil(filteredTaxCategories.length / perPageTaxCategories);
    currentPageTaxCategories = totalPages;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

function goToPageTaxCategories(page) {
    currentPageTaxCategories = page;
    displayTaxCategories();
    updatePaginationTaxCategories();
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePaginationTypes();
    initializePaginationTaxCategories();
    initializePaginationStatus();
    initializePaginationFileType();
    initializePaginationSpecialization();
    initializePaginationEventStatus();
    initializePaginationExpenseCategory();
    initializePaginationSectionType();
    initializePaginationPayee();

    // Agency pagination is handled by Alpine.js - no separate initialization needed
});

// Category Status Pagination
let currentPageStatus = 1;
let perPageStatus = 10;
let allStatus = [];
let filteredStatus = [];

function initializePaginationStatus() {
    // Find Category Status section by looking for the h1 text
    const allH1s = document.querySelectorAll('h1');
    let statusTable = null;

    for (let h1 of allH1s) {
        if (h1.textContent.includes('Category Status')) {
            statusTable = h1.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    // Fallback: use table index method if section not found
    if (!statusTable) {
        const statusTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (statusTables.length < 2) return;
        statusTable = statusTables[1];
    }

    if (!statusTable) return;

    const statusRows = statusTable.querySelectorAll('tr');
    allStatus = Array.from(statusRows).map((row, index) => ({
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
    // Find File Type section by looking for the h1 text
    const allH1s = document.querySelectorAll('h1');
    let fileTypeTable = null;

    for (let h1 of allH1s) {
        if (h1.textContent.includes('File Type')) {
            fileTypeTable = h1.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    // Fallback: use table index method if section not found
    if (!fileTypeTable) {
        const fileTypeTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (fileTypeTables.length < 3) return;
        fileTypeTable = fileTypeTables[2];
    }

    if (!fileTypeTable) return;

    const fileTypeRows = fileTypeTable.querySelectorAll('tr');
    allFileTypes = Array.from(fileTypeRows).map((row, index) => ({
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

// Expense Category Pagination
let currentPageExpenseCategory = 1;
let perPageExpenseCategory = 10;
let allExpenseCategories = [];
let filteredExpenseCategories = [];

function initializePaginationExpenseCategory() {
    // Find Expense Categories section by looking for the h2 text
    const allH2s = document.querySelectorAll('h2');
    let expenseCategoryTable = null;

    for (let h2 of allH2s) {
        if (h2.textContent.includes('Expense Categories')) {
            expenseCategoryTable = h2.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    // Fallback: use table index method if section not found
    if (!expenseCategoryTable) {
        const expenseCategoryTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (expenseCategoryTables.length < 6) return;
        expenseCategoryTable = expenseCategoryTables[5];
    }

    if (!expenseCategoryTable) return;

    const expenseCategoryRows = expenseCategoryTable.querySelectorAll('tr');
    allExpenseCategories = Array.from(expenseCategoryRows).map((row, index) => ({
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredExpenseCategories = [...allExpenseCategories];
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function displayExpenseCategory() {
    const startIndex = (currentPageExpenseCategory - 1) * perPageExpenseCategory;
    const endIndex = startIndex + perPageExpenseCategory;

    allExpenseCategories.forEach(category => {
        if (category.element) category.element.style.display = 'none';
    });

    const categoriesToShow = filteredExpenseCategories.slice(startIndex, endIndex);
    categoriesToShow.forEach(category => {
        if (category.element) category.element.style.display = '';
    });
}

function updatePaginationExpenseCategory() {
    const totalItems = filteredExpenseCategories.length;
    const totalPages = Math.ceil(totalItems / perPageExpenseCategory);
    const startItem = totalItems === 0 ? 0 : (currentPageExpenseCategory - 1) * perPageExpenseCategory + 1;
    const endItem = Math.min(currentPageExpenseCategory * perPageExpenseCategory, totalItems);

    const pageInfo = document.getElementById('pageInfoExpenseCategory');
    if (pageInfo) pageInfo.textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;

    const prevBtn = document.getElementById('prevBtnExpenseCategory');
    const prevSingleBtn = document.getElementById('prevSingleBtnExpenseCategory');
    const nextBtn = document.getElementById('nextBtnExpenseCategory');
    const nextSingleBtn = document.getElementById('nextSingleBtnExpenseCategory');

    if (prevBtn) prevBtn.disabled = currentPageExpenseCategory === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageExpenseCategory === 1;
    if (nextBtn) nextBtn.disabled = currentPageExpenseCategory === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageExpenseCategory === totalPages || totalPages === 0;

    updatePageNumbersExpenseCategory(totalPages);
}

function updatePageNumbersExpenseCategory(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersExpenseCategory');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    let startPage = Math.max(1, currentPageExpenseCategory - 2);
    let endPage = Math.min(totalPages, startPage + 4);

    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        pageHtml += `
            <button onclick="goToPageExpenseCategory(${i})"
                    class="px-2 py-1 text-xs ${i === currentPageExpenseCategory ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600'} transition-colors">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageExpenseCategory() {
    const newPerPage = parseInt(document.getElementById('perPageExpenseCategory')?.value || 10);
    perPageExpenseCategory = newPerPage;
    currentPageExpenseCategory = 1;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function filterExpenseCategory() {
    const searchTerm = (document.getElementById('searchFilterExpenseCategory')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterExpenseCategory')?.value || '');

    filteredExpenseCategories = allExpenseCategories.filter(category => {
        const matchesSearch = searchTerm === '' || category.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || category.searchText.includes(statusFilter.toLowerCase());

        return matchesSearch && matchesStatus;
    });

    currentPageExpenseCategory = 1;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function resetFiltersExpenseCategory() {
    if (document.getElementById('searchFilterExpenseCategory')) document.getElementById('searchFilterExpenseCategory').value = '';
    if (document.getElementById('statusFilterExpenseCategory')) document.getElementById('statusFilterExpenseCategory').value = '';

    filteredExpenseCategories = [...allExpenseCategories];
    currentPageExpenseCategory = 1;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function previousPageExpenseCategory() {
    if (currentPageExpenseCategory > 1) {
        currentPageExpenseCategory--;
        displayExpenseCategory();
        updatePaginationExpenseCategory();
    }
}

function nextPageExpenseCategory() {
    const totalPages = Math.ceil(filteredExpenseCategories.length / perPageExpenseCategory);
    if (currentPageExpenseCategory < totalPages) {
        currentPageExpenseCategory++;
        displayExpenseCategory();
        updatePaginationExpenseCategory();
    }
}

function firstPageExpenseCategory() {
    currentPageExpenseCategory = 1;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function lastPageExpenseCategory() {
    const totalPages = Math.ceil(filteredExpenseCategories.length / perPageExpenseCategory);
    currentPageExpenseCategory = totalPages;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

function goToPageExpenseCategory(page) {
    currentPageExpenseCategory = page;
    displayExpenseCategory();
    updatePaginationExpenseCategory();
}

// Section Type Pagination
let currentPageSectionType = 1;
let perPageSectionType = 10;
let allSectionTypes = [];
let filteredSectionTypes = [];

function initializePaginationSectionType() {
    // Find Section Types section by looking for the h2 text
    const allH2s = document.querySelectorAll('h2');
    let sectionTypeTable = null;

    for (let h2 of allH2s) {
        if (h2.textContent.includes('Section Types')) {
            sectionTypeTable = h2.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    if (!sectionTypeTable) return;

    const sectionTypeRows = sectionTypeTable.querySelectorAll('tr');
    allSectionTypes = Array.from(sectionTypeRows).map((row, index) => ({
        id: index,
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredSectionTypes = [...allSectionTypes];
    displaySectionType();
    updatePaginationSectionType();
}

function displaySectionType() {
    const startIndex = (currentPageSectionType - 1) * perPageSectionType;
    const endIndex = startIndex + perPageSectionType;

    allSectionTypes.forEach(sectionType => {
        if (sectionType.element) sectionType.element.style.display = 'none';
    });

    const sectionTypesToShow = filteredSectionTypes.slice(startIndex, endIndex);
    sectionTypesToShow.forEach(sectionType => {
        if (sectionType.element) sectionType.element.style.display = '';
    });
}

function updatePaginationSectionType() {
    const totalItems = filteredSectionTypes.length;
    const totalPages = Math.ceil(totalItems / perPageSectionType);
    const startItem = totalItems === 0 ? 0 : (currentPageSectionType - 1) * perPageSectionType + 1;
    const endItem = Math.min(currentPageSectionType * perPageSectionType, totalItems);

    const pageInfo = document.getElementById('pageInfoSectionType');
    if (pageInfo) pageInfo.textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;

    const prevBtn = document.getElementById('prevBtnSectionType');
    const prevSingleBtn = document.getElementById('prevSingleBtnSectionType');
    const nextBtn = document.getElementById('nextBtnSectionType');
    const nextSingleBtn = document.getElementById('nextSingleBtnSectionType');

    if (prevBtn) prevBtn.disabled = currentPageSectionType === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageSectionType === 1;
    if (nextBtn) nextBtn.disabled = currentPageSectionType === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageSectionType === totalPages || totalPages === 0;

    updatePageNumbersSectionType(totalPages);
}

function updatePageNumbersSectionType(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersSectionType');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    let startPage = Math.max(1, currentPageSectionType - 2);
    let endPage = Math.min(totalPages, startPage + 4);

    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        pageHtml += `
            <button onclick="goToPageSectionType(${i})"
                    class="px-2 py-1 text-xs ${i === currentPageSectionType ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600'} transition-colors">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageSectionType() {
    const select = document.getElementById('perPageSectionType');
    if (select) {
        perPageSectionType = parseInt(select.value);
        currentPageSectionType = 1;
        displaySectionType();
        updatePaginationSectionType();
    }
}

function filterSectionType() {
    const searchInput = document.getElementById('searchFilterSectionType');
    const statusFilter = document.getElementById('statusFilterSectionType');

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusFilter ? statusFilter.value : '';

    filteredSectionTypes = allSectionTypes.filter(sectionType => {
        const matchesSearch = sectionType.searchText.includes(searchTerm);
        const matchesStatus = !statusValue || sectionType.searchText.includes(statusValue.toLowerCase());
        return matchesSearch && matchesStatus;
    });

    currentPageSectionType = 1;
    displaySectionType();
    updatePaginationSectionType();
}

function resetFiltersSectionType() {
    const searchInput = document.getElementById('searchFilterSectionType');
    const statusFilter = document.getElementById('statusFilterSectionType');

    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';

    filteredSectionTypes = [...allSectionTypes];
    currentPageSectionType = 1;
    displaySectionType();
    updatePaginationSectionType();
}

function previousPageSectionType() {
    if (currentPageSectionType > 1) {
        currentPageSectionType--;
        displaySectionType();
        updatePaginationSectionType();
    }
}

function nextPageSectionType() {
    const totalPages = Math.ceil(filteredSectionTypes.length / perPageSectionType);
    if (currentPageSectionType < totalPages) {
        currentPageSectionType++;
        displaySectionType();
        updatePaginationSectionType();
    }
}

function firstPageSectionType() {
    currentPageSectionType = 1;
    displaySectionType();
    updatePaginationSectionType();
}

function lastPageSectionType() {
    const totalPages = Math.ceil(filteredSectionTypes.length / perPageSectionType);
    currentPageSectionType = totalPages;
    displaySectionType();
    updatePaginationSectionType();
}

function goToPageSectionType(page) {
    currentPageSectionType = page;
    displaySectionType();
    updatePaginationSectionType();
}

// Event Status Pagination
let currentPageEventStatus = 1;
let perPageEventStatus = 10;
let allEventStatuses = [];
let filteredEventStatuses = [];

function initializePaginationEventStatus() {
    // Find Event Status section by looking for the h1 text
    const allH1s = document.querySelectorAll('h1');
    let eventStatusTable = null;

    for (let h1 of allH1s) {
        if (h1.textContent.includes('Event Status')) {
            eventStatusTable = h1.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    // Fallback: use table index method if section not found
    if (!eventStatusTable) {
        const eventStatusTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (eventStatusTables.length < 5) return;
        eventStatusTable = eventStatusTables[4];
    }

    if (!eventStatusTable) return;

    const eventStatusRows = eventStatusTable.querySelectorAll('tr');
    allEventStatuses = Array.from(eventStatusRows).map((row, index) => ({
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredEventStatuses = [...allEventStatuses];
    displayEventStatus();
    updatePaginationEventStatus();
}

function displayEventStatus() {
    const startIndex = (currentPageEventStatus - 1) * perPageEventStatus;
    const endIndex = startIndex + perPageEventStatus;

    allEventStatuses.forEach(status => {
        if (status.element) status.element.style.display = 'none';
    });

    const statusesToShow = filteredEventStatuses.slice(startIndex, endIndex);
    statusesToShow.forEach(status => {
        if (status.element) status.element.style.display = '';
    });
}

function updatePaginationEventStatus() {
    const totalItems = filteredEventStatuses.length;
    const totalPages = Math.ceil(totalItems / perPageEventStatus);
    const startItem = totalItems === 0 ? 0 : (currentPageEventStatus - 1) * perPageEventStatus + 1;
    const endItem = Math.min(currentPageEventStatus * perPageEventStatus, totalItems);

    const pageInfo = document.getElementById('pageInfoEventStatus');
    if (pageInfo) pageInfo.textContent = `Showing ${startItem} to ${endItem} of ${totalItems} records`;

    const prevBtn = document.getElementById('prevBtnEventStatus');
    const prevSingleBtn = document.getElementById('prevSingleBtnEventStatus');
    const nextBtn = document.getElementById('nextBtnEventStatus');
    const nextSingleBtn = document.getElementById('nextSingleBtnEventStatus');

    if (prevBtn) prevBtn.disabled = currentPageEventStatus === 1;
    if (prevSingleBtn) prevSingleBtn.disabled = currentPageEventStatus === 1;
    if (nextBtn) nextBtn.disabled = currentPageEventStatus === totalPages || totalPages === 0;
    if (nextSingleBtn) nextSingleBtn.disabled = currentPageEventStatus === totalPages || totalPages === 0;

    updatePageNumbersEventStatus(totalPages);
}

function updatePageNumbersEventStatus(totalPages) {
    const pageNumbersContainer = document.getElementById('pageNumbersEventStatus');
    if (!pageNumbersContainer) return;

    pageNumbersContainer.innerHTML = '';
    if (totalPages <= 1) return;

    let startPage = Math.max(1, currentPageEventStatus - 2);
    let endPage = Math.min(totalPages, startPage + 4);

    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    let pageHtml = '';
    for (let i = startPage; i <= endPage; i++) {
        pageHtml += `
            <button onclick="goToPageEventStatus(${i})"
                    class="px-2 py-1 text-xs ${i === currentPageEventStatus ? 'bg-blue-500 text-white' : 'text-gray-600 hover:text-blue-600'} transition-colors">
                ${i}
            </button>
        `;
    }
    pageNumbersContainer.innerHTML = pageHtml;
}

function changePerPageEventStatus() {
    const newPerPage = parseInt(document.getElementById('perPageEventStatus')?.value || 10);
    perPageEventStatus = newPerPage;
    currentPageEventStatus = 1;
    displayEventStatus();
    updatePaginationEventStatus();
}

function filterEventStatus() {
    const searchTerm = (document.getElementById('searchFilterEventStatus')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('statusFilterEventStatus')?.value || '');

    filteredEventStatuses = allEventStatuses.filter(status => {
        const matchesSearch = searchTerm === '' || status.searchText.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status.searchText.includes(statusFilter.toLowerCase());
        return matchesSearch && matchesStatus;
    });

    currentPageEventStatus = 1;
    displayEventStatus();
    updatePaginationEventStatus();
}

function resetFiltersEventStatus() {
    if (document.getElementById('searchFilterEventStatus')) document.getElementById('searchFilterEventStatus').value = '';
    if (document.getElementById('statusFilterEventStatus')) document.getElementById('statusFilterEventStatus').value = '';

    filteredEventStatuses = [...allEventStatuses];
    currentPageEventStatus = 1;
    displayEventStatus();
    updatePaginationEventStatus();
}

function previousPageEventStatus() {
    if (currentPageEventStatus > 1) {
        currentPageEventStatus--;
        displayEventStatus();
        updatePaginationEventStatus();
    }
}

function nextPageEventStatus() {
    const totalPages = Math.ceil(filteredEventStatuses.length / perPageEventStatus);
    if (currentPageEventStatus < totalPages) {
        currentPageEventStatus++;
        displayEventStatus();
        updatePaginationEventStatus();
    }
}

function firstPageEventStatus() {
    currentPageEventStatus = 1;
    displayEventStatus();
    updatePaginationEventStatus();
}

function lastPageEventStatus() {
    const totalPages = Math.ceil(filteredEventStatuses.length / perPageEventStatus);
    currentPageEventStatus = totalPages;
    displayEventStatus();
    updatePaginationEventStatus();
}

function goToPageEventStatus(page) {
    currentPageEventStatus = page;
    displayEventStatus();
    updatePaginationEventStatus();
}

// Specialization Pagination
let currentPageSpecialization = 1;
let perPageSpecialization = 10;
let allSpecializations = [];
let filteredSpecializations = [];

function initializePaginationSpecialization() {
    // Find Specialization section by looking for the h1 text
    const allH1s = document.querySelectorAll('h1');
    let specializationTable = null;

    for (let h1 of allH1s) {
        if (h1.textContent.includes('Specialization')) {
            specializationTable = h1.closest('.bg-white').querySelector('table tbody');
            break;
        }
    }

    // Fallback: use table index method if section not found
    if (!specializationTable) {
        const specializationTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
        if (specializationTables.length < 4) return;
        specializationTable = specializationTables[3];
    }

    if (!specializationTable) return;

    const specializationRows = specializationTable.querySelectorAll('tr');
    allSpecializations = Array.from(specializationRows).map((row, index) => ({
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
    if (payeeTables.length < 7) return;

    const payeeRows = payeeTables[6].querySelectorAll('tr'); // Payee List is index 6
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

// Agency Pagination - Now handled by Alpine.js
// let currentPageAgency = 1;
// let perPageAgency = 10;
// let allAgencies = [];
// let filteredAgencies = [];

// Agency pagination now handled by Alpine.js
/*
function initializePaginationAgency() {
    // Agency section is the last table (index 6 - after Types, Status, FileType, Specialization, EventStatus, ExpenseCategory, Payee)
    const allTables = document.querySelectorAll('.bg-white.rounded.shadow-md.border.border-gray-300 table tbody');
    if (allTables.length < 8) return; // Need at least 8 tables

    const agencyTbody = allTables[7]; // Agency is the 8th table (index 7)
    const agencyRows = agencyTbody.querySelectorAll('tr');

    allAgencies = Array.from(agencyRows).map((row, index) => ({
        element: row,
        searchText: row.textContent.toLowerCase()
    }));

    filteredAgencies = [...allAgencies];
    displayAgency();
    updatePaginationAgency();

}
*/

/*
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
*/

/*
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
*/
</script>


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