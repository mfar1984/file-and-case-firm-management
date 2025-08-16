@extends('layouts.app')

@section('breadcrumb')
    Settings > Log Activity
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-red-600">history</span>
                    <h2 class="text-lg font-semibold text-gray-800 text-[14px]">System Logs & Monitoring</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="refreshAllLogs()" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Refresh All
                    </button>
                    <button type="button" onclick="exportLogs()" class="px-3 py-1.5 bg-green-600 text-white rounded-sm text-xs font-medium hover:bg-green-700">
                        Export
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Comprehensive logging and monitoring for DDoS protection, system events, and security incidents.</p>
        </div>
    </div>

    <!-- DDoS Protection Logs Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-purple-600">security</span>
                    <h3 class="text-md font-semibold text-gray-800 text-[13px]">DDoS Protection Logs</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="refreshDdosLogs()" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Refresh
                    </button>
                    <button type="button" onclick="clearDdosLogs()" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700">
                        Clear
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- DDoS Log Filters -->
            <div class="mb-4 flex flex-wrap gap-2">
                <button type="button" onclick="filterDdosLogs('all')" class="px-3 py-1.5 bg-gray-600 text-white rounded-sm text-xs font-medium hover:bg-gray-700 ddos-log-filter active" data-filter="all">
                    All
                </button>
                <button type="button" onclick="filterDdosLogs('warning')" class="px-3 py-1.5 bg-yellow-600 text-white rounded-sm text-xs font-medium hover:bg-yellow-700 ddos-log-filter" data-filter="warning">
                    Warnings
                </button>
                <button type="button" onclick="filterDdosLogs('error')" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700 ddos-log-filter" data-filter="error">
                    Errors
                </button>
                <button type="button" onclick="filterDdosLogs('info')" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700 ddos-log-filter" data-filter="info">
                    Info
                </button>
            </div>
            
            <!-- DDoS Logs Container -->
            <div id="ddos-logs-container" class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                <div class="text-center text-gray-500 text-sm">
                    Loading DDoS logs...
                </div>
            </div>
            
            <!-- DDoS Log Stats -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <div class="text-lg font-bold text-blue-600" id="ddos-total-logs">0</div>
                    <div class="text-xs text-gray-600">Total Logs</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                    <div class="text-lg font-bold text-yellow-600" id="ddos-warning-logs">0</div>
                    <div class="text-xs text-gray-600">Warnings</div>
                </div>
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <div class="text-lg font-bold text-red-600" id="ddos-error-logs">0</div>
                    <div class="text-xs text-gray-600">Errors</div>
                </div>
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <div class="text-lg font-bold text-green-600" id="ddos-info-logs">0</div>
                    <div class="text-xs text-gray-600">Info</div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Logs Section -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-orange-600">computer</span>
                    <h3 class="text-md font-semibold text-gray-800 text-[13px]">System Logs</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="refreshSystemLogs()" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        Refresh
                    </button>
                    <button type="button" onclick="clearSystemLogs()" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700">
                        Clear
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <!-- Search and Filters -->
            <div class="mb-4 flex flex-wrap gap-2 items-center">
                <input type="text" id="system-log-search" placeholder="Search logs..." class="px-3 py-1.5 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="searchSystemLogs()" class="px-3 py-1.5 bg-gray-600 text-white rounded-sm text-xs font-medium hover:bg-gray-700">
                    Search
                </button>
                <button type="button" onclick="filterSystemLogs('all')" class="px-3 py-1.5 bg-gray-600 text-white rounded-sm text-xs font-medium hover:bg-gray-700 system-log-filter active" data-filter="all">
                    All
                </button>
                <button type="button" onclick="filterSystemLogs('warning')" class="px-3 py-1.5 bg-yellow-600 text-white rounded-sm text-xs font-medium hover:bg-yellow-700 system-log-filter" data-filter="warning">
                    Warnings
                </button>
                <button type="button" onclick="filterSystemLogs('error')" class="px-3 py-1.5 bg-red-600 text-white rounded-sm text-xs font-medium hover:bg-red-700 system-log-filter" data-filter="error">
                    Errors
                </button>
                <button type="button" onclick="filterSystemLogs('info')" class="px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700 system-log-filter" data-filter="info">
                    Info
                </button>
            </div>
            
            <!-- System Logs Container -->
            <div id="system-logs-container" class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                <div class="text-center text-gray-500 text-sm">
                    Loading system logs...
                </div>
            </div>
            
            <!-- System Log Stats -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <div class="text-lg font-bold text-blue-600" id="system-total-logs">0</div>
                    <div class="text-xs text-gray-600">Total Logs</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                    <div class="text-lg font-bold text-yellow-600" id="system-warning-logs">0</div>
                    <div class="text-xs text-gray-600">Warnings</div>
                </div>
                <div class="bg-red-50 rounded-lg p-3 border border-red-200">
                    <div class="text-lg font-bold text-red-600" id="system-error-logs">0</div>
                    <div class="text-xs text-gray-600">Errors</div>
                </div>
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <div class="text-lg font-bold text-green-600" id="system-info-logs">0</div>
                    <div class="text-xs text-gray-600">Info</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Monitoring -->
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="material-icons mr-2 text-green-600">monitor</span>
                    <h3 class="text-md font-semibold text-gray-800 text-[13px]">Real-time Monitoring</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-xs text-gray-600">Live</span>
                    </div>
                    <span class="text-xs text-gray-500">Auto-refresh: 10s</span>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-blue-600" id="active-connections">0</div>
                            <div class="text-xs text-gray-600">Active Connections</div>
                        </div>
                        <span class="material-icons text-blue-600">wifi</span>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-green-600" id="requests-per-minute">0</div>
                            <div class="text-xs text-gray-600">Requests/Min</div>
                        </div>
                        <span class="material-icons text-green-600">speed</span>
                    </div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-red-600" id="blocked-requests-live">0</div>
                            <div class="text-xs text-gray-600">Blocked Requests</div>
                        </div>
                        <span class="material-icons text-red-600">block</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let ddosLogs = [];
