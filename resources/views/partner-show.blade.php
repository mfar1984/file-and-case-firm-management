@extends('layouts.app')

@section('breadcrumb')
    Partner > View
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-orange-600">business</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">View Partner: {{ $partner->firm_name }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Partner ID: {{ $partner->partner_code }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-block {{ $partner->status_badge_color }} px-2 py-1 rounded-full text-xs">{{ ucfirst($partner->status) }}</span>
                    <form action="{{ route('partner.toggle-ban', $partner->id) }}" method="POST">
                        @csrf
                        <button class="px-3 py-1.5 {{ $partner->is_banned ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded text-xs">{{ $partner->is_banned ? 'Unban' : 'Ban' }}</button>
                    </form>
                    <form action="{{ route('partner.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Delete this partner?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Delete</button>
                    </form>
                    <a href="{{ route('partner.index') }}" class="px-3 py-1.5 border border-gray-300 rounded text-xs">Back</a>
                </div>
            </div>
        </div>
        <div class="p-4 md:p-6">
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Firm Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name</label>
                        <p class="text-xs text-gray-900 font-medium">{{ $partner->firm_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-xs text-gray-900">{{ $partner->email ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <p class="text-xs text-gray-900">{{ $partner->address }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact No</label>
                        <p class="text-xs text-gray-900">{{ $partner->contact_no }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Specialization</label>
                        <p class="text-xs text-gray-900">{{ $partner->specialization ? ucfirst($partner->specialization).' Law' : '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Incharge Person</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Name</label>
                        <p class="text-xs text-gray-900">{{ $partner->incharge_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <p class="text-xs text-gray-900">{{ $partner->incharge_contact }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-xs text-gray-900">{{ $partner->incharge_email ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <p class="text-xs text-gray-900">{{ ucfirst($partner->status) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Years of Experience</label>
                        <p class="text-xs text-gray-900">{{ $partner->years_of_experience ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bar Council Number</label>
                        <p class="text-xs text-gray-900">{{ $partner->bar_council_number ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Registration Date</label>
                        <p class="text-xs text-gray-900">{{ $partner->registration_date ? $partner->registration_date->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Notes</h3>
                <p class="text-xs text-gray-900">{{ $partner->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection 