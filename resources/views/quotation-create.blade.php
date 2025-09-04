@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Add New Quotation</span>
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
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add New Quotation</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new quotation for your client. @if(request('case_number')) <span class="ml-2 inline-block px-2 py-0.5 rounded-sm bg-blue-50 text-blue-700">Case: {{ request('case_number') }}</span> @endif</p>
        </div>
        
        <form class="p-4 md:p-6" method="POST" action="{{ route('quotation.store') }}" onsubmit="return window.__prepareQuotationItems(this)">
            @csrf
            <input type="hidden" id="hidden_case_id" name="case_id" value="{{ request('case_id') }}">
            @if(request('case_number'))
                <input type="hidden" name="case_number" value="{{ request('case_number') }}">
            @endif
            <!-- Customer and Quotation Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Customer Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Case ID *</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            @php /* $caseOptions, $casePrefill provided by controller */ @endphp
                            <select id="case_select" class="w-full md:flex-2 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Case ID</option>
                                @foreach($caseOptions as $opt)
                                    <option value="{{ $opt->id }}" data-case-number="{{ $opt->case_number }}">{{ $opt->id }} — {{ $opt->case_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="customer_address" name="customer_address" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Customer address will auto-populate" readonly></textarea>
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                            <div class="text-[11px] space-x-3">
                                <label class="inline-flex items-center space-x-1">
                                    <input type="radio" name="party_source" id="party_applicant" value="applicant" checked>
                                    <span>Use Applicant</span>
                                </label>
                                <label class="inline-flex items-center space-x-1">
                                    <input type="radio" name="party_source" id="party_respondent" value="respondent">
                                    <span>Use Respondent</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input id="contact_person" name="customer_name" type="text" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contact Person">
                            <input id="contact_phone" name="customer_phone" type="text" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone Number">
                            <input id="contact_email" name="customer_email" type="email" class="w-full md:flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Address">
                        </div>
                    </div>
                </div>
                
                <!-- Quotation Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Quotation Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Quotation No. *</label>
                        <input id="quotation_no" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date *</label>
                        <input id="quotation_date" name="quotation_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Valid Until</label>
                        <input id="valid_until" name="valid_until" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ now()->addDays(30)->format('Y-m-d') }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                        <select id="payment_terms" name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="net_30">Net 30 days</option>
                            <option value="net_15">Net 15 days</option>
                            <option value="immediate">Immediate</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input id="remark" name="remark" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            <div id="hidden-items-container"></div>
            
            <!-- Item Entry Section -->
            <div class="mb-8" id="qItems" x-data="{
    items: @js(isset($qFrom) && $qFrom
        ? $qFrom->items->map(function($i){
            return [
                'description' => $i->description,
                'qty' => (float)$i->qty,
                'uom' => $i->uom,
                'price' => (float)$i->unit_price,
                'disc' => (float)($i->discount_amount ?? 0),
                'tax' => (float)($i->tax_percent ?? 0),
            ];
        })->values()
        : [[ 'description' => '', 'qty' => 1, 'uom' => 'lot', 'price' => 0, 'disc' => 0, 'tax' => 6 ]]
    ),
    amount(item) {
        let subtotal = item.qty * item.price;
        let afterDisc = subtotal - (item.disc || 0);
        return afterDisc; // Return amount before tax
    },
    itemTax(item) {
        let afterDisc = this.amount(item);
        return afterDisc * (item.tax / 100);
    },
    subtotal() {
        return this.items.reduce((sum, item) => sum + this.amount(item), 0);
    },
    totalTax() {
        return this.items.reduce((sum, item) => sum + this.itemTax(item), 0);
    },
    grandTotal() {
        return this.subtotal() + this.totalTax();
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
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Unit Price<br>(RM)</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Disc.</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Tax</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount<br>(RM) *</th>
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
                                        <input type="number" min="0" step="0.01" x-model.number="item.disc" class="w-16 px-1 mx-1 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
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
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount</label>
                                    <input type="number" min="0" step="0.01" x-model.number="item.disc" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
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
                            <span class="font-medium text-gray-700">Tax:</span>
                            <span class="text-gray-900" x-text="'RM ' + totalTax().toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-gray-300 pt-2">
                            <span class="font-medium text-gray-700">Total RM:</span>
                            <span class="text-gray-900 font-semibold" x-text="'RM ' + grandTotal().toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('quotation.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Quotation
                </button>
            </div>
        </form>
    </div>
</div>
<script>
// Prefill map with applicant/respondent
window.CASE_PREFILL = @json($casePrefill);

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const caseId = params.get('case_id');
    const caseNumber = params.get('case_number');
    const fromQuotation = params.get('from_quotation');
    if (caseNumber) {
        const remark = document.getElementById('remark');
        if (remark && !remark.value) remark.value = `Case: ${caseNumber}`;
    }

    const caseSelect = document.getElementById('case_select');
    if (caseSelect) {
        // Prefill from query param
        if (caseId) {
            caseSelect.value = caseId;
            caseSelect.dispatchEvent(new Event('change'));
        }
        // If from_quotation is present and controller passed qFrom, fill fields
        @if(!empty($qFrom))
        (function(){
            try{
                const contactPersonEl = document.getElementById('contact_person');
                const contactPhoneEl = document.getElementById('contact_phone');
                const contactEmailEl = document.getElementById('contact_email');
                const addressEl = document.getElementById('customer_address');
                const hiddenCaseId = document.getElementById('hidden_case_id');
                if (hiddenCaseId && !hiddenCaseId.value) hiddenCaseId.value = '{{ $qFrom->case_id }}';
                if (caseSelect && !caseSelect.value) caseSelect.value = '{{ $qFrom->case_id }}';
                if (contactPersonEl && !contactPersonEl.value) contactPersonEl.value = @json($qFrom->customer_name);
                if (contactPhoneEl && !contactPhoneEl.value) contactPhoneEl.value = @json($qFrom->customer_phone);
                if (contactEmailEl && !contactEmailEl.value) contactEmailEl.value = @json($qFrom->customer_email);
                if (addressEl && !addressEl.value) addressEl.value = @json($qFrom->customer_address);
            }catch(e){console.error(e)}
        })();
        @endif
        const applyPrefill = () => {
            const selectedId = caseSelect.value;
            const selectedSource = document.querySelector('input[name="party_source"]:checked')?.value || 'applicant';
            const data = window.CASE_PREFILL && window.CASE_PREFILL[selectedId];
            const contactPersonEl = document.getElementById('contact_person');
            const contactPhoneEl = document.getElementById('contact_phone');
            const contactEmailEl = document.getElementById('contact_email');
            const addressEl = document.getElementById('customer_address');
            if (data) {
                const party = data[selectedSource] || {};
                contactPersonEl.value = party.name || '';
                contactPhoneEl.value = party.phone || '';
                if (contactEmailEl) contactEmailEl.value = party.email || '';
                if (addressEl && !addressEl.value) addressEl.value = party.address || '';
            }
        };
        caseSelect.addEventListener('change', applyPrefill);
        const appRadio = document.getElementById('party_applicant');
        const respRadio = document.getElementById('party_respondent');
        if (appRadio) appRadio.addEventListener('change', applyPrefill);
        if (respRadio) respRadio.addEventListener('change', applyPrefill);
    }
});

