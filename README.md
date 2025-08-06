# Naeelah Firm - Law Firm Management System

A comprehensive file and case management system for Malaysian law firms built with Laravel.

## Features

### Core Modules
- **Case Management**: Track cases, clients, and legal proceedings
- **File Management**: Digital file storage with physical location tracking
- **Client Management**: Client information and case history
- **Partner Management**: Law firm partner profiles and specializations
- **Accounting**: Quotations, invoices, receipts, vouchers, and bills
- **Calendar**: Court dates and case scheduling
- **Weather Integration**: Real-time weather data with webhook support

### Weather System

#### Webhook Integration
The system supports webhook-based weather data updates for real-time information.

**Webhook Endpoint**: `https://firm.kflr.dev/webhook/weather`
**Secret**: `481b6f7eb9d94e5facf63df6860a600b`
**API Key**: `UINsXMQBMJemd36Z2AUaQ4e65nWw7i9V`

#### Webhook Usage

1. **Send Weather Data**:
```bash
curl -X POST https://firm.kflr.dev/webhook/weather \
  -H "Content-Type: application/json" \
  -H "X-Webhook-Secret: 481b6f7eb9d94e5facf63df6860a600b" \
  -d '{
    "location": "Kuala Lumpur",
    "current": {
      "temperature": 28.5,
      "skytext": "Partly Cloudy",
      "humidity": 85,
      "winddisplay": "8.5 km/h",
      "feels_like": 30.2,
      "pressure": 1012,
      "visibility": 12
    },
    "forecast": {
      "today": {
        "high": 32,
        "low": 25,
        "condition": "Partly Cloudy",
        "humidity": 80,
        "precipitation": 20
      },
      "tomorrow": {
        "high": 31,
        "low": 24,
        "condition": "Light Rain",
        "humidity": 85,
        "precipitation": 35
      },
      "day_after": {
        "high": 30,
        "low": 23,
        "condition": "Cloudy",
        "humidity": 82,
        "precipitation": 25
      }
    }
  }'
```

2. **Test Webhook**:
```bash
curl -X GET https://firm.kflr.dev/webhook/test
```

3. **Get Cached Weather**:
```bash
curl -X GET https://firm.kflr.dev/webhook/weather/cached
```

#### Weather Widget Features
- **Real-time Data**: Updates via webhook or API calls
- **Hover Tooltip**: Detailed weather information
- **3-Day Forecast**: Today, tomorrow, and day after
- **Multiple Sources**: Webhook, API, and fallback data
- **Location Support**: Configurable via admin panel
- **Rate Limit Handling**: Graceful fallback when API limits are reached

#### Weather Settings
Access weather configuration at: `http://localhost:8000/settings/global`

- **API Provider**: Tomorrow.io, OpenWeatherMap
- **Location Details**: City, State, Country, Coordinates
- **Units**: Metric/Imperial
- **API Testing**: Built-in connection testing
- **Database Storage**: Settings stored in `weather_settings` table

## Installation

1. **Clone Repository**:
```bash
git clone <repository-url>
cd naeelah-firm
```

2. **Install Dependencies**:
```bash
composer install
npm install
```

3. **Environment Setup**:
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=firm
DB_USERNAME=root
DB_PASSWORD=root
```

5. **Run Migrations**:
```bash
php artisan migrate
```

6. **Build Assets**:
```bash
npm run build
```

7. **Start Server**:
```bash
php artisan serve
```

## Database Structure

### Weather Settings Table
```sql
CREATE TABLE weather_settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    api_provider VARCHAR(255) DEFAULT 'tomorrow_io',
    api_key VARCHAR(255) NULL,
    location_name VARCHAR(255) DEFAULT 'Kuala Lumpur',
    postcode VARCHAR(20) NULL,
    country VARCHAR(100) DEFAULT 'Malaysia',
    state VARCHAR(100) DEFAULT 'Kuala Lumpur',
    city VARCHAR(100) DEFAULT 'Kuala Lumpur',
    latitude DECIMAL(10,8) DEFAULT 3.1390,
    longitude DECIMAL(11,8) DEFAULT 101.6869,
    units VARCHAR(50) DEFAULT 'metric',
    is_active BOOLEAN DEFAULT TRUE,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## API Endpoints

### Weather API
- `GET /api/weather` - Get current weather data
- `POST /webhook/weather` - Webhook endpoint for weather updates
- `GET /webhook/test` - Test webhook connectivity
- `GET /webhook/weather/cached` - Get cached weather data

### Weather Settings
- `GET /settings/weather` - Weather settings page
- `POST /settings/weather` - Save weather settings
- `POST /settings/weather/test` - Test API connection
- `GET /settings/weather/get` - Get current settings

## Security

### Webhook Security
- **Secret Validation**: All webhook requests must include the correct secret
- **CSRF Protection**: Webhook endpoints are excluded from CSRF protection
- **Request Logging**: All webhook requests are logged for security monitoring
- **Error Handling**: Comprehensive error handling and validation

### API Security
- **Rate Limiting**: API calls are rate-limited to prevent abuse
- **Input Validation**: All inputs are validated and sanitized
- **Database Security**: API keys are stored securely in database
- **Rate Limit Handling**: Graceful fallback to realistic data when API limits are reached

## Weather Data Flow

1. **Webhook Reception**: External service sends weather data to webhook endpoint
2. **Data Processing**: Weather data is processed and formatted
3. **Caching**: Processed data is cached for 30 minutes
4. **Widget Display**: Weather widget displays cached data
5. **Fallback**: If no webhook data, falls back to API or realistic data

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team.
