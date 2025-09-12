@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Edit Bill</span>
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
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Edit Bill - {{ $bill->bill_no }}</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Update bill information and items.</p>
        </div>

        <form class="p-4 md:p-6" method="POST" action="{{ route('bill.update', $bill->id) }}">
            @csrf
            @method('PUT')

            <!-- Vendor Information -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Vendor Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Vendor Name *</label>
                            <input type="text" name="vendor_name" value="{{ old('vendor_name', $bill->vendor_name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('vendor_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach($expenseCategories as $category)
                                    <option value="{{ $category->name }}" {{ old('category', $bill->category) == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="vendor_phone" value="{{ old('vendor_phone', $bill->vendor_phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('vendor_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="vendor_email" value="{{ old('vendor_email', $bill->vendor_email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('vendor_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="vendor_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('vendor_address', $bill->vendor_address) }}</textarea>
                        @error('vendor_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bill Dates & Payment Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Bill & Payment Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Bill Date *</label>
                            <input type="date" name="bill_date" value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('bill_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Due Date *</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('due_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method', $bill->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method', $bill->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="bank_transfer" {{ old('payment_method', $bill->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="online_banking" {{ old('payment_method', $bill->payment_method) == 'online_banking' ? 'selected' : '' }}>Online Banking</option>
                                <option value="credit_card" {{ old('payment_method', $bill->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            </select>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', $bill->payment_date ? $bill->payment_date->format('Y-m-d') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('payment_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Reference</label>
                            <input type="text" name="payment_reference" value="{{ old('payment_reference', $bill->payment_reference) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('payment_reference')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $bill->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <textarea name="remark" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remark', $bill->remark) }}</textarea>
                        @error('remark')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Bill Items Section -->
            <div class="mb-8" x-data="{
                items: [
                    @if($bill->items->count() > 0)
                        @foreach($bill->items as $item)
                        {
                            description: '{{ $item->description }}',
                            category: '{{ $item->category }}',
                            qty: {{ $item->qty }},
                            uom: '{{ $item->uom }}',
                            unit_price: {{ $item->unit_price }},
                            discount_percent: {{ $item->discount_percent ?? 0 }},
                            tax_percent: {{ $item->tax_percent ?? 0 }}
                        }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    @else
                        {
                            description: '',
                            category: '',
                            qty: 1,
                            uom: 'unit',
                            unit_price: 0,
                            discount_percent: 0,
                            tax_percent: 0
                        }
                    @endif
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
                    this.items.push({
                        description: '',
                        category: '',
                        qty: 1,
                        uom: 'unit',
                        unit_price: 0,
                        discount_percent: 0,
                        tax_percent: 0
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
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
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-800">Bill Items</h3>
                    <button type="button" @click="addItem()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-sm text-xs">
                        <span class="material-icons text-xs mr-1">add</span>
                        Add Item
                    </button>
                </div>

                <!-- Desktop Table View for Items -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full border border-gray-300">
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
                        <tbody class="bg-white" id="itemsContainer">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="item-row">
                                    <td class="px-4 py-3 align-middle">
                                        <textarea :name="`items[${index}][description]`" x-model="item.description" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Description" rows="1" required></textarea>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select :name="`items[${index}][category]`" x-model="item.category" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            <option value="">Select</option>
                                            @foreach($expenseCategories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" step="0.01" min="0.01" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <select :name="`items[${index}][uom]`" x-model="item.uom" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
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
                                        <input type="number" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" :name="`items[${index}][discount_percent]`" x-model.number="item.discount_percent" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-1 mx-1 py-3 text-center">
                                        <input type="number" :name="`items[${index}][tax_percent]`" x-model.number="item.tax_percent" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View for Items -->
                <div class="md:hidden space-y-4" id="mobileItemsContainer">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="item-row bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800" x-text="`Item ${index + 1}`"></span>
                                <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea :name="`items[${index}][description]`" x-model="item.description" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Description" rows="2" required></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                    <select :name="`items[${index}][category]`" x-model="item.category" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        @foreach($expenseCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" :name="`items[${index}][qty]`" x-model.number="item.qty" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">UOM *</label>
                                    <select :name="`items[${index}][uom]`" x-model="item.uom" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
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
                                    <input type="number" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount (%)</label>
                                    <input type="number" :name="`items[${index}][discount_percent]`" x-model.number="item.discount_percent" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tax (%)</label>
                                    <input type="number" :name="`items[${index}][tax_percent]`" x-model.number="item.tax_percent" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
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

            <div class="flex justify-end space-x-4">
                <a href="{{ route('bill.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-sm text-xs font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-sm text-xs font-medium">
                    Update Bill
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
