<?php

namespace Database\Seeders;

use App\Models\EventStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventStatuses = [
            [
                'name' => 'completed',
                'description' => 'Event has been completed successfully',
                'background_color' => 'bg-green-500',
                'icon' => 'check',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'active',
                'description' => 'Event is currently ongoing',
                'background_color' => 'bg-blue-500',
                'icon' => 'radio_button_checked',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'in_progress',
                'description' => 'Event is in progress',
                'background_color' => 'bg-blue-500',
                'icon' => 'trending_up',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => 'processing',
                'description' => 'Event is being processed or scheduled',
                'background_color' => 'bg-yellow-500',
                'icon' => 'schedule',
                'status' => 'active',
                'sort_order' => 4,
            ],
            [
                'name' => 'cancelled',
                'description' => 'Event has been cancelled',
                'background_color' => 'bg-red-500',
                'icon' => 'cancel',
                'status' => 'active',
                'sort_order' => 5,
            ],
        ];

        foreach ($eventStatuses as $status) {
            EventStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
