@extends('layouts.app')

@section('breadcrumb')
    <span class="text-gray-500">Edit Voucher</span>
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
                <span class="material-icons mr-2 text-green-600">receipt</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Edit Voucher - {{ $voucher->voucher_no }}</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Update voucher information and items.</p>
        </div>

        <form class="p-4 md:p-6" method="POST" action="{{ route('voucher.update', $voucher->id) }}">
            @csrf
            @method('PUT')

            <!-- Payee Information -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Payee Information</h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payee Name *</label>
                        <select name="payee_id" id="payeeSelect" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select payee</option>
                            @foreach($payees as $payee)
                                <option value="{{ $payee->id }}"
                                        data-name="{{ $payee->name }}"
                                        data-address="{{ $payee->address }}"
                                        data-contact="{{ $payee->contact_person }}"
                                        data-phone="{{ $payee->phone }}"
                                        data-email="{{ $payee->email }}"
                                        {{ old('payee_id', $voucher->payee_name == $payee->name ? $payee->id : '') == $payee->id ? 'selected' : '' }}>
                                    {{ $payee->name }} ({{ $payee->category }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="payee_name" id="payeeName" value="{{ old('payee_name', $voucher->payee_name) }}" required>
                        @error('payee_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Contact Person</label>
                            <input type="text" name="contact_person" id="contactPerson" value="{{ old('contact_person', $voucher->contact_person) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('contact_person')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" id="phoneNumber" value="{{ old('phone', $voucher->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="payeeEmail" value="{{ old('email', $voucher->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="payee_address" id="payeeAddress" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('payee_address', $voucher->payee_address) }}</textarea>
                        @error('payee_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Payment Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select name="payment_method"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method', $voucher->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method', $voucher->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="bank_transfer" {{ old('payment_method', $voucher->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="online_banking" {{ old('payment_method', $voucher->payment_method) == 'online_banking' ? 'selected' : '' }}>Online Banking</option>
                                <option value="credit_card" {{ old('payment_method', $voucher->payment_method) == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                            </select>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Date *</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', $voucher->payment_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('payment_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Approved By *</label>
                            <input type="text" name="approved_by" value="{{ old('approved_by', $voucher->approved_by) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('approved_by')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <textarea name="remark" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remark', $voucher->remark) }}</textarea>
                        @error('remark')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Item Entry Section -->
            <div class="mb-8" x-data="{
                items: [
                    @foreach($voucher->items as $item)
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
                }
            }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-800">Voucher Items</h3>
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
                                        <button type="button" @click="items.splice(index, 1)" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded-sm text-xs">
                                            <span class="material-icons text-xs">delete</span>
                                        </button>
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

            <div class="flex justify-end space-x-4">
                <a href="{{ route('voucher.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-sm text-xs font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-sm text-xs font-medium">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Payee selection auto-populate functionality
document.addEventListener('DOMContentLoaded', function() {
    const payeeSelect = document.getElementById('payeeSelect');
    const payeeName = document.getElementById('payeeName');
    const payeeAddress = document.getElementById('payeeAddress');
    const contactPerson = document.getElementById('contactPerson');
    const phoneNumber = document.getElementById('phoneNumber');
    const payeeEmail = document.getElementById('payeeEmail');

    payeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value) {
            // Auto-populate payee details
            payeeName.value = selectedOption.dataset.name || '';
            payeeAddress.value = selectedOption.dataset.address || '';
            contactPerson.value = selectedOption.dataset.contact || '';
            phoneNumber.value = selectedOption.dataset.phone || '';
            payeeEmail.value = selectedOption.dataset.email || '';
        } else {
            // Clear fields if no payee selected
            payeeName.value = '';
            payeeAddress.value = '';
            contactPerson.value = '';
            phoneNumber.value = '';
            payeeEmail.value = '';
        }
    });
});

let itemIndex = {{ count($voucher->items) }};

// Generate category options for dynamic items
function getCategoryOptions() {
    let options = '<option value="">Select</option>';
    @foreach($expenseCategories as $category)
        options += '<option value="{{ $category->name }}">{{ $category->name }}</option>';
    @endforeach
    return options;
}

function getCategoryOptionsForMobile() {
    let options = '<option value="">Select Category</option>';
    @foreach($expenseCategories as $category)
        options += '<option value="{{ $category->name }}">{{ $category->name }}</option>';
    @endforeach
    return options;
}

document.getElementById('addItem').addEventListener('click', function() {
    const desktopContainer = document.getElementById('itemsContainer');
    const mobileContainer = document.getElementById('mobileItemsContainer');

    // Desktop table row
    const desktopRowHtml = `
        <tr class="item-row">
            <td class="px-4 py-3 align-middle">
                <textarea name="items[${itemIndex}][description]" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Description" rows="1" required></textarea>
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <select name="items[${itemIndex}][category]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
                    ${getCategoryOptions()}
                </select>
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <input type="number" name="items[${itemIndex}][qty]" value="1" step="0.01" min="0.01" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <select name="items[${itemIndex}][uom]" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    <option value="unit">Unit</option>
                    <option value="hour">Hour</option>
                    <option value="day">Day</option>
                    <option value="month">Month</option>
                    <option value="lot" selected>Lot</option>
                    <option value="piece">Piece</option>
                    <option value="set">Set</option>
                </select>
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <input type="number" name="items[${itemIndex}][unit_price]" value="0" step="0.01" min="0" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <input type="number" name="items[${itemIndex}][discount_percent]" value="0" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="px-1 mx-1 py-3 text-center">
                <input type="number" name="items[${itemIndex}][tax_percent]" value="0" step="0.01" min="0" max="100" class="w-full px-2 py-1 border border-gray-300 rounded text-xs text-center focus:outline-none focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="remove-item text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
            </td>
        </tr>
    `;

    // Mobile card
    const mobileCardHtml = `
        <div class="item-row bg-white border border-gray-200 rounded-lg p-4 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-800">Item ${itemIndex + 1}</span>
                <button type="button" class="remove-item text-red-600 hover:text-red-800 text-lg" title="Delete Row">❌</button>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                <textarea name="items[${itemIndex}][description]" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Description" rows="2" required></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                    <select name="items[${itemIndex}][category]" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                        ${getCategoryOptionsForMobile()}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Qty</label>
                    <input type="number" name="items[${itemIndex}][qty]" value="1" step="0.01" min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">UOM *</label>
                    <select name="items[${itemIndex}][uom]" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                        <option value="unit">Unit</option>
                        <option value="hour">Hour</option>
                        <option value="day">Day</option>
                        <option value="month">Month</option>
                        <option value="lot" selected>Lot</option>
                        <option value="piece">Piece</option>
                        <option value="set">Set</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit Price (RM)</label>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="0" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Discount (%)</label>
                    <input type="number" name="items[${itemIndex}][discount_percent]" value="0" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tax (%)</label>
                    <input type="number" name="items[${itemIndex}][tax_percent]" value="0" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
        </div>
    `;

    desktopContainer.insertAdjacentHTML('beforeend', desktopRowHtml);
    mobileContainer.insertAdjacentHTML('beforeend', mobileCardHtml);
    itemIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        const itemRow = e.target.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            itemRow.remove();
        } else {
            alert('At least one item is required.');
        }
    }
});
</script>
@endsection
