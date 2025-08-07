@extends('layouts.app')

@section('breadcrumb')
    Partner > Add New Partner
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">business</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add New Partner</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Add a new partner firm to the system.</p>
        </div>
        
        <form class="p-4 md:p-6">
            <!-- Firm Information -->
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons text-blue-600 text-xs mr-2">business</span>
                    Firm Information
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad & Associates" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter complete address" required></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                        <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 3-1234 5678" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., info@ahmadassociates.com" required>
                    </div>
                </div>
            </div>

            <!-- Incharge Person Information -->
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons text-green-600 text-xs mr-2">person</span>
                    Incharge Person Information
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Incharge Name *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad bin Abdullah" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                        <input type="tel" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 12-345 6789" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., ahmad@ahmadassociates.com" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons text-purple-600 text-xs mr-2">info</span>
                    Additional Information
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Specialization</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Specialization</option>
                            <option value="civil">Civil Law</option>
                            <option value="criminal">Criminal Law</option>
                            <option value="family">Family Law</option>
                            <option value="corporate">Corporate Law</option>
                            <option value="property">Property Law</option>
                            <option value="general">General Practice</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Years of Experience</label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 15" min="0" max="50">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bar Council Number</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., BC12345">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Registration Date</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="material-icons text-orange-600 text-xs mr-2">note</span>
                    Notes
                </h2>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Enter any additional notes or comments about this partner"></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('partner.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Partner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 