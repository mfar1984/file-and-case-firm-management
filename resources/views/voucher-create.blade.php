@extends('layouts.app')

@section('breadcrumb')
    Voucher > Add New Voucher
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payeeSelect = document.getElementById('payeeSelect');
    const payeeAddress = document.getElementById('payeeAddress');
    const contactPerson = document.getElementById('contactPerson');
    const phoneNumber = document.getElementById('phoneNumber');
    
    payeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            // Auto-populate payee details
            payeeAddress.value = selectedOption.dataset.address || '';
            contactPerson.value = selectedOption.dataset.contact || '';
            phoneNumber.value = selectedOption.dataset.phone || '';
        } else {
            // Clear fields if no payee selected
            payeeAddress.value = '';
            contactPerson.value = '';
            phoneNumber.value = '';
        }
    });
});
</script>

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
                <span class="material-icons mr-2 text-purple-600">payment</span>
                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">Add New Payment Voucher</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new payment voucher for expenses.</p>
        </div>
        
        <form class="p-4 md:p-6" method="POST" action="{{ route('voucher.store') }}">
            @csrf
            <!-- Payee Selection and Voucher Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Payee Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Payee Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payee Name *</label>
                        <select name="payee_id" id="payeeSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select payee</option>
                            @foreach(\App\Models\Payee::where('is_active', true)->orderBy('name')->get() as $payee)
                                <option value="{{ $payee->id }}" 
                                        data-address="{{ $payee->address }}"
                                        data-contact="{{ $payee->contact_person }}"
                                        data-phone="{{ $payee->phone }}">
                                    {{ $payee->name }} ({{ $payee->category }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payee Address</label>
                        <textarea name="payee_address" id="payeeAddress" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" rows="3" readonly placeholder="Payee address will auto-populate"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input type="text" name="contact_person" id="contactPerson" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" placeholder="Contact Person" readonly>
                            <input type="text" name="phone" id="phoneNumber" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" placeholder="Phone Number" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Voucher Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Voucher Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Voucher No. *</label>
                        <input type="text" name="voucher_no" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Date *</label>
                        <input type="date" name="payment_date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="auto_debit">Auto Debit</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Approved By *</label>
                        <input type="text" name="approved_by" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter approver name" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input type="text" name="remark" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            
            <!-- Expense Breakdown Section -->
            <div class="mb-8" x-data="{
    expenses: [
        { description: '', category: '', amount: 0 }
    ],
    totalAmount() {
        return this.expenses.reduce((sum, expense) => sum + expense.amount, 0);
    },
    addExpense() {
        this.expenses.push({ description: '', category: '', amount: 0 });
    },
    insertExpense() {
        this.expenses.unshift({ description: '', category: '', amount: 0 });
    },
    removeExpense(idx) {
        if (this.expenses.length > 1) {
            this.expenses.splice(idx, 1);
        }
    }
}" x-init="$watch('expenses', value => { document.getElementById('expensesJson').value = JSON.stringify(value); }, {deep: true}); document.getElementById('expensesJson').value = JSON.stringify(expenses);">
                <!-- Add/Insert Buttons Above Table -->
                <div class="flex flex-row items-end space-x-8 my-3">
                    <div class="flex flex-col items-center">
                        <button type="button" @click="addExpense()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-green-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Add Expense">
                            +
                        </button>
                        <span class="text-green-600 text-xs font-medium">Add</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <button type="button" @click="insertExpense()" class="w-8 h-8 md:w-5 md:h-5 flex items-center justify-center bg-purple-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Insert Expense">
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Expense Description</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount (RM) *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <template x-for="(expense, idx) in expenses" :key="idx">
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <textarea x-model="expense.description" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Expense description" rows="1"></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <select x-model="expense.category" class="w-32 px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-left">
                                            <option value="">Select Category</option>
                                            @foreach($expenseCategories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" min="0" step="0.01" x-model.number="expense.amount" class="w-32 px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center">
                                            <button type="button" @click="removeExpense(idx)" class="text-red-600 hover:text-red-800 text-base font-light" title="Delete Expense">❌</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View for Expenses -->
                <div class="md:hidden space-y-4">
                    <template x-for="(expense, idx) in expenses" :key="idx">
                        <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800">Expense <span x-text="idx + 1"></span></span>
                                <button type="button" @click="removeExpense(idx)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Expense">❌</button>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="expense.description" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Expense description" rows="2"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                    <select x-model="expense.category" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="">Select Category</option>
                                        @foreach($expenseCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Amount (RM)</label>
                                    <input type="number" min="0" step="0.01" x-model.number="expense.amount" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-600">Amount:</span>
                                    <span class="text-sm font-semibold text-gray-900" x-text="'RM ' + expense.amount.toFixed(2)"></span>
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
            <input type="hidden" id="expensesJson" name="expenses" value="[]">
            
            <!-- Form Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-6">
                <a href="{{ route('voucher.index') }}" class="w-full md:w-auto px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Voucher
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 