<?php

namespace App\Http\Controllers;

use App\Models\WeatherSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    private $webhookSecret = '481b6f7eb9d94e5facf63df6860a600b';
    private $apiKey = 'UINsXMQBMJemd36Z2AUaQ4e65nWw7i9V';

    public function weather(Request $request): JsonResponse
    {
        try {
            // Validate webhook secret
            $providedSecret = $request->header('X-Webhook-Secret') ?? $request->input('secret');
            
            if ($providedSecret !== $this->webhookSecret) {
                Log::warning('Webhook: Invalid secret provided', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'provided_secret' => $providedSecret ? '***' : 'missing'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid webhook secret'
                ], 401);
            }

            // Validate request method
            if ($request->method() !== 'POST') {
                return response()->json([
                    'success' => false,
                    'message' => 'Method not allowed'
                ], 405);
            }

            // Get weather data from request
            $weatherData = $request->all();
            
            // Log webhook data
            Log::info('Webhook: Weather data received', [
                'data' => $weatherData,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            // Validate required fields
            if (!isset($weatherData['location']) || !isset($weatherData['current'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required weather data'
                ], 400);
            }

            // Process weather data
            $processedData = $this->processWeatherData($weatherData);

            // Store or update weather data (optional - for caching)
            $this->storeWeatherData($processedData);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Weather data processed successfully',
                'data' => $processedData,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook: Error processing weather data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processWeatherData(array $data): array
    {
        $current = $data['current'] ?? [];
        $forecast = $data['forecast'] ?? [];

        return [
            'location' => $data['location'] ?? 'Unknown',
            'current' => [
                'temperature' => $this->formatTemperature($current['temperature'] ?? null),
                'skytext' => $current['skytext'] ?? $current['condition'] ?? 'Unknown',
                'humidity' => $this->formatPercentage($current['humidity'] ?? null),
                'winddisplay' => $this->formatWindSpeed($current['winddisplay'] ?? $current['wind_speed'] ?? null),
                'feels_like' => $this->formatTemperature($current['feels_like'] ?? $current['apparent_temperature'] ?? null),
                'pressure' => $this->formatPressure($current['pressure'] ?? null),
                'visibility' => $this->formatVisibility($current['visibility'] ?? null)
            ],
            'forecast' => [
                'today' => $this->processForecastDay($forecast['today'] ?? []),
                'tomorrow' => $this->processForecastDay($forecast['tomorrow'] ?? []),
                'day_after' => $this->processForecastDay($forecast['day_after'] ?? [])
            ],
            'source' => 'webhook',
            'received_at' => now()->toISOString()
        ];
    }

    private function processForecastDay(array $dayData): array
    {
        return [
            'high' => $this->formatTemperature($dayData['high'] ?? $dayData['temperature_max'] ?? null),
            'low' => $this->formatTemperature($dayData['low'] ?? $dayData['temperature_min'] ?? null),
            'condition' => $dayData['condition'] ?? $dayData['skytext'] ?? 'Unknown',
            'humidity' => $this->formatPercentage($dayData['humidity'] ?? null),
            'precipitation' => $this->formatPercentage($dayData['precipitation'] ?? $dayData['precipitation_probability'] ?? null)
        ];
    }

    private function formatTemperature($temp): string
    {
        if ($temp === null) return 'N/A';
        
        $temp = (float) $temp;
        return round($temp) . '°C';
    }

    private function formatPercentage($value): string
    {
        if ($value === null) return 'N/A';
        
        $value = (float) $value;
        return round($value) . '%';
    }

    private function formatWindSpeed($speed): string
    {
        if ($speed === null) return 'N/A';
        
        $speed = (float) $speed;
        return round($speed, 1) . ' km/h';
    }

    private function formatPressure($pressure): string
    {
        if ($pressure === null) return 'N/A';
        
        $pressure = (float) $pressure;
        return round($pressure) . ' hPa';
    }

    private function formatVisibility($visibility): string
    {
        if ($visibility === null) return 'N/A';
        
        $visibility = (float) $visibility;
        return round($visibility) . ' km';
    }

    private function storeWeatherData(array $data): void
    {
        // Optional: Store weather data in cache or database for faster access
        // This can be implemented based on your caching strategy
        cache()->put('weather_data', $data, now()->addMinutes(30));
        
        Log::info('Webhook: Weather data cached', [
            'location' => $data['location'],
            'temperature' => $data['current']['temperature'],
            'cached_at' => now()
        ]);
    }

    public function test(Request $request): JsonResponse
    {
        // Test endpoint to verify webhook is working
        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint is active',
            'timestamp' => now()->toISOString(),
            'webhook_url' => url('/webhook/weather'),
            'method' => 'POST',
            'headers' => [
                'Content-Type: application/json',
                'X-Webhook-Secret: ' . $this->webhookSecret
            ]
        ]);
    }

    public function getCachedWeather(): JsonResponse
    {
        $cachedData = cache()->get('weather_data');
        
        if (!$cachedData) {
            return response()->json([
                'success' => false,
                'message' => 'No cached weather data available'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cachedData,
            'cached_at' => cache()->get('weather_data_cached_at')
        ]);
    }
}
