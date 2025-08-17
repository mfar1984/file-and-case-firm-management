@extends('layouts.app')

@section('breadcrumb')
    Partner > Edit Partner
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-yellow-600">edit</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Partner</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8">Update information for {{ $partner->firm_name }} ({{ $partner->partner_code }}).</p>
        </div>
        <div class="p-4 md:p-6">
            <form action="{{ route('partner.update', $partner->id) }}" method="POST" class="space-y-0">
                @csrf
                @method('PUT')
                <!-- Firm Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <span class="material-icons text-blue-600 text-xs mr-2">business</span>
                        Firm Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                            <input type="text" name="firm_name" value="{{ old('firm_name', $partner->firm_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                            <textarea name="address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" required>{{ old('address', $partner->address) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                            <input type="tel" name="contact_no" value="{{ old('contact_no', $partner->contact_no) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $partner->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Incharge Person Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <span class="material-icons text-green-600 text-xs mr-2">person</span>
                        Incharge Person Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Incharge Name *</label>
                            <input type="text" name="incharge_name" value="{{ old('incharge_name', $partner->incharge_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact No *</label>
                            <input type="tel" name="incharge_contact" value="{{ old('incharge_contact', $partner->incharge_contact) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="incharge_email" value="{{ old('incharge_email', $partner->incharge_email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Operational Status *</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="active" {{ old('status', $partner->status)==='active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $partner->status)==='inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $partner->status)==='suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Operational status for business activities</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <span class="material-icons text-purple-600 text-xs mr-2">info</span>
                        Additional Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Specialization</label>
                            <select name="specialization" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->specialist_name }}" {{ old('specialization', $partner->specialization) == $specialization->specialist_name ? 'selected' : '' }}>
                                        {{ $specialization->specialist_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Years of Experience</label>
                            <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $partner->years_of_experience) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" max="80">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Bar Council Number</label>
                            <input type="text" name="bar_council_number" value="{{ old('bar_council_number', $partner->bar_council_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Registration Date</label>
                            <input type="date" name="registration_date" value="{{ old('registration_date', optional($partner->registration_date)->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <span class="material-icons text-orange-600 text-xs mr-2">note</span>
                        Notes
                    </h3>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Additional Notes</label>
                        <textarea name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4">{{ old('notes', $partner->notes) }}</textarea>
                    </div>
                </div>
                <!-- Form Actions -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('partner.show', $partner->id) }}" class="w-full md:w-auto px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-yellow-600 text-white rounded-md text-xs font-medium hover:bg-yellow-700 flex items-center justify-center">
                        <span class="material-icons text-xs mr-1">save</span>
                        Update Partner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 