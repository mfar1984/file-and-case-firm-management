@extends('layouts.app')

@section('breadcrumb')
    Partner > Add New Partner
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">add_circle</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Add New Partner</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Create a new partner firm with all necessary details.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form action="{{ route('partner.store') }}" method="POST" class="space-y-0">
                @csrf
                
                <!-- Firm Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">business</span>
                        Firm Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the basic firm details and contact information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                            <input type="text" name="firm_name" value="{{ old('firm_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad & Associates" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                            <input type="tel" name="contact_no" value="{{ old('contact_no') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 3-1234 5678" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., info@ahmadassociates.com">
                        </div>
                                            <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Operational Status *</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Operational Status</option>
                            <option value="active" {{ old('status')==='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status')==='suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Operational status for business activities</p>
                    </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Address Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">location_on</span>
                        Address Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the complete firm address</p>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter complete address" required>{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Incharge Person Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-purple-600 text-base mr-2">person</span>
                        Incharge Person Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the details of the person in charge</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Incharge Name *</label>
                            <input type="text" name="incharge_name" value="{{ old('incharge_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad bin Abdullah" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                            <input type="tel" name="incharge_contact" value="{{ old('incharge_contact') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 12-345 6789" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="incharge_email" value="{{ old('incharge_email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., ahmad@ahmadassociates.com">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Bar Council Number</label>
                            <input type="text" name="bar_council_number" value="{{ old('bar_council_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., BC12345">
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Professional Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-orange-600 text-base mr-2">work</span>
                        Professional Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter professional qualifications and experience</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Specialization</label>
                            <select name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->specialist_name }}" {{ old('specialization') == $specialization->specialist_name ? 'selected' : '' }}>
                                        {{ $specialization->specialist_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Years of Experience</label>
                            <input type="number" name="years_of_experience" value="{{ old('years_of_experience') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 15" min="0" max="80">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Registration Date</label>
                            <input type="date" name="registration_date" value="{{ old('registration_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Notes Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-gray-600 text-base mr-2">note</span>
                        Notes
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter any additional notes or comments</p>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Enter any additional notes or comments about this partner">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4">
                    <a href="{{ route('partner.index') }}" class="w-full md:w-auto px-3 py-1.5 text-gray-600 border border-gray-300 rounded-sm text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Create Partner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 