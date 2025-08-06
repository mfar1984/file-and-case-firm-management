@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Case Details</span>
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">folder</span>
                        <h1 class="text-xl font-bold text-gray-800 text-[14px]">Case Details</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Case Reference: C-2025-001</p>
                </div>
                <div class="flex space-x-2">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">edit</span>
                        Edit Case
                    </button>
                    <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                        <span class="material-icons text-xs mr-1">print</span>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-orange-600 to-orange-700 text-white">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-xs">person</span>
                <h2 class="text-sm font-semibold">Client Information</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Client Name</label>
                    <p class="text-[11px] text-gray-900 font-medium">Ahmad bin Mohamed</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Identity Card / Passport</label>
                    <p class="text-[11px] text-gray-900">850101-01-1234</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                    <p class="text-[11px] text-gray-900">+60 12-345 6789</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-[11px] text-gray-900">ahmad.mohamed@email.com</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Occupation</label>
                    <p class="text-[11px] text-gray-900">Business Owner</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                    <p class="text-[11px] text-gray-900">No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Information -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-xs">account_balance_wallet</span>
                <h2 class="text-sm font-semibold">Financial Information</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Quotation Amount</label>
                    <p class="text-[11px] text-gray-900 font-medium">RM 15,000.00</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Amount Paid</label>
                    <p class="text-[11px] text-gray-900">RM 5,000.00</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Balance</label>
                    <p class="text-[11px] text-gray-900 font-medium text-red-600">RM 10,000.00</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Status</label>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-[11px] font-medium">
                        Partial Payment
                    </span>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-medium text-gray-700">Payment Progress</span>
                    <span class="text-xs text-gray-500">33%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-emerald-600 h-2 rounded-full" style="width: 33%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Information -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-xs">description</span>
                <h2 class="text-sm font-semibold">Case Information</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Case Reference</label>
                    <p class="text-[11px] text-gray-900 font-medium">C-2025-001</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">File Reference</label>
                    <p class="text-[11px] text-gray-900">F-2025-001</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Court Reference</label>
                    <p class="text-[11px] text-gray-900">CR-2025-001</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Case Type</label>
                    <p class="text-[11px] text-gray-900">Civil Action</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded-full text-[11px] font-medium">
                        Open File
                    </span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Open Date</label>
                    <p class="text-[11px] text-gray-900">15 January 2025</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Partner In Charge</label>
                    <p class="text-[11px] text-gray-900">Ahmad bin Abdullah</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Court</label>
                    <p class="text-[11px] text-gray-900">High Court of Malaya, Kuala Lumpur</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Timeline -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-xs">timeline</span>
                    <h2 class="text-sm font-semibold">Case Timeline</h2>
                </div>
                <button @click="$dispatch('open-modal', 'add-timeline')" class="bg-white text-green-600 px-3 py-1 rounded-md text-xs font-medium flex items-center hover:bg-gray-50 transition-colors">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Event
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-2"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-[11px] font-medium text-gray-900">Case Opened</h3>
                                <p class="text-[11px] text-gray-600">Initial consultation and case registration</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[11px] text-gray-500">15 Jan 2025</span>
                                <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                    <span class="material-icons text-yellow-700 text-xs">edit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mt-2"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-[11px] font-medium text-gray-900">Quotation Sent</h3>
                                <p class="text-[11px] text-gray-600">Legal fees quotation provided to client</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[11px] text-gray-500">18 Jan 2025</span>
                                <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                    <span class="material-icons text-yellow-700 text-xs">edit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-green-500 rounded-full mt-2"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-[11px] font-medium text-gray-900">Client Agreement</h3>
                                <p class="text-[11px] text-gray-600">Client accepted quotation and signed agreement</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[11px] text-gray-500">22 Jan 2025</span>
                                <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                    <span class="material-icons text-yellow-700 text-xs">edit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mt-2"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-[11px] font-medium text-gray-900">Document Preparation</h3>
                                <p class="text-[11px] text-gray-600">Legal documents being prepared</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[11px] text-gray-500">25 Jan 2025</span>
                                <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                    <span class="material-icons text-yellow-700 text-xs">edit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-xs">folder</span>
                    <h2 class="text-sm font-semibold">Documents</h2>
                </div>
                <button @click="$dispatch('open-modal', 'add-document')" class="bg-white text-purple-600 px-3 py-1 rounded-md text-xs font-medium flex items-center hover:bg-gray-50 transition-colors">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Document
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="material-icons text-red-500 text-xs">picture_as_pdf</span>
                        <div>
                            <p class="text-[11px] font-medium text-gray-900">Client Agreement.pdf</p>
                            <p class="text-[11px] text-gray-500">Contract • 2.3 MB • Uploaded 22 Jan 2025</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                            <span class="material-icons text-blue-600 text-xs">visibility</span>
                        </button>
                        <button class="p-1 bg-green-50 rounded hover:bg-green-100 border border-green-100" title="Download">
                            <span class="material-icons text-green-600 text-xs">download</span>
                        </button>
                        <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                            <span class="material-icons text-red-600 text-xs">delete</span>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="material-icons text-blue-500 text-xs">description</span>
                        <div>
                            <p class="text-[11px] font-medium text-gray-900">Court Documents.docx</p>
                            <p class="text-[11px] text-gray-500">Court Filing • 1.8 MB • Uploaded 25 Jan 2025</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                            <span class="material-icons text-blue-600 text-xs">visibility</span>
                        </button>
                        <button class="p-1 bg-green-50 rounded hover:bg-green-100 border border-green-100" title="Download">
                            <span class="material-icons text-green-600 text-xs">download</span>
                        </button>
                        <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                            <span class="material-icons text-red-600 text-xs">delete</span>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <span class="material-icons text-green-500 text-xs">image</span>
                        <div>
                            <p class="text-[11px] font-medium text-gray-900">Evidence Photos.jpg</p>
                            <p class="text-[11px] text-gray-500">Evidence • 4.2 MB • Uploaded 26 Jan 2025</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-1 bg-blue-50 rounded hover:bg-blue-100 border border-blue-100" title="View">
                            <span class="material-icons text-blue-600 text-xs">visibility</span>
                        </button>
                        <button class="p-1 bg-green-50 rounded hover:bg-green-100 border border-green-100" title="Download">
                            <span class="material-icons text-green-600 text-xs">download</span>
                        </button>
                        <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                            <span class="material-icons text-red-600 text-xs">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
        <div class="p-2 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-xs">flash_on</span>
                <h2 class="text-sm font-semibold">Quick Actions</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100">
                    <span class="material-icons text-blue-600 text-lg mb-2">receipt</span>
                    <span class="text-[11px] font-medium text-blue-700">Create Invoice</span>
                </button>
                
                <button class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors border border-green-100">
                    <span class="material-icons text-green-600 text-lg mb-2">event</span>
                    <span class="text-[11px] font-medium text-green-700">Schedule Hearing</span>
                </button>
                
                <button class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors border border-purple-100">
                    <span class="material-icons text-purple-600 text-lg mb-2">email</span>
                    <span class="text-[11px] font-medium text-purple-700">Send Email</span>
                </button>
                
                <button class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors border border-orange-100">
                    <span class="material-icons text-orange-600 text-lg mb-2">phone</span>
                    <span class="text-[11px] font-medium text-orange-700">Call Client</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail === 'add-document') open = true"
     @close-modal.window="open = false"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-purple-600">upload_file</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">Add New Document</h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Document Title *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="e.g., Court Filing Document" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Document Type *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                    <option value="">Select Type</option>
                                    <option value="contract">Contract</option>
                                    <option value="court_filing">Court Filing</option>
                                    <option value="evidence">Evidence</option>
                                    <option value="correspondence">Correspondence</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Upload File *</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                                    <span class="material-icons text-gray-400 text-2xl mb-2">cloud_upload</span>
                                    <p class="text-xs text-gray-500">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, JPG, PNG (max 10MB)</p>
                                    <input type="file" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" rows="3" placeholder="Enter document description"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                    Upload Document
                </button>
                <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Timeline Event Modal -->
<div x-data="{ open: false }" 
     @open-modal.window="if ($event.detail === 'add-timeline') open = true"
     @close-modal.window="open = false"
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-icons text-green-600 text-xs">event</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">Add Timeline Event</h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Event Title *</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g., Court Hearing Scheduled" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Event Type *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">Select Type</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="court_hearing">Court Hearing</option>
                                    <option value="document_filing">Document Filing</option>
                                    <option value="payment">Payment</option>
                                    <option value="settlement">Settlement</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Date *</label>
                                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Event Time</label>
                                    <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" rows="3" placeholder="Enter event description"></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="e.g., High Court of Malaya, Kuala Lumpur">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                    Add Event
                </button>
                <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 