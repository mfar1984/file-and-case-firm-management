<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CaseTimeline;
use App\Models\CourtCase;
use App\Models\User;

class CaseTimelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing cases
        $cases = CourtCase::all();
        $users = User::all();
        
        if ($cases->isEmpty()) {
            $this->command->info('No cases found. Please create cases first.');
            return;
        }

        foreach ($cases as $case) {
            $createdAt = $case->created_at;
            $user = $users->random();
            
            // Create timeline events for each case
            $events = [
                [
                    'event_type' => 'case_created',
                    'title' => 'Case Created',
                    'description' => "Case registered in system and assigned case number {$case->case_number}. Initial documentation and case setup completed.",
                    'status' => 'completed',
                    'event_date' => $createdAt,
                    'metadata' => [
                        'case_number' => $case->case_number,
                        'case_type' => $case->caseType->description ?? 'Unknown',
                        'priority' => $case->priority_level ?? 'medium'
                    ]
                ],
                [
                    'event_type' => 'case_filed',
                    'title' => 'Case Filed',
                    'description' => "Case documentation submitted to court registry. Case assigned to " . ($case->judge_name ?? 'Judge') . " for review and scheduling.",
                    'status' => 'completed',
                    'event_date' => $createdAt->addMinutes(5),
                    'metadata' => [
                        'judge_name' => $case->judge_name,
                        'court_location' => $case->court_location,
                        'filing_date' => $createdAt->addMinutes(5)->format('Y-m-d H:i:s')
                    ]
                ],
                [
                    'event_type' => 'hearing_scheduled',
                    'title' => 'First Hearing Scheduled',
                    'description' => 'First hearing date scheduled. Court notification sent to all parties. Please confirm attendance and prepare required documents.',
                    'status' => 'active',
                    'event_date' => $createdAt->addHours(2),
                    'metadata' => [
                        'hearing_type' => 'First Hearing',
                        'notification_sent' => true,
                        'parties_notified' => $case->parties->count()
                    ]
                ],
                [
                    'event_type' => 'case_review',
                    'title' => 'Case Review',
                    'description' => 'Judge will review case documentation and evidence. Preliminary assessment and next steps will be determined.',
                    'status' => 'pending',
                    'event_date' => $createdAt->addDays(1),
                    'metadata' => [
                        'review_type' => 'Preliminary Review',
                        'estimated_duration' => '2-3 hours',
                        'reviewer' => $case->judge_name
                    ]
                ],
                [
                    'event_type' => 'evidence_submission',
                    'title' => 'Evidence Submission Deadline',
                    'description' => 'Deadline for submission of additional evidence and supporting documents. Late submissions may not be considered.',
                    'status' => 'pending',
                    'event_date' => $createdAt->addDays(7),
                    'metadata' => [
                        'deadline_type' => 'Evidence Submission',
                        'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'png'],
                        'max_file_size' => '10MB'
                    ]
                ],
                [
                    'event_type' => 'pre_trial_conference',
                    'title' => 'Pre-Trial Conference',
                    'description' => 'Pre-trial conference to discuss case management, settlement possibilities, and trial preparation.',
                    'status' => 'pending',
                    'event_date' => $createdAt->addDays(14),
                    'metadata' => [
                        'conference_type' => 'Pre-Trial',
                        'participants' => ['Judge', 'Plaintiff Counsel', 'Defendant Counsel'],
                        'duration' => '1-2 hours'
                    ]
                ]
            ];

            foreach ($events as $eventData) {
                CaseTimeline::create([
                    'case_id' => $case->id,
                    'event_type' => $eventData['event_type'],
                    'title' => $eventData['title'],
                    'description' => $eventData['description'],
                    'status' => $eventData['status'],
                    'event_date' => $eventData['event_date'],
                    'metadata' => $eventData['metadata'],
                    'created_by' => $user->id
                ]);
            }
        }

        $this->command->info('Case timeline events created successfully!');
    }
}
