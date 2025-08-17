@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Client Details</span>
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">person</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Client Details</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Client Reference: {{ $client->client_code }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('client.edit', $client->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Client
                    </a>
                    <form action="{{ route('client.toggle-ban', $client->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-{{ $client->is_banned ? 'green' : 'red' }}-600 hover:bg-{{ $client->is_banned ? 'green' : 'red' }}-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">{{ $client->is_banned ? 'check_circle' : 'block' }}</span>
                            {{ $client->is_banned ? 'Unban Client' : 'Ban Client' }}
                        </button>
                    </form>
                    <form action="{{ route('client.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">delete</span>
                            Delete Client
                        </button>
                    </form>
                    <a href="{{ route('client.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">arrow_back</span>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="p-4 md:p-6">
            <!-- Personal Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">person</span>
                        <h2 class="text-sm font-semibold">Personal Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $client->name }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">IC / Passport</label>
                            <p class="text-[11px] text-gray-900">{{ $client->ic_passport ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Party Type</label>
                            <p class="text-[11px] text-gray-900">{{ ucfirst($client->party_type ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Identity Type</label>
                            <p class="text-[11px] text-gray-900">{{ $client->identity_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                            <p class="text-[11px] text-gray-900">{{ ucfirst($client->gender ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nationality</label>
                            <p class="text-[11px] text-gray-900">{{ $client->nationality ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Race</label>
                            <p class="text-[11px] text-gray-900">{{ $client->race ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Client Code</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $client->client_code }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-block {{ $client->is_banned ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} px-2 py-1 rounded-full text-[11px] font-medium">
                                {{ $client->is_banned ? 'Banned' : 'Active' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">User Account</label>
                            @if($client->user)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-[11px] font-medium">
                                    {{ $client->user->email }}
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

            <!-- Contact Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">contact_phone</span>
                        <h2 class="text-sm font-semibold">Contact Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $client->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Mobile Number</label>
                            <p class="text-[11px] text-gray-900">{{ $client->mobile ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fax Number</label>
                            <p class="text-[11px] text-gray-900">{{ $client->fax ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                            <p class="text-[11px] text-gray-900">{{ $client->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">location_on</span>
                        <h2 class="text-sm font-semibold">Address Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    @if($client->addresses->count() > 0)
                        <div class="space-y-4">
                            @foreach($client->addresses->sortByDesc('is_primary')->values() as $index => $address)
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <span class="text-xs font-semibold text-gray-900">
                                                Address {{ $index + 1 }}
                                            </span>
                                            @if($address->is_primary)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <span class="w-1 h-1 bg-blue-400 rounded-full mr-1"></span>
                                                    Primary
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @if($address->address_line1)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Address Line 1</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->address_line1 }}</p>
                                            </div>
                                        @endif
                                        @if($address->address_line2)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Address Line 2</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->address_line2 }}</p>
                                            </div>
                                        @endif
                                        @if($address->address_line3)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Address Line 3</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->address_line3 }}</p>
                                            </div>
                                        @endif
                                        @if($address->postcode)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Postcode</label>
                                                <p class="text-[11px] text-gray-900 font-mono">{{ $address->postcode }}</p>
                                            </div>
                                        @endif
                                        @if($address->city)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">City</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->city }}</p>
                                            </div>
                                        @endif
                                        @if($address->state)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">State</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->state }}</p>
                                            </div>
                                        @endif
                                        @if($address->country)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Country</label>
                                                <p class="text-[11px] text-gray-900">{{ $address->country }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if(!$loop->last)
                                        <div class="border-t border-gray-200 mt-3 pt-3"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="material-icons text-gray-400 text-3xl mb-2">location_off</span>
                            <p class="text-xs text-gray-500">No addresses found for this client.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">account_balance_wallet</span>
                        <h2 class="text-sm font-semibold">Financial Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">TIN Number</label>
                            <p class="text-[11px] text-gray-900 font-medium">{{ $client->tin_no ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Job / Work</label>
                            <p class="text-[11px] text-gray-900">{{ $client->job ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Salary (RM)</label>
                            <p class="text-[11px] text-gray-900 font-medium text-emerald-600">
                                {{ $client->salary ? 'RM ' . number_format($client->salary, 2) : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-orange-600 to-orange-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">family_restroom</span>
                        <h2 class="text-sm font-semibold">Family Information</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Dependents</label>
                            <p class="text-[11px] text-gray-900">{{ $client->dependent ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Family Contact Name</label>
                            <p class="text-[11px] text-gray-900">{{ $client->family_contact_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Family Contact Phone</label>
                            <p class="text-[11px] text-gray-900">{{ $client->family_contact_phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Family Address</label>
                            <p class="text-[11px] text-gray-900">{{ $client->family_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Contacts -->
            <div class="bg-gray-50 p-4 rounded-sm mb-6">
                <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white -mx-4 -mt-4">
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-xs">business</span>
                        <h2 class="text-sm font-semibold">Professional Contacts</h2>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent / Banker</label>
                            <p class="text-[11px] text-gray-900">{{ $client->agent_banker ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Financier / Bank</label>
                            <p class="text-[11px] text-gray-900">{{ $client->financier_bank ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Other Lawyers</label>
                            <p class="text-[11px] text-gray-900">{{ $client->lawyers_parties ?? 'N/A' }}</p>
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
                    @if($client->notes)
                        <div class="text-[11px] text-gray-900 leading-relaxed">{{ $client->notes }}</div>
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
                        
                        if ($client->updated_at) {
                            $formattedDateTime = $client->updated_at->setTimezone($timezone)->format($dateFormat . ' - ' . $timeFormat);
                            $relativeTime = $client->updated_at->diffForHumans();
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