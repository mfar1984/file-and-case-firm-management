@extends('layouts.app')

@section('breadcrumb')
    Client > Add New Client
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">person_add</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Add New Client</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new client profile with all necessary information.</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <form class="space-y-6">
                <!-- Personal Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Full Name (IC) *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad bin Abdullah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Identity Card / Passport *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 850101-01-1234 or A12345678" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact Number *</label>
                            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 12-345 6789" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., ahmad@email.com">
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Address Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Current Address *</label>
                            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter current residential address..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Correspondence Address</label>
                            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter correspondence address (if different from current address)..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Financial Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">TIN No</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tax Identification Number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Job / Work</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Engineer, Business Owner">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Salary (RM)</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- Family Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Family Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Dependent</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Number of dependents">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Contact Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Siti binti Ahmad">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Contact Number</label>
                            <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 12-345 6789">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Address</label>
                            <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Family contact address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Professional Contacts Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Professional Contacts</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Agent / Banker</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name of agent or banker">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Financier / Bank</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Bank or financial institution">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Lawyer(s) parties (if any)</label>
                            <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Names of other lawyers involved in the case"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes or comments about the client..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('client.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700">
                        Create Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 