@extends('layouts.app')

@section('breadcrumb')
    Tax Invoice / Edit
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start space-y-4 md:space-y-0">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">receipt</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Edit Tax Invoice</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Update tax invoice details and items.</p>
                </div>
                
                <!-- Back Button -->
                <a href="{{ route('tax-invoice.show', $taxInvoice->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                    <span class="material-icons text-sm md:text-xs mr-1">arrow_back</span>
                    Back to Invoice
                </a>
            </div>
        </div>
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tax-invoice.update', $taxInvoice->id) }}" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Hidden Fields -->
            <input type="hidden" name="case_id" value="{{ $taxInvoice->case_id }}">
            <input type="hidden" name="quotation_id" value="{{ $taxInvoice->quotation_id }}">
            
            <!-- Header Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Invoice Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Invoice Number</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $taxInvoice->invoice_no }}" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Invoice Date *</label>
                        <input name="invoice_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->invoice_date?->format('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Due Date *</label>
                        <input name="due_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->due_date?->format('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                        <select name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="net_30" {{ $taxInvoice->payment_terms === 'net_30' ? 'selected' : '' }}>Net 30</option>
                            <option value="net_15" {{ $taxInvoice->payment_terms === 'net_15' ? 'selected' : '' }}>Net 15</option>
                            <option value="net_7" {{ $taxInvoice->payment_terms === 'net_7' ? 'selected' : '' }}>Net 7</option>
                            <option value="immediate" {{ $taxInvoice->payment_terms === 'immediate' ? 'selected' : '' }}>Immediate</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer Name *</label>
                        <input name="customer_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->customer_name }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer Email</label>
                        <input name="customer_email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->customer_email }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer Phone</label>
                        <input name="customer_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->customer_phone }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact Person</label>
                        <input name="contact_person" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->contact_person }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input name="contact_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->contact_phone }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer Address *</label>
                        <textarea name="customer_address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ $taxInvoice->customer_address }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input name="remark" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $taxInvoice->remark }}">
                    </div>
                </div>
            </div>
            <div id="hidden-items-container"></div>
            
            <!-- Item Entry Section -->
            <div class="mb-8" id="invoiceItems" x-data="{
                items: @js($items),
                amount(item) {
                    let subtotal = item.qty * item.price;
                    let afterDisc = subtotal * (1 - item.disc / 100);
                    let afterTax = afterDisc * (1 + item.tax / 100);
                    return afterTax;
                },
                subtotal() {
                    return this.items.reduce((sum, item) => sum + this.amount(item), 0);
                },
                addRow() {
                    this.items.push({ description: '', qty: 1, uom: 'lot', price: 0, disc: 0, tax: 6 });
                },
                insertRow() {
                    this.items.unshift({ description: '', qty: 1, uom: 'lot', price: 0, disc: 0, tax: 6 });
                },
                removeRow(idx) {
                    this.items.splice(idx, 1);
                }
            }">
                <!-- Add/Insert Buttons Above Table -->
                <div class="flex flex-row items-end space-x-8 my-3">
                    <div class="flex flex-col items-center">
                        <button type="button" @click="addRow()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-green-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Add Row">
                            +
                        </button>
                        <span class="text-green-600 text-xs font-medium">Add</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <button type="button" @click="insertRow()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-purple-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Insert Row">
                            +
                        </button>
                        <span class="text-purple-600 text-xs font-medium">Insert</span>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-sm">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Qty</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">UOM</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Unit Price</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Disc.</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Tax</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount (RM) *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <textarea x-model="item.description" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Description" rows="1"></textarea>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" min="1" x-model.number="item.qty" class="w-12 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="Qty">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select x-model="item.uom" class="w-16 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-left">
                                            <option value="lot">lot</option>
                                            <option value="unit">unit</option>
                                            <option value="hour">hour</option>
                                            <option value="day">day</option>
                                            <option value="week">week</option>
                                            <option value="month">month</option>
                                        </select>
                                    </td>
                                    <td class="px-1 mx-1 py-3 w-20 text-center">
                                        <input type="number" min="0" step="0.01" x-model.number="item.price" class="w-20 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="Price">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" min="0" max="100" x-model.number="item.disc" class="w-12 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="Disc">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select x-model.number="item.tax" class="w-16 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-left">
                                            <option value="0">0%</option>
                                            <option value="6">6%</option>
                                            <option value="10">10%</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs font-medium" x-text="'RM ' + amount(item).toFixed(2)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeRow(idx)" class="text-red-600 hover:text-red-700" title="Remove Row">
                                            <span class="material-icons text-base">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    <template x-for="(item, idx) in items" :key="idx">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                    <textarea x-model="item.description" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Description" rows="2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" min="1" x-model.number="item.qty" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Qty">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">UOM</label>
                                    <select x-model="item.uom" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="lot">lot</option>
                                        <option value="unit">unit</option>
                                        <option value="hour">hour</option>
                                        <option value="day">day</option>
                                        <option value="week">week</option>
                                        <option value="month">month</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price</label>
                                    <input type="number" min="0" step="0.01" x-model.number="item.price" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Price">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount %</label>
                                    <input type="number" min="0" max="100" x-model.number="item.disc" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Disc">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tax %</label>
                                    <input type="number" min="0" max="100" x-model.number="item.tax" class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Tax">
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-medium" x-text="'Amount: RM ' + amount(item).toFixed(2)"></span>
                                <button type="button" @click="removeRow(idx)" class="text-red-600 hover:text-red-700" title="Remove Row">
                                    <span class="material-icons text-base">delete</span>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Mobile Add/Insert Buttons -->
                    <div class="flex space-x-4">
                        <button type="button" @click="addRow()" class="flex-1 bg-green-600 text-white py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-sm mr-1">add</span>
                            Add Item
                        </button>
                        <button type="button" @click="insertRow()" class="flex-1 bg-purple-600 text-white py-2 px-3 rounded-md text-xs font-medium flex items-center justify-center">
                            <span class="material-icons text-sm mr-1">add</span>
                            Insert Item
                        </button>
                    </div>
                </div>
                
                <!-- Summary Section -->
                <div class="border-t border-gray-200 mt-6 mb-4"></div>
                <div class="flex justify-end">
                    <div class="w-full md:w-64 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Subtotal:</span>
                            <span class="text-gray-900" x-text="'RM ' + subtotal().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="font-medium text-gray-700">Total RM:</span>
                            <span class="text-gray-900 font-semibold" x-text="'RM ' + subtotal().toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('tax-invoice.show', $taxInvoice->id) }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Update Tax Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
