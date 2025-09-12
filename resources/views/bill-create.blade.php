@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Add New Bill</span>
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
                <span class="material-icons mr-2 text-orange-600">account_balance_wallet</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add New Bill</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new bill for incoming expenses.</p>
        </div>
        
        <form class="p-4 md:p-6" method="POST" action="{{ route('bill.store') }}">
            @csrf
            <!-- Vendor Selection and Bill Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Vendor Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Vendor Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Vendor Name *</label>
                        <input type="text" name="vendor_name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter vendor name" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Vendor Address</label>
                        <textarea name="vendor_address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter vendor address"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input type="text" name="vendor_phone" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone Number">
                            <input type="email" name="vendor_email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Address">
                        </div>
                    </div>
                </div>
                
                <!-- Bill Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Bill Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bill No. *</label>
                        <input type="text" name="bill_no" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bill Date *</label>
                        <input type="date" name="bill_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Due Date *</label>
                        <input type="date" name="due_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select category</option>
                            @foreach($expenseCategories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Enter bill description"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input type="text" name="remark" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            
            <!-- Bill Items Section -->
            <div class="mb-8" x-data="{
    items: [
        { description: '', category: '', qty: 1, uom: 'unit', unit_price: 0, discount_percent: 0, tax_percent: 0 }
    ],
    totalAmount() {
        return this.items.reduce((sum, item) => {
            const lineTotal = item.qty * item.unit_price;
            const discountAmount = lineTotal * (item.discount_percent / 100);
            const afterDiscount = lineTotal - discountAmount;
            const taxAmount = afterDiscount * (item.tax_percent / 100);
            return sum + afterDiscount + taxAmount;
        }, 0);
    },
    addItem() {
        this.items.push({ description: '', category: '', qty: 1, uom: 'unit', unit_price: 0, discount_percent: 0, tax_percent: 0 });
    },
    insertItem() {
        this.items.unshift({ description: '', category: '', qty: 1, uom: 'unit', unit_price: 0, discount_percent: 0, tax_percent: 0 });
    },
    removeItem(idx) {
        if (this.items.length > 1) {
            this.items.splice(idx, 1);
        }
    },
    generateHiddenInputs() {
        const container = document.getElementById('hiddenItemsContainer');
        container.innerHTML = '';
        this.items.forEach((item, index) => {
            const fields = ['description', 'category', 'qty', 'uom', 'unit_price', 'discount_percent', 'tax_percent'];
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${index}][${field}]`;
                input.value = item[field] || '';
                container.appendChild(input);
            });
        });
    }
}" x-init="$watch('items', () => { generateHiddenInputs(); }, {deep: true}); generateHiddenInputs();">
                <!-- Add/Insert Buttons Above Table -->
                <div class="flex flex-row items-end space-x-8 my-3">
                    <div class="flex flex-col items-center">
                        <button type="button" @click="addItem()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-green-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Add Item">
                            +
                        </button>
                        <span class="text-green-600 text-xs font-medium">Add</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <button type="button" @click="insertItem()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-orange-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Insert Item">
                            +
                        </button>
                        <span class="text-orange-600 text-xs font-medium">Insert</span>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Qty *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">UOM *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Unit Price<br>(RM) *</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Discount<br>(%)</th>
                                <th class="px-1 mx-1 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Tax<br>(%)</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <textarea x-model="item.description" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Description" rows="1" required></textarea>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select x-model="item.category" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select</option>
                                            @foreach($expenseCategories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" x-model.number="item.qty" step="0.01" min="0.01" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select x-model="item.uom" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                            <option value="unit">Unit</option>
                                            <option value="hour">Hour</option>
                                            <option value="day">Day</option>
                                            <option value="month">Month</option>
                                            <option value="lot">Lot</option>
                                            <option value="piece">Piece</option>
                                            <option value="set">Set</option>
                                        </select>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" x-model.number="item.unit_price" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" x-model.number="item.discount_percent" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" x-model.number="item.tax_percent" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(idx)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
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
                                <span class="text-sm font-medium text-gray-800" x-text="`Item ${idx + 1}`"></span>
                                <button type="button" @click="removeItem(idx)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="item.description" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Description" rows="2" required></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                    <select x-model="item.category" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        @foreach($expenseCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" x-model.number="item.qty" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">UOM *</label>
                                    <select x-model="item.uom" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                        <option value="unit">Unit</option>
                                        <option value="hour">Hour</option>
                                        <option value="day">Day</option>
                                        <option value="month">Month</option>
                                        <option value="lot">Lot</option>
                                        <option value="piece">Piece</option>
                                        <option value="set">Set</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price (RM)</label>
                                    <input type="number" x-model.number="item.unit_price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount (%)</label>
                                    <input type="number" x-model.number="item.discount_percent" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tax (%)</label>
                                    <input type="number" x-model.number="item.tax_percent" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
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
                            <span class="font-medium text-gray-700">Total Amount:</span>
                            <span class="text-gray-900 font-semibold" x-text="'RM ' + totalAmount().toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for items -->
            <div id="hiddenItemsContainer"></div>

            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('bill.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Bill
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 