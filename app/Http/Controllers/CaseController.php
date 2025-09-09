<?php

namespace App\Http\Controllers;

use App\Models\CourtCase;
use App\Models\CaseParty;
use App\Models\CasePartner;
use App\Models\Partner;
use App\Models\CaseType;
use App\Models\CaseStatus;
use App\Models\EventStatus;
use App\Models\Client;
use App\Models\FileType;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\WarrantToActPdfService;

class CaseController extends Controller
{
    public function index()
    {
        $cases = CourtCase::with([
            'parties', 
            'partners.partner',
            'caseType',
            'caseStatus'
        ])->orderBy('created_at', 'desc')->get();
        
        $caseStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        
        return view('case', compact('cases', 'caseStatuses'));
    }

    public function create()
    {
        $partners = Partner::where('status', 'active')->orderBy('firm_name')->get();
        $caseTypes = \App\Models\CaseType::active()->orderBy('description')->get();
        $caseStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        $clients = \App\Models\Client::orderBy('name')->get();
        $fileTypes = \App\Models\FileType::active()->orderBy('description')->get();
        // Fetch Category Status (for document status dropdown)
        $categoryStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        return view('case-create', compact('partners', 'caseTypes', 'caseStatuses', 'clients', 'fileTypes', 'categoryStatuses'));
    }

    public function show($id)
    {
        $case = CourtCase::with([
            'parties',
            'partners.partner',
            'caseType',
            'caseStatus',
            'createdBy',
            'files.fileType',
            'timeline.createdBy'
        ])->findOrFail($id);
        
        // Get system settings for date/time formatting
        $systemSettings = SystemSetting::getSystemSettings();
        
        // Get additional data for timeline event form
        $caseTypes = \App\Models\CaseType::active()->orderBy('description')->get();
        $eventStatuses = EventStatus::active()->ordered()->get();
        $allCases = CourtCase::orderBy('case_number')->pluck('case_number', 'id');
        $courtLocations = CourtCase::distinct()->pluck('court_location')->filter();

        return view('case-view', compact('case', 'systemSettings', 'caseTypes', 'eventStatuses', 'allCases', 'courtLocations'));
    }

    public function edit($id)
    {
        $case = CourtCase::with([
            'parties',
            'partners.partner',
            'caseType',
            'caseStatus',
            'files.fileType',
            'taxInvoices',
            'receipts',
        ])->findOrFail($id);

        $partners = Partner::where('status', 'active')->orderBy('firm_name')->get();
        $caseTypes = \App\Models\CaseType::active()->orderBy('description')->get();
        $caseStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        $clients = \App\Models\Client::orderBy('name')->get();
        $fileTypes = \App\Models\FileType::active()->orderBy('description')->get();
        $categoryStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();

        // Render the full create view in edit mode so all form scripts (section->documents) run properly
        return view('case-create', compact('case', 'partners', 'caseTypes', 'caseStatuses', 'clients', 'fileTypes', 'categoryStatuses'));
    }

