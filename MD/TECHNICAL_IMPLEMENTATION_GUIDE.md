# 🔧 **TECHNICAL IMPLEMENTATION GUIDE**

## **📋 OVERVIEW**

Panduan teknikal mendalam untuk memahami implementasi sistem Naeelah Firm, termasuk patterns, best practices, dan code examples yang digunakan dalam sistem.

---

## **🏗️ ARCHITECTURE PATTERNS**

### **1. Multi-Tenancy Implementation**

#### **Global Scope Pattern**
```php
// app/Scopes/FirmScope.php
class FirmScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Skip during console commands
        if (app()->runningInConsole()) return;
        
        // Prevent recursion
        if (app()->bound('firm_scope_active')) return;
        
        app()->instance('firm_scope_active', true);
        
        try {
            $user = Auth::user();
            $firmId = $this->determineFirmId($user);
            
            if ($firmId) {
                $builder->where($model->getTable() . '.firm_id', $firmId);
            } else {
                $builder->whereRaw('1 = 0'); // No data visible
            }
        } finally {
            app()->forgetInstance('firm_scope_active');
        }
    }
    
    private function determineFirmId($user)
    {
        // Super Admin can switch firms
        if ($this->isSuperAdmin($user) && session('current_firm_id')) {
            return session('current_firm_id');
        }
        
        return $user->firm_id ?? null;
    }
}
```

#### **Trait Implementation**
```php
// app/Traits/HasFirmScope.php
trait HasFirmScope
{
    protected static function bootHasFirmScope()
    {
        static::addGlobalScope(new FirmScope);
        
        // Auto-assign firm_id on creation
        static::creating(function ($model) {
            if (!$model->firm_id) {
                $model->firm_id = session('current_firm_id') ?? auth()->user()->firm_id;
            }
        });
    }
    
    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }
    
    // Bypass firm scope for system operations
    public function scopeWithoutFirmScope($query)
    {
        return $query->withoutGlobalScope(FirmScope::class);
    }
    
    // Query specific firm
    public function scopeForFirm($query, $firmId)
    {
        return $query->withoutGlobalScope(FirmScope::class)->where('firm_id', $firmId);
    }
}
```

### **2. Repository Pattern Implementation**

#### **Base Repository**
```php
// app/Repositories/BaseRepository.php
abstract class BaseRepository
{
    protected $model;
    
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }
    
    abstract protected function getModelClass(): string;
    
    public function find($id)
    {
        return $this->model->find($id);
    }
    
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    
    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }
    
    public function delete($id)
    {
        return $this->model->destroy($id);
    }
    
    public function paginate($perPage = 15)
    {
        return $this->model->paginate($perPage);
    }
}
```

#### **Case Repository Example**
```php
// app/Repositories/CaseRepository.php
class CaseRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return CourtCase::class;
    }
    
    public function findWithRelations($id)
    {
        return $this->model->with([
            'parties',
            'partners.partner',
            'caseType',
            'caseStatus',
            'createdBy',
            'files.fileType',
            'timeline.createdBy'
        ])->findOrFail($id);
    }
    
    public function getActiveCases()
    {
        return $this->model->whereHas('caseStatus', function($query) {
            $query->where('name', '!=', 'Closed');
        })->with(['caseType', 'caseStatus'])->get();
    }
    
    public function searchCases($searchTerm)
    {
        return $this->model->where(function($query) use ($searchTerm) {
            $query->where('case_number', 'like', "%{$searchTerm}%")
                  ->orWhere('title', 'like', "%{$searchTerm}%")
                  ->orWhereHas('parties', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
        })->get();
    }
}
```

---

## **🔄 SERVICE LAYER PATTERN**

