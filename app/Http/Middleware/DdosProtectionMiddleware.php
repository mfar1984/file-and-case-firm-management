<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Shieldon\Firewall\Firewall;
use Shieldon\Firewall\Captcha\Csrf;
use Symfony\Component\HttpFoundation\Response;

class DdosProtectionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        
        // Track total requests
        $this->incrementRequestCount();
        
        // Get DDoS settings
        $ddosSettings = $this->getDdosSettings();
        
        if (!$ddosSettings['enabled']) {
            return $next($request);
        }
        
        // 1. Check if IP is already blocked
        if ($this->isIpBlocked($clientIp)) {
            $this->incrementBlockedCount();
            Log::warning('DDoS Protection: Blocked IP attempting access: ' . $clientIp);
            return $this->createBlockedResponse('IP address is blocked');
        }
        
        // 2. Check whitelist
        if ($this->isIpWhitelisted($clientIp)) {
            return $next($request);
        }
        
        // 3. Advanced Rate Limiting
        if ($this->isAdvancedRateLimited($clientIp, $ddosSettings)) {
            $this->incrementBlockedCount();
            Log::warning('DDoS Protection: Rate limit exceeded for IP: ' . $clientIp);
            return $this->createRateLimitResponse();
        }
        
        // 4. HTTP Flood Protection
        if ($this->isHttpFlood($request, $clientIp)) {
            $this->incrementBlockedCount();
            Log::warning('DDoS Protection: HTTP flood detected from IP: ' . $clientIp);
            return $this->createBlockedResponse('HTTP flood detected');
        }
        
        // 5. Session Protection
        if ($this->isSessionAttack($request, $clientIp)) {
            $this->incrementBlockedCount();
            Log::warning('DDoS Protection: Session attack detected from IP: ' . $clientIp);
            return $this->createBlockedResponse('Session attack detected');
        }
        
        // 6. Traffic Pattern Analysis
        $trafficAnalysis = $this->analyzeTrafficPattern($request, $clientIp);
        if ($trafficAnalysis['is_suspicious']) {
            Log::warning('DDoS Protection: Suspicious traffic pattern detected from IP: ' . $clientIp . ' Score: ' . $trafficAnalysis['suspicious_score']);
            $this->decreaseReputation($clientIp, 25);
            
            if ($trafficAnalysis['suspicious_score'] > 0.9) {
                $this->incrementBlockedCount();
                return $this->createBlockedResponse('Suspicious traffic pattern detected');
            }
        }
        
        // 7. Enhanced Shieldon Firewall
        if ($ddosSettings['shieldon']['enabled']) {
            // Temporarily disabled due to Shieldon setup issues
            // $firewallResponse = $this->processShieldonFirewall($request, $clientIp, $ddosSettings);
            // if ($firewallResponse !== null) {
            //     return $firewallResponse;
            // }
        }
        
        // 8. Progressive Rate Limiting
        $this->updateProgressiveRateLimit($clientIp);
        
        return $next($request);
    }

    /**
     * Increment total request count
     */
    private function incrementRequestCount(): void
    {
        $totalRequests = Cache::get('ddos_total_requests', 0);
        Cache::put('ddos_total_requests', $totalRequests + 1, 86400); // 24 hours
    }

    /**
     * Increment blocked request count
     */
    private function incrementBlockedCount(): void
    {
        $blockedRequests = Cache::get('ddos_blocked_requests', 0);
        Cache::put('ddos_blocked_requests', $blockedRequests + 1, 86400); // 24 hours
    }

    /**
     * Get DDoS settings from database
     */
    private function getDdosSettings(): array
    {
        try {
            $ddosSetting = app(\App\Models\DdosSetting::class);
            return $ddosSetting::getNestedStructure();
        } catch (\Exception $e) {
            Log::error('DDoS Protection: Error loading settings, using defaults', ['error' => $e->getMessage()]);
            return [
                'enabled' => true,
                'rate_limiting' => [
                    'requests_per_minute' => 60,
                    'requests_per_hour' => 1000,
                    'block_duration' => 3600,
                ],
                'shieldon' => ['enabled' => false],
                'logging' => ['enabled' => true]
            ];
        }
    }

    /**
     * Enhanced Rate Limiting with Progressive Blocking
     */
    private function isAdvancedRateLimited(string $ip, array $settings): bool
    {
        $minuteKey = 'rate_limit_minute:' . $ip;
        $hourKey = 'rate_limit_hour:' . $ip;
        $reputationKey = 'ip_reputation:' . $ip;
        
        $requestsPerMinute = Cache::get($minuteKey, 0);
        $requestsPerHour = Cache::get($hourKey, 0);
        $reputation = Cache::get($reputationKey, 100);
        
        // Dynamic limits based on reputation
        $minuteLimit = max((int) $settings['rate_limiting']['requests_per_minute'] / 2, $reputation);
        $hourLimit = max((int) $settings['rate_limiting']['requests_per_hour'] / 2, $reputation * 10);
        
        if ($requestsPerMinute >= $minuteLimit || $requestsPerHour >= $hourLimit) {
            $this->decreaseReputation($ip, 15);
            $this->updateProgressiveRateLimit($ip);
            return true;
        }
        
        Cache::put($minuteKey, $requestsPerMinute + 1, 60);
        Cache::put($hourKey, $requestsPerHour + 1, 3600);
        
        return false;
    }

    /**
     * Enhanced HTTP Flood Protection with Per-IP Rate Limiting
     */
    private function isHttpFlood(Request $request, string $ip): bool
    {
        // Enhanced per-IP rate limiting
        $minuteKey = 'http_flood_minute:' . $ip;
        $secondKey = 'http_flood_second:' . $ip;
        $reputationKey = 'ip_reputation:' . $ip;
        
        // Get current request counts
        $requestsPerMinute = Cache::get($minuteKey, 0);
        $requestsPerSecond = Cache::get($secondKey, 0);
        $reputation = Cache::get($reputationKey, 100);
        
        // Dynamic thresholds based on IP reputation
        $maxRequestsPerMinute = max(50, $reputation / 2); // Good IPs get higher limits
        $maxRequestsPerSecond = max(5, $reputation / 20);
        
        // Check for rapid successive requests
        if ($requestsPerSecond > $maxRequestsPerSecond) {
            $this->decreaseReputation($ip, 10);
            return true;
        }
        
        // Check for high volume requests
        if ($requestsPerMinute > $maxRequestsPerMinute) {
            $this->decreaseReputation($ip, 5);
            return true;
        }
        
        // Check for concurrent connections
        $concurrentKey = 'concurrent:' . $ip;
        $concurrent = Cache::get($concurrentKey, 0);
        
        if ($concurrent > 3) { // Reduced from 5 to 3
            $this->decreaseReputation($ip, 15);
            return true;
        }
        
        // Update counters
        Cache::put($minuteKey, $requestsPerMinute + 1, 60);
        Cache::put($secondKey, $requestsPerSecond + 1, 1);
        Cache::put($concurrentKey, $concurrent + 1, 5);
        
        // Increase reputation for good behavior
        if ($requestsPerMinute < $maxRequestsPerMinute / 2) {
            $this->increaseReputation($ip, 1);
        }
        
        return false;
    }

    /**
     * Enhanced Session Protection with Behavioral Analysis
     */
    private function isSessionAttack(Request $request, string $ip): bool
    {
        // Whitelist localhost
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return false;
        }
        
        $sessionKey = 'session_attempts:' . $ip;
        $sessionCreationKey = 'session_creation:' . $ip;
        $reputationKey = 'ip_reputation:' . $ip;
        
        $sessionAttempts = Cache::get($sessionKey, 0);
        $sessionCreations = Cache::get($sessionCreationKey, 0);
        $reputation = Cache::get($reputationKey, 100);
        
        // Dynamic thresholds based on reputation
        $maxSessionAttempts = max(10, $reputation / 10);
        $maxSessionCreations = max(5, $reputation / 20);
        
        // Check for session fixation attempts
        if ($request->hasSession() && $request->session()->has('_token')) {
            if ($sessionAttempts > $maxSessionAttempts) {
                $this->decreaseReputation($ip, 20);
                return true;
            }
        }
        
        // Check for rapid session creation
        if ($sessionCreations > $maxSessionCreations) {
            $this->decreaseReputation($ip, 25);
            return true;
        }
        
        // Update counters
        Cache::put($sessionKey, $sessionAttempts + 1, 300);
        Cache::put($sessionCreationKey, $sessionCreations + 1, 300);
        
        return false;
    }

    /**
     * Header Validation
     */
    private function hasMaliciousHeaders(Request $request): bool
    {
        // Whitelist localhost and common legitimate IPs
        $clientIp = $request->ip();
        if (in_array($clientIp, ['127.0.0.1', '::1', 'localhost'])) {
            return false; // Allow localhost requests
        }
        
        // Only check for obviously malicious headers
        $maliciousHeaders = [
            'User-Agent' => [
                '/bot|crawler|spider|scraper/i', // Block obvious bots
                '/curl|wget|python|java|perl|ruby|php/i', // Block automation tools
            ],
            'X-Forwarded-For' => [
                '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', // Validate IP format
            ],
        ];
        
        foreach ($maliciousHeaders as $header => $patterns) {
            $value = $request->header($header);
            if ($value) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        // Only block if it's clearly malicious
                        if ($header === 'User-Agent' && preg_match('/bot|crawler|spider|scraper|curl|wget/i', $value)) {
                            return true;
                        }
                        // For X-Forwarded-For, only block if it's clearly spoofed
                        if ($header === 'X-Forwarded-For' && !filter_var($value, FILTER_VALIDATE_IP)) {
                            return true;
                        }
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Payload Analysis
     */
    private function hasMaliciousPayload(Request $request): bool
    {
        // Whitelist localhost
        $clientIp = $request->ip();
        if (in_array($clientIp, ['127.0.0.1', '::1', 'localhost'])) {
            return false; // Allow localhost requests
        }
        
        // Only check for clearly malicious patterns
        $maliciousPatterns = [
            // SQL Injection - only obvious patterns
            '/union\s+select.*from/i',
            '/drop\s+table/i',
            '/delete\s+from.*where/i',
            '/insert\s+into.*values/i',
            
            // XSS - only script tags and javascript
            '/<script[^>]*>/i',
            '/javascript:/i',
            
            // Command Injection - only system calls
            '/system\s*\(/i',
            '/exec\s*\(/i',
            '/shell_exec\s*\(/i',
            '/passthru\s*\(/i',
            
            // Directory Traversal - only obvious patterns
            '/\.\.\/\.\.\//',
            '/\.\.\\\\\.\.\\\\\//',
            
            // File Inclusion - only PHP includes
            '/include\s*\(.*\.php/i',
            '/require\s*\(.*\.php/i',
            
            // PHP Code - only PHP tags
            '/<\?php/i',
            '/<\?=/i',
        ];
        
        $payload = $request->getContent() . $request->getQueryString() . $request->path();
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $payload)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Enhanced Suspicious Request Detection
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        // Whitelist localhost
        $clientIp = $request->ip();
        if (in_array($clientIp, ['127.0.0.1', '::1', 'localhost'])) {
            return false; // Allow localhost requests
        }
        
        $suspiciousPatterns = [
            '/\.\.\/\.\./',     // Directory traversal (only obvious)
            '/union\s+select.*from/i', // SQL injection (only obvious)
            '/<script[^>]*>/i', // XSS attempts (only script tags)
            '/eval\s*\(/i',     // Code injection
            '/system\s*\(/i',   // Command injection
            '/\.env/',          // Environment file access
            '/\.git/',          // Git directory access
            '/phpinfo/',        // PHP info access
            '/wp-admin/',       // WordPress admin
            '/admin/',          // Admin panel access
        ];

        $userAgent = $request->header('User-Agent', '');
        $path = $request->path();
        $query = $request->getQueryString() ?? '';

        // Check for suspicious User-Agent - only obvious bots
        $suspiciousUserAgents = [
            'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'
        ];

        foreach ($suspiciousUserAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return true;
            }
        }

        // Check for suspicious patterns in URL
        $fullUrl = $path . '?' . $query;
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $fullUrl)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Enhanced Shieldon Firewall
     */
    private function processShieldonFirewall(Request $request, string $ip, array $settings): ?Response
    {
        try {
            $firewall = new Firewall();
            $firewall->setup(storage_path('shieldon'));
            
            // Dynamic configuration based on settings
            $firewall->setConfig([
                'time_unit_quota' => [
                    's' => 3,   // 3 requests per second
                    'm' => (int) $settings['rate_limiting']['requests_per_minute'],
                    'h' => (int) $settings['rate_limiting']['requests_per_hour'],
                ],
                'data_circle' => [
                    's' => 60,  // Store 60 seconds of data
                    'm' => 60,  // Store 60 minutes of data
                    'h' => 24,  // Store 24 hours of data
                ],
            ]);

            // Check if IP is banned
            if ($firewall->getKernel()->isBanned($ip)) {
                Log::warning('DDoS Protection: Shieldon banned IP attempting access: ' . $ip);
                return $this->createBlockedResponse('IP address is banned');
            }

            // Process the request through Shieldon
            $response = $firewall->run();
            
            if ($response->getStatusCode() !== 200) {
                Log::warning('DDoS Protection: Shieldon blocked request from IP: ' . $ip);
                return $response;
            }
        } catch (\Exception $e) {
            Log::error('DDoS Protection: Shieldon error', ['error' => $e->getMessage()]);
        }
        
        return null;
    }

    /**
     * IP Reputation Management System
     */
    private function decreaseReputation(string $ip, int $points): void
    {
        $reputationKey = 'ip_reputation:' . $ip;
        $currentReputation = Cache::get($reputationKey, 100);
        $newReputation = max(0, $currentReputation - $points);
        
        Cache::put($reputationKey, $newReputation, 86400); // 24 hours
        
        // Log reputation change
        Log::warning("DDoS Protection: IP reputation decreased for {$ip} from {$currentReputation} to {$newReputation}");
        
        // If reputation is very low, block IP
        if ($newReputation < 10) {
            $this->blockIp($ip, 'Very low reputation', 3600);
        }
    }

    private function increaseReputation(string $ip, int $points): void
    {
        $reputationKey = 'ip_reputation:' . $ip;
        $currentReputation = Cache::get($reputationKey, 100);
        $newReputation = min(100, $currentReputation + $points);
        
        Cache::put($reputationKey, $newReputation, 86400); // 24 hours
    }

    /**
     * Enhanced Progressive Rate Limiting
     */
    private function updateProgressiveRateLimit(string $ip): void
    {
        $key = 'progressive_rate:' . $ip;
        $violations = Cache::get($key, 0);
        
        if ($violations > 0) {
            // Exponential backoff blocking
            $blockDuration = min(3600 * pow(2, $violations), 86400); // Max 24 hours
            Cache::put($key, $violations + 1, $blockDuration);
            
            // Block IP for calculated duration
            $this->blockIp($ip, 'Progressive rate limit exceeded', $blockDuration);
        }
    }

    /**
     * Enhanced IP Blocking with Reputation Reset
     */
    private function blockIp(string $ip, string $reason, int $duration): void
    {
        $blockedIps = Cache::get('blocked_ips', []);
        $blockedIps[$ip] = [
            'reason' => $reason,
            'blocked_at' => now()->toISOString(),
            'expires_at' => now()->addSeconds($duration)->toISOString(),
            'duration' => $duration,
            'reputation' => Cache::get('ip_reputation:' . $ip, 100)
        ];
        
        Cache::put('blocked_ips', $blockedIps, $duration);
        
        // Increment blocked IPs counter
        $blockedIpsCount = Cache::get('ddos_blocked_ips', 0);
        Cache::put('ddos_blocked_ips', $blockedIpsCount + 1, 86400); // 24 hours
        
        // Reset reputation to 0 for blocked IPs
        Cache::put('ip_reputation:' . $ip, 0, $duration);
        
        // Log blocking action
        Log::warning("DDoS Protection: IP {$ip} blocked for {$duration} seconds. Reason: {$reason}");
        
        // Update progressive rate limit
        $progressiveKey = 'progressive_rate:' . $ip;
        $violations = Cache::get($progressiveKey, 0);
        Cache::put($progressiveKey, $violations + 1, $duration);
    }

    /**
     * Traffic Pattern Analysis
     */
    private function analyzeTrafficPattern(Request $request, string $ip): array
    {
        $patternKey = 'traffic_pattern:' . $ip;
        $patterns = Cache::get($patternKey, []);
        
        $currentPattern = [
            'user_agent' => $request->header('User-Agent'),
            'accept' => $request->header('Accept'),
            'accept_language' => $request->header('Accept-Language'),
            'method' => $request->method(),
            'path' => $request->path(),
            'timestamp' => now()->timestamp
        ];
        
        $patterns[] = $currentPattern;
        
        // Keep only last 50 patterns
        if (count($patterns) > 50) {
            $patterns = array_slice($patterns, -50);
        }
        
        Cache::put($patternKey, $patterns, 3600);
        
        // Analyze pattern for suspicious behavior
        $suspiciousScore = $this->calculateSuspiciousScore($patterns);
        
        return [
            'patterns' => $patterns,
            'suspicious_score' => $suspiciousScore,
            'is_suspicious' => $suspiciousScore > 0.7
        ];
    }

    /**
     * Calculate Suspicious Score based on Traffic Patterns
     */
    private function calculateSuspiciousScore(array $patterns): float
    {
        if (count($patterns) < 5) {
            return 0.0; // Not enough data
        }
        
        $score = 0.0;
        
        // Check for rapid requests
        $timeGaps = [];
        for ($i = 1; $i < count($patterns); $i++) {
            $timeGaps[] = $patterns[$i]['timestamp'] - $patterns[$i-1]['timestamp'];
        }
        
        $avgTimeGap = array_sum($timeGaps) / count($timeGaps);
        if ($avgTimeGap < 1) { // Less than 1 second between requests
            $score += 0.3;
        }
        
        // Check for repetitive patterns
        $uniquePaths = array_unique(array_column($patterns, 'path'));
        $pathVariety = count($uniquePaths) / count($patterns);
        if ($pathVariety < 0.3) { // Very repetitive paths
            $score += 0.2;
        }
        
        // Check for suspicious user agents
        $suspiciousUAs = ['bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'];
        foreach ($patterns as $pattern) {
            if (stripos($pattern['user_agent'], implode('|', $suspiciousUAs)) !== false) {
                $score += 0.2;
                break;
            }
        }
        
        // Check for rapid method changes
        $methods = array_column($patterns, 'method');
        $methodChanges = 0;
        for ($i = 1; $i < count($methods); $i++) {
            if ($methods[$i] !== $methods[$i-1]) {
                $methodChanges++;
            }
        }
        if ($methodChanges > count($methods) * 0.8) { // Too many method changes
            $score += 0.1;
        }
        
        return min(1.0, $score);
    }

    /**
     * Create Rate Limit Response
     */
    private function createRateLimitResponse(): Response
    {
        return response()->json([
            'error' => 'Too many requests. Please try again later.',
            'retry_after' => 60,
            'type' => 'rate_limit'
        ], 429);
    }

    /**
     * Create Blocked Response
     */
    private function createBlockedResponse(string $reason): Response
    {
        return response()->json([
            'error' => 'Access denied: ' . $reason,
            'type' => 'blocked'
        ], 403);
    }

    /**
     * Check if IP is blocked
     */
    private function isIpBlocked(string $ip): bool
    {
        $blockedIps = Cache::get('blocked_ips', []);
        
        if (isset($blockedIps[$ip])) {
            $blockData = $blockedIps[$ip];
            $expiresAt = \Carbon\Carbon::parse($blockData['expires_at']);
            
            // Check if block has expired
            if ($expiresAt->isPast()) {
                unset($blockedIps[$ip]);
                Cache::put('blocked_ips', $blockedIps, 86400);
                return false;
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Check if IP is whitelisted
     */
    private function isIpWhitelisted(string $ip): bool
    {
        $whitelist = Cache::get('ddos_whitelist', []);
        return isset($whitelist[$ip]);
    }
}
