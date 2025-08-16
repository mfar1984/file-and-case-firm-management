<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DDoS Protection Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for DDoS protection middleware
    | and Shieldon firewall settings with advanced Layer 7 protection.
    |
    */

    'protection' => [
        'enabled' => env('DDOS_PROTECTION_ENABLED', true),
        'level' => env('DDOS_PROTECTION_LEVEL', 'high'), // low, medium, high, extreme
        
        // Enhanced rate limiting
        'rate_limiting' => [
            'requests_per_minute' => env('DDOS_RATE_LIMIT_MINUTE', 100),
            'requests_per_hour' => env('DDOS_RATE_LIMIT_HOUR', 1000),
            'requests_per_second' => env('DDOS_RATE_LIMIT_SECOND', 10),
            'block_duration' => env('DDOS_BLOCK_DURATION', 3600),
            'progressive_blocking' => env('DDOS_PROGRESSIVE_BLOCKING', true),
        ],
        
        // HTTP flood protection
        'http_flood' => [
            'enabled' => env('DDOS_HTTP_FLOOD_PROTECTION', true),
            'max_requests_per_minute' => env('DDOS_HTTP_FLOOD_MINUTE', 50),
            'max_requests_per_second' => env('DDOS_HTTP_FLOOD_SECOND', 5),
            'max_concurrent_connections' => env('DDOS_HTTP_FLOOD_CONCURRENT', 3),
            'detection_threshold' => env('DDOS_HTTP_FLOOD_THRESHOLD', 0.7),
        ],
        
        // Session protection
        'session' => [
            'enabled' => env('DDOS_SESSION_PROTECTION', true),
            'max_attempts_per_minute' => env('DDOS_SESSION_ATTEMPTS', 10),
            'max_creations_per_minute' => env('DDOS_SESSION_CREATIONS', 5),
            'block_duration' => env('DDOS_SESSION_BLOCK_DURATION', 1800),
        ],
        
        // IP reputation system
        'reputation' => [
            'enabled' => env('DDOS_REPUTATION_SYSTEM', true),
            'initial_score' => env('DDOS_REPUTATION_INITIAL', 100),
            'min_score' => env('DDOS_REPUTATION_MIN', 10),
            'max_score' => env('DDOS_REPUTATION_MAX', 100),
            'decrease_points' => [
                'http_flood' => env('DDOS_REPUTATION_HTTP_FLOOD', 10),
                'rate_limit' => env('DDOS_REPUTATION_RATE_LIMIT', 15),
                'session_attack' => env('DDOS_REPUTATION_SESSION', 20),
                'suspicious_pattern' => env('DDOS_REPUTATION_SUSPICIOUS', 25),
            ],
            'increase_points' => [
                'good_behavior' => env('DDOS_REPUTATION_GOOD', 1),
            ],
        ],
        
        // Traffic pattern analysis
        'traffic_analysis' => [
            'enabled' => env('DDOS_TRAFFIC_ANALYSIS', true),
            'pattern_history_size' => env('DDOS_PATTERN_HISTORY', 50),
            'suspicious_threshold' => env('DDOS_SUSPICIOUS_THRESHOLD', 0.7),
            'block_threshold' => env('DDOS_BLOCK_THRESHOLD', 0.9),
            'analysis_factors' => [
                'time_gaps' => env('DDOS_ANALYSIS_TIME_GAPS', true),
                'path_variety' => env('DDOS_ANALYSIS_PATH_VARIETY', true),
                'user_agent' => env('DDOS_ANALYSIS_USER_AGENT', true),
                'method_changes' => env('DDOS_ANALYSIS_METHOD_CHANGES', true),
            ],
        ],
        
        // Progressive blocking
        'progressive_blocking' => [
            'enabled' => env('DDOS_PROGRESSIVE_BLOCKING', true),
            'base_duration' => env('DDOS_PROGRESSIVE_BASE', 3600), // 1 hour
            'max_duration' => env('DDOS_PROGRESSIVE_MAX', 86400), // 24 hours
            'multiplier' => env('DDOS_PROGRESSIVE_MULTIPLIER', 2),
        ],
        
        // Whitelist and blacklist
        'whitelist' => [
            'enabled' => env('DDOS_WHITELIST_ENABLED', true),
            'ips' => env('DDOS_WHITELIST_IPS', '127.0.0.1,::1,localhost'),
            'auto_whitelist' => env('DDOS_AUTO_WHITELIST', true),
            'min_reputation' => env('DDOS_AUTO_WHITELIST_REPUTATION', 80),
        ],
        
        'blacklist' => [
            'enabled' => env('DDOS_BLACKLIST_ENABLED', true),
            'ips' => env('DDOS_BLACKLIST_IPS', ''),
            'auto_blacklist' => env('DDOS_AUTO_BLACKLIST', true),
            'max_violations' => env('DDOS_AUTO_BLACKLIST_VIOLATIONS', 5),
        ],
    ],

    'suspicious_patterns' => [
        // Directory traversal
        '/\.\./', '/\.\.%2f/', '/\.\.%5c/', '/\.\.\\/',
        
        // SQL injection
        '/union\s+select/i', '/select\s+.*\s+from/i', '/insert\s+into/i', 
        '/update\s+.*\s+set/i', '/delete\s+from/i', '/drop\s+table/i', 
        '/create\s+table/i', '/alter\s+table/i', '/exec\s*\(/i',
        
        // XSS attempts
        '/<script/i', '/javascript:/i', '/on\w+\s*=/i', '/vbscript:/i',
        
        // Code injection
        '/eval\s*\(/i', '/system\s*\(/i', '/shell_exec/i', '/passthru/i',
        
        // File inclusion
        '/include\s*\(/i', '/require\s*\(/i', '/include_once/i', '/require_once/i',
        
        // PHP code
        '/<\?php/i', '/<\?=/i', '/<\?/i',
        
        // Common attack paths
        '/\.env/', '/\.git/', '/phpinfo/', '/wp-admin/', '/admin/',
        '/config/', '/backup/', '/db/', '/database/', '/sql/',
    ],

    'suspicious_user_agents' => [
        'bot', 'crawler', 'spider', 'scraper', 'curl', 'wget',
        'python', 'java', 'perl', 'ruby', 'php', 'node', 'go',
        'masscan', 'nmap', 'sqlmap', 'nikto', 'dirb', 'gobuster'
    ],

    'logging' => [
        'enabled' => env('DDOS_LOGGING_ENABLED', true),
        'channel' => env('DDOS_LOG_CHANNEL', 'daily'),
        'level' => env('DDOS_LOG_LEVEL', 'warning'), // debug, info, warning, error
        'log_blocked_ips' => true,
        'log_suspicious_activity' => true,
        'log_rate_limit_violations' => true,
    ],

    // Geographic blocking (optional)
    'geographic_blocking' => [
        'enabled' => env('DDOS_GEO_BLOCKING_ENABLED', false),
        'blocked_countries' => [
            // Add country codes to block
            // 'CN', 'RU', 'KP', 'IR',
        ],
        'allowed_countries' => [
            // Add country codes to allow (if empty, all countries allowed)
            // 'MY', 'SG', 'US', 'GB',
        ],
    ],

    // Advanced monitoring
    'monitoring' => [
        'enabled' => env('DDOS_MONITORING_ENABLED', true),
        'alert_threshold' => env('DDOS_ALERT_THRESHOLD', 100), // Requests per minute to trigger alert
        'stats_retention' => env('DDOS_STATS_RETENTION', 30),  // Days to keep statistics
    ],
]; 