### **Case Service Implementation**
```php
// app/Services/CaseService.php
class CaseService
{
    protected $caseRepository;
    protected $activityLogger;
    
    public function __construct(CaseRepository $caseRepository, ActivityLogger $activityLogger)
    {
        $this->caseRepository = $caseRepository;
        $this->activityLogger = $activityLogger;
    }
    
    public function createCase(array $data): CourtCase
    {
        DB::beginTransaction();
        
        try {
            // Create main case
            $case = $this->caseRepository->create($this->prepareCaseData($data));
            
            // Add parties
            $this->addPartiesToCase($case, $data['parties'] ?? []);
            
            // Add partners
            $this->addPartnersToCase($case, $data['partners'] ?? []);
            
            // Create initial timeline event
            $this->createInitialTimelineEvent($case);
            
            // Log activity
            $this->activityLogger->logCaseCreation($case);
            
            DB::commit();
            
            return $case;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CaseCreationException("Failed to create case: " . $e->getMessage());
        }
    }
    
    private function prepareCaseData(array $data): array
    {
        return [
            'case_number' => $data['case_ref'],
            'title' => $this->determineCaseTitle($data),
            'description' => $data['case_description'],
            'case_type_id' => $data['case_type_id'],
            'case_status_id' => $data['case_status_id'],
            'priority_level' => $data['priority_level'] ?? 'medium',
            'judge_name' => $data['judge_name'],
            'court_location' => $data['court_name'],
            'claim_amount' => $data['claim_amount'],
            'created_by' => auth()->id(),
            'firm_id' => $this->determineFirmId($data),
            // Additional fields...
        ];
    }
    
    private function determineCaseTitle(array $data): string
    {
        if ($data['section'] === 'conveyancing' && !empty($data['name_of_property'])) {
            return $data['name_of_property'];
        }
        
        return $data['case_title'];
    }
}
```

---

## **📊 ADVANCED QUERY PATTERNS**

### **Complex Relationship Queries**
```php
// app/Http/Controllers/ReportController.php
class ReportController extends Controller
{
    public function getCaseStatistics(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        $statistics = CourtCase::select([
                'case_types.description as case_type',
                'case_statuses.name as status',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(DATEDIFF(COALESCE(updated_at, NOW()), created_at)) as avg_duration_days'),
                DB::raw('SUM(claim_amount) as total_claim_amount')
            ])
            ->join('case_types', 'cases.case_type_id', '=', 'case_types.id')
            ->join('case_statuses', 'cases.case_status_id', '=', 'case_statuses.id')
            ->whereBetween('cases.created_at', $dateRange)
            ->groupBy('case_types.id', 'case_statuses.id')
            ->orderBy('case_types.description')
            ->get();
            
        return response()->json($statistics);
    }
    
    public function getFinancialSummary(Request $request)
    {
        $dateRange = $this->getDateRange($request);
        
        // Revenue from receipts
        $revenue = Receipt::whereBetween('receipt_date', $dateRange)
            ->selectRaw('
                SUM(amount_paid) as total_revenue,
                COUNT(*) as receipt_count,
                AVG(amount_paid) as avg_receipt_amount
            ')
            ->first();
            
        // Expenses from bills and vouchers
        $expenses = collect([
            Bill::whereBetween('bill_date', $dateRange)
                ->selectRaw('SUM(total_amount) as amount, COUNT(*) as count, "bills" as type')
                ->first(),
            Voucher::whereBetween('payment_date', $dateRange)
                ->selectRaw('SUM(total_amount) as amount, COUNT(*) as count, "vouchers" as type')
                ->first()
        ]);
        
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $revenue->total_revenue - $totalExpenses;
        
        return response()->json([
            'revenue' => $revenue,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'profit_margin' => $revenue->total_revenue > 0 ? ($netProfit / $revenue->total_revenue) * 100 : 0
        ]);
    }
}
```

### **Dynamic Query Builder**
```php
// app/Services/QueryBuilderService.php
class QueryBuilderService
{
    public function buildCaseQuery(array $filters)
    {
        $query = CourtCase::with(['caseType', 'caseStatus', 'parties']);
        
        // Date range filter
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('created_at', [$filters['date_from'], $filters['date_to']]);
        }
        
        // Case type filter
        if (!empty($filters['case_type_id'])) {
            $query->where('case_type_id', $filters['case_type_id']);
        }
        
        // Status filter
        if (!empty($filters['status_id'])) {
            $query->where('case_status_id', $filters['status_id']);
        }
        
        // Priority filter
        if (!empty($filters['priority'])) {
            $query->where('priority_level', $filters['priority']);
        }
        
        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('parties', function($partyQuery) use ($search) {
                      $partyQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);
        
        return $query;
    }
}
```

