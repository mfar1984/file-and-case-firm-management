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
            <form id="firmForm" class="space-y-4" x-data="{ 
                loading: false,
                formData: {
                    firm_name: '',
                    registration_number: '',
                    phone_number: '',
                    email: '',
                    address: '',
                    website: '',
                    tax_registration_number: ''
                }
            }" x-init="
                fetch('/settings/firm/get')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            formData = { ...formData, ...data };
                        }
                    })
                    .catch(error => console.error('Error loading firm settings:', error));
            ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Firm Name *</label>
                        <input type="text" x-model="formData.firm_name" placeholder="Enter firm name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Registration Number</label>
                        <input type="text" x-model="formData.registration_number" placeholder="Enter registration number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" x-model="formData.phone_number" placeholder="Enter phone number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" x-model="formData.email" placeholder="Enter email address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                        <textarea rows="3" x-model="formData.address" placeholder="Enter complete address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" x-model="formData.website" placeholder="Enter website URL" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Tax Registration Number</label>
                        <input type="text" x-model="formData.tax_registration_number" placeholder="Enter tax registration number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" @click.prevent="
                        loading = true;
                        fetch('/settings/firm', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Firm information saved successfully!');
                            } else {
                                alert('Error saving firm information: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error saving firm information: ' + error.message);
                        })
                        .finally(() => {
                            loading = false;
                        });
                    " :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Firm Information</span>
                        <span x-show="loading">Saving...</span>
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
            <form id="systemForm" class="space-y-4" x-data="{ 
                loading: false,
                formData: {
                    date_format: 'l, j F Y',
                    time_format: 'g:i:s a',
                    time_zone: 'Asia/Kuala_Lumpur',
                    maintenance_mode: false,
                    maintenance_message: '',
                    session_timeout: 120,
                    debug_mode: false
                },
                currentDate: new Date(),
                formatDate(format) {
                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const shortDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    const shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    
                    let result = format;
                    const date = this.currentDate;
                    
                    // Date formatting with word boundaries to prevent partial replacements
                    result = result.replace(/\bl\b/g, days[date.getDay()]);
                    result = result.replace(/\bD\b/g, shortDays[date.getDay()]);
                    result = result.replace(/\bj\b/g, date.getDate());
                    result = result.replace(/\bF\b/g, months[date.getMonth()]);
                    result = result.replace(/\bM\b/g, shortMonths[date.getMonth()]);
                    result = result.replace(/\bn\b/g, date.getMonth() + 1);
                    result = result.replace(/\bY\b/g, date.getFullYear());
                    
                    return result;
                },
                formatTime(format) {
                    const date = this.currentDate;
                    let result = format;
                    
                    // Time formatting with word boundaries to prevent partial replacements
                    result = result.replace(/\bg\b/g, date.getHours() % 12 || 12);
                    result = result.replace(/\bG\b/g, date.getHours());
                    result = result.replace(/\bh\b/g, String(date.getHours() % 12 || 12).padStart(2, '0'));
                    result = result.replace(/\bH\b/g, String(date.getHours()).padStart(2, '0'));
                    result = result.replace(/\bi\b/g, String(date.getMinutes()).padStart(2, '0'));
                    result = result.replace(/\bs\b/g, String(date.getSeconds()).padStart(2, '0'));
                    result = result.replace(/\ba\b/g, date.getHours() < 12 ? 'am' : 'pm');
                    result = result.replace(/\bA\b/g, date.getHours() < 12 ? 'AM' : 'PM');
                    
                    return result;
                }
            }" x-init="
                fetch('/settings/system/get')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            formData = { ...formData, ...data };
                        }
                    })
                    .catch(error => console.error('Error loading system settings:', error));
            ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Date Format</label>
                        <select x-model="formData.date_format" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="l, j F Y" x-text="formatDate('l, j F Y')"></option>
                            <option value="l, j M Y" x-text="formatDate('l, j M Y')"></option>
                            <option value="D, j M Y" x-text="formatDate('D, j M Y')"></option>
                            <option value="D, j F Y" x-text="formatDate('D, j F Y')"></option>
                            <option value="j M Y" x-text="formatDate('j M Y')"></option>
                            <option value="j F Y" x-text="formatDate('j F Y')"></option>
                            <option value="M j Y" x-text="formatDate('M j Y')"></option>
                            <option value="F j Y" x-text="formatDate('F j Y')"></option>
                            <option value="n/j/Y" x-text="formatDate('n/j/Y')"></option>
                            <option value="n.j.Y" x-text="formatDate('n.j.Y')"></option>
                            <option value="n-j-Y" x-text="formatDate('n-j-Y')"></option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Time Format</label>
                        <select x-model="formData.time_format" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="g:i:s a" x-text="formatTime('g:i:s a')"></option>
                            <option value="G:i:s a" x-text="formatTime('G:i:s a')"></option>
                            <option value="g:i a" x-text="formatTime('g:i a')"></option>
                            <option value="G:i a" x-text="formatTime('G:i a')"></option>
                            <option value="H:i" x-text="formatTime('H:i')"></option>
                            <option value="H:i:s" x-text="formatTime('H:i:s')"></option>
                            <option value="G:i" x-text="formatTime('G:i')"></option>
                            <option value="G:i:s" x-text="formatTime('G:i:s')"></option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Time Zone</label>
                        <select x-model="formData.time_zone" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Asia/Kuala_Lumpur">Asia/Kuala Lumpur (UTC+8)</option>
                            <option value="Asia/Singapore">Asia/Singapore (UTC+8)</option>
                            <option value="UTC">UTC (UTC+0)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                        <input type="number" x-model="formData.session_timeout" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Maintenance Message</label>
                        <textarea rows="3" x-model="formData.maintenance_message" placeholder="Enter maintenance message (optional)" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" x-model="formData.maintenance_mode" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-xs text-gray-700">Enable Maintenance Mode</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" x-model="formData.debug_mode" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-xs text-gray-700">Enable Debug Mode</span>
                    </label>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" @click.prevent="
                        loading = true;
                        fetch('/settings/system', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('System settings saved successfully!');
                            } else {
                                alert('Error saving system settings: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error saving system settings: ' + error.message);
                        })
                        .finally(() => {
                            loading = false;
                        });
                    " :disabled="loading" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save System Settings</span>
                        <span x-show="loading">Saving...</span>
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
            <form id="emailForm" class="space-y-4" x-data="{ 
                loading: false,
                formData: {
                    smtp_host: '',
                    smtp_port: 587,
                    email_username: '',
                    email_password: '',
                    from_name: '',
                    from_email: '',
                    encryption: true,
                    notify_new_cases: true,
                    notify_document_uploads: true,
                    notify_case_status: true,
                    notify_maintenance: false
                }
            }" x-init="
                fetch('/settings/email/get')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            formData = { ...formData, ...data };
                        }
                    })
                    .catch(error => console.error('Error loading email settings:', error));
            ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" x-model="formData.smtp_host" placeholder="e.g., smtp.gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" x-model="formData.smtp_port" placeholder="e.g., 587" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Username</label>
                        <input type="email" x-model="formData.email_username" placeholder="Enter email username" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Password</label>
                        <input type="password" x-model="formData.email_password" placeholder="Enter email password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Name</label>
                        <input type="text" x-model="formData.from_name" placeholder="Enter sender name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">From Email</label>
                        <input type="email" x-model="formData.from_email" placeholder="Enter sender email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-800 mb-3">Email Notifications</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.notify_new_cases" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-xs text-gray-700">New case assignments</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.notify_document_uploads" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-xs text-gray-700">Document uploads</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.notify_case_status" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-xs text-gray-700">Case status updates</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.notify_maintenance" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-xs text-gray-700">System maintenance alerts</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" @click.prevent="
                        loading = true;
                        fetch('/settings/email', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Email settings saved successfully!');
                            } else {
                                alert('Error saving email settings: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error saving email settings: ' + error.message);
                        })
                        .finally(() => {
                            loading = false;
                        });
                    " :disabled="loading" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Email Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Weather Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">wb_sunny</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Weather Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure weather API settings and location preferences.</p>
        </div>
        
        <div class="p-6">
            <form id="weatherForm" class="space-y-4" x-data="{ 
                loading: false, 
                detectingLocation: false,
                formData: {
                    api_provider: 'tomorrow_io',
                    api_key: '',
                    postcode: '',
                    country: 'Malaysia',
                    state: 'Kuala Lumpur',
                    city: 'Kuala Lumpur',
                    latitude: 3.1390,
                    longitude: 101.6869,
                    units: 'metric',
                    is_active: true,
                    notes: ''
                },
                manualMode: false,
                async detectLocation() {
                    if (!this.formData.city || !this.formData.api_key) {
                        return;
                    }
                    
                    this.detectingLocation = true;
                    
                    try {
                        // Get location data from API using city name
                        const response = await fetch('/settings/weather/test', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                api_provider: this.formData.api_provider,
                                api_key: this.formData.api_key,
                                city: this.formData.city,
                                latitude: this.formData.latitude,
                                longitude: this.formData.longitude
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (result.success && result.data) {
                            // Update location fields with API data
                            this.formData.city = result.data.city || this.formData.city;
                            this.formData.state = result.data.state || this.formData.state;
                            this.formData.country = result.data.country || this.formData.country;
                            this.formData.postcode = result.data.postcode || '';
                            
                            // If we have coordinates, update them
                            if (result.data.latitude && result.data.longitude) {
                                this.formData.latitude = result.data.latitude;
                                this.formData.longitude = result.data.longitude;
                            }
                            
                            // Show warning if city mismatch
                            if (result.warning) {
                                alert('Warning: ' + result.warning + '\n\n' + result.message);
                            } else {
                                alert('Success: ' + result.message);
                            }
                        }
                    } catch (error) {
                        console.error('Location detection error:', error);
                    } finally {
                        this.detectingLocation = false;
                    }
                }
            }" x-init="
                fetch('/settings/weather/get')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            formData = { ...formData, ...data };
                        }
                    })
                    .catch(error => console.error('Error loading settings:', error));
            ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">API Provider *</label>
                        <select x-model="formData.api_provider" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="tomorrow_io">Tomorrow.io</option>
                            <option value="openweathermap">OpenWeatherMap</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">API Key *</label>
                        <input type="password" x-model="formData.api_key" placeholder="Enter your API key" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">City *</label>
                        <div class="flex space-x-2">
                            <input type="text" x-model="formData.city" placeholder="e.g., Kuala Lumpur" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" @click="detectLocation()" :disabled="detectingLocation || !formData.city || !formData.api_key || manualMode" class="px-3 py-2 bg-blue-600 text-white rounded-md text-xs hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!detectingLocation">🔍 Auto-Detect</span>
                                <span x-show="detectingLocation">⏳ Detecting...</span>
                            </button>
                        </div>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" x-model="manualMode" class="mr-2">
                            <label class="text-xs font-medium text-gray-700">Manual Mode (disable auto-detection)</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-show="!manualMode">Type city name manually, then click "Auto-Detect" to get location details</p>
                        <p class="text-xs text-orange-600 mt-1" x-show="manualMode">Manual mode enabled - you can edit all fields directly</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Postcode</label>
                        <input type="text" x-model="formData.postcode" placeholder="Auto-detected from city" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" :class="manualMode ? 'bg-white' : 'bg-gray-100 cursor-not-allowed'" :disabled="!manualMode">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Country *</label>
                        <input type="text" x-model="formData.country" placeholder="Auto-detected from city" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" :class="manualMode ? 'bg-white' : 'bg-gray-100 cursor-not-allowed'" :disabled="!manualMode">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">State/Province *</label>
                        <input type="text" x-model="formData.state" placeholder="Auto-detected from city" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" :class="manualMode ? 'bg-white' : 'bg-gray-100 cursor-not-allowed'" :disabled="!manualMode">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Latitude *</label>
                        <input type="number" x-model="formData.latitude" step="0.000001" placeholder="e.g., 3.1390" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" :class="manualMode ? 'bg-white' : 'bg-gray-100 cursor-not-allowed'" :disabled="!manualMode">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Longitude *</label>
                        <input type="number" x-model="formData.longitude" step="0.000001" placeholder="e.g., 101.6869" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs" :class="manualMode ? 'bg-white' : 'bg-gray-100 cursor-not-allowed'" :disabled="!manualMode">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Units</label>
                        <select x-model="formData.units" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="metric">Metric (°C, km/h)</option>
                            <option value="imperial">Imperial (°F, mph)</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" x-model="formData.is_active" class="mr-2">
                        <label class="text-xs font-medium text-gray-700">Active Weather Widget</label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                    <textarea x-model="formData.notes" rows="3" placeholder="Additional notes about weather settings..." class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                

                
                <div class="flex justify-end space-x-3">
                    <button type="submit" @click.prevent="
                        fetch('/settings/weather', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Weather settings saved successfully!');
                            } else {
                                alert('Error saving settings: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error saving settings: ' + error.message);
                        });
                    " class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium">
                        Save Weather Settings
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
            <form id="securityForm" class="space-y-4" x-data="{ 
                loading: false,
                formData: {
                    two_factor_auth: false,
                    password_expiry: false,
                    password_expiry_days: 90,
                    force_password_change: false,
                    max_login_attempts: 5,
                    lockout_duration: 30,
                    session_timeout_enabled: true,
                    session_timeout_minutes: 120,
                    ip_whitelist_enabled: false,
                    ip_whitelist: '',
                    audit_log_enabled: true
                }
            }" x-init="
                fetch('/settings/security/get')
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            formData = { ...formData, ...data };
                        }
                    })
                    .catch(error => console.error('Error loading security settings:', error));
            ">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Password Expiry (days)</label>
                        <input type="number" x-model="formData.password_expiry_days" min="30" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Failed Login Attempts</label>
                        <input type="number" x-model="formData.max_login_attempts" min="3" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Account Lockout Duration (minutes)</label>
                        <input type="number" x-model="formData.lockout_duration" min="15" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                        <input type="number" x-model="formData.session_timeout_minutes" min="15" max="1440" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-2">IP Whitelist</label>
                        <textarea rows="3" x-model="formData.ip_whitelist" placeholder="Enter IP addresses (one per line) for whitelist" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-sm font-medium text-gray-800 mb-3">Security Features</h3>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.two_factor_auth" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">Two-factor authentication</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.password_expiry" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">Password expiry</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.force_password_change" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">Force password change on next login</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.session_timeout_enabled" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">Session timeout</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.ip_whitelist_enabled" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">IP address restrictions</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.audit_log_enabled" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-xs text-gray-700">Audit log enabled</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" @click.prevent="
                        loading = true;
                        fetch('/settings/security', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Security settings saved successfully!');
                            } else {
                                alert('Error saving security settings: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error saving security settings: ' + error.message);
                        })
                        .finally(() => {
                            loading = false;
                        });
                    " :disabled="loading" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Security Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 