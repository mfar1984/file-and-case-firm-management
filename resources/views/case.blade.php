@extends('layouts.app')

@section('breadcrumb')
    Case
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">folder</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Case Management</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Manage all court cases, clients, and related documents.</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('case.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                        <span class="material-icons text-sm md:text-xs mr-1">add</span>
                        Add Case
                    </a>
                    <a href="{{ route('file-management.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                        <span class="material-icons text-sm md:text-xs mr-1">folder</span>
                        File Management
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block p-6">
            <div class="overflow-visible border border-gray-200 rounded">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-primary-light text-white uppercase text-xs">
                            <th class="py-3 px-4 text-left rounded-tl">Case Number</th>
                            <th class="py-3 px-4 text-left">Case Title</th>
                            <th class="py-3 px-4 text-left">Court Location</th>
                            <th class="py-3 px-4 text-left">Parties</th>
                            <th class="py-3 px-4 text-left">Partners</th>
                            <th class="py-3 px-4 text-left">Created Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center rounded-tr">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cases as $case)
                        <tr class="hover:bg-gray-50" data-case-row-id="{{ $case->id }}">
                            <td class="py-1 px-4 text-[11px] font-medium">{{ $case->case_number }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->title }}</td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->court_location ?? 'N/A' }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->parties->count() > 0)
                                    @php
                                        $applicants = $case->parties->where('party_type', 'plaintiff');
                                        $respondents = $case->parties->where('party_type', 'defendant');
                                    @endphp
                                    <div class="text-[10px]">
                                        @if($applicants->count() > 0)
                                            <div class="mb-1">
                                                <span class="font-medium text-green-700">Applicants:</span><br>
                                                @foreach($applicants->take(2) as $applicant)
                                                    {{ $applicant->name }}<br>
                                                @endforeach
                                                @if($applicants->count() > 2)
                                                    <span class="text-gray-500">+{{ $applicants->count() - 2 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if($respondents->count() > 0)
                                            <div>
                                                <span class="font-medium text-red-700">Respondents:</span><br>
                                                @foreach($respondents->take(2) as $respondent)
                                                    {{ $respondent->name }}<br>
                                                @endforeach
                                                @if($respondents->count() > 2)
                                                    <span class="text-gray-500">+{{ $respondents->count() - 2 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">No parties</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->partners->count() > 0)
                                    @foreach($case->partners->take(2) as $casePartner)
                                        <div class="mb-1">
                                            <span class="font-medium">{{ $casePartner->partner->firm_name ?? 'N/A' }}</span><br>
                                            <span class="text-gray-500 text-[9px]">{{ $casePartner->partner->incharge_name ?? 'N/A' }}</span>
                                        </div>
                                    @endforeach
                                    @if($case->partners->count() > 2)
                                        <span class="text-gray-500 text-[9px]">+{{ $case->partners->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">No partners</span>
                                @endif
                            </td>
                            <td class="py-1 px-4 text-[11px]">{{ $case->created_at->format('d M Y') }}</td>
                            <td class="py-1 px-4 text-[11px]">
                                @if($case->caseStatus)
                                    <span class="inline-block bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-[10px]">
                                        {{ $case->caseStatus->name }}
                                    </span>
                                @else
                                    <span class="inline-block bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded-full text-[10px]">
                                        Unknown
                                    </span>
                                @endif
                            </td>
                            <td class="py-1 px-4">
                                <div class="flex justify-center space-x-1 items-center" x-data="{ open: false }">
                                    <button @click="open = !open" class="p-0.5 text-purple-600 hover:text-purple-700" title="Change Status">
                                        <span class="material-icons text-base">add</span>
                                    </button>
                                                        <a href="{{ route('case.show', $case->id) }}" class="p-1 text-blue-600 hover:text-blue-700" title="View">
                        <span class="material-icons text-sm">visibility</span>
                    </a>
                                    <a href="{{ route('case.edit', $case->id) }}" class="p-0.5 text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <span class="material-icons text-base">edit</span>
                                    </a>
                                    <button class="p-0.5 text-red-600 hover:text-red-700 delete-case-btn" data-case-id="{{ $case->id }}" title="Delete">
                                        <span class="material-icons text-base">delete</span>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-64 bg-white rounded shadow-xl border border-gray-200 p-3 text-[11px]">
                                        <div class="mb-2 font-bold text-gray-800">Change Status</div>
                                        <ul class="space-y-1">
                                            @foreach($caseStatuses as $status)
                                                <li>
                                                    <button class="w-full text-left px-2 py-1 hover:bg-gray-100 rounded change-status-btn" 
                                                            data-case-id="{{ $case->id }}" 
                                                            data-status-id="{{ $status->id }}"
                                                            data-status-name="{{ $status->name }}">
                                                        {{ $status->name }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                                    <p class="text-sm">No cases found</p>
                                    <p class="text-xs text-gray-400">Create your first case to get started</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden p-4 space-y-4">
            @forelse($cases as $case)
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4" data-case-card-id="{{ $case->id }}" x-data="{ showStatusMenu: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $case->case_number }}</h3>
                        <p class="text-xs text-gray-600">{{ $case->title }}</p>
                    </div>
                    @if($case->caseStatus)
                        <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">{{ $case->caseStatus->name }}</span>
                    @else
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Unknown</span>
                    @endif
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Parties:</span>
                        <span class="text-xs font-medium">
                            @if($case->parties->count() > 0)
                                {{ $case->parties->count() }} parties
                            @else
                                No parties
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Partners:</span>
                        <span class="text-xs font-medium">
                            @if($case->partners->count() > 0)
                                {{ $case->partners->count() }} partners
                            @else
                                No partners
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Court:</span>
                        <span class="text-xs font-medium">{{ $case->court_location ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Created:</span>
                        <span class="text-xs font-medium">{{ $case->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="flex space-x-2">
                    <button @click="showStatusMenu = !showStatusMenu" class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center hover:bg-gray-50">
                        <span class="material-icons text-sm mr-1">add</span>
                        Status
                    </button>
                    <a href="{{ route('case.show', $case->id) }}" class="flex-1 bg-blue-100 text-blue-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">visibility</span>
                        View
                    </a>
                    <a href="{{ route('case.edit', $case->id) }}" class="flex-1 bg-yellow-100 text-yellow-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                        <span class="material-icons text-sm mr-1">edit</span>
                        Edit
                    </a>
                    <button class="flex-1 bg-red-100 text-red-700 py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center delete-case-btn" data-case-id="{{ $case->id }}">
                        <span class="material-icons text-sm mr-1">delete</span>
                        Delete
                    </button>
                </div>
                
                <!-- Mobile Status Dropdown -->
                <div x-show="showStatusMenu" @click.away="showStatusMenu = false" class="mt-3 bg-white rounded-md shadow-lg border border-gray-200 p-3">
                    <div class="mb-2 font-bold text-gray-800 text-xs">Change Status</div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($caseStatuses as $status)
                            <button class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100 rounded change-status-btn" 
                                    data-case-id="{{ $case->id }}" 
                                    data-status-id="{{ $status->id }}"
                                    data-status-name="{{ $status->name }}">
                                {{ $status->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
                <span class="material-icons text-gray-400 text-4xl mb-2">folder_open</span>
                <p class="text-sm text-gray-600">No cases found</p>
                <p class="text-xs text-gray-400">Create your first case to get started</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('change-status-btn')) {
            const caseId = e.target.dataset.caseId;
            const statusId = e.target.dataset.statusId;
            const statusName = e.target.dataset.statusName;
            
            if (confirm('Are you sure you want to change the status to "' + statusName + '"?')) {
                changeCaseStatus(caseId, statusId, statusName);
            }
        }
        if (e.target.closest('.delete-case-btn')) {
            const btn = e.target.closest('.delete-case-btn');
            const caseId = btn.dataset.caseId;
            if (confirm('Delete this case? This action cannot be undone.')) {
                deleteCase(caseId, btn);
            }
        }
    });
    
    function changeCaseStatus(caseId, statusId, statusName) {
        fetch(`/case/${caseId}/change-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status_id: statusId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status changed successfully to: ' + statusName);
                location.reload(); // Reload page to show updated status
            } else {
                alert('Failed to change status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to change status. Please try again.');
        });
    }

    function deleteCase(caseId, triggerEl) {
        fetch(`/case/${caseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Remove desktop row
                const row = triggerEl.closest('tr');
                if (row) {
                    row.remove();
                }
                // Remove mobile card
                const card = triggerEl.closest('[data-case-card-id]');
                if (card) {
                    card.remove();
                }
            } else {
                alert('Failed to delete case: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to delete case.');
        });
    }
});
</script>
@endsection 