---

## **🔒 SECURITY IMPLEMENTATION**

### **Custom Middleware**
```php
// app/Http/Middleware/CheckCaseAccess.php
class CheckCaseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $caseId = $request->route('id') ?? $request->route('case');
        
        if ($caseId) {
            $case = CourtCase::find($caseId);
            
            if (!$case) {
                abort(404, 'Case not found');
            }
            
            $user = auth()->user();
            
            // Super Admin can access all cases
            if ($user->hasRole('Super Administrator')) {
                return $next($request);
            }
            
            // Check firm access
            if ($case->firm_id !== $user->firm_id) {
                abort(403, 'Access denied to this case');
            }
            
            // Check specific case permissions
            if (!$user->can('view-case', $case)) {
                abort(403, 'Insufficient permissions to view this case');
            }
        }
        
        return $next($request);
    }
}
```

### **Policy Implementation**
```php
// app/Policies/CasePolicy.php
class CasePolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view-cases');
    }
    
    public function view(User $user, CourtCase $case)
    {
        // Super Admin can view all
        if ($user->hasRole('Super Administrator')) {
            return true;
        }
        
        // Must be same firm
        if ($case->firm_id !== $user->firm_id) {
            return false;
        }
        
        // Check specific permissions
        return $user->hasPermissionTo('view-cases') || 
               $case->created_by === $user->id ||
               $case->assigned_to === $user->id;
    }
    
    public function create(User $user)
    {
        return $user->hasPermissionTo('create-cases');
    }
    
    public function update(User $user, CourtCase $case)
    {
        if ($user->hasRole('Super Administrator')) {
            return true;
        }
        
        return $case->firm_id === $user->firm_id && 
               $user->hasPermissionTo('edit-cases');
    }
    
    public function delete(User $user, CourtCase $case)
    {
        if ($user->hasRole('Super Administrator')) {
            return true;
        }
        
        return $case->firm_id === $user->firm_id && 
               $user->hasPermissionTo('delete-cases') &&
               $case->created_by === $user->id;
    }
}
```

---

## **📈 PERFORMANCE OPTIMIZATION**

### **Query Optimization Techniques**

#### **1. Eager Loading Strategy**
```php
// Optimized case loading
public function index()
{
    $cases = CourtCase::with([
        'parties:id,case_id,name,party_type', // Select specific columns
        'partners.partner:id,partner_code,firm_name',
        'caseType:id,description,color',
        'caseStatus:id,name,color',
        'createdBy:id,name'
    ])
    ->select(['id', 'case_number', 'title', 'case_type_id', 'case_status_id', 'created_by', 'created_at'])
    ->orderBy('created_at', 'desc')
    ->paginate(25);
    
    return view('case', compact('cases'));
}
```

#### **2. Database Indexing Strategy**
```sql
-- Core indexes for performance
CREATE INDEX idx_cases_firm_id ON cases(firm_id);
CREATE INDEX idx_cases_status_firm ON cases(case_status_id, firm_id);
CREATE INDEX idx_cases_type_firm ON cases(case_type_id, firm_id);
CREATE INDEX idx_cases_created_at ON cases(created_at);
CREATE INDEX idx_cases_case_number ON cases(case_number);

-- Composite indexes for common queries
CREATE INDEX idx_cases_firm_status_created ON cases(firm_id, case_status_id, created_at);
CREATE INDEX idx_receipts_firm_date ON receipts(firm_id, receipt_date);
CREATE INDEX idx_bills_firm_date ON bills(firm_id, bill_date);

-- Full-text search indexes
CREATE FULLTEXT INDEX idx_cases_search ON cases(case_number, title, description);
```

#### **3. Caching Implementation**
```php
// app/Services/CacheService.php
class CacheService
{
    const CACHE_TTL = 3600; // 1 hour
    
    public function getCaseStatistics($firmId)
    {
        $cacheKey = "case_statistics_{$firmId}";
        
        return cache()->remember($cacheKey, self::CACHE_TTL, function() use ($firmId) {
            return CourtCase::where('firm_id', $firmId)
                ->selectRaw('
                    case_status_id,
                    COUNT(*) as count,
                    AVG(claim_amount) as avg_claim_amount,
                    SUM(claim_amount) as total_claim_amount
                ')
                ->groupBy('case_status_id')
                ->with('caseStatus:id,name,color')
                ->get();
        });
    }
    
    public function getFinancialSummary($firmId, $dateRange)
    {
        $cacheKey = "financial_summary_{$firmId}_" . md5(serialize($dateRange));
        
        return cache()->remember($cacheKey, 1800, function() use ($firmId, $dateRange) { // 30 minutes
            $revenue = Receipt::where('firm_id', $firmId)
                ->whereBetween('receipt_date', $dateRange)
                ->sum('amount_paid');
                
            $expenses = Bill::where('firm_id', $firmId)
                ->whereBetween('bill_date', $dateRange)
                ->sum('total_amount');
                
            return [
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit' => $revenue - $expenses
            ];
        });
    }
    
    public function clearFirmCache($firmId)
    {
        $patterns = [
            "case_statistics_{$firmId}",
            "financial_summary_{$firmId}_*",
            "firm_settings_{$firmId}"
        ];
        
        foreach ($patterns as $pattern) {
            cache()->forget($pattern);
        }
    }
}
```

---

## **🔄 EVENT-DRIVEN ARCHITECTURE**

### **Event System Implementation**
```php
// app/Events/CaseCreated.php
class CaseCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $case;
    public $user;
    
    public function __construct(CourtCase $case, User $user)
    {
        $this->case = $case;
        $this->user = $user;
    }
}

// app/Listeners/LogCaseCreation.php
class LogCaseCreation
{
    public function handle(CaseCreated $event)
    {
        activity()
            ->performedOn($event->case)
            ->causedBy($event->user)
            ->withProperties([
                'ip' => request()->ip(),
                'case_number' => $event->case->case_number,
                'case_type' => $event->case->caseType->description ?? 'N/A'
            ])
            ->log("Case {$event->case->case_number} created");
    }
}

// app/Listeners/SendCaseNotification.php
class SendCaseNotification
{
    public function handle(CaseCreated $event)
    {
        // Send email notification to assigned lawyer
        if ($event->case->assigned_to) {
            $assignedUser = User::find($event->case->assigned_to);
            Mail::to($assignedUser)->send(new CaseAssignedMail($event->case));
        }
        
        // Send notification to firm administrators
        $admins = User::where('firm_id', $event->case->firm_id)
            ->whereHas('roles', function($query) {
                $query->where('name', 'Administrator');
            })->get();
            
        foreach ($admins as $admin) {
            $admin->notify(new NewCaseNotification($event->case));
        }
    }
}
```

---

## **🧪 TESTING STRATEGIES**

