# Weather System Documentation

## Overview
The weather system provides real-time weather information with 3-Day Forecast and Fallback functionality. It supports multiple data sources: API, Webhook, and Fallback data.

## Features

### 🌤️ 3-Day Forecast
- **Today**: Current day weather forecast
- **Tomorrow**: Next day weather forecast  
- **Day After**: Third day weather forecast

Each forecast includes:
- **Day**: Day name (Today, Tomorrow, Day After)
- **Condition**: Weather condition (Sunny, Cloudy, Light Rain, etc.)
- **High**: Maximum temperature (°C)
- **Low**: Minimum temperature (°C)

### 🔄 Fallback System
The system automatically falls back to realistic weather data when:
- API is unavailable
- Rate limit exceeded
- Network errors
- Invalid API key

### 📡 Webhook Support
- Real-time weather data via webhook
- Secure authentication with webhook secret
- Automatic data processing and caching

## Data Sources

### 1. API Data (Primary)
- **Source**: Tomorrow.io Weather API
- **Priority**: Highest
- **Cache**: 30 minutes
- **Status**: `🌐 API`

### 2. Webhook Data (Secondary)
- **Source**: External webhook calls
- **Priority**: High (overrides API)
- **Cache**: 30 minutes
- **Status**: `📡 Webhook`

### 3. Fallback Data (Tertiary)
- **Source**: Realistic simulated data
- **Priority**: Lowest
- **Cache**: None
- **Status**: `🔄 Fallback`

## API Endpoints

### Get Weather Data
```http
GET /api/weather
```

**Response:**
```json
{
  "location": "Petaling Jaya, Selangor, Malaysia",
  "current": {
    "temperature": "28°C",
    "skytext": "Sunny",
    "humidity": "75%",
    "winddisplay": "12 km/h",
    "feels_like": "30°C",
    "pressure": "1012 hPa",
    "visibility": "15 km"
  },
  "forecast": [
    {
      "day": "Today",
      "condition": "Sunny",
      "high": "34°C",
      "low": "26°C"
    },
    {
      "day": "Tomorrow",
      "condition": "Partly Cloudy",
      "high": "33°C",
      "low": "25°C"
    },
    {
      "day": "Day After",
      "condition": "Light Rain",
      "high": "32°C",
      "low": "24°C"
    }
  ],
  "source": "webhook",
  "received_at": "2025-08-11T05:57:43.045613Z"
}
```

### Webhook Endpoint
```http
POST /webhook/weather
Headers:
  X-Webhook-Secret: 481b6f7eb9d94e5facf63df6860a600b
  Content-Type: application/json
```

**Request Body:**
```json
{
  "location": "Petaling Jaya, Selangor, Malaysia",
  "current": {
    "temperature": 28,
    "skytext": "Sunny",
    "humidity": 75,
    "winddisplay": "12 km/h",
    "feels_like": 30,
    "pressure": 1012,
    "visibility": 15
  },
  "forecast": {
    "today": {
      "high": "34°C",
      "low": "26°C",
      "condition": "Sunny"
    },
    "tomorrow": {
      "high": "33°C",
      "low": "25°C",
      "condition": "Partly Cloudy"
    },
    "day_after": {
      "high": "32°C",
      "low": "24°C",
      "condition": "Light Rain"
    }
  }
}
```

## Weather Widget Display

### Header
- Location name
- Current temperature
- Current condition
- Data source indicator

### Current Weather Section
- Temperature
- Feels Like
- Humidity
- Wind Speed
- Pressure
- Visibility

### 3-Day Forecast Section
- Day name
- Weather condition
- High temperature (red)
- Low temperature (blue)

### Footer
- Data source status (Webhook/API/Fallback)
- Fallback indicator
- Timestamp (if available)

## Configuration

### Weather Settings
Location: `/settings/global`

**Settings:**
- API Provider (Tomorrow.io)
- API Key
- Location (City, State, Country)
- Latitude/Longitude
- Active Weather Widget (Enable/Disable)

### Webhook Configuration
- **Secret**: `481b6f7eb9d94e5facf63df6860a600b`
- **Endpoint**: `/webhook/weather`
- **Method**: POST
- **Headers**: 
  - `X-Webhook-Secret`
  - `Content-Type: application/json`

## Testing

### Test API Data
```bash
curl http://localhost:8000/api/weather
```

### Test Webhook
```bash
curl -X POST http://localhost:8000/webhook/weather \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Secret: 481b6f7eb9d94e5facf63df6860a600b" \
  -d '{"location":"Test Location","current":{"temperature":25,"skytext":"Cloudy","humidity":80,"winddisplay":"10 km/h","feels_like":27,"pressure":1013,"visibility":10},"forecast":{"today":{"high":"30°C","low":"22°C","condition":"Cloudy"},"tomorrow":{"high":"29°C","low":"21°C","condition":"Light Rain"},"day_after":{"high":"28°C","low":"20°C","condition":"Partly Cloudy"}}}'
```

### Test Fallback
```bash
# Clear cache to force fallback
php artisan cache:clear
curl http://localhost:8000/api/weather
```

## Troubleshooting

### Common Issues

1. **3-Day Forecast Not Displaying**
   - Check data structure in response
   - Verify forecast array format
   - Clear browser cache

2. **Fallback Not Working**
   - Check API key configuration
   - Verify network connectivity
   - Check API rate limits

3. **Webhook Not Processing**
   - Verify webhook secret
   - Check request format
   - Review server logs

### Logs
Weather system logs are available in:
- `storage/logs/laravel.log`
- Webhook processing logs
- API error logs

## Security

### Webhook Security
- Secret-based authentication
- IP logging for security monitoring
- Request validation
- Error handling

### API Security
- API key validation
- Rate limiting
- Error handling
- Fallback protection

## Performance

### Caching Strategy
- API data: 30 minutes
- Webhook data: 30 minutes
- Fallback data: No cache

### Optimization
- Efficient data processing
- Minimal API calls
- Smart fallback system
- Real-time updates via webhook 