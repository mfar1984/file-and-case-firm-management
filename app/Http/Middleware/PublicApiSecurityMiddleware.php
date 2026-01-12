<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PublicApiSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        
        // Check if IP is blacklisted
        if ($this->isBlacklisted($ip)) {
            Log::warning('Blocked request from blacklisted IP', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Your IP has been blocked due to suspicious activity.',
            ], 403);
        }
        
        // Track failed attempts
        $this->trackRequest($ip);
        
        $response = $next($request);
        
        // Track failed verification attempts
        if ($response->status() === 403) {
            $this->trackFailedAttempt($ip);
        }
        
        return $response;
    }
    
    /**
     * Check if IP is blacklisted
     */
    private function isBlacklisted(string $ip): bool
    {
        // Check if IP is in blacklist cache
        return Cache::has("blacklist:ip:{$ip}");
    }
    
    /**
     * Track request from IP
     */
    private function trackRequest(string $ip): void
    {
        $key = "api_requests:{$ip}";
        $requests = Cache::get($key, 0);
        Cache::put($key, $requests + 1, now()->addMinutes(60));
    }
    
    /**
     * Track failed verification attempts
     * Auto-blacklist after 10 failed attempts in 1 hour
     */
    private function trackFailedAttempt(string $ip): void
    {
        $key = "failed_attempts:{$ip}";
        $attempts = Cache::get($key, 0);
        $newAttempts = $attempts + 1;
        
        Cache::put($key, $newAttempts, now()->addHour());
        
        // Auto-blacklist after 10 failed attempts
        if ($newAttempts >= 10) {
            $this->blacklistIp($ip);
            
            Log::warning('IP auto-blacklisted due to multiple failed attempts', [
                'ip' => $ip,
                'attempts' => $newAttempts,
                'timestamp' => now(),
            ]);
        }
    }
    
    /**
     * Blacklist an IP address
     */
    private function blacklistIp(string $ip): void
    {
        // Blacklist for 24 hours
        Cache::put("blacklist:ip:{$ip}", true, now()->addHours(24));
    }
}

