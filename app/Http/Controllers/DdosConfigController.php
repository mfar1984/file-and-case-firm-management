<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\DdosSetting;

class DdosConfigController extends Controller
{
    /**
     * Display DDoS configuration page
     */
    public function index()
    {
        $config = DdosSetting::getNestedStructure();
        $whitelist = Cache::get('ddos_whitelist', []);
        $blacklist = Cache::get('ddos_blacklist', []);
        $stats = $this->getDdosStats();
        
        return view('settings.ddos', compact('config', 'whitelist', 'blacklist', 'stats'));
    }

    /**
     * Store DDoS configuration
     */
    public function store(Request $request)
    {
        // Debug: Log incoming request data
        Log::info('DDoS Config Store Request:', $request->all());
        
        $validated = $request->validate([
            'enabled' => 'nullable|boolean',
            'rate_limiting.requests_per_minute' => 'nullable|integer|min:1|max:1000',
            'rate_limiting.requests_per_hour' => 'nullable|integer|min:1|max:10000',
            'rate_limiting.block_duration' => 'nullable|integer|min:60|max:86400',
            'shieldon.enabled' => 'nullable|boolean',
            'logging.enabled' => 'nullable|boolean',
            'layer7_protection.enabled' => 'nullable|boolean',
            'layer7_protection.http_flood.enabled' => 'nullable|boolean',
            'layer7_protection.http_flood.max_requests_per_10s' => 'nullable|integer|min:5|max:100',
            'layer7_protection.http_flood.max_concurrent_connections' => 'nullable|integer|min:1|max:20',
            'layer7_protection.session_protection.enabled' => 'nullable|boolean',
            'layer7_protection.session_protection.max_session_attempts' => 'nullable|integer|min:5|max:50',
            'layer7_protection.session_protection.max_session_creations' => 'nullable|integer|min:1|max:20',
            'layer7_protection.header_validation.enabled' => 'nullable|boolean',
            'layer7_protection.payload_analysis.enabled' => 'nullable|boolean',
            'layer7_protection.progressive_blocking.enabled' => 'nullable|boolean',
            'layer7_protection.progressive_blocking.base_duration' => 'nullable|integer|min:300|max:7200',
            'layer7_protection.progressive_blocking.max_duration' => 'nullable|integer|min:3600|max:604800',
            'layer7_protection.progressive_blocking.multiplier' => 'nullable|numeric|min:1|max:10',
        ]);

        // Debug: Log validated data
        Log::info('DDoS Config Validated Data:', $validated);

        // Update configuration in database
        $this->updateConfig($validated);

        return redirect()->route('settings.ddos.index')
            ->with('success', 'DDoS protection settings updated successfully');
    }

    /**
     * Get DDoS protection statistics for live chart
     */
    public function getStats()
    {
        // Get current statistics
        $stats = $this->getDdosStats();
        
        // Return JSON response for AJAX requests
        return response()->json($stats);
    }

    /**
     * Get DDoS protection logs
     */
    public function getLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $ddosLogs = [];
            
            foreach ($lines as $line) {
                if (strpos($line, 'DDoS') !== false) {
                    $ddosLogs[] = $line;
                }
            }
            
            // Get last 100 DDoS logs
            $ddosLogs = array_slice($ddosLogs, -100);
            
