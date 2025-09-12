# 🔌 **API & INTEGRATION GUIDE**

## **📋 OVERVIEW**

Panduan lengkap untuk API endpoints, external integrations, dan webhook implementations dalam sistem Naeelah Firm.

---

## **🌐 INTERNAL API ENDPOINTS**

### **1. Authentication & Authorization**

#### **Login & Session Management**
```php
// routes/web.php
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Firm switching for Super Admin
Route::post('/switch-firm/{firmId}', [FirmController::class, 'switchFirm'])
    ->middleware(['auth', 'role:Super Administrator'])
    ->name('firm.switch');
```

#### **API Response Format**
```php
// app/Http/Controllers/Api/BaseApiController.php
class BaseApiController extends Controller
{
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
            'firm_id' => session('current_firm_id')
        ], $code);
    }
    
    protected function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString()
        ], $code);
    }
}
```

### **2. Case Management API**

#### **Case CRUD Operations**
```php
// routes/api.php
Route::middleware(['auth:sanctum', 'firm.scope'])->group(function () {
    Route::apiResource('cases', CaseApiController::class);
    Route::get('cases/{case}/timeline', [CaseApiController::class, 'timeline']);
    Route::post('cases/{case}/timeline', [CaseApiController::class, 'addTimelineEvent']);
    Route::get('cases/{case}/documents', [CaseApiController::class, 'documents']);
});

// app/Http/Controllers/Api/CaseApiController.php
class CaseApiController extends BaseApiController
{
    public function index(Request $request)
    {
        $query = CourtCase::with(['caseType', 'caseStatus', 'parties']);
        
        // Apply filters
        if ($request->has('status')) {
            $query->where('case_status_id', $request->status);
        }
        
        if ($request->has('type')) {
            $query->where('case_type_id', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }
        
        $cases = $query->paginate($request->get('per_page', 15));
        
        return $this->successResponse($cases, 'Cases retrieved successfully');
    }
    
    public function store(CaseStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $case = CourtCase::create($request->validated());
            
            // Add parties if provided
            if ($request->has('parties')) {
                foreach ($request->parties as $party) {
                    $case->parties()->create($party);
                }
            }
            
            // Log activity
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties(['ip' => $request->ip()])
                ->log("Case {$case->case_number} created via API");
            
            DB::commit();
            
            return $this->successResponse(
                $case->load(['caseType', 'caseStatus', 'parties']),
                'Case created successfully',
                201
            );
            
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create case: ' . $e->getMessage(), 500);
        }
    }
    
    public function timeline(CourtCase $case)
    {
        $timeline = $case->timeline()
            ->with('createdBy:id,name')
            ->orderBy('event_date', 'desc')
            ->get();
            
        return $this->successResponse($timeline, 'Timeline retrieved successfully');
    }
}
```

### **3. Financial API Endpoints**

#### **Financial Documents API**
```php
// Financial document endpoints
Route::middleware(['auth:sanctum', 'firm.scope'])->group(function () {
    Route::apiResource('quotations', QuotationApiController::class);
    Route::apiResource('invoices', TaxInvoiceApiController::class);
    Route::apiResource('receipts', ReceiptApiController::class);
    
    // Financial reports
    Route::get('reports/financial-summary', [ReportApiController::class, 'financialSummary']);
    Route::get('reports/general-ledger', [ReportApiController::class, 'generalLedger']);
    Route::get('reports/profit-loss', [ReportApiController::class, 'profitLoss']);
});

// app/Http/Controllers/Api/ReportApiController.php
class ReportApiController extends BaseApiController
{
    public function financialSummary(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $revenue = Receipt::whereBetween('receipt_date', $dateRange)
            ->sum('amount_paid');
            
        $expenses = Bill::whereBetween('bill_date', $dateRange)
            ->sum('total_amount') + 
            Voucher::whereBetween('payment_date', $dateRange)
            ->sum('total_amount');
            
        $netProfit = $revenue - $expenses;
        $profitMargin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;
        
        return $this->successResponse([
            'period' => [
                'from' => $dateRange[0],
                'to' => $dateRange[1]
            ],
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'profit_margin' => round($profitMargin, 2)
        ], 'Financial summary retrieved successfully');
    }
    
    private function getDateRange(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->endOfMonth()->toDateString());
        
        return [$from, $to];
    }
}
```

