@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">{{ isset($preQuotation) ? 'Edit Pre-Quotation' : 'Add New Pre-Quotation' }}</span>
@endsection

@section('content')
<style>
/* Hide number input spinners for all browsers */
input[type='number']::-webkit-outer-spin-button,
input[type='number']::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type='number'] {
  -moz-appearance: textfield;
}
</style>
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">request_quote</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">{{ isset($preQuotation) ? 'Edit Pre-Quotation' : 'Add New Pre-Quotation' }}</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">{{ isset($preQuotation) ? 'Update pre-quotation details' : 'Create a new pre-quotation. All fields are optional.' }}</p>
        </div>
        
        <form class="p-4 md:p-6" method="POST" action="{{ isset($preQuotation) ? route('pre-quotation.update', $preQuotation->id) : route('pre-quotation.store') }}" onsubmit="return window.__preparePreQuotationItems(this)">
            @csrf
            @if(isset($preQuotation))
                @method('PUT')
            @endif
            
            <!-- Customer and Pre-Quotation Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Customer Information (Optional)</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Full Name</label>
                        <input name="full_name" type="text" value="{{ old('full_name', $preQuotation->full_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter full name">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="customer_address" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter customer address">{{ old('customer_address', $preQuotation->customer_address ?? '') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input name="customer_phone" type="text" value="{{ old('customer_phone', $preQuotation->customer_phone ?? '') }}" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone Number">
                            <input name="customer_email" type="email" value="{{ old('customer_email', $preQuotation->customer_email ?? '') }}" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Address">
                        </div>
                    </div>
                </div>
                
                <!-- Pre-Quotation Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Pre-Quotation Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Pre-Quotation No. *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ isset($preQuotation) ? $preQuotation->quotation_no : 'Auto-generated' }}" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date *</label>
                        <input name="quotation_date" type="date" value="{{ old('quotation_date', isset($preQuotation) ? $preQuotation->quotation_date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Valid Until</label>
                        <input name="valid_until" type="date" value="{{ old('valid_until', isset($preQuotation) && $preQuotation->valid_until ? $preQuotation->valid_until->format('Y-m-d') : now()->addDays(30)->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                        <input name="payment_terms" type="text" value="{{ old('payment_terms', $preQuotation->payment_terms ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 30 days">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <textarea name="remark" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Additional notes or remarks">{{ old('remark', $preQuotation->remark ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div id="hidden-items-container"></div>

            <!-- Item Entry Section -->
            <div class="mb-8" id="qItems" x-data="{
    items: @js(isset($preQuotation) && $preQuotation->items->count() > 0
        ? $preQuotation->items->map(function($i){
            return [
                'description' => $i->description,
                'qty' => (float)$i->qty,
                'uom' => $i->uom,
                'price' => (float)$i->unit_price,
                'disc' => (float)($i->discount_percent ?? 0),
                'tax' => (float)($i->tax_percent ?? 0),
            ];
        })->values()
        : [[ 'description' => '', 'qty' => 1, 'uom' => 'lot', 'price' => 0, 'disc' => 0, 'tax' => 6 ]]
    ),
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
                                        <input type="number" min="0" step="0.01" x-model.number="item.price" class="w-20 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" min="0" max="100" x-model.number="item.disc" class="w-12 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0%">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select x-model.number="item.tax" class="w-16 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-left">
                                            <option value="0">0%</option>
                                            <option value="6">6%</option>
                                            <option value="10">10%</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 w-20 text-center">
                                        <span class="text-xs text-gray-900 w-20 inline-block text-center" x-text="amount(item).toFixed(2)"></span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center">
                                            <button type="button" @click="removeRow(idx)" class="text-red-600 hover:text-red-800 text-base font-light" title="Delete Row">❌</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View for Items -->
                <div class="md:hidden space-y-4">
                    <template x-for="(item, idx) in items" :key="idx">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800">Item <span x-text="idx + 1"></span></span>
                                <button type="button" @click="removeRow(idx)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="item.description" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Description" rows="2"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                                    <input type="number" min="1" x-model.number="item.qty" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="Qty">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">UOM</label>
                                    <select x-model="item.uom" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="lot">lot</option>
                                        <option value="unit">unit</option>
                                        <option value="hour">hour</option>
                                        <option value="day">day</option>
                                        <option value="week">week</option>
                                        <option value="month">month</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price</label>
                                    <input type="number" min="0" step="0.01" x-model.number="item.price" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount %</label>
                                    <input type="number" min="0" max="100" x-model.number="item.disc" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0%">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tax %</label>
                                    <select x-model.number="item.tax" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="0">0%</option>
                                        <option value="6">6%</option>
                                        <option value="10">10%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-600">Amount:</span>
                                    <span class="text-sm font-semibold text-gray-900" x-text="'RM ' + amount(item).toFixed(2)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
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

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    {{ isset($preQuotation) ? 'Update Pre-Quotation' : 'Create Pre-Quotation' }}
                </button>
                <a href="{{ route('pre-quotation.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-sm text-xs font-medium flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">arrow_back</span>
                    Back to List
                </a>
            </div>
        </form>
    </div>
</div>
<script>
window.__preparePreQuotationItems = function(form) {
    // Get Alpine.js data from the qItems component
    const qItemsEl = document.getElementById('qItems');
    if (qItemsEl && qItemsEl._x_dataStack && qItemsEl._x_dataStack[0]) {
        const alpineData = qItemsEl._x_dataStack[0];
        const items = alpineData.items;

        // Clear existing hidden inputs
        const hiddenContainer = document.getElementById('hidden-items-container');
        hiddenContainer.innerHTML = '';

        // Create hidden inputs for each item
        items.forEach((item, index) => {
            const fields = ['description', 'qty', 'uom', 'unit_price', 'discount_percent', 'tax_percent'];
            const mapping = {
                'description': item.description,
                'qty': item.qty,
                'uom': item.uom,
                'unit_price': item.price,
                'discount_percent': item.disc,
                'tax_percent': item.tax
            };

            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${field}]`;
                input.value = mapping[field] || '';
                hiddenContainer.appendChild(input);
            });
        });
    }
    return true;
};
</script>
@endsection