window.__prepareInvoiceItems = function() {
    console.log('Preparing invoice items for submission...');
    
    const container = document.getElementById('hidden-items-container');
    if (!container) {
        console.error('Hidden items container not found!');
        return false;
    }
    
    // Clear existing hidden inputs
    container.innerHTML = '';
    
    // Get Alpine.js data
    const alpineData = Alpine.store('invoiceItems') || window.Alpine.$data(document.getElementById('invoiceItems'));
    let items = [];
    
    if (alpineData && alpineData.items) {
        items = alpineData.items;
    } else {
        console.warn('Alpine.js data not found, using fallback...');
        // Fallback: try to get items from the DOM
        const rows = document.querySelectorAll('#invoiceItems tbody tr, #invoiceItems .bg-gray-50');
        items = Array.from(rows).map((row, idx) => ({
            description: row.querySelector('textarea, input[placeholder="Description"]')?.value || '',
            qty: parseFloat(row.querySelector('input[placeholder="Qty"]')?.value || 1),
            uom: row.querySelector('select')?.value || 'lot',
            price: parseFloat(row.querySelector('input[placeholder="Price"]')?.value || 0),
            disc: parseFloat(row.querySelector('input[placeholder="Disc"]')?.value || 0),
            tax: parseFloat(row.querySelector('input[placeholder="Tax"]')?.value || 6)
        }));
    }
    
    console.log('Items to submit:', items);
    
    // Create hidden inputs for each item
    items.forEach((item, index) => {
        const fields = ['description', 'qty', 'uom', 'unit_price', 'discount_percent', 'tax_percent', 'amount'];
        const values = [item.description, item.qty, item.uom, item.price, item.disc, item.tax, item.qty * item.price];
        
        fields.forEach((field, fieldIndex) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${field}]`;
            input.value = values[fieldIndex];
            container.appendChild(input);
        });
    });
    
    return true;
};

// Add form submit handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!window.__prepareInvoiceItems()) {
                e.preventDefault();
                alert('Failed to prepare invoice items. Please try again.');
            }
        });
    }
});
</script>
@endsection