### **4. Calendar & Events API**

#### **Calendar Integration**
```php
// Calendar API endpoints
Route::middleware(['auth:sanctum', 'firm.scope'])->group(function () {
    Route::get('calendar/events', [CalendarApiController::class, 'events']);
    Route::post('calendar/events', [CalendarApiController::class, 'store']);
    Route::put('calendar/events/{event}', [CalendarApiController::class, 'update']);
    Route::delete('calendar/events/{event}', [CalendarApiController::class, 'destroy']);
});

// app/Http/Controllers/Api/CalendarApiController.php
class CalendarApiController extends BaseApiController
{
    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $events = CalendarEvent::when($start && $end, function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end]);
            })
            ->with(['case:id,case_number,title', 'timelineEvent:id,title'])
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'allDay' => $event->all_day,
                    'color' => $event->color,
                    'extendedProps' => [
                        'case' => $event->case,
                        'timeline_event' => $event->timelineEvent,
                        'location' => $event->location,
                        'description' => $event->description
                    ]
                ];
            });
            
        return $this->successResponse($events, 'Calendar events retrieved successfully');
    }
}
```

---

## **🌤️ EXTERNAL API INTEGRATIONS**

### **1. Weather API Integration**

#### **Tomorrow.io Weather Service**
```php
// app/Services/WeatherService.php
class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.tomorrow.io/v4';
    
    public function __construct()
    {
        $this->apiKey = config('services.weather.api_key');
    }
    
    public function getCurrentWeather($location)
    {
        $url = "{$this->baseUrl}/weather/realtime";
        
        $response = Http::timeout(10)
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->get($url, [
                'location' => $location,
                'apikey' => $this->apiKey,
                'units' => 'metric'
            ]);
            
        if ($response->successful()) {
            return $this->formatWeatherData($response->json());
        }
        
        throw new WeatherServiceException('Failed to fetch weather data');
    }
    
    public function getForecast($location, $days = 5)
    {
        $url = "{$this->baseUrl}/weather/forecast";
        
        $response = Http::timeout(15)
            ->get($url, [
                'location' => $location,
                'apikey' => $this->apiKey,
                'timesteps' => 'daily',
                'units' => 'metric'
            ]);
            
        if ($response->successful()) {
            return $this->formatForecastData($response->json(), $days);
        }
        
        return $this->getFallbackWeather($location);
    }
    
    private function formatWeatherData($data)
    {
        $values = $data['data']['values'] ?? [];
        
        return [
            'temperature' => $values['temperature'] ?? 28,
            'humidity' => $values['humidity'] ?? 75,
            'wind_speed' => $values['windSpeed'] ?? 5,
            'weather_code' => $values['weatherCode'] ?? 1000,
            'description' => $this->getWeatherDescription($values['weatherCode'] ?? 1000),
            'location' => $data['location'] ?? 'Kuala Lumpur',
            'timestamp' => now()->toISOString(),
            'source' => 'tomorrow.io'
        ];
    }
    
    private function getFallbackWeather($location)
    {
        // Realistic fallback data for KL
        return [
            'temperature' => rand(26, 32),
            'humidity' => rand(70, 85),
            'wind_speed' => rand(3, 8),
            'weather_code' => 1001,
            'description' => 'Partly Cloudy',
            'location' => 'Kuala Lumpur',
            'timestamp' => now()->toISOString(),
            'source' => 'fallback'
        ];
    }
}
```

