@extends('layouts.app')

@section('breadcrumb')
    Settings > Global Config
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto space-y-6">
    <!-- Firm Information Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">business</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Firm Information</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Update your law firm's basic information and contact details.</p>
        </div>

        <div class="p-4 md:p-6">


            <form id="firmForm" class="space-y-4" x-data="{
                loading: false,
                dataLoaded: false,
                errorMessage: '',
                formData: {
                    firm_name: '',
                    registration_number: '',
                    phone_number: '',
                    fax_number: '',
                    email: '',
                    address: '',
                    website: '',
                    tax_registration_number: ''
                },
                async saveFirmInformation() {
                    this.loading = true;
                    console.log('Saving firm information:', this.formData);

                    try {
                        const response = await fetch('/settings/firm', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error('Validation Error: ' + JSON.stringify(errorData));
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('Firm information saved successfully!');
                        } else {
                            alert('Error saving firm information: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Firm information error:', error);
                        alert('Error saving firm information: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }" x-init="
                fetch('/settings/firm/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401) {
                                throw new Error('Authentication required. Please refresh the page and login again.');
                            }
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && typeof data === 'object') {
                            // Check if we got an error response
                            if (data.message && !data.firm_name) {
                                throw new Error(data.message);
                            }

                            // Direct assignment to formData
                            formData.firm_name = data.firm_name || '';
                            formData.registration_number = data.registration_number || '';
                            formData.phone_number = data.phone_number || '';
                            formData.fax_number = data.fax_number || '';
                            formData.email = data.email || '';
                            formData.address = data.address || '';
                            formData.website = data.website || '';
                            formData.tax_registration_number = data.tax_registration_number || '';

                            dataLoaded = true;
                            errorMessage = ''; // Clear any previous errors
                        } else {
                            errorMessage = 'Invalid data format received from server';
                        }
                    })
                    .catch(error => {
                        errorMessage = 'Error loading firm settings: ' + error.message;
                        dataLoaded = false;
                    });
            ">

                <!-- Loading State -->
                <div x-show="!dataLoaded && !errorMessage" class="text-center py-4">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-xs text-gray-600">Loading firm information...</span>
                    </div>
                </div>

                <!-- Error State -->
                <div x-show="errorMessage" class="bg-red-50 border border-red-200 rounded-md p-3 mb-4">
                    <div class="flex">
                        <span class="material-icons text-red-400 text-sm mr-2">error</span>
                        <div>
                            <h3 class="text-xs font-medium text-red-800">Error Loading Data</h3>
                            <p class="text-xs text-red-700 mt-1" x-text="errorMessage"></p>
                            <button @click="location.reload()" class="text-xs text-red-600 hover:text-red-800 underline mt-2">Refresh Page</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4" x-show="dataLoaded || errorMessage">
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
                        <label class="block text-xs font-medium text-gray-700 mb-2">Fax No</label>
                        <input type="tel" x-model="formData.fax_number" placeholder="Enter fax number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" x-model="formData.email" placeholder="Enter email address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Address *</label>
                        <textarea rows="3" x-model="formData.address" placeholder="Enter complete address" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Website</label>
                        <input type="url" x-model="formData.website" placeholder="Enter website URL" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">SST Registration No.</label>
                        <input type="text" x-model="formData.tax_registration_number" placeholder="Enter SST registration number" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" @click.prevent="saveFirmInformation()" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Firm Information</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- System Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">settings</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">System Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure system behavior and default settings.</p>
        </div>

        <div class="p-4 md:p-6">
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
                },
                async saveSystemSettings() {
                    this.loading = true;
                    console.log('Saving system settings:', this.formData);

                    try {
                        const response = await fetch('/settings/system', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error('Validation Error: ' + JSON.stringify(errorData));
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('System settings saved successfully!');
                        } else {
                            alert('Error saving system settings: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('System settings error:', error);
                        alert('Error saving system settings: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }" x-init="
                fetch('/settings/system/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && typeof data === 'object') {
                        // Direct assignment to formData (no 'this.' in x-init)
                        formData.date_format = data.date_format || 'l, j F Y';
                        formData.time_format = data.time_format || 'g:i:s a';
                        formData.time_zone = data.time_zone || 'Asia/Kuala_Lumpur';
                        formData.maintenance_mode = data.maintenance_mode !== undefined ? Boolean(data.maintenance_mode) : false;
                        formData.maintenance_message = data.maintenance_message || '';
                        formData.session_timeout = data.session_timeout || 120;
                        formData.debug_mode = data.debug_mode !== undefined ? Boolean(data.debug_mode) : false;
                    }
                })
                .catch(error => {
                    // Silent error handling
                });
            ">
                <div class="grid grid-cols-1 gap-4">
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

                    <div>
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
                    <button type="submit" @click.prevent="saveSystemSettings()" :disabled="loading" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save System Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-purple-600">email</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Email Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure email notifications and SMTP settings.</p>
        </div>

        <div class="p-4 md:p-6">
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
                },
                async saveEmailSettings() {
                    this.loading = true;
                    console.log('Saving email settings:', this.formData);

                    try {
                        const response = await fetch('/settings/email', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error('Validation Error: ' + JSON.stringify(errorData));
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('Email settings saved successfully!');
                        } else {
                            alert('Error saving email settings: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Email settings error:', error);
                        alert('Error saving email settings: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }" x-init="
                fetch('/settings/email/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && typeof data === 'object') {
                        // Direct assignment to formData (no 'this.' in x-init)
                        formData.smtp_host = data.smtp_host || '';
                        formData.smtp_port = data.smtp_port || 587;
                        formData.email_username = data.email_username || '';
                        formData.email_password = data.email_password || '';
                        formData.from_name = data.from_name || '';
                        formData.from_email = data.from_email || '';
                        formData.encryption = data.encryption !== undefined ? Boolean(data.encryption) : true;
                        formData.notify_new_cases = data.notify_new_cases !== undefined ? Boolean(data.notify_new_cases) : true;
                        formData.notify_document_uploads = data.notify_document_uploads !== undefined ? Boolean(data.notify_document_uploads) : true;
                        formData.notify_case_status = data.notify_case_status !== undefined ? Boolean(data.notify_case_status) : true;
                        formData.notify_maintenance = data.notify_maintenance !== undefined ? Boolean(data.notify_maintenance) : false;
                    }
                })
                .catch(error => {
                    // Silent error handling
                });
            ">
                <div class="grid grid-cols-1 gap-4">
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
                        <input type="email" x-model="formData.email_username" placeholder="Enter email username" autocomplete="username" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Email Password</label>
                        <input type="password" x-model="formData.email_password" placeholder="Enter email password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="current-password">
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
                        <label class="flex items-center">
                            <input type="checkbox" x-model="formData.notify_user_accounts" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <span class="ml-2 text-xs text-gray-700">User account creation notifications</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" @click.prevent="saveEmailSettings()" :disabled="loading" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Email Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Weather Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-blue-600">wb_sunny</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Weather Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure weather API settings and location preferences.</p>
        </div>

        <div class="p-4 md:p-6">
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

                    } finally {
                        this.detectingLocation = false;
                    }
                },
                async saveWeatherSettings() {
                    this.loading = true;
                    console.log('Saving weather settings:', this.formData);

                    try {
                        const response = await fetch('/settings/weather', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error('Validation Error: ' + JSON.stringify(errorData));
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('Weather settings saved successfully!');
                        } else {
                            alert('Error saving settings: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Weather settings error:', error);
                        alert('Error saving settings: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }" x-init="
                fetch('/settings/weather/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && typeof data === 'object') {
                        // Direct assignment to formData (no 'this.' in x-init)
                        formData.api_provider = data.api_provider || 'tomorrow_io';
                        formData.api_key = data.api_key || '';
                        formData.postcode = data.postcode || '';
                        formData.country = data.country || 'Malaysia';
                        formData.state = data.state || 'Kuala Lumpur';
                        formData.city = data.city || 'Kuala Lumpur';
                        formData.latitude = data.latitude || 3.1390;
                        formData.longitude = data.longitude || 101.6869;
                        formData.units = data.units || 'metric';
                        formData.is_active = data.is_active !== undefined ? Boolean(data.is_active) : true;
                        formData.notes = data.notes || '';
                    }
                })
                .catch(error => {
                    // Silent error handling
                });
            ">
                <!-- Hidden username field to satisfy browser heuristic for password fields -->
                <input type="text" name="weather_username_placeholder" autocomplete="username" class="sr-only absolute opacity-0 pointer-events-none" tabindex="-1" aria-hidden="true">

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">API Provider *</label>
                        <select x-model="formData.api_provider" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="tomorrow_io">Tomorrow.io</option>
                            <option value="openweathermap">OpenWeatherMap</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">API Key *</label>
                        <input type="password" x-model="formData.api_key" name="api_key" placeholder="Enter your API key" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="new-password">
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
                    <button type="submit" @click.prevent="saveWeatherSettings()" :disabled="loading" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Weather Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-red-600">security</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Security Settings</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure security and authentication settings.</p>
        </div>

        <div class="p-4 md:p-6">
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
                },
                async saveSecuritySettings() {
                    this.loading = true;
                    console.log('Saving security settings:', this.formData);

                    try {
                        const response = await fetch('/settings/security', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error('Validation Error: ' + JSON.stringify(errorData));
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('Security settings saved successfully!');
                        } else {
                            alert('Error saving security settings: ' + (data.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Security settings error:', error);
                        alert('Error saving security settings: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                }
            }" x-init="
                fetch('/settings/security/get', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && typeof data === 'object') {
                        // Direct assignment to formData (no 'this.' in x-init)
                        formData.two_factor_auth = data.two_factor_auth !== undefined ? Boolean(data.two_factor_auth) : false;
                        formData.password_expiry = data.password_expiry !== undefined ? Boolean(data.password_expiry) : false;
                        formData.password_expiry_days = data.password_expiry_days || 90;
                        formData.force_password_change = data.force_password_change !== undefined ? Boolean(data.force_password_change) : false;
                        formData.max_login_attempts = data.max_login_attempts || 5;
                        formData.lockout_duration = data.lockout_duration || 30;
                        formData.session_timeout_enabled = data.session_timeout_enabled !== undefined ? Boolean(data.session_timeout_enabled) : true;
                        formData.ip_whitelist = data.ip_whitelist || '';
                        formData.audit_log_enabled = data.audit_log_enabled !== undefined ? Boolean(data.audit_log_enabled) : true;
                    }
                })
                .catch(error => {
                    // Silent error handling
                });
            ">
                <div class="grid grid-cols-1 gap-4">
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

                    <div>
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
                    <button type="submit" @click.prevent="saveSecuritySettings()" :disabled="loading" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-xs font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Save Security Settings</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Opening Balance Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-green-600">account_balance</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Opening Balance</h2>
                </div>
                <button @click="$dispatch('open-modal', 'add-opening-balance')" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium flex items-center">
                    <span class="material-icons text-xs mr-1">add</span>
                    Add Balance
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage opening balances for bank accounts and cash accounts.</p>
        </div>

        <div class="p-4 md:p-6" x-data="{
            openingBalances: [],
            loading: false,
            editingBalance: null,
            loadOpeningBalances() {
                this.loading = true;
                fetch('/settings/opening-balance')
                    .then(response => response.json())
                    .then(data => {
                        this.openingBalances = data;
                    })
                    .catch(error => {
                        alert('Failed to load opening balances');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            editOpeningBalance(balance) {
                this.editingBalance = balance;
                // Dispatch custom event with balance data
                const modal = document.querySelector('#edit-opening-balance');
                if (modal) {
                    modal.dispatchEvent(new CustomEvent('set-edit-balance', { detail: balance }));
                }
                this.$dispatch('open-modal', 'edit-opening-balance');
            },
            deleteOpeningBalance(balanceId) {
                if (!confirm('Are you sure you want to delete this opening balance?')) {
                    return;
                }

                fetch(`/settings/opening-balance/${balanceId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Opening balance deleted successfully');
                        this.loadOpeningBalances();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Failed to delete opening balance');
                });
            }
        }" x-init="loadOpeningBalances()">

            <!-- Opening Balance Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Code</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit (MYR)</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit (MYR)</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <template x-for="balance in openingBalances" :key="balance.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="balance.bank_code"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="balance.bank_name"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="balance.currency"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="parseFloat(balance.debit).toFixed(2)"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="parseFloat(balance.credit).toFixed(2)"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="parseFloat(balance.debit_myr).toFixed(2)"></td>
                                <td class="px-4 py-2 text-xs text-gray-900" x-text="parseFloat(balance.credit_myr).toFixed(2)"></td>
                                <td class="px-4 py-2 text-xs">
                                    <div class="flex space-x-2">
                                        <button @click="editOpeningBalance(balance)" class="text-blue-600 hover:text-blue-800" title="Edit">
                                            <span class="material-icons text-sm">edit</span>
                                        </button>
                                        <button @click="deleteOpeningBalance(balance.id)" class="text-red-600 hover:text-red-800" title="Delete">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="openingBalances.length === 0">
                            <td colspan="8" class="px-4 py-8 text-center text-xs text-gray-500">
                                No opening balances found. Click "Add Balance" to create one.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</div>

<!-- Add Opening Balance Modal -->
<div x-data="{
    open: false,
    formData: {
        bank_code: '',
        bank_name: '',
        currency: 'MYR',
        debit: 0,
        credit: 0,
        exchange_rate: 1.0000
    },
    isSubmitting: false,
    availableBankCodes: [
        { code: '3010/010', name: 'Petty Cash' },
        { code: '3010/020', name: 'Client Account' },
        { code: '3010/030', name: 'Office Account' }
    ],
    existingBankCodes: [],
    async loadExistingBankCodes() {
        try {
            const response = await fetch('/settings/opening-balance');
            const data = await response.json();
            this.existingBankCodes = data.map(item => item.bank_code);
        } catch (error) {
            console.error('Failed to load existing bank codes:', error);
        }
    },
    getAvailableBankCodes() {
        return this.availableBankCodes.filter(bank => !this.existingBankCodes.includes(bank.code));
    },
    updateBankName() {
        const bankCodes = {
            '3010/010': 'Petty Cash',
            '3010/020': 'Client Account',
            '3010/030': 'Office Account'
        };
        this.formData.bank_name = bankCodes[this.formData.bank_code] || '';
    },
    async submitAddOpeningBalance() {
        this.isSubmitting = true;
        console.log('Adding opening balance:', this.formData);

        try {
            const response = await fetch('/settings/opening-balance', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(this.formData)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error('Validation Error: ' + JSON.stringify(errorData));
            }

            const data = await response.json();
            if (data.success) {
                alert('Opening balance added successfully');
                this.open = false;
                // Reset form
                this.formData = {
                    bank_code: '',
                    bank_name: '',
                    currency: 'MYR',
                    debit: 0,
                    credit: 0,
                    exchange_rate: 1.0000
                };
                // Reload the page to refresh opening balances
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Opening balance error:', error);
            let errorMessage = error.message;

            // Check if it's a validation error with specific bank_code duplicate message
            if (errorMessage.includes('bank code has already been taken')) {
                errorMessage = 'This bank code already exists for your firm. Please choose a different bank code or edit the existing one.';
            } else if (errorMessage.includes('Duplicate entry')) {
                errorMessage = 'This bank code already exists. Please choose a different bank code.';
            }

            alert('Error adding opening balance: ' + errorMessage);
        } finally {
            this.isSubmitting = false;
        }
    }
}" @open-modal.window="if ($event.detail === 'add-opening-balance') { open = true; loadExistingBankCodes(); }" @close-modal.window="open = false" x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
    <div class="relative top-20 mx-auto p-5 border w-11/12 sm:max-w-lg shadow-lg rounded-md bg-white">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <div class="flex items-center gap-x-2 mb-6 pl-1">
                        <span class="material-icons text-green-600 text-xl">account_balance</span>
                        <h3 class="text-lg font-semibold text-gray-900">Add Opening Balance</h3>
                    </div>
                    <div class="border-b border-gray-200 mb-6"></div>

                    <form class="space-y-4" @submit.prevent="submitAddOpeningBalance()">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bank Code *</label>
                            <select x-model="formData.bank_code" @change="updateBankName()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <option value="">Select Bank Code</option>
                                <template x-for="bank in getAvailableBankCodes()" :key="bank.code">
                                    <option :value="bank.code" x-text="`${bank.code} - ${bank.name}`"></option>
                                </template>
                                <template x-if="getAvailableBankCodes().length === 0">
                                    <option value="" disabled>All bank codes are already used</option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Bank Name *</label>
                            <input type="text" x-model="formData.bank_name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Currency *</label>
                            <select x-model="formData.currency" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                <option value="MYR">MYR</option>
                                <option value="USD">USD</option>
                                <option value="SGD">SGD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Debit</label>
                                <input type="number" step="0.01" x-model="formData.debit" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Credit</label>
                                <input type="number" step="0.01" x-model="formData.credit" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                            </div>
                        </div>

                        <div x-show="formData.currency !== 'MYR'">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Exchange Rate to MYR</label>
                            <input type="number" step="0.0001" x-model="formData.exchange_rate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0.0001">
                        </div>


                    </form>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="submitAddOpeningBalance()" type="button" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-xs font-medium" :disabled="isSubmitting">
                <span x-show="!isSubmitting">Add Balance</span>
                <span x-show="isSubmitting">Adding...</span>
            </button>
            <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                Cancel
            </button>
        </div>
    </div>
</div>

<!-- Edit Opening Balance Modal -->
<div x-data="{ open: false }" x-show="open" @open-modal.window="if ($event.detail === 'edit-opening-balance') open = true" @close-modal.window="if ($event.detail === 'edit-opening-balance') open = false" @keydown.escape.window="open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div id="edit-opening-balance" x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             x-data="{
                editFormData: {
                    id: '',
                    bank_code: '',
                    bank_name: '',
                    currency: 'MYR',
                    debit: 0,
                    credit: 0,
                    exchange_rate: 1.0000
                },
                isSubmitting: false,
                initEditForm() {
                    this.$el.addEventListener('set-edit-balance', (event) => {
                        const balance = event.detail;
                        if (balance) {
                            this.editFormData = {
                                id: balance.id,
                                bank_code: balance.bank_code,
                                bank_name: balance.bank_name,
                                currency: balance.currency,
                                debit: balance.debit,
                                credit: balance.credit,
                                exchange_rate: balance.exchange_rate
                            };
                        }
                    });
                },
                updateEditBankName() {
                    const bankCodes = {
                        '3010/010': 'Petty Cash',
                        '3010/020': 'Client Account',
                        '3010/030': 'Office Account'
                    };
                    this.editFormData.bank_name = bankCodes[this.editFormData.bank_code] || '';
                },
                submitEditOpeningBalance() {
                    this.isSubmitting = true;
                    fetch(`/settings/opening-balance/${this.editFormData.id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.editFormData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Opening balance updated successfully');
                            this.$dispatch('close-modal', 'edit-opening-balance');
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(() => {
                        alert('Failed to update opening balance');
                    })
                    .finally(() => {
                        this.isSubmitting = false;
                    });
                }
            }" x-init="initEditForm()">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Edit Opening Balance</h3>

                        <form class="space-y-4" @submit.prevent="submitEditOpeningBalance()">

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bank Code *</label>
                                <select x-model="editFormData.bank_code" @change="updateEditBankName()" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">Select Bank Code</option>
                                    <option value="3010/010">3010/010 - Petty Cash</option>
                                    <option value="3010/020">3010/020 - Client Account</option>
                                    <option value="3010/030">3010/030 - Office Account</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bank Name *</label>
                                <input type="text" x-model="editFormData.bank_name" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required readonly>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Currency *</label>
                                <select x-model="editFormData.currency" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="MYR">MYR</option>
                                    <option value="USD">USD</option>
                                    <option value="SGD">SGD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Debit</label>
                                    <input type="number" step="0.01" x-model="editFormData.debit" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Credit</label>
                                    <input type="number" step="0.01" x-model="editFormData.credit" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0">
                                </div>
                            </div>

                            <div x-show="editFormData.currency !== 'MYR'">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Exchange Rate to MYR</label>
                                <input type="number" step="0.0001" x-model="editFormData.exchange_rate" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-green-500" min="0.0001">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button @click="submitEditOpeningBalance()" type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-xs font-medium" :disabled="isSubmitting">
                    <span x-show="!isSubmitting">Update Balance</span>
                    <span x-show="isSubmitting">Updating...</span>
                </button>
                <button @click="open = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection