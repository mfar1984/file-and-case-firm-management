<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Firm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ActivityLogMultiFirmTest extends TestCase
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

    public function test_activity_logs_are_filtered_by_firm()
    {
        // Create activities for different firms
        $activity1 = Activity::create([
            'description' => 'Activity Firm 1',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user1->id,
            'firm_id' => $this->firm1->id
        ]);
        
        $activity2 = Activity::create([
            'description' => 'Activity Firm 2',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user2->id,
            'firm_id' => $this->firm2->id
        ]);

        // User 1 should only see activities from firm 1
        $response = $this->actingAs($this->user1)->get('/settings/log/data');
        $response->assertOk();
        
        $data = $response->json();
        $descriptions = collect($data['logs'])->pluck('description');
        
        $this->assertTrue($descriptions->contains('Activity Firm 1'));
        $this->assertFalse($descriptions->contains('Activity Firm 2'));
    }

    public function test_activity_creation_assigns_correct_firm()
    {
        $this->actingAs($this->user1);
        
        activity()
            ->causedBy($this->user1)
            ->log('Test Activity Creation');

        $activity = Activity::where('description', 'Test Activity Creation')->first();
        $this->assertNotNull($activity);
        $this->assertEquals($this->firm1->id, $activity->firm_id);
    }

    public function test_super_admin_can_switch_firm_context_for_logs()
    {
        // Create activities for different firms
        Activity::create([
            'description' => 'Activity Firm 1',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user1->id,
            'firm_id' => $this->firm1->id
        ]);
        
        Activity::create([
            'description' => 'Activity Firm 2',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user2->id,
            'firm_id' => $this->firm2->id
        ]);

        // Super admin should see firm 2 activities when firm context is switched
        $response = $this->actingAs($this->superAdmin)
            ->withSession(['current_firm_id' => $this->firm2->id])
            ->get('/settings/log/data');

        $response->assertOk();
        
        $data = $response->json();
        $descriptions = collect($data['logs'])->pluck('description');
        
        $this->assertFalse($descriptions->contains('Activity Firm 1'));
        $this->assertTrue($descriptions->contains('Activity Firm 2'));
    }

    public function test_clear_logs_only_affects_current_firm()
    {
        // Create activities for different firms
        Activity::create([
            'description' => 'Activity Firm 1',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user1->id,
            'firm_id' => $this->firm1->id
        ]);
        
        Activity::create([
            'description' => 'Activity Firm 2',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user2->id,
            'firm_id' => $this->firm2->id
        ]);

        // Give admin role to user1 for clearing logs
        $this->user1->assignRole('admin');

        // Clear logs as user from firm 1
        $response = $this->actingAs($this->user1)
            ->delete('/settings/log/clear');

        $response->assertOk();

        // Firm 1 activities should be deleted, firm 2 should remain
        $this->assertNull(Activity::where('description', 'Activity Firm 1')->first());
        $this->assertNotNull(Activity::where('description', 'Activity Firm 2')->first());
    }

    public function test_activity_log_firm_relationship()
    {
        $activity = Activity::create([
            'description' => 'Test Relationship',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user1->id,
            'firm_id' => $this->firm1->id
        ]);

        $this->assertNotNull($activity->firm);
        $this->assertEquals($this->firm1->id, $activity->firm->id);
        $this->assertEquals($this->firm1->name, $activity->firm->name);
    }

    public function test_firm_has_activity_logs_relationship()
    {
        Activity::create([
            'description' => 'Test Firm Relationship',
            'causer_type' => 'App\\Models\\User',
            'causer_id' => $this->user1->id,
            'firm_id' => $this->firm1->id
        ]);

        $this->assertTrue($this->firm1->activityLogs()->exists());
        $this->assertEquals(1, $this->firm1->activityLogs()->count());
        $this->assertEquals('Test Firm Relationship', $this->firm1->activityLogs()->first()->description);
    }

    public function test_activity_auto_assigns_firm_from_causer()
    {
        $this->actingAs($this->user2);
        
        // Create activity without explicitly setting firm_id
        activity()
            ->causedBy($this->user2)
            ->log('Auto Assign Test');

        $activity = Activity::where('description', 'Auto Assign Test')->first();
        $this->assertNotNull($activity);
        $this->assertEquals($this->firm2->id, $activity->firm_id);
    }
}