#### **Weather Controller with Caching**
```php
// app/Http/Controllers/WeatherController.php
class WeatherController extends Controller
{
    protected $weatherService;
    
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    
    public function getWeather(): JsonResponse
    {
        try {
            // Check cache first
            $cacheKey = 'weather_data_' . session('current_firm_id');
            $cachedData = cache()->get($cacheKey);
            
            if ($cachedData) {
                return response()->json($cachedData);
            }
            
            // Get weather settings
            $settings = WeatherSetting::getActiveSettings();
            $location = $settings ? $settings->getCoordinatesString() : '3.1390,101.6869'; // KL default
            
            // Fetch weather data
            $weatherData = $this->weatherService->getCurrentWeather($location);
            
            // Cache for 30 minutes
            cache()->put($cacheKey, $weatherData, now()->addMinutes(30));
            
            return response()->json($weatherData);
            
        } catch (\Exception $e) {
            Log::error('Weather API Error: ' . $e->getMessage());
            
            // Return fallback data
            return response()->json($this->weatherService->getFallbackWeather('Kuala Lumpur'));
        }
    }
    
    public function webhook(Request $request)
    {
        // Handle weather webhook updates
        $data = $request->validate([
            'location' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'weather_code' => 'required|integer'
        ]);
        
        $weatherData = [
            'temperature' => $data['temperature'],
            'humidity' => $data['humidity'],
            'weather_code' => $data['weather_code'],
            'description' => $this->getWeatherDescription($data['weather_code']),
            'location' => $data['location'],
            'timestamp' => now()->toISOString(),
            'source' => 'webhook'
        ];
        
        // Cache webhook data
        cache()->put('weather_data', $weatherData, now()->addHours(1));
        
        return response()->json(['status' => 'success']);
    }
}
```

### **2. Email Service Integration**

#### **Mail Configuration**
```php
// config/mail.php - Dynamic configuration
return [
    'default' => env('MAIL_MAILER', 'smtp'),
    
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],
    ],
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@naeelahfirm.com'),
        'name' => env('MAIL_FROM_NAME', 'Naeelah Firm'),
    ],
];

// app/Services/EmailService.php
class EmailService
{
    public function sendCaseNotification(CourtCase $case, User $recipient)
    {
        try {
            Mail::to($recipient->email)->send(new CaseNotificationMail($case));
            
            // Log email activity
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties([
                    'recipient' => $recipient->email,
                    'email_type' => 'case_notification'
                ])
                ->log("Case notification sent to {$recipient->name}");
                
            return true;
            
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendDocumentByEmail($documentType, $documentId, $recipientEmail)
    {
        $document = $this->getDocument($documentType, $documentId);
        
        if (!$document) {
            throw new DocumentNotFoundException("Document not found");
        }
        
        // Generate PDF
        $pdf = $this->generateDocumentPdf($document, $documentType);
        
        // Send email with attachment
        Mail::to($recipientEmail)->send(new DocumentMail($document, $pdf, $documentType));
        
        // Log activity
        activity()
            ->performedOn($document)
            ->causedBy(auth()->user())
            ->withProperties([
                'recipient' => $recipientEmail,
                'document_type' => $documentType
            ])
            ->log("{$documentType} sent via email to {$recipientEmail}");
    }
}
```

### **3. File Storage Integration**

#### **Document Management Service**
```php
// app/Services/DocumentStorageService.php
class DocumentStorageService
{
    public function storeDocument($file, $caseId, $fileType)
    {
        $case = CourtCase::findOrFail($caseId);
        
        // Generate unique filename
        $filename = $this->generateFilename($file, $case);
        
        // Store file
        $path = $file->storeAs(
            "documents/case_{$caseId}",
            $filename,
            'public'
        );
        
        // Create database record
        $caseFile = CaseFile::create([
            'case_id' => $caseId,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'file_type' => $fileType,
            'mime_type' => $file->getMimeType(),
            'status' => 'IN',
            'uploaded_by' => auth()->id(),
            'firm_id' => $case->firm_id
        ]);
        
        // Log activity
        activity()
            ->performedOn($case)
            ->causedBy(auth()->user())
            ->withProperties([
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $fileType
            ])
            ->log("Document uploaded to case {$case->case_number}");
            
        return $caseFile;
    }
    
    public function generateWarrantToAct($caseId, $templateData)
    {
        $case = CourtCase::findOrFail($caseId);
        
        // Use FPDI service for PDF generation
        $pdfService = new WarrantToActPdfService();
        
        $templatePath = storage_path('app/templates/warrant_to_act_template.pdf');
        $outputPath = "documents/case_{$caseId}/WARRANT_TO_ACT_" . time() . ".pdf";
        
        $pdfService->generate($templatePath, $outputPath, $templateData);
        
        // Store in database
        $caseFile = CaseFile::create([
            'case_id' => $caseId,
            'file_name' => 'Warrant to Act - ' . $case->case_number . '.pdf',
            'file_path' => $outputPath,
            'file_type' => 'warrant_to_act',
            'status' => 'IN',
            'uploaded_by' => auth()->id(),
            'firm_id' => $case->firm_id
        ]);
        
        return $caseFile;
    }
}
```

