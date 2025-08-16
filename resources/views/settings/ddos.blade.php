@extends('layouts.app')

@section('breadcrumb')
    Settings > DDoS Config
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto space-y-6">
    <!-- Single Form for All Settings -->
    <form action="{{ route('settings.ddos.store') }}" method="POST" x-data="ddosConfig()">
        @csrf
        
        <!-- Hidden inputs for unchecked checkboxes -->
        <input type="hidden" name="enabled" value="false">
        <input type="hidden" name="shieldon[enabled]" value="false">
        <input type="hidden" name="logging[enabled]" value="false">
        <input type="hidden" name="layer7_protection[enabled]" value="false">
        <input type="hidden" name="layer7_protection[http_flood][enabled]" value="false">
        <input type="hidden" name="layer7_protection[session_protection][enabled]" value="false">
        <input type="hidden" name="layer7_protection[header_validation][enabled]" value="false">
        <input type="hidden" name="layer7_protection[payload_analysis][enabled]" value="false">
        <input type="hidden" name="layer7_protection[progressive_blocking][enabled]" value="false">

        <!-- General Settings Section -->
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-blue-600">security</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">General Settings</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure DDoS protection system and basic settings.</p>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="space-y-4">
                    <!-- Enable Protection -->
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-sm font-medium text-gray-700 text-xs">Enable DDoS Protection</label>
                            <p class="text-xs text-gray-500 mt-1">Turn on/off DDoS protection system</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enabled" x-model="config.enabled" :checked="config.enabled === 'true' || config.enabled === true" value="true" class="sr-only peer">
                            <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rate Limiting Section -->
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-green-600">speed</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Rate Limiting</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure request rate limits and connection limits.</p>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Requests per Minute</label>
                            <input type="number" name="rate_limiting[requests_per_minute]" x-model="config.rate_limiting.requests_per_minute" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="1000">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Requests per Hour</label>
                            <input type="number" name="rate_limiting[requests_per_hour]" x-model="config.rate_limiting.requests_per_hour" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="10000">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Block Duration (seconds)</label>
                            <input type="number" name="rate_limiting[block_duration]" x-model="config.rate_limiting.block_duration" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="60" max="86400">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Layer 7 Protection Section -->
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-purple-600">shield</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Advanced Layer 7 Protection</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure advanced protection against HTTP DDoS and application layer attacks.</p>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="space-y-6">
                    <!-- Layer 7 Protection Master Switch -->
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-sm font-medium text-gray-700 text-xs">Enable Layer 7 Protection</label>
                            <p class="text-xs text-gray-500">Advanced protection against HTTP DDoS attacks</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="layer7_protection[enabled]" x-model="config.layer7_protection?.enabled" :checked="config.layer7_protection?.enabled === 'true' || config.layer7_protection?.enabled === true" value="true" class="sr-only peer">
                            <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- HTTP Flood Protection -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">HTTP Flood Protection</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 text-xs">Enable HTTP Flood Protection</label>
                                    <p class="text-xs text-gray-500">Protect against rapid successive requests</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="layer7_protection[http_flood][enabled]" x-model="config.layer7_protection?.http_flood?.enabled" :checked="config.layer7_protection?.http_flood?.enabled === 'true' || config.layer7_protection?.http_flood?.enabled === true" value="true" class="sr-only peer">
                                    <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Max Requests per 10s</label>
                                    <input type="number" name="layer7_protection[http_flood][max_requests_per_10s]" x-model="config.layer7_protection?.http_flood?.max_requests_per_10s" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="5" max="100" value="20">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Max Concurrent Connections</label>
                                    <input type="number" name="layer7_protection[http_flood][max_concurrent_connections]" x-model="config.layer7_protection?.http_flood?.max_concurrent_connections" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="20" value="5">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Protection -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Session Protection</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 text-xs">Enable Session Protection</label>
                                    <p class="text-xs text-gray-500">Protect against session-based attacks</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="layer7_protection[session_protection][enabled]" x-model="config.layer7_protection?.session_protection?.enabled" :checked="config.layer7_protection?.session_protection?.enabled === 'true' || config.layer7_protection?.session_protection?.enabled === true" value="true" class="sr-only peer">
                                    <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Max Session Attempts (5min)</label>
                                    <input type="number" name="layer7_protection[session_protection][max_session_attempts]" x-model="config.layer7_protection?.session_protection?.max_session_attempts" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="5" max="50" value="10">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Max Session Creations (1min)</label>
                                    <input type="number" name="layer7_protection[session_protection][max_session_creations]" x-model="config.layer7_protection?.session_protection?.max_session_creations" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="20" value="5">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Header Validation -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Header Validation</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700 text-xs">Enable Header Validation</label>
                                <p class="text-xs text-gray-500">Block suspicious HTTP headers</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="layer7_protection[header_validation][enabled]" x-model="config.layer7_protection?.header_validation?.enabled" :checked="config.layer7_protection?.header_validation?.enabled === 'true' || config.layer7_protection?.header_validation?.enabled === true" value="true" class="sr-only peer">
                                <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Payload Analysis -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Payload Analysis</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700 text-xs">Enable Payload Analysis</label>
                                <p class="text-xs text-gray-500">Scan request body and query strings</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="layer7_protection[payload_analysis][enabled]" x-model="config.layer7_protection?.payload_analysis?.enabled" :checked="config.layer7_protection?.payload_analysis?.enabled === 'true' || config.layer7_protection?.payload_analysis?.enabled === true" value="true" class="sr-only peer">
                                <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Progressive Blocking -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Progressive Blocking</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 text-xs">Enable Progressive Blocking</label>
                                    <p class="text-xs text-gray-500">Increase block duration with violations</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="layer7_protection[progressive_blocking][enabled]" x-model="config.layer7_protection?.progressive_blocking?.enabled" :checked="config.layer7_protection?.progressive_blocking?.enabled === 'true' || config.layer7_protection?.progressive_blocking?.enabled === true" value="true" class="sr-only peer">
                                    <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Base Duration (seconds)</label>
                                    <input type="number" name="layer7_protection[progressive_blocking][base_duration]" x-model="config.layer7_protection?.progressive_blocking?.base_duration" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="300" max="7200" value="3600">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Max Duration (seconds)</label>
                                    <input type="number" name="layer7_protection[progressive_blocking][max_duration]" x-model="config.layer7_protection?.progressive_blocking?.max_duration" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="3600" max="604800" value="86400">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Multiplier</label>
                                    <input type="number" name="layer7_protection[progressive_blocking][multiplier]" x-model="config.layer7_protection?.progressive_blocking?.multiplier" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" max="10" step="0.5" value="2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shieldon Settings Section -->
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-orange-600">firewall</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Advanced Firewall (Shieldon)</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure Shieldon advanced web application firewall.</p>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-sm font-medium text-gray-700 text-xs">Enable Shieldon</label>
                            <p class="text-xs text-gray-500">Advanced web application firewall</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="shieldon[enabled]" x-model="config.shieldon.enabled" :checked="config.shieldon.enabled === 'true' || config.shieldon.enabled === true" value="true" class="sr-only peer">
                            <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logging Section -->
        <div class="bg-white rounded shadow-md border border-gray-300">
            <div class="p-4 md:p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-indigo-600">analytics</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Logging & Monitoring</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Configure security logging and monitoring settings.</p>
            </div>
            
            <div class="p-4 md:p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-sm font-medium text-gray-700 text-xs">Enable Security Logging</label>
                            <p class="text-xs text-gray-500">Log all security events and blocked requests</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="logging[enabled]" x-model="config.logging.enabled" :checked="config.logging.enabled === 'true' || config.logging.enabled === true" value="true" class="sr-only peer">
                            <div class="w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-4 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save All Settings Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                Save All DDoS Settings
            </button>
        </div>
    </form>

    <!-- IP Management Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center">
                <span class="material-icons mr-2 text-green-600">list</span>
                <h2 class="text-lg font-semibold text-gray-800 text-[14px]">IP Address Management</h2>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage whitelist and blacklist for trusted and blocked IP addresses.</p>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Whitelist -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Whitelist (Trusted IPs)</h4>
                <div class="flex gap-2 mb-3">
                    <input type="text" id="whitelist-ip" placeholder="Enter IP address" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="text" id="whitelist-desc" placeholder="Description (optional)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="addToWhitelist()" class="px-3 py-1.5 bg-green-600 text-white rounded-sm text-xs font-medium hover:bg-green-700">
                        Add
                    </button>
                </div>
                <div id="whitelist-container" class="space-y-2">
                    @foreach($whitelist as $ip => $data)
                    <div class="flex items-center justify-between p-2 bg-green-50 border border-green-200 rounded-md">
                        <div>
                            <span class="text-sm font-medium text-green-800 text-xs">{{ $ip }}</span>
                            <span class="text-xs text-green-600 ml-2">{{ $data['description'] ?? 'Trusted IP' }}</span>
                        </div>
                        <button type="button" onclick="removeFromWhitelist('{{ $ip }}')" class="text-red-600 hover:text-red-700 text-xs">
                            <span class="material-icons text-sm">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Blacklist -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3 text-xs">Blacklist (Blocked IPs)</h4>
                <div class="flex gap-2 mb-3">
                    <input type="text" id="blacklist-ip" placeholder="Enter IP address" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="text" id="blacklist-reason" placeholder="Reason (optional)" class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select id="blacklist-duration" class="px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="3600">1 Hour</option>
                        <option value="86400">1 Day</option>
                        <option value="604800">1 Week</option>
                        <option value="2592000">1 Month</option>
                        <option value="31536000">1 Year</option>
                    </select>
                    <button type="button" onclick="addToBlacklist()" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700">
                        Add
                    </button>
                </div>
                <div id="blacklist-container" class="space-y-2">
                    @foreach($blacklist as $ip => $data)
                    <div class="flex items-center justify-between p-2 bg-red-50 border border-red-200 rounded-md">
                        <div>
                            <span class="text-sm font-medium text-red-800 text-xs">{{ $ip }}</span>
                            <span class="text-xs text-red-600 ml-2">{{ $data['reason'] ?? 'Blocked IP' }}</span>
                        </div>
                        <button type="button" onclick="removeFromBlacklist('{{ $ip }}')" class="text-red-600 hover:text-red-700 text-xs">
                            <span class="material-icons text-sm">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-purple-600">analytics</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">Live Protection Statistics</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-xs text-gray-600">Live</span>
                    </div>
                    <span class="text-xs text-gray-500">Auto-refresh: 5s</span>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Real-time monitoring of DDoS protection performance and security metrics.</p>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Time Range Filter -->
            <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <button onclick="changeTimeRange('5s')" class="time-range-btn bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-blue-200 transition-colors" data-range="5s">5s</button>
                    <button onclick="changeTimeRange('1m')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="1m">1m</button>
                    <button onclick="changeTimeRange('5m')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="5m">5m</button>
                    <button onclick="changeTimeRange('15m')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="15m">15m</button>
                    <button onclick="changeTimeRange('1h')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="1h">1h</button>
                    <button onclick="changeTimeRange('2h')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="2h">2h</button>
                    <button onclick="changeTimeRange('1d')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="1d">1d</button>
                    <button onclick="changeTimeRange('30d')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="30d">30d</button>
                    <button onclick="changeTimeRange('1y')" class="time-range-btn bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-xs font-medium hover:bg-gray-200 transition-colors" data-range="1y">1y</button>
                </div>
            </div>

            <!-- Live Chart -->
            <div class="mb-6">
                <canvas id="protectionChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Current Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="text-center py-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-2xl font-bold text-blue-600" id="total-requests">{{ number_format($stats['total_requests']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Total Requests</div>
                    <div class="text-xs text-blue-600 mt-1" id="total-requests-change">+0</div>
                </div>
                <div class="text-center py-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="text-2xl font-bold text-red-600" id="blocked-requests">{{ number_format($stats['blocked_requests']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Blocked Requests</div>
                    <div class="text-xs text-red-600 mt-1" id="blocked-requests-change">+0</div>
                </div>
                <div class="text-center py-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600" id="protection-level">{{ $stats['protection_level'] }}</div>
                    <div class="text-xs text-gray-500 mt-1">Protection Level</div>
                    <div class="text-xs text-green-600 mt-1" id="protection-level-status">Active</div>
                </div>
                <div class="text-center py-4 bg-orange-50 rounded-lg border border-orange-200">
                    <div class="text-2xl font-bold text-orange-600" id="blocked-ips">{{ number_format($stats['blocked_ips']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Blocked IPs</div>
                    <div class="text-xs text-orange-600 mt-1" id="blocked-ips-change">+0</div>
                </div>
                <div class="text-center py-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600" id="whitelisted-ips">{{ number_format($stats['whitelisted_ips']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Whitelisted IPs</div>
                    <div class="text-xs text-green-600 mt-1" id="whitelisted-ips-change">+0</div>
                </div>
                <div class="text-center py-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="text-2xl font-bold text-red-600" id="blacklisted-ips">{{ number_format($stats['blacklisted_ips']) }}</div>
                    <div class="text-xs text-gray-500 mt-1">Blacklisted IPs</div>
                    <div class="text-xs text-red-600 mt-1" id="blacklisted-ips-change">+0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- DDoS Protection Logs Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-red-600">history</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">DDoS Protection Logs</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="refreshLogs()" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Refresh
                    </button>
                    <button type="button" onclick="clearLogs()" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700">
                        Clear
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Real-time logs of DDoS protection events, blocked requests, and security incidents.</p>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Log Filters -->
            <div class="mb-4 flex flex-wrap gap-2">
                <button type="button" onclick="filterLogs('all')" class="px-3 py-1.5 bg-gray-600 text-white rounded-sm text-xs font-medium hover:bg-gray-700 log-filter active" data-filter="all">
                    All
                </button>
                <button type="button" onclick="filterLogs('warning')" class="px-3 py-1.5 bg-yellow-600 text-white rounded-sm text-xs font-medium hover:bg-yellow-700 log-filter" data-filter="warning">
                    Warnings
                </button>
                <button type="button" onclick="filterLogs('error')" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700 log-filter" data-filter="error">
                    Errors
                </button>
                <button type="button" onclick="filterLogs('info')" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700 log-filter" data-filter="info">
                    Info
                </button>
            </div>
            
            <!-- Logs Container -->
            <div id="logs-container" class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                <div class="text-center text-gray-500 text-sm">
                    Loading logs...
                </div>
            </div>
            
            <!-- Log Stats -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <div class="text-lg font-bold text-blue-600" id="total-logs">0</div>
                    <div class="text-xs text-gray-600">Total Logs</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                    <div class="text-lg font-bold text-yellow-600" id="warning-logs">0</div>
                    <div class="text-xs text-gray-600">Warnings</div>
                </div>
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <div class="text-lg font-bold text-red-600" id="error-logs">0</div>
                    <div class="text-xs text-gray-600">Errors</div>
                </div>
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <div class="text-lg font-bold text-green-600" id="info-logs">0</div>
                    <div class="text-xs text-gray-600">Info</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function ddosConfig() {
    return {
        config: @json($config),
    }
}

// Live Chart Configuration
let protectionChart;
let chartData = {
    labels: [],
    totalRequests: [],
    blockedRequests: [],
    blockedIps: []
};
let currentTimeRange = '5s';
let chartUpdateInterval;

// Time Range Configuration
const timeRangeConfig = {
    '5s': { interval: 5000, points: 10, label: '5 seconds' },
    '1m': { interval: 5000, points: 12, label: '1 minute' },
    '5m': { interval: 10000, points: 30, label: '5 minutes' },
    '15m': { interval: 30000, points: 30, label: '15 minutes' },
    '1h': { interval: 60000, points: 60, label: '1 hour' },
    '2h': { interval: 120000, points: 60, label: '2 hours' },
    '1d': { interval: 300000, points: 288, label: '1 day' },
    '30d': { interval: 3600000, points: 720, label: '30 days' },
    '1y': { interval: 86400000, points: 365, label: '1 year' }
};

// Change Time Range
function changeTimeRange(range) {
    // Prevent multiple rapid changes
    if (currentTimeRange === range) return;
    
    currentTimeRange = range;
    
    // Update button styles
    document.querySelectorAll('.time-range-btn').forEach(btn => {
        btn.classList.remove('bg-blue-100', 'text-blue-700');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    const activeBtn = document.querySelector(`[data-range="${range}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        activeBtn.classList.add('bg-blue-100', 'text-blue-700');
    }
    
    // Reset chart data
    resetChartData();
    
    // Reinitialize chart
    if (protectionChart) {
        protectionChart.destroy();
    }
    
    // Initialize chart immediately
    initChart();
    
    // Update chart title
    if (protectionChart) {
        protectionChart.options.plugins.title.text = `DDoS Protection Metrics (${timeRangeConfig[range].label})`;
        protectionChart.update('none');
    }
}

// Reset Chart Data
function resetChartData() {
    chartData = {
        labels: [],
        totalRequests: [],
        blockedRequests: [],
        blockedIps: []
    };
    
    const config = timeRangeConfig[currentTimeRange];
    const now = new Date();
    
    // For longer time ranges, use fewer data points to prevent hanging
    let actualPoints = config.points;
    if (actualPoints > 100) {
        actualPoints = 100; // Limit to 100 points max for performance
    }
    
    for (let i = actualPoints - 1; i >= 0; i--) {
        const time = new Date(now.getTime() - (i * config.interval));
        
        // Format time based on range
        let timeLabel;
        if (currentTimeRange === '5s' || currentTimeRange === '1m') {
            timeLabel = time.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        } else if (currentTimeRange === '5m' || currentTimeRange === '15m') {
            timeLabel = time.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit'
            });
        } else if (currentTimeRange === '1h' || currentTimeRange === '2h') {
            timeLabel = time.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit'
            });
        } else {
            timeLabel = time.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        chartData.labels.push(timeLabel);
        // Always start with zero for truly live data
        chartData.totalRequests.push(0);
        chartData.blockedRequests.push(0);
        chartData.blockedIps.push(0);
    }
}

// Initialize Chart
function initChart() {
    try {
        const chartElement = document.getElementById('protectionChart');
        if (!chartElement) {
            console.error('Chart element not found');
            return;
        }
        
        const ctx = chartElement.getContext('2d');
        if (!ctx) {
            console.error('Could not get chart context');
            return;
        }
        
        // Reset chart data based on current time range
        resetChartData();
        
        // Destroy existing chart if it exists
        if (protectionChart) {
            protectionChart.destroy();
        }
        
        protectionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Total Requests',
                        data: chartData.totalRequests,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.2,
                        fill: true
                    },
                    {
                        label: 'Blocked Requests',
                        data: chartData.blockedRequests,
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.2,
                        fill: true
                    },
                    {
                        label: 'Blocked IPs',
                        data: chartData.blockedIps,
                        borderColor: 'rgb(249, 115, 22)',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: `DDoS Protection Metrics (${timeRangeConfig[currentTimeRange].label})`,
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            maxRotation: 45,
                            maxTicksLimit: 20
                        }
                    }
                },
                animation: {
                    duration: 300,
                    easing: 'easeInOutQuart'
                },
                elements: {
                    point: {
                        radius: 2,
                        hoverRadius: 4
                    },
                    line: {
                        tension: 0.2
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        console.log('Chart initialized successfully');
    } catch (error) {
        console.error('Error initializing chart:', error);
        // Show error message in chart container
        const chartContainer = document.getElementById('protectionChart').parentElement;
        chartContainer.innerHTML = '<div class="flex items-center justify-center h-48"><div class="text-red-500">Error loading chart. Please refresh the page.</div></div>';
    }
}

// Update Chart Data
function updateChart(newData) {
    // Only update chart for shorter time ranges to prevent hanging
    if (currentTimeRange === '5s' || currentTimeRange === '1m' || currentTimeRange === '5m') {
        // Check if there's actual DDoS activity
        const hasActivity = newData.total_requests > 0 || newData.blocked_requests > 0 || newData.blocked_ips > 0;
        
        if (!hasActivity) {
            // Reset chart data if no activity
            resetChartData();
            if (protectionChart) {
                protectionChart.data.labels = chartData.labels;
                protectionChart.data.datasets[0].data = chartData.totalRequests;
                protectionChart.data.datasets[1].data = chartData.blockedRequests;
                protectionChart.data.datasets[2].data = chartData.blockedIps;
                protectionChart.update('none');
            }
            return;
        }
        
        // Remove oldest data point
        chartData.labels.shift();
        chartData.totalRequests.shift();
        chartData.blockedRequests.shift();
        chartData.blockedIps.shift();
        
        // Add new data point
        const now = new Date();
        let timeLabel;
        
        if (currentTimeRange === '5s' || currentTimeRange === '1m') {
            timeLabel = now.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
        } else {
            timeLabel = now.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit'
            });
        }
        
        chartData.labels.push(timeLabel);
        chartData.totalRequests.push(newData.total_requests);
        chartData.blockedRequests.push(newData.blocked_requests);
        chartData.blockedIps.push(newData.blocked_ips);
        
        // Update chart with performance optimization
        if (protectionChart) {
            protectionChart.data.labels = chartData.labels;
            protectionChart.data.datasets[0].data = chartData.totalRequests;
            protectionChart.data.datasets[1].data = chartData.blockedRequests;
            protectionChart.data.datasets[2].data = chartData.blockedIps;
            protectionChart.update('none');
        }
    }
    // For longer time ranges, just update the stats without chart animation
}

// Update Stats Display
function updateStats(newData) {
    // Update total requests
    const totalRequestsEl = document.getElementById('total-requests');
    const totalRequestsChangeEl = document.getElementById('total-requests-change');
    const oldTotal = parseInt(totalRequestsEl.textContent.replace(/,/g, ''));
    const newTotal = newData.total_requests;
    const change = newTotal - oldTotal;
    
    totalRequestsEl.textContent = newTotal.toLocaleString();
    totalRequestsChangeEl.textContent = change >= 0 ? `+${change}` : `${change}`;
    totalRequestsChangeEl.className = `text-xs ${change >= 0 ? 'text-green-600' : 'text-red-600'} mt-1`;
    
    // Update blocked requests
    const blockedRequestsEl = document.getElementById('blocked-requests');
    const blockedRequestsChangeEl = document.getElementById('blocked-requests-change');
    const oldBlocked = parseInt(blockedRequestsEl.textContent.replace(/,/g, ''));
    const newBlocked = newData.blocked_requests;
    const blockedChange = newBlocked - oldBlocked;
    
    blockedRequestsEl.textContent = newBlocked.toLocaleString();
    blockedRequestsChangeEl.textContent = blockedChange >= 0 ? `+${blockedChange}` : `${blockedChange}`;
    blockedRequestsChangeEl.className = `text-xs ${blockedChange >= 0 ? 'text-green-600' : 'text-red-600'} mt-1`;
    
    // Update blocked IPs
    const blockedIpsEl = document.getElementById('blocked-ips');
    const blockedIpsChangeEl = document.getElementById('blocked-ips-change');
    const oldBlockedIps = parseInt(blockedIpsEl.textContent.replace(/,/g, ''));
    const newBlockedIps = newData.blocked_ips;
    const blockedIpsChange = newBlockedIps - oldBlockedIps;
    
    blockedIpsEl.textContent = newBlockedIps.toLocaleString();
    blockedIpsChangeEl.textContent = blockedIpsChange >= 0 ? `+${blockedIpsChange}` : `${blockedIpsChange}`;
    blockedIpsChangeEl.className = `text-xs ${blockedIpsChange >= 0 ? 'text-green-600' : 'text-red-600'} mt-1`;
    
    // Update other stats
    document.getElementById('whitelisted-ips').textContent = newData.whitelisted_ips.toLocaleString();
    document.getElementById('blacklisted-ips').textContent = newData.blacklisted_ips.toLocaleString();
    document.getElementById('protection-level').textContent = newData.protection_level;
    
    // Update protection level status
    const statusEl = document.getElementById('protection-level-status');
    if (newData.protection_level === 'ENTERPRISE-GRADE') {
        statusEl.textContent = 'Active';
        statusEl.className = 'text-xs text-green-600 mt-1';
    } else {
        statusEl.textContent = 'Standard';
        statusEl.className = 'text-xs text-orange-600 mt-1';
    }
}

// Live Chart and Stats Update
async function fetchLiveData() {
    try {
        const response = await fetch('{{ route("ddos.stats.public") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const newData = await response.json();
            
            // Check if there's actual DDoS activity
            const hasActivity = newData.total_requests > 0 || newData.blocked_requests > 0 || newData.blocked_ips > 0;
            
            if (hasActivity) {
                console.log('DDoS activity detected:', newData);
                updateChart(newData);
                updateStats(newData);
            } else {
                console.log('No DDoS activity - resetting chart');
                // Reset chart to show no activity
                resetChartData();
                if (protectionChart) {
                    protectionChart.data.labels = chartData.labels;
                    protectionChart.data.datasets[0].data = chartData.totalRequests;
                    protectionChart.data.datasets[1].data = chartData.blockedRequests;
                    protectionChart.data.datasets[2].data = chartData.blockedIps;
                    protectionChart.update('none');
                }
                updateStats(newData);
            }
        }
    } catch (error) {
        console.error('Error fetching live data:', error);
    }
}

// Logs functionality
let currentLogs = [];
let currentFilter = 'all';

function loadLogs() {
    fetch('{{ route("ddos.logs.public") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        currentLogs = data.logs;
        displayLogs(data.logs);
        updateLogStats(data.stats);
    })
    .catch(error => {
        console.error('Error loading logs:', error);
        document.getElementById('logs-container').innerHTML = '<div class="text-center text-red-500 text-sm">Error loading logs</div>';
    });
}

function displayLogs(logs) {
    const container = document.getElementById('logs-container');
    
    if (logs.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500 text-sm">No logs found</div>';
        return;
    }
    
    const logsHtml = logs.map(log => {
        const levelClass = getLevelClass(log.level);
        const levelIcon = getLevelIcon(log.level);
        
        return `
            <div class="log-entry ${levelClass} p-3 mb-2 rounded-lg border-l-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-2">
                        <span class="material-icons text-sm mt-0.5">${levelIcon}</span>
                        <div class="flex-1">
                            <div class="text-xs text-gray-600 mb-1">${log.timestamp}</div>
                            <div class="text-sm">${log.message}</div>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full ${getLevelBadgeClass(log.level)}">${log.level.toUpperCase()}</span>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = logsHtml;
}

function filterLogs(level) {
    currentFilter = level;
    
    // Update filter buttons
    document.querySelectorAll('.log-filter').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === level) {
            btn.classList.add('active');
        }
    });
    
    // Filter logs
    let filteredLogs = currentLogs;
    if (level !== 'all') {
        filteredLogs = currentLogs.filter(log => log.level === level);
    }
    
    displayLogs(filteredLogs);
}

function refreshLogs() {
    loadLogs();
}

function clearLogs() {
    if (confirm('Are you sure you want to clear all DDoS protection logs? This action cannot be undone.')) {
        fetch('{{ route("settings.ddos.logs.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadLogs();
                alert('Logs cleared successfully');
            }
        })
        .catch(error => {
            console.error('Error clearing logs:', error);
            alert('Error clearing logs');
        });
    }
}

function updateLogStats(stats) {
    document.getElementById('total-logs').textContent = stats.total;
    document.getElementById('warning-logs').textContent = stats.warning;
    document.getElementById('error-logs').textContent = stats.error;
    document.getElementById('info-logs').textContent = stats.info;
}

function getLevelClass(level) {
    switch (level) {
        case 'error': return 'bg-red-50 border-red-200';
        case 'warning': return 'bg-yellow-50 border-yellow-200';
        case 'info': return 'bg-blue-50 border-blue-200';
        default: return 'bg-gray-50 border-gray-200';
    }
}

function getLevelIcon(level) {
    switch (level) {
        case 'error': return 'error';
        case 'warning': return 'warning';
        case 'info': return 'info';
        default: return 'info';
    }
}

function getLevelBadgeClass(level) {
    switch (level) {
        case 'error': return 'bg-red-100 text-red-800';
        case 'warning': return 'bg-yellow-100 text-yellow-800';
        case 'info': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function addToWhitelist() {
    const ip = document.getElementById('whitelist-ip').value;
    const description = document.getElementById('whitelist-desc').value;
    
    if (!ip) return;
    
    fetch('{{ route("settings.ddos.whitelist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ip, description })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeFromWhitelist(ip) {
    fetch(`{{ route("settings.ddos.whitelist.remove", "PLACEHOLDER") }}`.replace('PLACEHOLDER', ip), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function addToBlacklist() {
    const ip = document.getElementById('blacklist-ip').value;
    const reason = document.getElementById('blacklist-reason').value;
    const duration = document.getElementById('blacklist-duration').value;
    
    if (!ip) return;
    
    fetch('{{ route("settings.ddos.blacklist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ip, reason, duration })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeFromBlacklist(ip) {
    fetch(`{{ route("settings.ddos.blacklist.remove", "PLACEHOLDER") }}`.replace('PLACEHOLDER', ip), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing chart...');
    
    // Initialize chart
    initChart();
    
    // Start live data updates
    fetchLiveData();
    
    // Set up auto-refresh
    setInterval(fetchLiveData, 5000);
    
    // Load logs
    loadLogs();
    
    console.log('Initialization complete');
});
</script>
@endsection 