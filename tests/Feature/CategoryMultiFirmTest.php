<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Firm;
use App\Models\CaseType;
use App\Models\CaseStatus;
use App\Models\FileType;
use App\Models\Specialization;
use App\Models\EventStatus;
use App\Models\ExpenseCategory;
use App\Models\Payee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryMultiFirmTest extends TestCase
{
    use RefreshDatabase;

    protected $firm1;
    protected $firm2;
    protected $user1;
    protected $user2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two firms
        $this->firm1 = Firm::factory()->create(['name' => 'Firm A']);
        $this->firm2 = Firm::factory()->create(['name' => 'Firm B']);

        // Create users for each firm
        $this->user1 = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->user2 = User::factory()->create(['firm_id' => $this->firm2->id]);
    }

    /** @test */
    public function case_types_are_isolated_by_firm()
    {
        // Create case types for each firm
        $caseType1 = CaseType::create([
            'code' => 'DIV',
            'description' => 'Divorce',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        $caseType2 = CaseType::create([
            'code' => 'CR',
            'description' => 'Criminal',
            'status' => 'active',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user can only see firm 1 case types
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1CaseTypes = CaseType::all();
        $this->assertCount(1, $firm1CaseTypes);
        $this->assertEquals('DIV', $firm1CaseTypes->first()->code);

        // Test firm 2 user can only see firm 2 case types
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2CaseTypes = CaseType::all();
        $this->assertCount(1, $firm2CaseTypes);
        $this->assertEquals('CR', $firm2CaseTypes->first()->code);
    }

    /** @test */
    public function case_statuses_are_isolated_by_firm()
    {
        // Create case statuses for each firm
        $status1 = CaseStatus::create([
            'name' => 'Settled',
            'description' => 'Case settled',
            'color' => 'green',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        $status2 = CaseStatus::create([
            'name' => 'Pending',
            'description' => 'Case pending',
            'color' => 'yellow',
            'status' => 'active',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm isolation
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Statuses = CaseStatus::all();
        $this->assertCount(1, $firm1Statuses);
        $this->assertEquals('Settled', $firm1Statuses->first()->name);

        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Statuses = CaseStatus::all();
        $this->assertCount(1, $firm2Statuses);
        $this->assertEquals('Pending', $firm2Statuses->first()->name);
    }

    /** @test */
    public function payees_are_isolated_by_firm()
    {
        // Create payees for each firm
        $payee1 = Payee::create([
            'name' => 'ABC Bank',
            'category' => 'Bank',
            'is_active' => true,
            'firm_id' => $this->firm1->id
        ]);

        $payee2 = Payee::create([
            'name' => 'XYZ Supplier',
            'category' => 'Supplier',
            'is_active' => true,
            'firm_id' => $this->firm2->id
        ]);

        // Test firm isolation
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Payees = Payee::all();
        $this->assertCount(1, $firm1Payees);
        $this->assertEquals('ABC Bank', $firm1Payees->first()->name);

        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Payees = Payee::all();
        $this->assertCount(1, $firm2Payees);
        $this->assertEquals('XYZ Supplier', $firm2Payees->first()->name);
    }

    /** @test */
    public function expense_categories_are_isolated_by_firm()
    {
        // Create expense categories for each firm
        $category1 = ExpenseCategory::create([
            'name' => 'Office Rent',
            'description' => 'Monthly office rent',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        $category2 = ExpenseCategory::create([
            'name' => 'Legal Fees',
            'description' => 'External legal fees',
            'status' => 'active',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm isolation
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Categories = ExpenseCategory::all();
        $this->assertCount(1, $firm1Categories);
        $this->assertEquals('Office Rent', $firm1Categories->first()->name);

        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Categories = ExpenseCategory::all();
        $this->assertCount(1, $firm2Categories);
        $this->assertEquals('Legal Fees', $firm2Categories->first()->name);
    }
}
