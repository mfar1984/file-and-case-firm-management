<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Firm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AgencyMultiFirmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Administrator']);
        Role::create(['name' => 'Administrator']);
        
        // Create firms
        $this->firm1 = Firm::create([
            'name' => 'Test Firm 1',
            'status' => 'active'
        ]);
        
        $this->firm2 = Firm::create([
            'name' => 'Test Firm 2', 
            'status' => 'active'
        ]);
        
        // Create users
        $this->user1 = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->user1->assignRole('Administrator');
        
        $this->user2 = User::factory()->create(['firm_id' => $this->firm2->id]);
        $this->user2->assignRole('Administrator');
        
        $this->superAdmin = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->superAdmin->assignRole('Super Administrator');
    }

    public function test_agencies_are_filtered_by_firm()
    {
        // Create agencies for different firms
        $agency1 = Agency::create([
            'name' => 'Agency Firm 1',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);
        
        $agency2 = Agency::create([
            'name' => 'Agency Firm 2',
            'status' => 'active', 
            'firm_id' => $this->firm2->id
        ]);

        // User 1 should only see agencies from firm 1
        $response = $this->actingAs($this->user1)->get('/settings/agency');
        $response->assertOk();
        
        $agencyResponse = $this->actingAs($this->user1)->get('/settings/agency');
        $this->assertEquals(200, $agencyResponse->status());
    }

    public function test_agency_creation_assigns_correct_firm()
    {
        $response = $this->actingAs($this->user1)
            ->postJson('/settings/agency', [
                'name' => 'New Test Agency',
                'status' => 'active'
            ]);

        $response->assertJson(['success' => true]);
        
        $agency = Agency::where('name', 'New Test Agency')->first();
        $this->assertNotNull($agency);
        $this->assertEquals($this->firm1->id, $agency->firm_id);
    }

    public function test_agency_update_respects_firm_boundaries()
    {
        $agency = Agency::create([
            'name' => 'Test Agency',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        $response = $this->actingAs($this->user1)
            ->putJson("/settings/agency/{$agency->id}", [
                'name' => 'Updated Agency Name',
                'status' => 'active'
            ]);

        $response->assertJson(['success' => true]);
        
        $agency->refresh();
        $this->assertEquals('Updated Agency Name', $agency->name);
        $this->assertEquals($this->firm1->id, $agency->firm_id);
    }

    public function test_bulk_import_assigns_correct_firm()
    {
        $response = $this->actingAs($this->user1)
            ->postJson('/settings/agency/bulk', [
                'names' => ['Bulk Agency 1', 'Bulk Agency 2', 'Bulk Agency 3']
            ]);

        $response->assertJson(['success' => true]);
        
        $agencies = Agency::whereIn('name', ['Bulk Agency 1', 'Bulk Agency 2', 'Bulk Agency 3'])->get();
        $this->assertCount(3, $agencies);
        
        foreach ($agencies as $agency) {
            $this->assertEquals($this->firm1->id, $agency->firm_id);
        }
    }

    public function test_duplicate_agency_names_allowed_across_firms()
    {
        // Create agency in firm 1
        Agency::create([
            'name' => 'Duplicate Agency',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        // Should be able to create same name in firm 2
        $response = $this->actingAs($this->user2)
            ->postJson('/settings/agency', [
                'name' => 'Duplicate Agency',
                'status' => 'active'
            ]);

        $response->assertJson(['success' => true]);
        
        // Should have 2 agencies with same name but different firms
        $agencies = Agency::where('name', 'Duplicate Agency')->get();
        $this->assertCount(2, $agencies);
        $this->assertNotEquals($agencies[0]->firm_id, $agencies[1]->firm_id);
    }

    public function test_duplicate_agency_names_not_allowed_within_same_firm()
    {
        // Create agency in firm 1
        Agency::create([
            'name' => 'Duplicate Agency',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);

        // Should not be able to create same name in same firm
        $response = $this->actingAs($this->user1)
            ->postJson('/settings/agency', [
                'name' => 'Duplicate Agency',
                'status' => 'active'
            ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_super_admin_can_switch_firm_context()
    {
        // Create agencies for different firms
        $agency1 = Agency::create([
            'name' => 'Agency Firm 1',
            'status' => 'active',
            'firm_id' => $this->firm1->id
        ]);
        
        $agency2 = Agency::create([
            'name' => 'Agency Firm 2',
            'status' => 'active',
            'firm_id' => $this->firm2->id
        ]);

        // Super admin should be able to create agency for specific firm via session
        $this->actingAs($this->superAdmin)
            ->withSession(['current_firm_id' => $this->firm2->id])
            ->postJson('/settings/agency', [
                'name' => 'Super Admin Agency',
                'status' => 'active'
            ]);

        $agency = Agency::where('name', 'Super Admin Agency')->first();
        $this->assertNotNull($agency);
        $this->assertEquals($this->firm2->id, $agency->firm_id);
    }
}
