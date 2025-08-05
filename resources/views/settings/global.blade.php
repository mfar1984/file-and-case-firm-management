@extends('layouts.app')

@section('breadcrumb')
    Settings > Global Config
@endsection

@section('content')
<div class="px-6 pt-6 pb-6 max-w-7xl mx-auto space-y-6">
    <!-- Firm Information Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">business</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Firm Information</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Update your law firm's basic information and contact details.</p>
        </div>
        
        <div class="p-6">
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                        <input type="text" value="Naeelah Saleh & Associates" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Registration Number</label>
                        <input type="text" value="LLP0012345" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" value="+603-1234-5678" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" value="info@naaelahsaleh.my" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                        <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>No. 123, Jalan Tun Razak, 50400 Kuala Lumpur, Malaysia</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" value="https://www.naaelahsaleh.my" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tax Registration Number</label>
                        <input type="text" value="123456789012" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                        Save Firm Information
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">settings</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">System Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure system behavior and default settings.</p>
        </div>
        
        <div class="p-6">
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Default Currency</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="MYR" selected>Malaysian Ringgit (MYR)</option>
                            <option value="USD">US Dollar (USD)</option>
                            <option value="SGD">Singapore Dollar (SGD)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date Format</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="d/m/Y" selected>DD/MM/YYYY</option>
                            <option value="m/d/Y">MM/DD/YYYY</option>
                            <option value="Y-m-d">YYYY-MM-DD</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Time Zone</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Kuala_Lumpur" selected>Asia/Kuala Lumpur (UTC+8)</option>
                            <option value="Asia/Singapore">Asia/Singapore (UTC+8)</option>
                            <option value="UTC">UTC (UTC+0)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                        <input type="number" value="120" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">File Upload Limit (MB)</label>
                        <input type="number" value="10" min="1" max="50" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Backup Frequency</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="daily" selected>Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                        Save System Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-purple-600">email</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Email Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure email notifications and SMTP settings.</p>
        </div>
        
        <div class="p-6">
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" value="smtp.gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" value="587" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Username</label>
                        <input type="email" value="noreply@naaelahsaleh.my" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Password</label>
                        <input type="password" value="••••••••" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Name</label>
                        <input type="text" value="Naeelah Saleh & Associates" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Email</label>
                        <input type="email" value="noreply@naaelahsaleh.my" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-800 mb-3">Email Notifications</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">New case assignments</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">Document uploads</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">Case status updates</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">System maintenance alerts</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                        Save Email Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-red-600">security</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Security Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure security and authentication settings.</p>
        </div>
        
        <div class="p-6">
            <form class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Password Minimum Length</label>
                        <input type="number" value="8" min="6" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Password Expiry (days)</label>
                        <input type="number" value="90" min="30" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Failed Login Attempts</label>
                        <input type="number" value="5" min="3" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Account Lockout Duration (minutes)</label>
                        <input type="number" value="30" min="15" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-800 mb-3">Security Features</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">Two-factor authentication</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">Login activity logging</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">IP address restrictions</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-xs text-gray-700">Force HTTPS</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                        Save Security Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 