    public function store(Request $request)
    {
        // Validate request with conditional rules
        $rules = [
            'case_ref' => 'required|string|max:255',
            'person_in_charge' => 'required|string|max:255',
            'court_ref' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'initiating_document' => 'nullable|string|max:255',
            'case_type_id' => 'required|exists:case_types,id',
            'case_status_id' => 'required|exists:case_statuses,id',
            'judge_name' => 'nullable|string|max:255',
            'court_name' => 'nullable|string|max:255',
            'claim_amount' => 'nullable|numeric|min:0',
            'case_description' => 'nullable|string',
            'priority_level' => 'nullable|in:low,medium,high,urgent',
            // Conveyancing specific fields
            'name_of_property' => 'nullable|string|max:500',
            'others_document' => 'nullable|string|max:500',
        ];

        // Conditional validation for case_title based on section
        if ($request->section === 'conveyancing') {
            // For conveyancing, case_title is not required (name_of_property is used instead)
            $rules['case_title'] = 'nullable|string|max:500';
            $rules['name_of_property'] = 'required|string|max:500';
        } else {
            // For other sections, case_title is required
            $rules['case_title'] = 'required|string|max:500';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();
            
            // Determine title based on section
            $title = $request->case_title;
            if ($request->section === 'conveyancing' && $request->name_of_property) {
                $title = $request->name_of_property;
            }

            // Create the case
            $case = CourtCase::create([
                'case_number' => $request->case_ref,
                'title' => $title,
                'description' => $request->case_description,
                'case_type_id' => $request->case_type_id,
                'case_status_id' => $request->case_status_id,
                'priority_level' => $request->priority_level ?? 'medium',
                'judge_name' => $request->judge_name, // Fixed: was case_judge_name
                'court_location' => $request->court_name,
                'claim_amount' => $request->claim_amount,
                'created_by' => auth()->id(),
                // New persisted fields
                'person_in_charge' => $request->person_in_charge,
                'court_ref' => $request->court_ref,
                'jurisdiction' => $request->jurisdiction,
                'section' => $request->section,
                'initiating_document' => $request->initiating_document,
                // Conveyancing specific fields
                'name_of_property' => $request->name_of_property,
                'others_document' => $request->others_document,
            ]);

            // Add plaintiffs (applicants)
            if ($request->has('plaintiffs')) {
                foreach ($request->plaintiffs as $plaintiff) {
                    if (!empty($plaintiff['ic'])) {
                        // Check if client exists
                        $existingClient = \App\Models\Client::where('ic_passport', $plaintiff['ic'])->first();
                        
                        if ($existingClient) {
                            // Use existing client
                            CaseParty::create([
                                'case_id' => $case->id,
                                'party_type' => 'plaintiff',
                                'name' => $existingClient->name,
                                'ic_passport' => $existingClient->ic_passport,
                                'phone' => $existingClient->phone,
                                'email' => $existingClient->email,
                                'username' => $existingClient->username ?? null,
                                'password' => null,
                                'gender' => $existingClient->gender,
                                'nationality' => $existingClient->nationality,
                            ]);
                        } else {
                            // Create new client without user account
                            $newClient = \App\Models\Client::create([
                                'name' => $plaintiff['name'],
                                'ic_passport' => $plaintiff['ic'],
                                'phone' => $plaintiff['phone'],
                                'email' => $plaintiff['email'],
                                'gender' => $plaintiff['gender'],
                                'nationality' => $plaintiff['nationality'],
                                'party_type' => 'applicant',
                                'status' => 'active',
                            ]);
                            
                            CaseParty::create([
                                'case_id' => $case->id,
                                'party_type' => 'plaintiff',
                                'name' => $newClient->name,
                                'ic_passport' => $newClient->ic_passport,
                                'phone' => $newClient->phone,
                                'email' => $newClient->email,
                                'username' => null,
                                'password' => null,
                                'gender' => $newClient->gender,
                                'nationality' => $newClient->nationality,
                            ]);
                        }
                    }
                }
            }

            // Add defendants (respondents)
            if ($request->has('defendants')) {
                foreach ($request->defendants as $defendant) {
                    if (!empty($defendant['ic'])) {
                        // Check if client exists
                        $existingClient = \App\Models\Client::where('ic_passport', $defendant['ic'])->first();
                        
                        if ($existingClient) {
                            // Use existing client
                            CaseParty::create([
                                'case_id' => $case->id,
                                'party_type' => 'defendant',
                                'name' => $existingClient->name,
                                'ic_passport' => $existingClient->ic_passport,
                                'phone' => $existingClient->phone,
                                'email' => $existingClient->email,
                                'username' => $existingClient->username ?? null,
                                'password' => null,
                                'gender' => $existingClient->gender,
                                'nationality' => $existingClient->nationality,
                            ]);
                        } else {
                            // Create new client without user account
                            $newClient = \App\Models\Client::create([
                                'name' => $defendant['name'],
                                'ic_passport' => $defendant['ic'],
                                'phone' => $defendant['phone'],
                                'email' => $defendant['email'],
                                'gender' => $defendant['gender'],
                                'nationality' => $defendant['nationality'],
                                'party_type' => 'respondent',
                                'status' => 'active',
                            ]);
                            
                            CaseParty::create([
                                'case_id' => $case->id,
                                'party_type' => 'defendant',
                                'name' => $newClient->name,
                                'ic_passport' => $newClient->ic_passport,
                                'phone' => $newClient->phone,
                                'email' => $newClient->email,
                                'username' => null,
                                'password' => null,
                                'gender' => $newClient->gender,
                                'nationality' => $newClient->nationality,
                            ]);
                        }
                    }
                }
            }

            // Add partners
            if ($request->has('partners')) {
                foreach ($request->partners as $partner) {
                    if (!empty($partner['partner_id'])) {
                        CasePartner::create([
                            'case_id' => $case->id,
                            'partner_id' => $partner['partner_id'],
                            'role' => $partner['role'] ?? '',
                        ]);
                    }
                }
            }

            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $document) {
                    if ($document && $document->isValid()) {
                        // Generate unique filename
                        $filename = time() . '_' . $index . '_' . $document->getClientOriginalName();
                        
                        // Store file in storage/app/public/documents
                        $path = $document->storeAs('documents', $filename, 'public');
                        
                        // Create document record
                        \App\Models\CaseFile::create([
                            'case_ref' => $case->case_number,
                            'file_name' => $document->getClientOriginalName(),
                            'file_path' => $path,
                            'category_id' => $request->input("documents.{$index}.type"),
                            'file_size' => $document->getSize(),
                            'mime_type' => $document->getMimeType(),
                            'description' => 'Case document: ' . $request->input("documents.{$index}.filed_by"),
                            'status' => 'IN',
                            'taken_by' => $request->input("documents.{$index}.filed_by"),
                            'purpose' => 'Case document',
                            'expected_return' => null,
                            'actual_return' => null,
                            'rack_location' => 'Digital Storage',
                        ]);
                    }
                }
            }