let systemLogs = [];
let currentDdosFilter = 'all';
let currentSystemFilter = 'all';

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadDdosLogs();
    loadSystemLogs();
    startRealTimeMonitoring();
});

// DDoS Logs functionality
function loadDdosLogs() {
    fetch('{{ route("ddos.logs.public") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        ddosLogs = data.logs;
        displayDdosLogs(ddosLogs);
        updateDdosLogStats(data.stats);
    })
    .catch(error => {
        console.error('Error loading DDoS logs:', error);
        document.getElementById('ddos-logs-container').innerHTML = '<div class="text-center text-red-500 text-sm">Error loading DDoS logs</div>';
    });
}

function displayDdosLogs(logs) {
    const container = document.getElementById('ddos-logs-container');
    
    if (logs.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500 text-sm">No DDoS logs found</div>';
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

function filterDdosLogs(level) {
    currentDdosFilter = level;
    
    // Update filter buttons
    document.querySelectorAll('.ddos-log-filter').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === level) {
            btn.classList.add('active');
        }
    });
    
    // Filter logs
    let filteredLogs = ddosLogs;
    if (level !== 'all') {
        filteredLogs = ddosLogs.filter(log => log.level === level);
    }
    
    displayDdosLogs(filteredLogs);
}

function refreshDdosLogs() {
    loadDdosLogs();
}

function clearDdosLogs() {
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
                loadDdosLogs();
                alert('DDoS logs cleared successfully');
            }
        })
        .catch(error => {
            console.error('Error clearing DDoS logs:', error);
            alert('Error clearing DDoS logs');
        });
    }
}

function updateDdosLogStats(stats) {
    document.getElementById('ddos-total-logs').textContent = stats.total;
    document.getElementById('ddos-warning-logs').textContent = stats.warning;
    document.getElementById('ddos-error-logs').textContent = stats.error;
    document.getElementById('ddos-info-logs').textContent = stats.info;
}

// System Logs functionality
function loadSystemLogs() {
    fetch('{{ route("settings.system.logs") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        systemLogs = data.logs;
        displaySystemLogs(systemLogs);
        updateSystemLogStats(data.stats);
    })
    .catch(error => {
        console.error('Error loading system logs:', error);
        document.getElementById('system-logs-container').innerHTML = '<div class="text-center text-red-500 text-sm">Error loading system logs</div>';
    });
}

function displaySystemLogs(logs) {
    const container = document.getElementById('system-logs-container');
    
    if (logs.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500 text-sm">No system logs found</div>';
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

function filterSystemLogs(level) {
    currentSystemFilter = level;
    
    // Update filter buttons
    document.querySelectorAll('.system-log-filter').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.filter === level) {
            btn.classList.add('active');
        }
    });
    
    // Filter logs
    let filteredLogs = systemLogs;
    if (level !== 'all') {
        filteredLogs = systemLogs.filter(log => log.level === level);
    }
    
    displaySystemLogs(filteredLogs);
}

function searchSystemLogs() {
    const searchTerm = document.getElementById('system-log-search').value.toLowerCase();
    
    if (!searchTerm) {
        displaySystemLogs(systemLogs);
        return;
    }
    
    const filteredLogs = systemLogs.filter(log => 
        log.message.toLowerCase().includes(searchTerm) ||
        log.timestamp.toLowerCase().includes(searchTerm)
    );
    
    displaySystemLogs(filteredLogs);
}

function refreshSystemLogs() {
    loadSystemLogs();
}

function clearSystemLogs() {
    if (confirm('Are you sure you want to clear all system logs? This action cannot be undone.')) {
        fetch('{{ route("settings.system.logs.clear") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSystemLogs();
                alert('System logs cleared successfully');
            }
        })
        .catch(error => {
            console.error('Error clearing system logs:', error);
            alert('Error clearing system logs');
        });
    }
}

function updateSystemLogStats(stats) {
    document.getElementById('system-total-logs').textContent = stats.total;
    document.getElementById('system-warning-logs').textContent = stats.warning;
    document.getElementById('system-error-logs').textContent = stats.error;
    document.getElementById('system-info-logs').textContent = stats.info;
}

// Real-time monitoring
function startRealTimeMonitoring() {
    // Update monitoring data every 10 seconds
    setInterval(updateMonitoringData, 10000);
    updateMonitoringData(); // Initial update
}

function updateMonitoringData() {
    fetch('{{ route("settings.monitoring.data") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('active-connections').textContent = data.active_connections || 0;
        document.getElementById('requests-per-minute').textContent = data.requests_per_minute || 0;
        document.getElementById('blocked-requests-live').textContent = data.blocked_requests || 0;
    })
    .catch(error => {
        console.error('Error updating monitoring data:', error);
    });
}

// Utility functions
function refreshAllLogs() {
    loadDdosLogs();
    loadSystemLogs();
}

function exportLogs() {
    const data = {
        ddos_logs: ddosLogs,
        system_logs: systemLogs,
        export_time: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `logs-export-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
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
</script>
@endsection 