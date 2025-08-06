<?php

namespace App\Http\Controllers;

use App\Models\WeatherSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WeatherSettingsController extends Controller
{
    public function index()
    {
        $weatherSettings = WeatherSetting::getActiveSettings() ?? new WeatherSetting();
        return view('settings.weather', compact('weatherSettings'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'api_provider' => 'required|string|in:tomorrow_io,openweathermap',
            'api_key' => 'nullable|string',
            'postcode' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'units' => 'required|string|in:metric,imperial',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        // Deactivate all existing settings
        WeatherSetting::query()->update(['is_active' => false]);

        // Create or update weather settings
        $weatherSettings = WeatherSetting::updateOrCreate(
            ['id' => $request->input('id')],
            $request->all()
        );

        return response()->json([
            'success' => true,
            'message' => 'Weather settings updated successfully',
            'data' => $weatherSettings
        ]);
    }

    public function testApi(Request $request): JsonResponse
    {
        $request->validate([
            'api_provider' => 'required|string',
            'api_key' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city' => 'nullable|string'
        ]);

        try {
            $apiProvider = $request->input('api_provider');
            $apiKey = $request->input('api_key');
            $city = $request->input('city');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            
            // If city is provided, use it for geocoding
            if ($city) {
                $location = urlencode($city);
            } else {
                // Use coordinates for weather API
                $location = $latitude . ',' . $longitude;
            }

            if ($apiProvider === 'tomorrow_io') {
                $url = "https://api.tomorrow.io/v4/weather/forecast?location={$location}&apikey={$apiKey}";
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported API provider'
                ]);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Network error: Unable to connect to API server'
                ]);
            }

            $data = json_decode($response, true);

            // Handle rate limit errors
            if ($httpCode === 429 || (isset($data['code']) && $data['code'] === 429001)) {
                // Get fallback data from database or use default
                $fallbackData = $this->getFallbackWeatherData($city);
                
                // Check if fallback data matches requested city
                $requestedCity = strtolower(trim($city));
                $fallbackCity = strtolower(trim($fallbackData['city']));
                $cityMatched = $requestedCity === $fallbackCity;
                
                $message = 'API key is valid but rate limit reached. This is normal for free tier. Weather widget will use fallback data.';
                $warning = 'Rate limit exceeded - using cached/fallback data';
                
                if (!$cityMatched) {
                    $message = "API found weather data for '{$fallbackData['city']}' instead of '{$city}'. You may want to use manual mode to enter exact location details.";
                    $warning = 'City name mismatch detected - using fallback data';
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'warning' => $warning,
                    'data' => $fallbackData
                ]);
            }

            // Handle other API errors
            if ($httpCode !== 200 || (isset($data['code']) && $data['code'] !== 200)) {
                $errorMessage = $data['message'] ?? 'Unknown API error';
                
                if (str_contains($errorMessage, 'Invalid API key')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid API key. Please check your API key and try again.'
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'API Error: ' . $errorMessage
                ]);
            }

            // Check if we have valid data structure
            if (!isset($data['timelines']['minutely'][0]['values'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'API returned invalid data structure'
                ]);
            }

            $locationName = $data['location']['name'] ?? 'Unknown';
            $parsedLocation = $this->parseLocationName($locationName);
            
            // Check if the detected city matches the requested city
            $requestedCity = strtolower(trim($city));
            $detectedCity = strtolower(trim($parsedLocation['city']));
            $cityMatched = $requestedCity === $detectedCity;
            
            $weatherData = [
                'location' => $locationName,
                'current_temp' => $data['timelines']['minutely'][0]['values']['temperature'] ?? 'N/A',
                'city' => $parsedLocation['city'],
                'state' => $parsedLocation['state'],
                'country' => $parsedLocation['country'],
                'postcode' => $parsedLocation['postcode'],
                'latitude' => $data['location']['lat'] ?? null,
                'longitude' => $data['location']['lon'] ?? null
            ];
            
            // Cache the weather data for fallback use
            $this->cacheWeatherData($city, $weatherData);
            
            $message = 'API connection successful! Weather data is available.';
            $warning = null;
            
            if (!$cityMatched) {
                $message = "API found weather data for '{$parsedLocation['city']}' instead of '{$city}'. You may want to use manual mode to enter exact location details.";
                $warning = 'City name mismatch detected';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'warning' => $warning,
                'data' => $weatherData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ]);
        }
    }

    public function getSettings(): JsonResponse
    {
        $settings = WeatherSetting::getActiveSettings();
        return response()->json($settings);
    }
    
    /**
     * Get fallback weather data from cache, database, or default values
     */
    private function getFallbackWeatherData(string $city): array
    {
        // 1. Try to get from cache first (most recent)
        $cachedData = $this->getCachedWeatherData($city);
        if ($cachedData) {
            return $cachedData;
        }
        
        // 2. Try to get from database
        $weatherSetting = WeatherSetting::getActiveSettings();
        if ($weatherSetting) {
            return [
                'location' => $weatherSetting->city . ', ' . $weatherSetting->country,
                'current_temp' => '25°C', // Default temperature
                'city' => $weatherSetting->city,
                'state' => $weatherSetting->state,
                'country' => $weatherSetting->country,
                'postcode' => $weatherSetting->postcode,
                'latitude' => $weatherSetting->latitude,
                'longitude' => $weatherSetting->longitude
            ];
        }
        
        // 3. Use city-based defaults as last resort
        $parsedLocation = $this->parseLocationName($city);
        
        return [
            'location' => $city,
            'current_temp' => '25°C',
            'city' => $parsedLocation['city'],
            'state' => $parsedLocation['state'],
            'country' => $parsedLocation['country'],
            'postcode' => $parsedLocation['postcode'],
            'latitude' => 0.0,
            'longitude' => 0.0
        ];
    }
    
    /**
     * Parse location name to extract city, state, country, and postcode
     */
    private function parseLocationName(string $locationName): array
    {
        $parts = explode(', ', $locationName);
        
        $city = $parts[0] ?? 'Unknown';
        $state = 'Unknown';
        $country = 'Unknown';
        $postcode = '';
        
        // Handle different location formats
        if (count($parts) >= 2) {
            $country = end($parts);
            
            if (count($parts) >= 3) {
                $state = $parts[1];
            }
        }
        
        // Extract postcode if present
        if (preg_match('/\b(\d{5})\b/', $locationName, $matches)) {
            $postcode = $matches[1];
        }
        
        // Special handling for known cities
        if ($city === 'Kuala Lumpur') {
            $state = 'Kuala Lumpur';
            $country = 'Malaysia';
            $postcode = '50000';
        } elseif ($city === 'Petaling Jaya') {
            $state = 'Selangor';
            $country = 'Malaysia';
            $postcode = '46000';
        } elseif ($city === 'Shah Alam') {
            $state = 'Selangor';
            $country = 'Malaysia';
            $postcode = '40000';
        } elseif ($city === 'Singapore') {
            $state = 'Singapore';
            $country = 'Singapore';
            $postcode = '';
        } elseif ($city === 'Bangkok') {
            $state = 'Bangkok';
            $country = 'Thailand';
            $postcode = '';
        } elseif ($city === 'Jakarta') {
            $state = 'Jakarta';
            $country = 'Indonesia';
            $postcode = '';
        }
        
        return [
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postcode' => $postcode
        ];
    }
    
    /**
     * Cache weather data for fallback use
     */
    private function cacheWeatherData(string $city, array $weatherData): void
    {
        $cacheKey = "weather_data_{$city}";
        $cacheData = [
            'data' => $weatherData,
            'cached_at' => now(),
            'expires_at' => now()->addHours(1) // Cache for 1 hour
        ];
        
        \Cache::put($cacheKey, $cacheData, 3600); // 1 hour cache
    }
    
    /**
     * Get cached weather data
     */
    private function getCachedWeatherData(string $city): ?array
    {
        $cacheKey = "weather_data_{$city}";
        $cachedData = \Cache::get($cacheKey);
        
        if ($cachedData && now()->lt($cachedData['expires_at'])) {
            return $cachedData['data'];
        }
        
        return null;
    }

}