            foreach ($ddosLogs as $log) {
                $logs[] = $this->parseLogLine($log);
            }
        }
        
        return response()->json([
            'logs' => $logs,
            'stats' => $this->getLogStats($logs)
        ]);
    }

    /**
     * Clear DDoS protection logs
     */
    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            // Create backup
            $backupFile = storage_path('logs/laravel-' . date('Y-m-d-H-i-s') . '.log');
            copy($logFile, $backupFile);
            
            // Clear current log
            file_put_contents($logFile, '');
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Logs cleared successfully'
        ]);
    }

    /**
     * Parse log line into structured data
     */
    private function parseLogLine($line)
    {
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.+)/';
        
        if (preg_match($pattern, $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'level' => strtolower($matches[3]),
                'message' => $matches[4],
                'raw' => $line
            ];
        }
        
        return [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'level' => 'info',
            'message' => $line,
            'raw' => $line
        ];
    }

    /**
     * Get log statistics
     */
    private function getLogStats($logs)
    {
        $stats = [
            'total' => count($logs),
            'warning' => 0,
            'error' => 0,
            'info' => 0
        ];
        
        foreach ($logs as $log) {
            $level = $log['level'];
            if (isset($stats[$level])) {
                $stats[$level]++;
            }
        }
        
        return $stats;
    }

    /**
     * Get system logs
     */
    public function getSystemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            // Get last 200 system logs (excluding DDoS logs)
            $systemLogs = [];
            foreach ($lines as $line) {
                if (strpos($line, 'DDoS') === false) {
                    $systemLogs[] = $line;
                }
            }
            
            $systemLogs = array_slice($systemLogs, -200);
            
            foreach ($systemLogs as $log) {
                $logs[] = $this->parseLogLine($log);
            }
        }
        
        return response()->json([
            'logs' => $logs,
            'stats' => $this->getLogStats($logs)
        ]);
    }

    /**
     * Clear system logs
     */
    public function clearSystemLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            // Create backup
            $backupFile = storage_path('logs/system-' . date('Y-m-d-H-i-s') . '.log');
            copy($logFile, $backupFile);
            
            // Clear current log
            file_put_contents($logFile, '');
        }
        
        return response()->json([
            'success' => true,
            'message' => 'System logs cleared successfully'
        ]);
    }

    /**
     * Get monitoring data
     */
    public function getMonitoringData()
    {
        // Get current statistics
        $stats = $this->getDdosStats();
        
        // Simulate real-time monitoring data
        $monitoringData = [
            'active_connections' => rand(5, 50), // Simulated active connections
            'requests_per_minute' => $stats['total_requests'] % 100 + rand(10, 30), // Based on total requests
            'blocked_requests' => $stats['blocked_requests'],
            'server_load' => rand(20, 80), // Simulated server load percentage
            'memory_usage' => rand(40, 90), // Simulated memory usage percentage
            'disk_usage' => rand(30, 85), // Simulated disk usage percentage
        ];
        
        return response()->json($monitoringData);
    }

    /**
     * Get DDoS protection statistics (private method for internal use)
     */
    private function getDdosStats()
    {
        // Get whitelist and blacklist from cache
        $whitelist = Cache::get('ddos_whitelist', []);
        $blacklist = Cache::get('ddos_blacklist', []);
        
        // Get current request counts from cache
        $totalRequests = Cache::get('ddos_total_requests', 0);
        $blockedRequests = Cache::get('ddos_blocked_requests', 0);
        $blockedIps = Cache::get('ddos_blocked_ips', 0);
        
        // Get protection level
        $protectionLevel = $this->getProtectionLevel();
        
        return [
            'total_requests' => $totalRequests,
            'blocked_requests' => $blockedRequests,
            'blocked_ips' => $blockedIps,
            'whitelisted_ips' => count($whitelist),
            'blacklisted_ips' => count($blacklist),
            'protection_level' => $protectionLevel,
        ];
    }

    /**
     * Add IP to whitelist
     */
    public function addWhitelist(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'description' => 'nullable|string|max:255',
        ]);

        $whitelist = Cache::get('ddos_whitelist', []);
        $whitelist[$validated['ip']] = [
            'description' => $validated['description'] ?? 'Trusted IP',
            'added_at' => now()->toISOString(),
        ];

        Cache::put('ddos_whitelist', $whitelist, 86400 * 365); // 1 year

        Log::info('DDoS Protection: IP added to whitelist', [
            'ip' => $validated['ip'],
            'description' => $validated['description'] ?? 'Trusted IP',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'IP added to whitelist successfully'
        ]);
    }

    /**
     * Remove IP from whitelist
     */
    public function removeWhitelist($ip)
    {
        $whitelist = Cache::get('ddos_whitelist', []);
        
        if (isset($whitelist[$ip])) {
            unset($whitelist[$ip]);
            Cache::put('ddos_whitelist', $whitelist, 86400 * 365);

            Log::info('DDoS Protection: IP removed from whitelist', ['ip' => $ip]);

            return response()->json([
                'success' => true,
                'message' => 'IP removed from whitelist successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'IP not found in whitelist'
        ], 404);
    }

    /**
     * Add IP to blacklist
     */
    public function addBlacklist(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'reason' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:3600|max:86400*365', // 1 hour to 1 year
        ]);

        $blacklist = Cache::get('ddos_blacklist', []);
        $duration = $validated['duration'] ?? 86400; // Default 1 day

        $blacklist[$validated['ip']] = [
            'reason' => $validated['reason'] ?? 'Manual blacklist',
            'added_at' => now()->toISOString(),
            'expires_at' => now()->addSeconds($duration)->toISOString(),
        ];

        Cache::put('ddos_blacklist', $blacklist, $duration);

        Log::warning('DDoS Protection: IP added to blacklist', [
            'ip' => $validated['ip'],
            'reason' => $validated['reason'] ?? 'Manual blacklist',
            'duration' => $duration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'IP added to blacklist successfully'
        ]);
    }

    /**
     * Remove IP from blacklist
     */
    public function removeBlacklist($ip)
    {
        $blacklist = Cache::get('ddos_blacklist', []);
        
        if (isset($blacklist[$ip])) {
            unset($blacklist[$ip]);
            Cache::put('ddos_blacklist', $blacklist, 86400 * 365);

            Log::info('DDoS Protection: IP removed from blacklist', ['ip' => $ip]);

            return response()->json([
                'success' => true,
                'message' => 'IP removed from blacklist successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'IP not found in blacklist'
        ], 404);
    }

    /**
     * Update configuration in database
     */
    private function updateConfig($data)
    {
        // Update general enabled setting
        if (isset($data['enabled'])) {
            $enabled = filter_var($data['enabled'], FILTER_VALIDATE_BOOLEAN);
            DdosSetting::setValue('enabled', $enabled ? 'true' : 'false', 'boolean', 'general', 'Enable DDoS protection system');
            Log::info('Updated general setting:', ['enabled' => $enabled]);
        }

        // Update rate limiting settings
        if (isset($data['rate_limiting']) && is_array($data['rate_limiting'])) {
            foreach ($data['rate_limiting'] as $key => $value) {
                if ($value !== null && $value !== '') {
                    DdosSetting::setValue($key, $value, 'integer', 'rate_limiting', "Rate limiting setting for {$key}");
                    Log::info("Updated rate limiting setting: {$key} = {$value}");
                }
            }
        }

        // Update Shieldon setting
        if (isset($data['shieldon']) && isset($data['shieldon']['enabled'])) {
            $enabled = filter_var($data['shieldon']['enabled'], FILTER_VALIDATE_BOOLEAN);
            DdosSetting::setValue('shieldon_enabled', $enabled ? 'true' : 'false', 'boolean', 'shieldon', 'Enable Shieldon advanced protection');
            Log::info('Updated Shieldon setting:', ['enabled' => $enabled]);
        }

        // Update logging setting
        if (isset($data['logging']) && isset($data['logging']['enabled'])) {
            $enabled = filter_var($data['logging']['enabled'], FILTER_VALIDATE_BOOLEAN);
            DdosSetting::setValue('logging_enabled', $enabled ? 'true' : 'false', 'boolean', 'logging', 'Enable security logging');
            Log::info('Updated logging setting:', ['enabled' => $enabled]);
        }

        // Update Layer 7 protection settings - FIXED LOGIC
        if (isset($data['layer7_protection']) && is_array($data['layer7_protection'])) {
            // Master switch
            if (isset($data['layer7_protection']['enabled'])) {
                $enabled = filter_var($data['layer7_protection']['enabled'], FILTER_VALIDATE_BOOLEAN);
                DdosSetting::setValue('layer7_protection.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable advanced Layer 7 protection');
                Log::info("Updated Layer 7 protection master: enabled = {$enabled}");
            }

            // HTTP Flood Protection
            if (isset($data['layer7_protection']['http_flood']) && is_array($data['layer7_protection']['http_flood'])) {
                $httpFlood = $data['layer7_protection']['http_flood'];
                
                if (isset($httpFlood['enabled'])) {
                    $enabled = filter_var($httpFlood['enabled'], FILTER_VALIDATE_BOOLEAN);
                    DdosSetting::setValue('layer7_protection.http_flood.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable HTTP flood protection');
                    Log::info("Updated HTTP flood enabled: {$enabled}");
                }
                
                if (isset($httpFlood['max_requests_per_10s']) && $httpFlood['max_requests_per_10s'] !== null && $httpFlood['max_requests_per_10s'] !== '') {
                    DdosSetting::setValue('layer7_protection.http_flood.max_requests_per_10s', $httpFlood['max_requests_per_10s'], 'integer', 'layer7_protection', 'Maximum requests per 10 seconds');
                    Log::info("Updated HTTP flood max_requests_per_10s: {$httpFlood['max_requests_per_10s']}");
                }
                
                if (isset($httpFlood['max_concurrent_connections']) && $httpFlood['max_concurrent_connections'] !== null && $httpFlood['max_concurrent_connections'] !== '') {
                    DdosSetting::setValue('layer7_protection.http_flood.max_concurrent_connections', $httpFlood['max_concurrent_connections'], 'integer', 'layer7_protection', 'Maximum concurrent connections per IP');
                    Log::info("Updated HTTP flood max_concurrent_connections: {$httpFlood['max_concurrent_connections']}");
                }
            }

            // Session Protection
            if (isset($data['layer7_protection']['session_protection']) && is_array($data['layer7_protection']['session_protection'])) {
                $sessionProtection = $data['layer7_protection']['session_protection'];
                
                if (isset($sessionProtection['enabled'])) {
                    $enabled = filter_var($sessionProtection['enabled'], FILTER_VALIDATE_BOOLEAN);
                    DdosSetting::setValue('layer7_protection.session_protection.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable session protection');
                    Log::info("Updated session protection enabled: {$enabled}");
                }
                
                if (isset($sessionProtection['max_session_attempts']) && $sessionProtection['max_session_attempts'] !== null && $sessionProtection['max_session_attempts'] !== '') {
                    DdosSetting::setValue('layer7_protection.session_protection.max_session_attempts', $sessionProtection['max_session_attempts'], 'integer', 'layer7_protection', 'Maximum session attempts per 5 minutes');
                    Log::info("Updated session protection max_session_attempts: {$sessionProtection['max_session_attempts']}");
                }
                
                if (isset($sessionProtection['max_session_creations']) && $sessionProtection['max_session_creations'] !== null && $sessionProtection['max_session_creations'] !== '') {
                    DdosSetting::setValue('layer7_protection.session_protection.max_session_creations', $sessionProtection['max_session_creations'], 'integer', 'layer7_protection', 'Maximum session creations per minute');
                    Log::info("Updated session protection max_session_creations: {$sessionProtection['max_session_creations']}");
                }
            }

            // Header Validation
            if (isset($data['layer7_protection']['header_validation']) && isset($data['layer7_protection']['header_validation']['enabled'])) {
                $enabled = filter_var($data['layer7_protection']['header_validation']['enabled'], FILTER_VALIDATE_BOOLEAN);
                DdosSetting::setValue('layer7_protection.header_validation.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable header validation');
                Log::info("Updated header validation enabled: {$enabled}");
            }

            // Payload Analysis
            if (isset($data['layer7_protection']['payload_analysis']) && isset($data['layer7_protection']['payload_analysis']['enabled'])) {
                $enabled = filter_var($data['layer7_protection']['payload_analysis']['enabled'], FILTER_VALIDATE_BOOLEAN);
                DdosSetting::setValue('layer7_protection.payload_analysis.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable payload analysis');
                Log::info("Updated payload analysis enabled: {$enabled}");
            }

            // Progressive Blocking
            if (isset($data['layer7_protection']['progressive_blocking']) && is_array($data['layer7_protection']['progressive_blocking'])) {
                $progressiveBlocking = $data['layer7_protection']['progressive_blocking'];
                
                if (isset($progressiveBlocking['enabled'])) {
                    $enabled = filter_var($progressiveBlocking['enabled'], FILTER_VALIDATE_BOOLEAN);
                    DdosSetting::setValue('layer7_protection.progressive_blocking.enabled', $enabled ? 'true' : 'false', 'boolean', 'layer7_protection', 'Enable progressive blocking');
                    Log::info("Updated progressive blocking enabled: {$enabled}");
                }
                
                if (isset($progressiveBlocking['base_duration']) && $progressiveBlocking['base_duration'] !== null && $progressiveBlocking['base_duration'] !== '') {
                    DdosSetting::setValue('layer7_protection.progressive_blocking.base_duration', $progressiveBlocking['base_duration'], 'integer', 'layer7_protection', 'Base block duration in seconds');
                    Log::info("Updated progressive blocking base_duration: {$progressiveBlocking['base_duration']}");
                }
                
                if (isset($progressiveBlocking['max_duration']) && $progressiveBlocking['max_duration'] !== null && $progressiveBlocking['max_duration'] !== '') {
                    DdosSetting::setValue('layer7_protection.progressive_blocking.max_duration', $progressiveBlocking['max_duration'], 'integer', 'layer7_protection', 'Maximum block duration in seconds');
                    Log::info("Updated progressive blocking max_duration: {$progressiveBlocking['max_duration']}");
                }
                
                if (isset($progressiveBlocking['multiplier']) && $progressiveBlocking['multiplier'] !== null && $progressiveBlocking['multiplier'] !== '') {
                    DdosSetting::setValue('layer7_protection.progressive_blocking.multiplier', $progressiveBlocking['multiplier'], 'numeric', 'layer7_protection', 'Block duration multiplier per violation');
                    Log::info("Updated progressive blocking multiplier: {$progressiveBlocking['multiplier']}");
                }
            }
        }

        // Clear cache to ensure fresh data
        Cache::forget('ddos_config');
    }

    /**
     * Get protection level based on current settings
     */
    private function getProtectionLevel()
    {
        // Get protection level from database
        $protectionLevel = DdosSetting::getValue('protection_level', 'Low');
        
        // If not set, calculate based on settings
        if (!$protectionLevel || $protectionLevel === 'Low') {
            $config = DdosSetting::getNestedStructure();
            
            if (!$config['enabled'] || $config['enabled'] === 'false') {
                return 'Disabled';
            }

            $rateLimit = (int) $config['rate_limiting']['requests_per_minute'];
            
            if ($rateLimit <= 5) {
                return 'High';
            } elseif ($rateLimit <= 20) {
                return 'Medium';
            } else {
                return 'Low';
            }
        }
        
        return $protectionLevel;
    }
}