            // Auto-generate Warrant To Act PDF based on user selection
            $generateWta = $request->input('generate_wta', '1') === '1';

            if ($generateWta) {
                try {
                    $running = now()->format('dmYHis');
                    $fileName = 'WARRANT_TO_ACT_' . $running . '.pdf';
                    $template = storage_path('app/private/pdf-templates/warrant_to_act.pdf');
                    if (file_exists($template)) {
                        $selectedParty = $this->getSelectedPartyForWta($case, $request);

                        if ($selectedParty) {
                            $addressLines = [];
                            // Prefer CaseParty address if available
                            if (!empty($selectedParty?->address)) {
                                $addressLines = preg_split('/\r?\n/', $selectedParty->address);
                            } else {
                                // Try fetch from Client by IC and use its addresses
                                $client = \App\Models\Client::where('ic_passport', $selectedParty?->ic_passport)->with('addresses')->first();
                        if ($client) {
                            if ($client->addresses && $client->addresses->count()) {
                                $addr = $client->addresses->first();
                                // Build 3 rows in uppercase:
                                // (address_line1 + address_line2 + address_line3), (postcode, city), (state, country)
                                $al1 = $addr->address_line1 ?? $addr->line1 ?? $addr->address1 ?? $addr->address_1 ?? '';
                                $al2 = $addr->address_line2 ?? $addr->line2 ?? $addr->address2 ?? $addr->address_2 ?? '';
                                $al3 = $addr->address_line3 ?? $addr->line3 ?? $addr->address3 ?? $addr->address_3 ?? '';

                                $firstLineParts = array_values(array_filter([trim($al1), trim($al2), trim($al3)], function ($v) { return $v !== ''; }));
                                $line123 = strtoupper(implode(', ', $firstLineParts));

                                $postcode = trim($addr->postcode ?? '');
                                $city = trim($addr->city ?? '');
                                $linePC = strtoupper(trim(($postcode !== '' ? $postcode : '') . ($postcode !== '' && $city !== '' ? ', ' : '') . ($city !== '' ? $city : '')));

                                $state = trim($addr->state ?? '');
                                $country = trim($addr->country ?? '');
                                $lineSC = strtoupper(trim(($state !== '' ? $state : '') . ($state !== '' && $country !== '' ? ', ' : '') . ($country !== '' ? $country : '')));

                                $addressLines = array_values(array_filter([$line123, $linePC, $lineSC]));
                            } else {
                                $addressLines = array_filter([
                                    $client->address ?? null,
                                ]);
                            }
                        }
                    }
                            $data = [
                                'name' => $selectedParty->name ?? '',
                                'nric' => $selectedParty->ic_passport ?? '',
                                'address_lines' => array_filter($addressLines),
                                'name_sign' => $selectedParty->name ?? '',
                                'nric_sign' => $selectedParty->ic_passport ?? '',
                                'date' => now()->format('d/m/Y'),
                            ];

                    $relativeOut = 'documents/'.$fileName;
                    app(WarrantToActPdfService::class)->generate($template, $relativeOut, $data);

                    // Create CaseFile record
                    $path = $relativeOut;
                    $size = Storage::disk('public')->size($path);
                    \App\Models\CaseFile::create([
                        'case_ref' => $case->case_number,
                        'file_name' => $fileName,
                        'file_path' => $path,
                        'category_id' => optional(\App\Models\FileType::where('code','WTA')->first())->id ?? optional(\App\Models\FileType::first())->id,
                        'file_size' => $size,
                        'mime_type' => 'application/pdf',
                        'description' => 'Warrant To Act',
                        'status' => 'IN',
                        'taken_by' => optional(\App\Models\Partner::first())->firm_name ?? 'Firm',
                        'purpose' => 'Warrant to Act',
                    ]);
                        }
                    }
                } catch (\Throwable $e) {
                    // Failed generating WTA PDF - continue without error
                }
            }

