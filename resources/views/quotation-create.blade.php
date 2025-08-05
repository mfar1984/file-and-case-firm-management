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
<div class="px-6 pt-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">request_quote</span>
                <h1 class="text-xl font-bold text-gray-800 text-[14px]">Add New Quotation</h1>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Create a new quotation for your client.</p>
        </div>
        
        <form class="p-6">
            <!-- Customer and Quotation Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Left Column - Customer Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Customer Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Customer *</label>
                        <div class="flex space-x-2">
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Customer ID" readonly>
                            <select class="flex-2 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Customer</option>
                                <option value="A0001">A0001 - ATLINE SDN BHD</option>
                                <option value="A0002">A0002 - TECH SOLUTIONS</option>
                                <option value="A0003">A0003 - GLOBAL TRADING</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address</label>
                        <textarea class="w-full px-3 py-7 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Customer address will auto-populate" readonly>UNIT 606, BLOCK C, KELANA SQUARE, NO.17 JALAN SS 7/26, KELANA JAYA 47301 SELANGOR Malaysia</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Contact</label>
                        <div class="flex space-x-2">
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contact Person" value="MOHD NURUL">
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone Number" value="016-2074769">
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Quotation Information -->
                <div class="space-y-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Quotation Information</h3>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Quotation No. *</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50" value="Auto-generated" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" value="2025-08-05" required>
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
                        <label class="block text-xs font-medium text-gray-700 mb-2 mt-5">Remark</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter any additional remarks">
                    </div>
                </div>
            </div>
            
            <!-- Item Entry Section -->
            <div class="mb-8" x-data="{
    items: [
        { description: '', qty: 1, uom: 'lot', price: 0, disc: 0, tax: 6 }
    ],
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
                        <button type="button" @click="addRow()" class="w-5 h-5 flex items-center justify-center bg-green-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Add Row">
                            +
                        </button>
                        <span class="text-green-600 text-xs font-medium">Add</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <button type="button" @click="insertRow()" class="w-5 h-5 flex items-center justify-center bg-purple-600 text-white rounded-full text-base mb-1 focus:outline-none" title="Insert Row">
                            +
                        </button>
                        <span class="text-purple-600 text-xs font-medium">Insert</span>
                    </div>
                </div>
                <!-- Items Table -->
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
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
                    <!-- Separator before summary inside scroll -->
                    <div class="border-t border-gray-200 mt-6 mb-4"></div>
                    <!-- Summary inside scroll -->
                    <div class="flex justify-end mt-0 mb-4">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-700">Subtotal:</span>
                                <span class="text-gray-900 pr-6 mr-2" x-text="'RM ' + subtotal().toFixed(2)"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-gray-700">Total RM:</span>
                                <span class="text-gray-900 font-semibold pr-6 mr-2" x-text="'RM ' + subtotal().toFixed(2)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6">
                <a href="{{ route('quotation.index') }}" class="px-4 py-2 bg-gray-500 text-white text-xs rounded-lg hover:bg-gray-600 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-icons text-xs mr-1">save</span>
                    Save Quotation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 