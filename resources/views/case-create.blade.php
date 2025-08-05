@extends('layouts.app')

@section('breadcrumb')
    Case > Add Case
@endsection

@section('content')
<div class="px-6 pt-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">add_circle</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Add New Case</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new court case with all necessary details.</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <form class="space-y-6">
                <!-- Case Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Case Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Reference *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 2025-001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">File Reference *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., NF-00126" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Reference</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., CR-2025-001">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Type *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select case type...</option>
                                <option value="civil">Civil Case</option>
                                <option value="criminal">Criminal Case</option>
                                <option value="family">Family Law</option>
                                <option value="corporate">Corporate Law</option>
                                <option value="property">Property Law</option>
                                <option value="employment">Employment Law</option>
                                <option value="sharia">Sharia Law</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., High Court of Malaya">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Judge Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Y.A. Dato' Ahmad">
                        </div>
                    </div>
                </div>

                <!-- Client Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Client Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Client Name *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select client...</option>
                                <option value="1">Ahmad bin Abdullah</option>
                                <option value="2">Sdn Bhd Property</option>
                                <option value="3">Lim Siew Mei</option>
                                <option value="4">Tan Family Trust</option>
                                <option value="5">Wong Corporation</option>
                                <option value="6">Singh & Associates</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Partner In Charge *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select partner...</option>
                                <option value="1">A. Rahman</option>
                                <option value="2">S. Kumar</option>
                                <option value="3">M. Lim</option>
                                <option value="4">N. Tan</option>
                                <option value="5">K. Wong</option>
                                <option value="6">R. Singh</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Opposing Party</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name of opposing party">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Opposing Counsel</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name of opposing counsel">
                        </div>
                    </div>
                </div>

                <!-- Case Details Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Case Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Open Date *</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Expected Close Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Description *</label>
                            <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of the case..." required></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Summary</label>
                            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Detailed summary of the case..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Financial Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Estimated Fee (RM)</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Retainer Amount (RM)</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select payment terms...</option>
                                <option value="upfront">Upfront Payment</option>
                                <option value="installment">Installment</option>
                                <option value="monthly">Monthly</option>
                                <option value="upon_completion">Upon Completion</option>
                                <option value="hourly">Hourly Rate</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Priority Level</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select status...</option>
                                <option value="consultation">Consultation</option>
                                <option value="quotation">Quotation</option>
                                <option value="open_file" selected>Open File</option>
                                <option value="proceed">Proceed</option>
                                <option value="closed_file">Closed File</option>
                                <option value="cancel">Cancel</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes or comments..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('case.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md text-xs font-medium hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-xs font-medium hover:bg-blue-700">
                        Create Case
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 