window.__prepareQuotationItems = function(form){
    try{
        const comp = document.getElementById('qItems');
        const data = window.Alpine ? Alpine.$data(comp) : null;
        let container = document.getElementById('hidden-items-container');
        if(!container){
            container = document.createElement('div');
            container.id = 'hidden-items-container';
            form.appendChild(container);
        }
        container.innerHTML = '';
        if(data && Array.isArray(data.items)){
            data.items.forEach((it, idx) => {
                const fields = {
                    description: it.description || '',
                    qty: it.qty || 0,
                    uom: it.uom || 'lot',
                    unit_price: it.price || 0,
                    discount_amount: it.disc || 0,
                    tax_percent: it.tax || 0,
                    amount: (Number(it.qty||0) * Number(it.price||0)) - (Number(it.disc||0))
                };
                Object.keys(fields).forEach(k => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `items[${idx}][${k}]`;
                    input.value = fields[k];
                    container.appendChild(input);
                });
            });
        }
        const caseSelect = document.getElementById('case_select');
        const hiddenCaseId = document.getElementById('hidden_case_id');
        if(caseSelect && hiddenCaseId){ hiddenCaseId.value = caseSelect.value || hiddenCaseId.value; }
    }catch(e){ console.error(e); }
    return true;
}
</script>
@endsection 