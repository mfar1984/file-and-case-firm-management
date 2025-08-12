@extends('layouts.app')

@section('breadcrumb')
    Client > View
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
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">View Client: {{ $client->name }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Client ID: {{ $client->client_code }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-block {{ $client->status_badge_color }} px-2 py-1 rounded-full text-xs">{{ $client->is_banned ? 'Banned' : 'Active' }}</span>
                    <form action="{{ route('client.toggle-ban', $client->id) }}" method="POST">
                        @csrf
                        <button class="px-3 py-1.5 {{ $client->is_banned ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded text-xs">{{ $client->is_banned ? 'Unban' : 'Ban' }}</button>
                    </form>
                    <form action="{{ route('client.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Delete this client?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs">Delete</button>
                    </form>
                    <a href="{{ route('client.index') }}" class="px-3 py-1.5 border border-gray-300 rounded text-xs">Back</a>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-4 md:p-6">
            <!-- Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Full Name</label>
                        <p class="text-xs text-gray-900 font-medium">{{ $client->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">IC / Passport</label>
                        <p class="text-xs text-gray-900">{{ $client->ic_passport }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Client Code</label>
                        <p class="text-xs text-gray-900">{{ $client->client_code }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                        <p class="text-xs text-gray-900">{{ $client->is_banned ? 'Banned' : 'Active' }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Phone</label>
                        <p class="text-xs text-gray-900 font-medium">{{ $client->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-xs text-gray-900">{{ $client->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Current Address</label>
                        <p class="text-xs text-gray-900">{{ $client->address_current }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Correspondence Address</label>
                        <p class="text-xs text-gray-900">{{ $client->address_correspondence ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Financial Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">TIN No</label>
                        <p class="text-xs text-gray-900">{{ $client->tin_no ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Job / Work</label>
                        <p class="text-xs text-gray-900">{{ $client->job ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Salary (RM)</label>
                        <p class="text-xs text-gray-900">{{ $client->salary ? number_format($client->salary, 2) : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Family & Professional Contacts -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Family & Professional Contacts</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Dependent</label>
                        <p class="text-xs text-gray-900">{{ $client->dependent }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Family Contact</label>
                        <p class="text-xs text-gray-900">{{ $client->family_contact_name ?? '-' }}{{ $client->family_contact_phone ? ' ('.$client->family_contact_phone.')' : '' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Family Address</label>
                        <p class="text-xs text-gray-900">{{ $client->family_address ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Agent / Banker</label>
                        <p class="text-xs text-gray-900">{{ $client->agent_banker ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Financier / Bank</label>
                        <p class="text-xs text-gray-900">{{ $client->financier_bank ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Other Lawyers (if any)</label>
                        <p class="text-xs text-gray-900">{{ $client->lawyers_parties ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Notes</h3>
                <p class="text-xs text-gray-900">{{ $client->notes ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection 