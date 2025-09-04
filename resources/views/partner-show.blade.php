@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Partner Details</span>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Partner Details</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Partner Reference: {{ $partner->partner_code }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('partner.edit', $partner->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Partner
                    </a>
                    <form action="{{ route('partner.toggle-ban', $partner->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-{{ $partner->is_banned ? 'green' : 'red' }}-600 hover:bg-{{ $partner->is_banned ? 'green' : 'red' }}-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">{{ $partner->is_banned ? 'check_circle' : 'block' }}</span>
                            {{ $partner->is_banned ? 'Unban Partner' : 'Ban Partner' }}
                        </button>
                    </form>
                    <form action="{{ route('partner.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partner? This action cannot be undone.')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">delete</span>
                            Delete Partner
                        </button>
                    </form>
                    <a href="{{ route('partner.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4 md:p-6">
            <!-- Firm Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">business</span>
                        <h2 class="text-sm font-semibold">Firm Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Firm Name</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $partner->firm_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Partner Code</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $partner->partner_code }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-block {{ $partner->status_badge_color }} px-2 py-1 rounded-full text-[11px] font-medium">
                                {{ ucfirst($partner->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Contact No</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->contact_no }}</p>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->email ?? 'N/A' }}</p>
                    </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Operational Status</label>
                            <span class="inline-block {{ $partner->status_badge_color }} px-2 py-1 rounded-full text-[11px] font-medium">
                                {{ ucfirst($partner->status) }}
                            </span>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Ban Status</label>
                            <span class="inline-block {{ $partner->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded-full text-[11px] font-medium">
                                {{ $partner->is_banned ? 'Banned' : 'Not Banned' }}
                            </span>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">User Account</label>
                            @if($partner->user)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-[11px] font-medium">
                                    {{ $partner->user->email }}
                                </span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-[11px] font-medium">
                                    No User Account
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">location_on</span>
                        <h2 class="text-sm font-semibold">Address Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Firm Address</label>
                        <p class="text-[11px] text-gray-900 leading-relaxed">{{ $partner->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Incharge Person Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">person</span>
                        <h2 class="text-sm font-semibold">Incharge Person Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Incharge Name</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $partner->incharge_name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Contact No</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->incharge_contact }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->incharge_email ?? 'N/A' }}</p>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bar Council Number</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->bar_council_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-orange-600 to-orange-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">work</span>
                        <h2 class="text-sm font-semibold">Professional Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Specialization</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->specialization ?? 'N/A' }}</p>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Years of Experience</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->years_of_experience ? $partner->years_of_experience . ' years' : 'N/A' }}</p>
                    </div>
                    <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Registration Date</label>
                            <p class="text-[11px] text-gray-900">{{ $partner->registration_date ? $partner->registration_date->format('d F Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-gray-600 to-gray-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">note</span>
                        <h2 class="text-sm font-semibold">Additional Notes</h2>
                    </div>
                </div>
                <div class="p-4">
                    @if($partner->notes)
                        <div class="text-[11px] text-gray-900 leading-relaxed">{{ $partner->notes }}</div>
                    @else
                        <div class="text-center py-3">
                            <span class="material-icons text-gray-400 text-xl mb-1">note_add</span>
                            <p class="text-xs text-gray-500">No notes available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div></div>
                <div class="text-xs text-gray-500">
                    @php
                        $systemSettings = \App\Models\SystemSetting::getSystemSettings();
                        $dateFormat = $systemSettings->date_format ?? 'l, j F Y';
                        $timeFormat = $systemSettings->time_format ?? 'g:i:s a';
                        $timezone = $systemSettings->time_zone ?? 'Asia/Kuala_Lumpur';
                        
                        if ($partner->updated_at) {
                            $formattedDateTime = $partner->updated_at->setTimezone($timezone)->format($dateFormat . ' - ' . $timeFormat);
                            $relativeTime = $partner->updated_at->diffForHumans();
                        } else {
                            $formattedDateTime = 'Unknown';
                            $relativeTime = 'Unknown';
                        }
                    @endphp
                    Last updated: {{ $formattedDateTime }} 
                    <span class="text-gray-400">({{ $relativeTime }})</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 