### **Feature Test Example**
```php
// tests/Feature/CaseManagementTest.php
class CaseManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    protected $user;
    protected $firm;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->firm = Firm::factory()->create();
        $this->user = User::factory()->create(['firm_id' => $this->firm->id]);
        $this->user->assignRole('Administrator');
        
        $this->actingAs($this->user);
    }
    
    public function test_user_can_create_case()
    {
        $caseType = CaseType::factory()->create();
        $caseStatus = CaseStatus::factory()->create();
        
        $caseData = [
            'case_ref' => 'TEST-001',
            'case_title' => 'Test Case',
            'case_description' => 'Test case description',
            'case_type_id' => $caseType->id,
            'case_status_id' => $caseStatus->id,
            'priority_level' => 'medium',
            'person_in_charge' => 'John Doe'
        ];
        
        $response = $this->post(route('case.store'), $caseData);
        
        $response->assertRedirect(route('case.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('cases', [
            'case_number' => 'TEST-001',
            'title' => 'Test Case',
            'firm_id' => $this->firm->id,
            'created_by' => $this->user->id
        ]);
    }
    
    public function test_user_cannot_access_other_firm_cases()
    {
        $otherFirm = Firm::factory()->create();
        $otherCase = CourtCase::factory()->create(['firm_id' => $otherFirm->id]);
        
        $response = $this->get(route('case.show', $otherCase->id));
        
        $response->assertStatus(404); // Should not find due to firm scope
    }
    
    public function test_case_creation_logs_activity()
    {
        $caseType = CaseType::factory()->create();
        $caseStatus = CaseStatus::factory()->create();
        
        $caseData = [
            'case_ref' => 'LOG-001',
            'case_title' => 'Logged Case',
            'case_type_id' => $caseType->id,
            'case_status_id' => $caseStatus->id,
            'person_in_charge' => 'Jane Doe'
        ];
        
        $this->post(route('case.store'), $caseData);
        
        $this->assertDatabaseHas('activity_log', [
            'description' => 'Case LOG-001 created',
            'causer_id' => $this->user->id,
            'causer_type' => User::class
        ]);
    }
}
```

### **Unit Test Example**
```php
// tests/Unit/FirmScopeTest.php
class FirmScopeTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_firm_scope_filters_by_user_firm()
    {
        $firm1 = Firm::factory()->create();
        $firm2 = Firm::factory()->create();
        
        $user1 = User::factory()->create(['firm_id' => $firm1->id]);
        $user2 = User::factory()->create(['firm_id' => $firm2->id]);
        
        $case1 = CourtCase::factory()->create(['firm_id' => $firm1->id]);
        $case2 = CourtCase::factory()->create(['firm_id' => $firm2->id]);
        
        // Test user1 can only see firm1 cases
        $this->actingAs($user1);
        $cases = CourtCase::all();
        
        $this->assertCount(1, $cases);
        $this->assertEquals($case1->id, $cases->first()->id);
        
        // Test user2 can only see firm2 cases
        $this->actingAs($user2);
        $cases = CourtCase::all();
        
        $this->assertCount(1, $cases);
        $this->assertEquals($case2->id, $cases->first()->id);
    }
    
    public function test_super_admin_can_switch_firms()
    {
        $firm1 = Firm::factory()->create();
        $firm2 = Firm::factory()->create();
        
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Administrator');
        
        $case1 = CourtCase::factory()->create(['firm_id' => $firm1->id]);
        $case2 = CourtCase::factory()->create(['firm_id' => $firm2->id]);
        
        $this->actingAs($superAdmin);
        
        // Without firm selection, should see all cases
        $cases = CourtCase::withoutFirmScope()->get();
        $this->assertCount(2, $cases);
        
        // With firm1 selected
        session(['current_firm_id' => $firm1->id]);
        $cases = CourtCase::all();
        $this->assertCount(1, $cases);
        $this->assertEquals($case1->id, $cases->first()->id);
    }
}
```

---

## **📝 BEST PRACTICES SUMMARY**

### **1. Code Organization**
- Use Repository pattern for data access
- Implement Service layer for business logic
- Apply Policy classes for authorization
- Utilize Events and Listeners for decoupling

### **2. Performance**
- Implement eager loading to prevent N+1 queries
- Use database indexing strategically
- Apply caching for expensive operations
- Optimize query selection with specific columns

### **3. Security**
- Implement multi-layer security checks
- Use Global Scopes for data isolation
- Apply proper input validation
- Maintain comprehensive activity logging

### **4. Testing**
- Write Feature tests for user workflows
- Create Unit tests for business logic
- Test security boundaries thoroughly
- Maintain high test coverage

### **5. Maintainability**
- Follow SOLID principles
- Use consistent naming conventions
- Document complex business logic
- Implement proper error handling

---

**Panduan ini menyediakan foundation yang kuat untuk memahami dan mengembangkan sistem Naeelah Firm dengan mengikuti best practices dalam Laravel development.**
