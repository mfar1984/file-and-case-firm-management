@extends('layouts.app')

@section('breadcrumb')
    File Management
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- File List Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-lg font-bold text-gray-800 text-[13px]">Case Files</h2>
                    <p class="text-xs text-gray-500 mt-1">Manage and track all case-related files</p>
                </div>
                
                <!-- Upload Files Button -->
                <button @click="$dispatch('open-modal', 'file-management')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-2">cloud_upload</span>
                    Upload Files
                </button>
            </div>
        </div>
        

        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <!-- Filter Section - Above table, right aligned -->
            <div class="flex justify-end mb-4">
                <form method="GET" class="flex space-x-2">
                    <select name="case_ref" class="px-3 py-1 border border-gray-300 rounded text-xs">
                        <option value="">All Cases</option>
                        @foreach($cases as $case)
                            <option value="{{ $case }}" {{ request('case_ref') == $case ? 'selected' : '' }}>{{ $case }}</option>
                        @endforeach
                    </select>
                    <select name="file_type" class="px-3 py-1 border border-gray-300 rounded text-xs">
                        <option value="">All Types</option>
                        @foreach($fileTypes as $type)
                            <option value="{{ $type->id }}" {{ request('file_type') == $type->id ? 'selected' : '' }}>{{ $type->description }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-xs">Filter</button>
                </form>
            </div>
            
            @if($files->count() > 0)
                <div class="overflow-visible border border-gray-200 rounded">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-primary-light text-white uppercase text-xs">
                                <th class="py-3 px-4 text-left rounded-tl">File Name</th>
                                <th class="py-3 px-4 text-left">Case Ref</th>
                                <th class="py-3 px-4 text-left">File Type</th>
                                <th class="py-3 px-4 text-left">Size</th>
                                <th class="py-3 px-4 text-left">Upload Date</th>
                                <th class="py-3 px-4 text-left">Status</th>
                                <th class="py-3 px-4 text-center rounded-tr">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($files as $file)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-1 px-4 text-[11px] font-medium">
                                        <div class="flex items-center">
                                            <span class="material-icons {{ $file->file_icon_color }} text-sm mr-2">{{ $file->file_icon }}</span>
                                            {{ $file->file_name }}
                                        </div>
                                    </td>
                                    <td class="py-1 px-4 text-[11px]">{{ $file->case_ref }}</td>
                                    <td class="py-1 px-4 text-[11px]">
                                        <span class="inline-block {{ $file->file_type_badge_color }} px-1.5 py-0.5 rounded-full text-[10px]">
                                            {{ $file->fileType ? $file->fileType->description : 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="py-1 px-4 text-[11px]">{{ $file->formatted_size }}</td>
                                    <td class="py-1 px-4 text-[11px]">{{ $file->created_at->format('d/m/Y') }}</td>
                                    <td class="py-1 px-4 text-[11px]">
                                        <span class="inline-block {{ $file->status_badge_color }} px-1.5 py-0.5 rounded-full text-[10px]">
                                            {{ $file->status }}
                                        </span>
                                        @if($file->is_overdue)
                                            <span class="ml-1 text-red-500 text-[10px]">⚠️ Overdue</span>
                                        @endif
                                    </td>
                                    <td class="py-1 px-4">
                                        <div class="flex justify-center space-x-1 items-center">
                                            <a href="{{ route('file-management.download', $file->id) }}" class="p-0.5 text-blue-600 hover:text-blue-700" title="Download">
                                                <span class="material-icons text-base">download</span>
                                            </a>
                                            <button onclick="openStatusModal({{ $file->id }}, '{{ $file->status }}', '{{ $file->taken_by }}', '{{ $file->purpose }}', '{{ $file->expected_return }}', '{{ $file->rack_location }}')" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                                <span class="material-icons text-base">swap_horiz</span>
                                            </button>
                                            <a href="#" class="p-0.5 text-blue-600 hover:text-blue-700" title="View">
                                                <span class="material-icons text-base">visibility</span>
                                            </a>
                                            <form action="{{ route('file-management.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-0.5 text-red-600 hover:text-red-700" title="Delete">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                    <p class="text-gray-500 text-sm">No files found. Upload your first file above.</p>
                </div>
            @endif
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            <!-- Filter Section for Mobile - Above cards -->
            <div class="border-b border-gray-200 pb-4">
                <form method="GET" class="flex flex-col space-y-2">
                    <select name="case_ref" class="px-3 py-1 border border-gray-300 rounded text-xs">
                        <option value="">All Cases</option>
                        @foreach($cases as $case)
                            <option value="{{ $case }}" {{ request('case_ref') == $case ? 'selected' : '' }}>{{ $case }}</option>
                        @endforeach
                    </select>
                    <select name="file_type" class="px-3 py-1 border border-gray-300 rounded text-xs">
                        <option value="">All Types</option>
                        @foreach($fileTypes as $type)
                            <option value="{{ $type->id }}" {{ request('file_type') == $type->id ? 'selected' : '' }}>{{ $type->description }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-xs">Filter</button>
                </form>
            </div>
            
            @if($files->count() > 0)
                @foreach($files as $file)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="material-icons {{ $file->file_icon_color }} text-lg">{{ $file->file_icon }}</span>
                                <div>
                                    <span class="text-sm font-medium text-gray-800">{{ $file->file_name }}</span>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-block {{ $file->file_type_badge_color }} px-2 py-1 rounded-full text-xs">
                                            {{ $file->fileType ? $file->fileType->description : 'Unknown' }}
                                        </span>
                                        <span class="inline-block {{ $file->status_badge_color }} px-2 py-1 rounded-full text-xs">
                                            {{ $file->status }}
                                        </span>
                                        @if($file->is_overdue)
                                            <span class="text-red-500 text-xs">⚠️ Overdue</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('file-management.download', $file->id) }}" class="p-2 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100">
                                    <span class="material-icons text-blue-600 text-sm">download</span>
                                </a>
                                <button onclick="openStatusModal({{ $file->id }}, '{{ $file->status }}', '{{ $file->taken_by }}', '{{ $file->purpose }}', '{{ $file->expected_return }}', '{{ $file->rack_location }}')" class="p-2 bg-purple-50 rounded hover:bg-purple-100 border border-purple-100">
                                    <span class="material-icons text-purple-600 text-sm">swap_horiz</span>
                                </button>
                                <a href="#" class="p-2 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100">
                                    <span class="material-icons text-blue-600 text-sm">visibility</span>
                                </a>
                                <form action="{{ route('file-management.destroy', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                                        <span class="material-icons text-red-600 text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Case Ref:</span>
                                <span class="text-xs text-gray-800">{{ $file->case_ref }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Size:</span>
                                <span class="text-xs text-gray-800">{{ $file->formatted_size }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Upload Date:</span>
                                <span class="text-xs text-gray-800">{{ $file->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                    <p class="text-gray-500 text-sm">No files found. Upload your first file above.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- File Management Modal -->
<div x-data="{
    open: false,
    searchCase: '',
    selectedCase: '',
    showCaseDropdown: false,
    cases: {{ json_encode($cases) }}
}" 
     @open-modal.window="if ($event.detail === 'file-management') open = true"
     @close-modal.window="open = false"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-blue-600 text-xs">folder</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">Upload New Files</h3>
                        
                        <!-- File Upload Form -->
                        <form action="{{ route('file-management.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Case Selection with Search -->
                                <div class="relative">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Select Case</label>
                                    <div class="relative">
                                        <input type="hidden" name="case_ref" x-model="selectedCase" required>
                                        <input type="text" 
                                               x-model="searchCase" 
                                               @click="showCaseDropdown = true"
                                               @click.away="showCaseDropdown = false"
                                               placeholder="Search case number..."
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               readonly>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                                            <span class="material-icons text-gray-400 text-sm">search</span>
                                        </div>
                                        
                                        <!-- Dropdown with Search Results -->
                                        <div x-show="showCaseDropdown" 
                                             x-transition
                                             class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                            <div class="p-2 border-b border-gray-200">
                                                <input type="text" 
                                                       x-model="searchCase" 
                                                       placeholder="Type to search cases..."
                                                       class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                       @click.stop>
                                            </div>
                                            <div class="max-h-48 overflow-y-auto">
                                                <template x-for="caseItem in cases.filter(c => c.toLowerCase().includes(searchCase.toLowerCase()))" :key="caseItem">
                                                    <div @click="selectedCase = caseItem; searchCase = caseItem; showCaseDropdown = false" 
                                                         class="px-3 py-2 text-xs hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                                         :class="{ 'bg-blue-100': selectedCase === caseItem }">
                                                        <span x-text="caseItem"></span>
                                                        <span x-show="selectedCase === caseItem" class="float-right text-blue-600">✓</span>
                                                    </div>
                                                </template>
                                                <div x-show="cases.filter(c => c.toLowerCase().includes(searchCase.toLowerCase())).length === 0" 
                                                     class="px-3 py-2 text-xs text-gray-500 text-center">
                                                    No cases found
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- File Type -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">File Type</label>
                                    <select name="file_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select file type...</option>
                                        @foreach($fileTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('file_type') == $type->id ? 'selected' : '' }}>{{ $type->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- File Upload -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Upload Files</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                                    <span class="material-icons text-gray-400 text-3xl mb-2">cloud_upload</span>
                                    <p class="text-xs text-gray-600 mb-2">Drag and drop files here, or click to browse</p>
                                    <p class="text-xs text-gray-500">Supports: PDF, DOC, DOCX, JPG, PNG, ZIP, RAR (Max 10MB each)</p>
                                    <input type="file" name="files[]" multiple class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip,.rar" id="modalFileInput">
                                    <button type="button" onclick="document.getElementById('modalFileInput').click()" class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                                        Choose Files
                                    </button>
                                    <div id="modalSelectedFiles" class="mt-3 text-xs text-gray-600"></div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter file description...">{{ old('description') }}</textarea>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button @click="open = false" type="button" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                                    Upload Files
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Status Modal -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" id="statusModal">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Change File Status</h3>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Current Status: <span id="currentStatus" class="text-green-600">IN</span></label>
                        <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded" onchange="toggleStatusFields()">
                            <option value="IN">IN (File in office)</option>
                            <option value="OUT">OUT (File taken out)</option>
                        </select>
                    </div>
                    <div id="outFields" class="hidden">
                        <div>
                            <label class="block text-sm font-medium mb-2">Taken by:</label>
                            <input type="text" name="taken_by" id="takenBy" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Purpose:</label>
                            <textarea name="purpose" id="purpose" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter purpose"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Expected Return:</label>
                            <input type="date" name="expected_return" id="expectedReturn" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div id="inFields">
                        <div>
                            <label class="block text-sm font-medium mb-2">Rack Location:</label>
                            <input type="text" name="rack_location" id="rackLocation" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Enter rack location">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Alpine.js Store for Cases
document.addEventListener('alpine:init', () => {
    Alpine.store('cases', @json($cases));
});

// File input handling for modal
document.getElementById('modalFileInput').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const fileList = document.getElementById('modalSelectedFiles');
    
    if (files.length > 0) {
        fileList.innerHTML = '<strong>Selected files:</strong><br>' + 
            files.map(file => file.name).join('<br>');
    } else {
        fileList.innerHTML = '';
    }
});

// Status modal functions
function openStatusModal(fileId, status, takenBy, purpose, expectedReturn, rackLocation) {
    document.getElementById('statusForm').action = `/file-management/${fileId}/status`;
    document.getElementById('statusSelect').value = status;
    document.getElementById('currentStatus').textContent = status;
    document.getElementById('takenBy').value = takenBy || '';
    document.getElementById('purpose').value = purpose || '';
    document.getElementById('expectedReturn').value = expectedReturn || '';
    document.getElementById('rackLocation').value = rackLocation || '';
    
    toggleStatusFields();
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function toggleStatusFields() {
    const status = document.getElementById('statusSelect').value;
    const outFields = document.getElementById('outFields');
    const inFields = document.getElementById('inFields');
    
    if (status === 'OUT') {
        outFields.classList.remove('hidden');
        inFields.classList.add('hidden');
    } else {
        outFields.classList.remove('hidden');
        inFields.classList.remove('hidden');
    }
}
</script>
@endsection 