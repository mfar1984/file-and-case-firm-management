<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Firm;
use App\Models\CourtCase;
use App\Models\PreQuotation;
use App\Models\Quotation;
use App\Models\TaxInvoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialDocumentsMultiFirmTest extends TestCase
{
    use RefreshDatabase;

    protected $firm1;
    protected $firm2;
    protected $user1;
    protected $user2;
    protected $case1;
    protected $case2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two firms
        $this->firm1 = Firm::factory()->create(['name' => 'Firm A']);
        $this->firm2 = Firm::factory()->create(['name' => 'Firm B']);

        // Create users for each firm
        $this->user1 = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->user2 = User::factory()->create(['firm_id' => $this->firm2->id]);

        // Create cases for each firm
        $this->case1 = CourtCase::factory()->create([
            'case_number' => 'C-001',
            'firm_id' => $this->firm1->id
        ]);
        
        $this->case2 = CourtCase::factory()->create([
            'case_number' => 'C-002', 
            'firm_id' => $this->firm2->id
        ]);
    }

    /** @test */
    public function pre_quotations_are_isolated_by_firm()
    {
        // Create pre-quotations for each firm
        $preQuotation1 = PreQuotation::create([
            'quotation_no' => 'PQ-001',
            'quotation_date' => now(),
            'full_name' => 'Client A',
            'total' => 1000.00,
            'status' => 'pending',
            'firm_id' => $this->firm1->id
        ]);

        $preQuotation2 = PreQuotation::create([
            'quotation_no' => 'PQ-002',
            'quotation_date' => now(),
            'full_name' => 'Client B',
            'total' => 2000.00,
            'status' => 'pending',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user can only see firm 1 pre-quotations
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1PreQuotations = PreQuotation::all();
        $this->assertCount(1, $firm1PreQuotations);
        $this->assertEquals('PQ-001', $firm1PreQuotations->first()->quotation_no);

        // Test firm 2 user can only see firm 2 pre-quotations
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2PreQuotations = PreQuotation::all();
        $this->assertCount(1, $firm2PreQuotations);
        $this->assertEquals('PQ-002', $firm2PreQuotations->first()->quotation_no);
    }

    /** @test */
    public function quotations_are_isolated_by_firm()
    {
        // Create quotations for each firm
        $quotation1 = Quotation::create([
            'case_id' => $this->case1->id,
            'quotation_no' => 'Q-001',
            'quotation_date' => now(),
            'customer_name' => 'Client A',
            'total' => 1500.00,
            'status' => 'pending',
            'firm_id' => $this->firm1->id
        ]);

        $quotation2 = Quotation::create([
            'case_id' => $this->case2->id,
            'quotation_no' => 'Q-002',
            'quotation_date' => now(),
            'customer_name' => 'Client B',
            'total' => 2500.00,
            'status' => 'pending',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user can only see firm 1 quotations
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Quotations = Quotation::all();
        $this->assertCount(1, $firm1Quotations);
        $this->assertEquals('Q-001', $firm1Quotations->first()->quotation_no);

        // Test firm 2 user can only see firm 2 quotations
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Quotations = Quotation::all();
        $this->assertCount(1, $firm2Quotations);
        $this->assertEquals('Q-002', $firm2Quotations->first()->quotation_no);
    }

    /** @test */
    public function tax_invoices_are_isolated_by_firm()
    {
        // Create tax invoices for each firm
        $taxInvoice1 = TaxInvoice::create([
            'invoice_no' => 'TI-001',
            'case_id' => $this->case1->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'customer_name' => 'Client A',
            'total' => 1800.00,
            'status' => 'draft',
            'firm_id' => $this->firm1->id
        ]);

        $taxInvoice2 = TaxInvoice::create([
            'invoice_no' => 'TI-002',
            'case_id' => $this->case2->id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'customer_name' => 'Client B',
            'total' => 2800.00,
            'status' => 'draft',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user can only see firm 1 tax invoices
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1TaxInvoices = TaxInvoice::all();
        $this->assertCount(1, $firm1TaxInvoices);
        $this->assertEquals('TI-001', $firm1TaxInvoices->first()->invoice_no);

        // Test firm 2 user can only see firm 2 tax invoices
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2TaxInvoices = TaxInvoice::all();
        $this->assertCount(1, $firm2TaxInvoices);
        $this->assertEquals('TI-002', $firm2TaxInvoices->first()->invoice_no);
    }

    /** @test */
    public function financial_document_creation_assigns_correct_firm_id()
    {
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);

        // Test pre-quotation creation
        $response = $this->post(route('pre-quotation.store'), [
            'quotation_date' => now()->format('Y-m-d'),
            'full_name' => 'Test Client',
            'items' => [
                [
                    'description' => 'Legal Service',
                    'qty' => 1,
                    'unit_price' => 1000,
                    'uom' => 'lot'
                ]
            ]
        ]);

        $response->assertRedirect();
        
        $createdPreQuotation = PreQuotation::where('full_name', 'Test Client')->first();
        $this->assertNotNull($createdPreQuotation);
        $this->assertEquals($this->firm1->id, $createdPreQuotation->firm_id);
    }

    /** @test */
    public function financial_documents_index_shows_only_firm_documents()
    {
        // Create documents for both firms
        PreQuotation::create([
            'quotation_no' => 'PQ-FIRM1',
            'quotation_date' => now(),
            'full_name' => 'Firm 1 Client',
            'total' => 1000.00,
            'status' => 'pending',
            'firm_id' => $this->firm1->id
        ]);

        PreQuotation::create([
            'quotation_no' => 'PQ-FIRM2',
            'quotation_date' => now(),
            'full_name' => 'Firm 2 Client',
            'total' => 2000.00,
            'status' => 'pending',
            'firm_id' => $this->firm2->id
        ]);

        // Test firm 1 user sees only firm 1 documents
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $response = $this->get(route('pre-quotation.index'));
        $response->assertStatus(200);
        $response->assertSee('PQ-FIRM1');
        $response->assertDontSee('PQ-FIRM2');

        // Test firm 2 user sees only firm 2 documents
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $response = $this->get(route('pre-quotation.index'));
        $response->assertStatus(200);
        $response->assertSee('PQ-FIRM2');
        $response->assertDontSee('PQ-FIRM1');
    }
}
