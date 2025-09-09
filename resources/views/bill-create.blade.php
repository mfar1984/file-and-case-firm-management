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
        
        <form class="p-4 md:p-6">
            <!-- Vendor Selection and Bill Details -->
            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Vendor Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Vendor Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Vendor/Supplier *</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select vendor</option>
                            <option value="TNB">TNB Berhad</option>
                            <option value="INTERNET">Internet Provider</option>
                            <option value="SUPPLIES">Office Supplies Co</option>
                            <option value="CLEANING">Cleaning Services</option>
                            <option value="INSURANCE">Insurance Co</option>
                            <option value="DATABASE">Legal Database</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Vendor Address</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" rows="3" readonly>Vendor address will auto-populate</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Contact Person" readonly>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Phone Number" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Bill Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Bill Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bill No. *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter bill number" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Bill Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-08-05" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Due Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-09-05" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="net_30">Net 30 days</option>
                            <option value="net_15">Net 15 days</option>
                            <option value="immediate">Immediate</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Remark</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            
            <!-- Bill Items Section -->
            <div class="mb-8" x-data="{
    items: [
        { description: 'Monthly electricity bill', category: 'Utilities', amount: 320 }
    ],
    totalAmount() {
        return this.items.reduce((sum, item) => sum + item.amount, 0);
    },
    addItem() {
        this.items.push({ description: '', category: 'Utilities', amount: 0 });
    },
    insertItem() {
        this.items.unshift({ description: '', category: 'Utilities', amount: 0 });
    },
    removeItem(idx) {
        this.items.splice(idx, 1);
    }
}">
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
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Item Description</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Amount (RM) *</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <template x-for="(item, idx) in items" :key="idx">
                                <tr>
                                    <td class="px-4 py-3 align-middle">
                                        <textarea x-model="item.description" class="w-full px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y align-middle" placeholder="Item description" rows="1"></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <select x-model="item.category" class="w-32 px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-left">
                                            @foreach($expenseCategories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <input type="number" min="0" step="0.01" x-model.number="item.amount" class="w-32 px-3 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center">
                                            <button type="button" @click="removeItem(idx)" class="text-red-600 hover:text-red-800 text-base font-light" title="Delete Item">❌</button>
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
                                <button type="button" @click="removeItem(idx)" class="text-red-600 hover:text-red-800 text-lg" title="Delete Item">❌</button>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <textarea x-model="item.description" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 resize-y" placeholder="Item description" rows="2"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                    <select x-model="item.category" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @foreach($expenseCategories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Amount (RM)</label>
                                    <input type="number" min="0" step="0.01" x-model.number="item.amount" class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 text-center" placeholder="0.00">
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-medium text-gray-600">Amount:</span>
                                    <span class="text-sm font-semibold text-gray-900" x-text="'RM ' + item.amount.toFixed(2)"></span>
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