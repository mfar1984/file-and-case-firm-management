<?php

namespace Tests\Feature;

use App\Models\CalendarEvent;
use App\Models\Firm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarMultiFirmTest extends TestCase
{
    use RefreshDatabase;

    protected $firm1;
    protected $firm2;
    protected $user1;
    protected $user2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test firms
        $this->firm1 = Firm::factory()->create(['name' => 'Test Firm 1']);
        $this->firm2 = Firm::factory()->create(['name' => 'Test Firm 2']);

        // Create test users
        $this->user1 = User::factory()->create(['firm_id' => $this->firm1->id]);
        $this->user2 = User::factory()->create(['firm_id' => $this->firm2->id]);
    }

    /** @test */
    public function calendar_events_are_isolated_by_firm()
    {
        // Create events for different firms
        $event1 = CalendarEvent::create([
            'title' => 'Firm 1 Event',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'category' => 'meeting',
            'firm_id' => $this->firm1->id,
            'created_by' => $this->user1->id,
            'status' => 'active'
        ]);

        $event2 = CalendarEvent::create([
            'title' => 'Firm 2 Event',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'category' => 'meeting',
            'firm_id' => $this->firm2->id,
            'created_by' => $this->user2->id,
            'status' => 'active'
        ]);

        // Test firm isolation
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);
        
        $firm1Events = CalendarEvent::all();
        $this->assertCount(1, $firm1Events);
        $this->assertEquals('Firm 1 Event', $firm1Events->first()->title);

        // Switch to firm 2
        $this->actingAs($this->user2);
        session(['current_firm_id' => $this->firm2->id]);
        
        $firm2Events = CalendarEvent::all();
        $this->assertCount(1, $firm2Events);
        $this->assertEquals('Firm 2 Event', $firm2Events->first()->title);
    }

    /** @test */
    public function calendar_event_creation_assigns_firm_context()
    {
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);

        $response = $this->postJson(route('calendar.store'), [
            'title' => 'Test Event',
            'description' => 'Test Description',
            'start_date' => now()->toISOString(),
            'end_date' => now()->addHour()->toISOString(),
            'category' => 'meeting',
            'location' => 'Test Location'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $event = CalendarEvent::where('title', 'Test Event')->first();
        $this->assertNotNull($event);
        $this->assertEquals($this->firm1->id, $event->firm_id);
        $this->assertEquals($this->user1->id, $event->created_by);
    }

    /** @test */
    public function calendar_events_api_respects_firm_context()
    {
        // Create events for both firms
        CalendarEvent::create([
            'title' => 'Firm 1 Event',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'category' => 'meeting',
            'firm_id' => $this->firm1->id,
            'created_by' => $this->user1->id,
            'status' => 'active'
        ]);

        CalendarEvent::create([
            'title' => 'Firm 2 Event',
            'start_date' => now(),
            'end_date' => now()->addHour(),
            'category' => 'meeting',
            'firm_id' => $this->firm2->id,
            'created_by' => $this->user2->id,
            'status' => 'active'
        ]);

        // Test API for firm 1
        $this->actingAs($this->user1);
        session(['current_firm_id' => $this->firm1->id]);

        $response = $this->getJson(route('calendar.events'));
        $response->assertStatus(200);
        
        $events = $response->json();
        $this->assertCount(1, $events);
        $this->assertEquals('Firm 1 Event', $events[0]['title']);
    }
}
