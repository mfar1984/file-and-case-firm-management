<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DynamicCorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get origin from request
        $origin = $request->headers->get('Origin');

        // Get allowed origins from database (cached for 1 hour)
        $allowedOrigins = Cache::remember('api_security_allowed_origins', 3600, function () {
            try {
                // Get all firms' allowed origins
                $settings = DB::table('api_security_settings')
                    ->where('cors_enabled', true)
                    ->where('api_enabled', true)
                    ->pluck('allowed_origins');
                
                $allOrigins = [];
                foreach ($settings as $originsJson) {
                    $origins = json_decode($originsJson, true);
                    if (is_array($origins)) {
                        $allOrigins = array_merge($allOrigins, $origins);
                    }
                }
                
                // Remove duplicates and empty values
                return array_values(array_unique(array_filter($allOrigins)));
            } catch (\Exception $e) {
                // Fallback to default origins if database query fails
                return [
                    'http://localhost:3000',
                    'http://localhost:8000',
                    'https://naaelahsaleh.co',
                    'https://www.naaelahsaleh.co',
                ];
            }
        });

        // Handle preflight OPTIONS request
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $origin && in_array($origin, $allowedOrigins) ? $origin : '')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }
        
        // Process the request
        $response = $next($request);
        
        // Add CORS headers to response if origin is allowed
        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Expose-Headers', 'X-RateLimit-Limit, X-RateLimit-Remaining');
        }
        
        return $response;
    }
}

