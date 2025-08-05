@extends('layouts.app')

@section('breadcrumb')
    Voucher > Add New Voucher
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
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-purple-600">payment</span>
                <h1 class="text-xl font-bold text-gray-800 text-[14px]">Add New Payment Voucher</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new payment voucher for expenses.</p>
        </div>
        
        <form class="p-6">
            <!-- Payee Selection and Voucher Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Left Column - Payee Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Payee Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payee Name *</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select payee</option>
                            <option value="TNB">TNB Berhad</option>
                            <option value="RENT">Office Rent Sdn Bhd</option>
                            <option value="INTERNET">Internet Provider</option>
                            <option value="SUPPLIES">Office Supplies</option>
                            <option value="MAINTENANCE">Maintenance Service</option>
                            <option value="SALARY">Staff Salary</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payee Address</label>
                        <textarea class="w-full px-3 py-7 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" rows="3" readonly>Payee address will auto-populate</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex space-x-2">
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Contact Person" readonly>
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Phone Number" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Voucher Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Voucher Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Voucher No. *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-08-05" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Method *</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter approver name" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2 mt-5">Remark</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            
            <!-- Expense Breakdown Section -->
            <div class="mb-8" x-data="{
    expenses: [
        { description: 'Electricity bill for January 2025', category: 'Utilities', amount: 850 }
    ],
    totalAmount() {
        return this.expenses.reduce((sum, expense) => sum + expense.amount, 0);
    },
    addExpense() {
        this.expenses.push({ description: '', category: 'Utilities', amount: 0 });
    },
    insertExpense() {
        this.expenses.unshift({ description: '', category: 'Utilities', amount: 0 });
    },
    removeExpense(idx) {
        this.expenses.splice(idx, 1);
    }
}">
                <!-- Add/Insert Buttons Above Table -->
                <div class="flex flex-row items-end space-x-8 my-3">
                    <div class="flex flex-col items-center">
                        <button type="button" @click="addExpense()" class="w-5 h-5 flex items-center justify-center bg-green-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Add Expense">
                            +
                        </button>
                        <span class="text-green-600 text-xs font-medium">Add</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <button type="button" @click="insertExpense()" class="w-5 h-5 flex items-center justify-center bg-purple-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Insert Expense">
                            +
                        </button>
                        <span class="text-purple-600 text-xs font-medium">Insert</span>
                    </div>
                </div>
                <!-- Expense Breakdown Table -->
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
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
                                            <option value="Utilities">Utilities</option>
                                            <option value="Rent">Rent</option>
                                            <option value="Salary">Salary</option>
                                            <option value="Internet">Internet</option>
                                            <option value="Supplies">Supplies</option>
                                            <option value="Maintenance">Maintenance</option>
                                            <option value="Insurance">Insurance</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Other">Other</option>
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
                    <!-- Separator before summary inside scroll -->
                    <div class="border-t border-gray-200 mt-6 mb-4"></div>
                    <!-- Summary inside scroll -->
                    <div class="flex justify-end mt-0 mb-4">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-700">Total Amount:</span>
                                <span class="text-gray-900 font-semibold pr-6 mr-2" x-text="'RM ' + totalAmount().toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6">
                <a href="{{ route('voucher.index') }}" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Voucher
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 