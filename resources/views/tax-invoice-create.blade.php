@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Add New Tax Invoice</span>
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
                <span class="material-icons mr-2 text-blue-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add New Tax Invoice</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new tax invoice from quotation or scratch. @if(request('from_quotation')) <span class="ml-2 inline-block px-2 py-0.5 rounded-sm bg-blue-50 text-blue-700">From Q: {{ request('from_quotation') }}</span> @endif</p>
        </div>
        
        @if (session('success'))
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-green-400">check_circle</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="material-icons text-red-400">error</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <form class="p-4 md:p-6" method="POST" action="{{ route('tax-invoice.store') }}" onsubmit="return window.__prepareInvoiceItems(this)">
            @csrf
            <input type="hidden" name="case_id" value="{{ request('case_id') }}">
            <input type="hidden" name="quotation_id" value="{{ request('from_quotation') }}">
            <!-- Quotation Selection and Invoice Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Quotation Selection -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Quotation Selection</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Quotation *</label>
                        @php $qFrom = request('from_quotation') ? \App\Models\Quotation::with('items','case.parties')->find(request('from_quotation')) : null; @endphp
                        <select id="quotation_select" name="quotation_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" @if(!$qFrom) required @endif onchange="window.__fillFromQuotation(this.value)">
                            <option value="">Choose from existing quotations</option>
                            @foreach(\App\Models\Quotation::latest()->take(50)->get(['id','quotation_no']) as $opt)
                                <option value="{{ $opt->id }}" @if($qFrom && $qFrom->id === $opt->id) selected @endif>{{ $opt->quotation_no }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer</label>
                        @php 
                            $fromParty = $qFrom?->case?->parties?->firstWhere('party_type','plaintiff') ?? $qFrom?->case?->parties?->first();
                            $prefillName = $qFrom?->customer_name ?? $fromParty?->name ?? '';
                            $prefillPhone = $qFrom?->customer_phone ?? $fromParty?->phone ?? '';
                            $prefillAddress = $qFrom?->customer_address ?? '';
                        @endphp
                        <input id="customer_name" name="customer_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $prefillName }}" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                        <input name="customer_email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $qFrom?->customer_email ?? '' }}" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Phone</label>
                        <input name="customer_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $qFrom?->customer_phone ?? '' }}" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="customer_address" name="customer_address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" rows="3" readonly>{{ $prefillAddress }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input id="contact_person" name="contact_person" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $prefillName }}" readonly>
                            <input id="contact_phone" name="contact_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="{{ $prefillPhone }}" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Invoice Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Invoice Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Invoice No. *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Issue Date *</label>
                        <input name="invoice_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Due Date *</label>
                        <input name="due_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ now()->addDays(30)->format('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                        <select name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="net_30">Net 30 days</option>
                            <option value="net_15">Net 15 days</option>
                            <option value="immediate">Immediate</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input name="remark" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            <div id="hidden-items-container"></div>
            

            <!-- Item Entry Section -->
            <div class="mb-8" id="invoiceItems" x-data="{
                items: @js(isset($qFrom) && $qFrom
                    ? $qFrom->items->map(function($i){
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
                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-lg">
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
            
            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('tax-invoice.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Tax Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
@php
$__qCache = \App\Models\Quotation::with('items')->latest()->take(100)->get()->map(function($q){
    return [
        'id' => $q->id,
        'quotation_no' => $q->quotation_no,
        'customer_name' => $q->customer_name,
        'customer_phone' => $q->customer_phone,
        'customer_address' => $q->customer_address,
        'customer_email' => $q->customer_email,
        'items' => $q->items->map(function($i){
            return [
                'description' => $i->description,
                'qty' => (float) $i->qty,
                'uom' => $i->uom,
                'price' => (float) $i->unit_price,
                'disc' => (float) ($i->discount_percent ?? 0),
                'tax' => (float) ($i->tax_percent ?? 0),
            ];
        })->values()->toArray(),
    ];
})->keyBy('id');
@endphp
window.__quotationCache = @json($__qCache);

window.__fillFromQuotation = function(id){
    const q = window.__quotationCache[id];
    if(!q) return;
    
    // Safely set values with null checks
    const elements = {
        'customer_name': q.customer_name || '',
        'customer_email': q.customer_email || '',
        'customer_phone': q.customer_phone || '',
        'customer_address': q.customer_address || '',
        'contact_person': q.customer_name || '',
        'contact_phone': q.customer_phone || ''
    };
    
    Object.keys(elements).forEach(elementId => {
        const element = document.getElementById(elementId);
        if(element) {
            element.value = elements[elementId];
        }
    });
    
    // Fill items into Alpine component if exists
    const comp = document.getElementById('invoiceItems');
    if(comp && window.Alpine){
        const data = Alpine.$data(comp);
        if(data && Array.isArray(q.items)){
            data.items = q.items.map(i => ({...i}));
        }
    }
}

// Auto-fill on load when from_quotation present
document.addEventListener('DOMContentLoaded', function(){
    const select = document.getElementById('quotation_select');
    if(select && select.value){
        window.__fillFromQuotation(select.value);
    }
});

window.__prepareInvoiceItems = function(form){
    try{
        console.log('Preparing invoice items...');
        const comp = document.getElementById('invoiceItems');
        console.log('Component found:', comp);
        
        const data = window.Alpine ? Alpine.$data(comp) : null;
        console.log('Alpine data:', data);
        
        let container = document.getElementById('hidden-items-container');
        if(!container){
            container = document.createElement('div');
            container.id = 'hidden-items-container';
            form.appendChild(container);
        }
        container.innerHTML = '';
        
        if(data && Array.isArray(data.items) && data.items.length > 0){
            console.log('Items found:', data.items);
            data.items.forEach((it, idx) => {
                const fields = {
                    description: it.description || '',
                    qty: it.qty || 0,
                    uom: it.uom || 'lot',
                    unit_price: it.price || 0,
                    discount_percent: it.disc || 0,
                    tax_percent: it.tax || 0,
                    amount: (Number(it.qty||0) * Number(it.price||0)) * (1 - (Number(it.disc||0)/100)) * (1 + (Number(it.tax||0)/100))
                };
                console.log(`Item ${idx}:`, fields);
                
                Object.keys(fields).forEach(k => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `items[${idx}][${k}]`;
                    input.value = fields[k];
                    container.appendChild(input);
                });
            });
        } else {
            console.log('No items data found, creating default item');
            // Create a default item if none exist
            const defaultItem = {
                description: 'Legal Services',
                qty: 1,
                uom: 'lot',
                unit_price: 1000,
                discount_percent: 0,
                tax_percent: 6,
                amount: 1000
            };
            
            Object.keys(defaultItem).forEach(k => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[0][${k}]`;
                input.value = defaultItem[k];
                container.appendChild(input);
            });
        }
    }catch(e){ 
        console.error('Error in __prepareInvoiceItems:', e); 
        // Fallback: create at least one item
        let container = document.getElementById('hidden-items-container');
        if(!container){
            container = document.createElement('div');
            container.id = 'hidden-items-container';
            form.appendChild(container);
        }
        container.innerHTML = '';
        
        const fallbackItem = {
            description: 'Legal Services',
            qty: 1,
            uom: 'lot',
            unit_price: 1000,
            discount_percent: 0,
            tax_percent: 6,
            amount: 1000
        };
        
        Object.keys(fallbackItem).forEach(k => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[0][${k}]`;
            input.value = fallbackItem[k];
            container.appendChild(input);
        });
    }
    return true;
}
</script>
@endsection