            // Log case creation
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'case_number' => $case->case_number,
                    'case_type' => $case->caseType->description ?? 'N/A',
                    'claim_amount' => $case->claim_amount
                ])
                ->log("Case {$case->case_number} created");

            DB::commit();

            return redirect()->route('case.index')->with('success', 'Case created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create case: ' . $e->getMessage()]);
        }
    }

    public function changeStatus($id, Request $request)
    {
        try {
            $case = CourtCase::findOrFail($id);
            $oldStatus = $case->caseStatus->name ?? 'Unknown';

            $case->update([
                'case_status_id' => $request->status_id
            ]);

            $newStatus = $case->fresh()->caseStatus->name ?? 'Unknown';

            // Log status change
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ])
                ->log("Case {$case->case_number} status changed from {$oldStatus} to {$newStatus}");

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addTimelineEvent($id, Request $request)
    {
        try {
            $case = CourtCase::findOrFail($id);
            
            // Validate request
            $request->validate([
                'title' => 'required|string|max:255',
                'event_type' => 'required|string|max:100',
                'event_date' => 'required|date',
                'event_time' => 'nullable|date_format:H:i',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'status' => 'required|in:completed,active,cancelled',
                // Metadata fields
                'priority' => 'nullable|string|in:high,medium,low',
                'case_type' => 'nullable|string|max:100',
                'case_number' => 'nullable|string|max:100',
                'judge_name' => 'nullable|string|max:255',
                'filing_date' => 'nullable|date',
                'court_location' => 'nullable|string|max:255',
                'hearing_type' => 'nullable|string|max:100',
                'parties_notified' => 'nullable|integer|min:0',
                'custom_metadata' => 'nullable|array',
                // Calendar fields
                'add_to_calendar' => 'nullable|boolean',
                'reminder' => 'nullable|integer|min:0',
            ]);

            // Combine date and time
            $eventDateTime = $request->event_date;
            if ($request->event_time) {
                $eventDateTime .= ' ' . $request->event_time;
            }

            // Prepare metadata array
            $metadata = [
                'location' => $request->location,
                'created_by_user' => auth()->user()->name ?? 'System',
            ];

            // Add specific metadata fields if provided
            if ($request->priority) {
                $metadata['priority'] = $request->priority;
            }
            if ($request->case_type) {
                $metadata['case_type'] = $request->case_type;
            }
            if ($request->case_number) {
                $metadata['case_number'] = $request->case_number;
            }
            if ($request->judge_name) {
                $metadata['judge_name'] = $request->judge_name;
            }
            if ($request->filing_date) {
                $metadata['filing_date'] = $request->filing_date;
            }
            if ($request->court_location) {
                $metadata['court_location'] = $request->court_location;
            }
            if ($request->hearing_type) {
                $metadata['hearing_type'] = $request->hearing_type;
            }
            if ($request->parties_notified) {
                $metadata['parties_notified'] = $request->input('parties_notified');
            }

            // Add calendar-specific metadata
            if ($request->add_to_calendar) {
                $metadata['add_to_calendar'] = true;
                $metadata['reminder'] = $request->reminder;
            }

            if ($request->custom_metadata) {
                // Handle custom metadata key-value pairs
                foreach ($request->custom_metadata as $key => $value) {
                    if (!empty($key) && !empty($value)) {
                        $metadata[$key] = $value;
                    }
                }
            }

            // Create timeline event
            $timelineEvent = \App\Models\CaseTimeline::create([
                'case_id' => $case->id,
                'event_type' => $request->event_type,
                'title' => $request->title,
                'description' => $request->description ?? '',
                'status' => $request->status, // Use the status from the request
                'event_date' => $eventDateTime,
                'metadata' => $metadata,
                'created_by' => auth()->id(),
            ]);

            // Create calendar event if requested
            if ($request->add_to_calendar) {
                try {
                    // Use court location from case, fallback to metadata location
                    $calendarLocation = $case->court_location ?? $request->location ?? '';

                    \App\Models\CalendarEvent::create([
                        'title' => $request->title,
                        'description' => $request->description ?? '',
                        'start_date' => $eventDateTime,
                        'end_date' => $eventDateTime, // Same as start for now
                        'location' => $calendarLocation,
                        'category' => $request->event_type, // Use event type as category
                        'reminder_minutes' => $request->reminder,
                        'case_id' => $case->id,
                        'timeline_event_id' => $timelineEvent->id,
                        'created_by' => auth()->id(),
                        'status' => 'active',
                    ]);
                } catch (\Exception $e) {
                    // Calendar creation failed, but timeline event was successful
                    // Log error but don't fail the entire operation
                    \Log::warning('Failed to create calendar event: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Timeline event added successfully',
                'event' => $timelineEvent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add timeline event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTimelineEvent(Request $request, $id, $timelineId)
    {
        try {
            $case = CourtCase::findOrFail($id);
            $timelineEvent = \App\Models\CaseTimeline::where('case_id', $case->id)->findOrFail($timelineId);

            // Validate request
            $request->validate([
                'title' => 'required|string|max:255',
                'event_type' => 'required|string',
                'event_date' => 'required|date',
                'event_time' => 'nullable|string',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
                'status' => 'required|string',
                // Calendar fields
                'add_to_calendar' => 'nullable|boolean',
                'reminder' => 'nullable|integer|min:0',
            ]);

            // Combine date and time
            $eventDateTime = $request->event_date;
            if ($request->event_time) {
                $eventDateTime = $request->event_date . ' ' . $request->event_time;
            }

            // Prepare metadata
            $metadata = [];
            $metadataFields = [
                'priority', 'case_type', 'case_number', 'judge_name',
                'filing_date', 'court_location', 'hearing_type', 'parties_notified'
            ];

            foreach ($metadataFields as $field) {
                if ($request->has($field) && !empty($request->$field)) {
                    $metadata[$field] = $request->$field;
                }
            }

            // Add calendar-specific metadata
            if ($request->add_to_calendar) {
                $metadata['add_to_calendar'] = true;
                $metadata['reminder'] = $request->reminder;
            }

            // Handle custom metadata
            if ($request->has('custom_metadata')) {
                foreach ($request->custom_metadata as $key => $value) {
                    if (!empty($key) && !empty($value)) {
                        $metadata[$key] = $value;
                    }
                }
            }

            // Update timeline event
            $timelineEvent->update([
                'event_type' => $request->event_type,
                'title' => $request->title,
                'description' => $request->description ?? '',
                'status' => $request->status,
                'event_date' => $eventDateTime,
                'location' => $request->location ?? '',
                'metadata' => $metadata,
                'updated_by' => auth()->id(),
            ]);

            // Handle calendar event update/creation/deletion
            $existingCalendarEvent = \App\Models\CalendarEvent::where('timeline_event_id', $timelineEvent->id)->first();

            if ($request->add_to_calendar) {
                // Use court location from case, fallback to metadata location
                $calendarLocation = $case->court_location ?? $request->location ?? '';

                $calendarData = [
                    'title' => $request->title,
                    'description' => $request->description ?? '',
                    'start_date' => $eventDateTime,
                    'end_date' => $eventDateTime, // Same as start for now
                    'location' => $calendarLocation,
                    'category' => $request->event_type, // Use event type as category
                    'reminder_minutes' => $request->reminder,
                    'case_id' => $case->id,
                    'timeline_event_id' => $timelineEvent->id,
                    'created_by' => auth()->id(),
                    'status' => 'active',
                ];

                if ($existingCalendarEvent) {
                    // Update existing calendar event
                    $existingCalendarEvent->update($calendarData);
                } else {
                    // Create new calendar event
                    try {
                        \App\Models\CalendarEvent::create($calendarData);
                    } catch (\Exception $e) {
                        // Calendar creation failed, but timeline event was successful
                        \Log::warning('Failed to create calendar event during timeline update: ' . $e->getMessage());
                    }
                }
            } else {
                // Remove calendar event if exists and add_to_calendar is false
                if ($existingCalendarEvent) {
                    $existingCalendarEvent->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Timeline event updated successfully',
                'event' => $timelineEvent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update timeline event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTimelineEvent($id, $timelineId)
    {
        try {
            $case = CourtCase::findOrFail($id);
            $timelineEvent = \App\Models\CaseTimeline::where('case_id', $case->id)->findOrFail($timelineId);

            // Delete associated calendar event if exists
            $calendarEvent = \App\Models\CalendarEvent::where('timeline_event_id', $timelineEvent->id)->first();
            if ($calendarEvent) {
                $calendarEvent->delete();
            }

            // Log timeline event deletion
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'timeline_event_id' => $timelineEvent->id,
                    'event_title' => $timelineEvent->title,
                    'event_type' => $timelineEvent->event_type,
                    'event_date' => $timelineEvent->event_date
                ])
                ->log("Timeline event '{$timelineEvent->title}' deleted from case {$case->case_number}");

            // Delete timeline event
            $timelineEvent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Timeline event deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete timeline event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $case = CourtCase::with(['parties', 'partners', 'files', 'timeline'])->findOrFail($id);

            DB::beginTransaction();

            // Delete related records
            if ($case->timeline) {
                foreach ($case->timeline as $event) {
                    $event->delete();
                }
            }

            if ($case->files) {
                foreach ($case->files as $file) {
                    // Remove file from storage if exists
                    if (!empty($file->file_path) && Storage::disk('public')->exists($file->file_path)) {
                        Storage::disk('public')->delete($file->file_path);
                    }
                    $file->delete();
                }
            }

            if ($case->parties) {
                foreach ($case->parties as $party) {
                    $party->delete();
                }
            }

            if ($case->partners) {
                foreach ($case->partners as $partner) {
                    $partner->delete();
                }
            }

            // Log case deletion before deleting
            activity()
                ->performedOn($case)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'case_number' => $case->case_number,
                    'title' => $case->title,
                    'case_type' => $case->caseType->description ?? 'N/A'
                ])
                ->log("Case {$case->case_number} deleted");

            $case->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update($id, Request $request)
    {
        $case = CourtCase::findOrFail($id);

        // Validate request with conditional rules (same as store method)
        $rules = [
            'case_ref' => 'required|string|max:255',
            'person_in_charge' => 'nullable|string|max:255',
            'court_ref' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'initiating_document' => 'nullable|string|max:255',
            'case_type_id' => 'required|exists:case_types,id',
            'case_status_id' => 'required|exists:case_statuses,id',
            'judge_name' => 'nullable|string|max:255',
            'court_name' => 'nullable|string|max:255',
            'claim_amount' => 'nullable|numeric|min:0',
            'case_description' => 'nullable|string',
            'priority_level' => 'nullable|in:low,medium,high,urgent',
            // Conveyancing specific fields
            'name_of_property' => 'nullable|string|max:500',
            'others_document' => 'nullable|string|max:500',
        ];

        // Conditional validation for case_title based on section
        if ($request->section === 'conveyancing') {
            // For conveyancing, case_title is not required (name_of_property is used instead)
            $rules['case_title'] = 'nullable|string|max:500';
            $rules['name_of_property'] = 'required|string|max:500';
        } else {
            // For other sections, case_title is required
            $rules['case_title'] = 'required|string|max:500';
        }

        $request->validate($rules);

        // Determine title based on section (same logic as store method)
        $title = $request->case_title;
        if ($request->section === 'conveyancing' && $request->name_of_property) {
            $title = $request->name_of_property;
        }

        try {
            DB::beginTransaction();

            $case->update([
                'case_number' => $request->case_ref,
                'title' => $title,
                'description' => $request->case_description,
                'case_type_id' => $request->case_type_id,
                'case_status_id' => $request->case_status_id,
                'priority_level' => $request->priority_level ?? $case->priority_level,
                'judge_name' => $request->judge_name,
                'court_location' => $request->court_name,
                'claim_amount' => $request->claim_amount,
                'person_in_charge' => $request->person_in_charge,
                'court_ref' => $request->court_ref,
                'jurisdiction' => $request->jurisdiction,
                'section' => $request->section,
                'initiating_document' => $request->initiating_document,
                // Conveyancing specific fields
                'name_of_property' => $request->name_of_property,
                'others_document' => $request->others_document,
            ]);

            // Update parties (plaintiffs and defendants)
            // Delete existing parties first
            $case->parties()->delete();

            // Add updated plaintiffs
            if ($request->has('plaintiffs')) {
                foreach ($request->plaintiffs as $plaintiff) {
                    if (!empty($plaintiff['ic'])) {
                        \App\Models\CaseParty::create([
                            'case_id' => $case->id,
                            'party_type' => 'plaintiff',
                            'name' => $plaintiff['name'],
                            'ic_passport' => $plaintiff['ic'],
                            'phone' => $plaintiff['phone'] ?? '',
                            'email' => $plaintiff['email'] ?? '',
                            'gender' => $plaintiff['gender'] ?? '',
                            'nationality' => $plaintiff['nationality'] ?? '',
                        ]);
                    }
                }
            }

            // Add updated defendants
            if ($request->has('defendants')) {
                foreach ($request->defendants as $defendant) {
                    if (!empty($defendant['ic'])) {
                        \App\Models\CaseParty::create([
                            'case_id' => $case->id,
                            'party_type' => 'defendant',
                            'name' => $defendant['name'],
                            'ic_passport' => $defendant['ic'],
                            'phone' => $defendant['phone'] ?? '',
                            'email' => $defendant['email'] ?? '',
                            'gender' => $defendant['gender'] ?? '',
                            'nationality' => $defendant['nationality'] ?? '',
                        ]);
                    }
                }
            }

            // Handle Warrant to Act PDF based on user selection
            $generateWta = $request->input('generate_wta', '0') === '1';

            // Always clean up existing WTA files first
            $existingWtaFiles = \App\Models\CaseFile::where('case_ref', $case->case_number)
                ->where('description', 'LIKE', '%Warrant To Act%')
                ->get();

            foreach ($existingWtaFiles as $wtaFile) {
                // Delete physical file
                if (Storage::disk('public')->exists($wtaFile->file_path)) {
                    Storage::disk('public')->delete($wtaFile->file_path);
                }
                // Delete database record
                $wtaFile->delete();
            }

            if ($generateWta) {
                try {
                    $running = now()->format('dmYHis');
                    $fileName = 'WARRANT_TO_ACT_' . $running . '.pdf';
                    $template = storage_path('app/private/pdf-templates/warrant_to_act.pdf');
                    if (file_exists($template)) {
                        $selectedParty = $this->getSelectedPartyForWta($case, $request);

                        if ($selectedParty) {
                            $addressLines = [];
                            // Prefer CaseParty address if available
                            if (!empty($selectedParty?->address)) {
                                $addressLines = preg_split('/\r?\n/', $selectedParty->address);
                            } else {
                                // Try fetch from Client by IC and use its addresses
                                $client = \App\Models\Client::where('ic_passport', $selectedParty?->ic_passport)->with('addresses')->first();
                                if ($client) {
                                    if ($client->addresses && $client->addresses->count()) {
                                        $addr = $client->addresses->first();
                                        // Build 3 rows in uppercase:
                                        // (address_line1 + address_line2 + address_line3), (postcode, city), (state, country)
                                        $al1 = $addr->address_line1 ?? $addr->line1 ?? $addr->address1 ?? $addr->address_1 ?? '';
                                        $al2 = $addr->address_line2 ?? $addr->line2 ?? $addr->address2 ?? $addr->address_2 ?? '';
                                        $al3 = $addr->address_line3 ?? $addr->line3 ?? $addr->address3 ?? $addr->address_3 ?? '';

                                        $firstLineParts = array_values(array_filter([trim($al1), trim($al2), trim($al3)], function ($v) { return $v !== ''; }));
                                        $line123 = strtoupper(implode(', ', $firstLineParts));

                                        $postcode = trim($addr->postcode ?? '');
                                        $city = trim($addr->city ?? '');
                                        $linePC = strtoupper(trim(($postcode !== '' ? $postcode : '') . ($postcode !== '' && $city !== '' ? ', ' : '') . ($city !== '' ? $city : '')));

                                        $state = trim($addr->state ?? '');
                                        $country = trim($addr->country ?? '');
                                        $lineSC = strtoupper(trim(($state !== '' ? $state : '') . ($state !== '' && $country !== '' ? ', ' : '') . ($country !== '' ? $country : '')));

                                        $addressLines = array_values(array_filter([$line123, $linePC, $lineSC]));
                                    } else {
                                        $addressLines = array_filter([
                                            $client->address ?? null,
                                        ]);
                                    }
                                }
                            }

                            $data = [
                                'name' => $selectedParty->name ?? '',
                                'nric' => $selectedParty->ic_passport ?? '',
                                'address_lines' => array_filter($addressLines),
                                'name_sign' => $selectedParty->name ?? '',
                                'nric_sign' => $selectedParty->ic_passport ?? '',
                                'date' => now()->format('d/m/Y'),
                            ];

                            $relativeOut = 'documents/'.$fileName;
                            app(WarrantToActPdfService::class)->generate($template, $relativeOut, $data);

                            // Create CaseFile record
                            $path = $relativeOut;
                            $size = Storage::disk('public')->size($path);
                            \App\Models\CaseFile::create([
                                'case_ref' => $case->case_number,
                                'file_name' => $fileName,
                                'file_path' => $path,
                                'category_id' => optional(\App\Models\FileType::where('code','WTA')->first())->id ?? optional(\App\Models\FileType::first())->id,
                                'file_size' => $size,
                                'mime_type' => 'application/pdf',
                                'description' => 'Warrant to Act',
                                'status' => 'IN',
                                'taken_by' => optional(\App\Models\Partner::first())->firm_name ?? 'Firm',
                                'purpose' => 'Warrant to Act',
                            ]);
                        }
                    }
                } catch (\Throwable $e) {
                    // Failed generating WTA PDF - continue without error
                }
            }

            DB::commit();

            return redirect()->route('case.show', $case->id)->with('success', 'Case updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update case: ' . $e->getMessage()]);
        }
    }

    /**
     * Get selected party for Warrant to Act based on user selection
     */
    private function getSelectedPartyForWta($case, $request)
    {
        $wtaPartyType = $request->input('wta_party_type', 'applicant');
        $wtaSpecificParty = $request->input('wta_specific_party');

        switch ($wtaPartyType) {
            case 'respondent':
                // Get first respondent/defendant
                return $case->parties->where('party_type', 'defendant')->first() ??
                       $case->parties->where('party_type', 'respondent')->first();

            case 'custom':
                // Get specific party based on selection
                if ($wtaSpecificParty) {
                    [$type, $index] = explode('_', $wtaSpecificParty);
                    $parties = $case->parties;

                    if ($type === 'applicant') {
                        $applicants = $parties->whereIn('party_type', ['plaintiff', 'applicant']);
                        return $applicants->skip($index)->first();
                    } elseif ($type === 'respondent') {
                        $respondents = $parties->whereIn('party_type', ['defendant', 'respondent']);
                        return $respondents->skip($index)->first();
                    }
                }
                // Fallback to first party if custom selection fails
                return $case->parties->first();

            case 'applicant':
            default:
                // Get first applicant/plaintiff (default behavior)
                return $case->parties->where('party_type', 'plaintiff')->first() ??
                       $case->parties->where('party_type', 'applicant')->first() ??
                       $case->parties->first();
        }
    }
}