---

## **🔗 WEBHOOK IMPLEMENTATIONS**

### **1. Webhook Handler**
```php
// app/Http/Controllers/WebhookController.php
class WebhookController extends Controller
{
    public function handle(Request $request, $service)
    {
        // Verify webhook signature
        if (!$this->verifySignature($request, $service)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }
        
        switch ($service) {
            case 'weather':
                return $this->handleWeatherWebhook($request);
            case 'payment':
                return $this->handlePaymentWebhook($request);
            case 'email':
                return $this->handleEmailWebhook($request);
            default:
                return response()->json(['error' => 'Unknown service'], 400);
        }
    }
    
    private function handleWeatherWebhook(Request $request)
    {
        $data = $request->validate([
            'location' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'weather_code' => 'required|integer'
        ]);
        
        // Update cached weather data
        $weatherData = [
            'temperature' => $data['temperature'],
            'humidity' => $data['humidity'],
            'weather_code' => $data['weather_code'],
            'location' => $data['location'],
            'timestamp' => now()->toISOString(),
            'source' => 'webhook'
        ];
        
        cache()->put('weather_data', $weatherData, now()->addHours(1));
        
        return response()->json(['status' => 'processed']);
    }
    
    private function verifySignature(Request $request, $service)
    {
        $signature = $request->header('X-Webhook-Signature');
        $payload = $request->getContent();
        
        $expectedSignature = hash_hmac('sha256', $payload, config("services.{$service}.webhook_secret"));
        
        return hash_equals($signature, $expectedSignature);
    }
}
```

### **2. API Rate Limiting**
```php
// app/Http/Middleware/ApiRateLimit.php
class ApiRateLimit
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'error' => 'Too many requests',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }
        
        RateLimiter::hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        return $response->withHeaders([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
        ]);
    }
    
    protected function resolveRequestSignature(Request $request)
    {
        $user = $request->user();
        $firmId = session('current_firm_id');
        
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . ($user ? $user->id : $request->ip()) .
            '|' . $firmId
        );
    }
}
```

---

## **📊 API MONITORING & LOGGING**

### **1. API Request Logging**
```php
// app/Http/Middleware/ApiLogger.php
class ApiLogger
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $startTime;
        
        // Log API request
        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'firm_id' => session('current_firm_id'),
            'status_code' => $response->getStatusCode(),
            'duration' => round($duration * 1000, 2) . 'ms',
            'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB'
        ]);
        
        return $response;
    }
}
```

### **2. API Health Check**
```php
// app/Http/Controllers/HealthController.php
class HealthController extends Controller
{
    public function check()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'external_apis' => $this->checkExternalApis()
        ];
        
        $allHealthy = collect($checks)->every(fn($check) => $check['status'] === 'ok');
        
        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'unhealthy',
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'version' => config('app.version', '1.0.0')
        ], $allHealthy ? 200 : 503);
    }
    
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }
    
    private function checkCache()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            $value = cache()->get('health_check');
            return ['status' => $value === 'ok' ? 'ok' : 'error', 'message' => 'Cache check'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache check failed'];
        }
    }
    
    private function checkExternalApis()
    {
        $apis = [];
        
        // Check weather API
        try {
            $response = Http::timeout(5)->get('https://api.tomorrow.io/v4/weather/realtime', [
                'location' => '3.1390,101.6869',
                'apikey' => config('services.weather.api_key')
            ]);
            
            $apis['weather'] = [
                'status' => $response->successful() ? 'ok' : 'error',
                'response_time' => $response->transferStats->getTransferTime() ?? 0
            ];
        } catch (\Exception $e) {
            $apis['weather'] = ['status' => 'error', 'message' => 'Weather API unreachable'];
        }
        
        return $apis;
    }
}
```

---

**API dan integration guide ini menyediakan foundation yang kuat untuk mengembangkan dan memelihara sistem yang scalable dan reliable.**
