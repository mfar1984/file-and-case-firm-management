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
use App\Models\Firm;
use App\Models\SectionType;
use App\Models\CaseInitiatingDocument;
use App\Models\SectionCustomField;
use App\Models\CaseCustomFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\WarrantToActPdfService;

class CaseController extends Controller
{
    public function index()
    {
        // Get cases with proper firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see all cases or filter by session firm
            if (session('current_firm_id')) {
                $cases = CourtCase::forFirm(session('current_firm_id'))
                    ->with(['parties', 'partners.partner', 'caseType', 'caseStatus'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $caseStatuses = \App\Models\CaseStatus::forFirm(session('current_firm_id'))
                    ->active()
                    ->orderBy('name')
                    ->get();
            } else {
                $cases = CourtCase::withoutFirmScope()
                    ->with(['parties', 'partners.partner', 'caseType', 'caseStatus'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $caseStatuses = \App\Models\CaseStatus::withoutFirmScope()
                    ->active()
                    ->orderBy('name')
                    ->get();
            }
        } else {
            // Regular users see only their firm's cases (HasFirmScope trait handles this)
            $cases = CourtCase::with(['parties', 'partners.partner', 'caseType', 'caseStatus'])
                ->orderBy('created_at', 'desc')
                ->get();
            $caseStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        }

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

        // Get all firms for Super Administrator, current firm for others
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        // Get dynamic section data with proper firm scope handling
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $sectionTypes = SectionType::forFirm(session('current_firm_id'))->active()->ordered()->get();
                $customFields = SectionCustomField::forFirm(session('current_firm_id'))->with('sectionType')->active()->ordered()->get();
            } else {
                $sectionTypes = SectionType::withoutFirmScope()->active()->ordered()->get();
                $customFields = SectionCustomField::withoutFirmScope()->with('sectionType')->active()->ordered()->get();
            }
        } else {
            $sectionTypes = SectionType::active()->ordered()->get();
            $customFields = SectionCustomField::with('sectionType')->active()->ordered()->get();
        }
        $initiatingDocuments = CaseInitiatingDocument::with('sectionType')->active()->ordered()->get();

        return view('case-create', compact('partners', 'caseTypes', 'caseStatuses', 'clients', 'fileTypes', 'categoryStatuses', 'firms', 'sectionTypes', 'initiatingDocuments', 'customFields'));
    }

    public function show($id)
    {
        // Find case with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $case = CourtCase::forFirm($currentFirmId)
                ->with(['parties', 'partners.partner', 'caseType', 'caseStatus', 'createdBy', 'files.fileType', 'timeline.createdBy'])
                ->findOrFail($id);
            // Get firm-scoped data for Super Admin
            $caseTypes = \App\Models\CaseType::forFirm($currentFirmId)->active()->orderBy('description')->get();
            $eventStatuses = EventStatus::forFirm($currentFirmId)->active()->ordered()->get();
            $allCases = CourtCase::forFirm($currentFirmId)->orderBy('case_number')->pluck('case_number', 'id');
            $courtLocations = CourtCase::forFirm($currentFirmId)->distinct()->pluck('court_location')->filter();
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can access any case (for system management)
            $case = CourtCase::withoutFirmScope()
                ->with(['parties', 'partners.partner', 'caseType', 'caseStatus', 'createdBy', 'files.fileType', 'timeline.createdBy'])
                ->findOrFail($id);
            // Get all data for Super Admin
            $caseTypes = \App\Models\CaseType::withoutFirmScope()->active()->orderBy('description')->get();
            $eventStatuses = EventStatus::withoutFirmScope()->active()->ordered()->get();
            $allCases = CourtCase::withoutFirmScope()->orderBy('case_number')->pluck('case_number', 'id');
            $courtLocations = CourtCase::withoutFirmScope()->distinct()->pluck('court_location')->filter();
        } else {
            // Regular users can only access cases from their firm (HasFirmScope trait handles this)
            $case = CourtCase::with(['parties', 'partners.partner', 'caseType', 'caseStatus', 'createdBy', 'files.fileType', 'timeline.createdBy'])
                ->findOrFail($id);
            // Get firm-scoped data for regular users
            $caseTypes = \App\Models\CaseType::active()->orderBy('description')->get();
            $eventStatuses = EventStatus::active()->ordered()->get();
            $allCases = CourtCase::orderBy('case_number')->pluck('case_number', 'id');
            $courtLocations = CourtCase::distinct()->pluck('court_location')->filter();
        }

        // Get system settings for date/time formatting
        $systemSettings = SystemSetting::getSystemSettings();

        // Get custom field values for this case with proper firm scope
        $customFieldValues = CaseCustomFieldValue::where('case_id', $case->id)->get();

        // Manually load custom fields with proper firm scope
        foreach ($customFieldValues as $value) {
            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                $value->customField = SectionCustomField::forFirm($currentFirmId)->with('sectionType')->find($value->custom_field_id);
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                $value->customField = SectionCustomField::withoutFirmScope()->with('sectionType')->find($value->custom_field_id);
            } else {
                $value->customField = SectionCustomField::with('sectionType')->find($value->custom_field_id);
            }
        }

        // Group by section type ID
        $customFieldValues = $customFieldValues->filter(function($value) {
            return $value->customField !== null;
        })->groupBy('customField.section_type_id');

        // Get section type for this case with proper firm scope
        $sectionType = null;
        if ($case->section) {
            // Case section is stored as ID, not code
            if (is_numeric($case->section)) {
                if ($user->hasRole('Super Administrator') && $currentFirmId) {
                    $sectionType = SectionType::forFirm($currentFirmId)->with('initiatingDocuments')->find($case->section);
                } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                    $sectionType = SectionType::withoutFirmScope()->with('initiatingDocuments')->find($case->section);
                } else {
                    $sectionType = SectionType::with('initiatingDocuments')->find($case->section);
                }
            } else {
                // Fallback for code-based lookup
                $sectionCode = $this->mapSectionToCode($case->section);
                if ($user->hasRole('Super Administrator') && $currentFirmId) {
                    $sectionType = SectionType::forFirm($currentFirmId)->with('initiatingDocuments')->where('code', $sectionCode)->first();
                } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                    $sectionType = SectionType::withoutFirmScope()->with('initiatingDocuments')->where('code', $sectionCode)->first();
                } else {
                    $sectionType = SectionType::with('initiatingDocuments')->where('code', $sectionCode)->first();
                }
            }
        }


        return view('case-view', compact('case', 'systemSettings', 'caseTypes', 'eventStatuses', 'allCases', 'courtLocations', 'customFieldValues', 'sectionType'));
    }

    public function edit($id)
    {
        // Find case with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $case = CourtCase::forFirm($currentFirmId)
                ->with([
                    'parties',
                    'partners.partner',
                    'caseType',
                    'caseStatus',
                    'files.fileType',
                    'taxInvoices',
                    'receipts',
                ])
                ->findOrFail($id);
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can edit any case (for system management)
            $case = CourtCase::withoutFirmScope()
                ->with([
                    'parties',
                    'partners.partner',
                    'caseType',
                    'caseStatus',
                    'files.fileType',
                    'taxInvoices',
                    'receipts',
                ])
                ->findOrFail($id);
        } else {
            // Regular users can only edit cases from their firm (HasFirmScope trait handles this)
            $case = CourtCase::with([
                'parties',
                'partners.partner',
                'caseType',
                'caseStatus',
                'files.fileType',
                'taxInvoices',
                'receipts',
            ])->findOrFail($id);
        }

        // Get partners with proper firm scope filtering
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            // Super Admin can see partners from session firm or all firms
            if (session('current_firm_id')) {
                $partners = Partner::forFirm(session('current_firm_id'))
                    ->where('status', 'active')
                    ->orderBy('firm_name')
                    ->get();
            } else {
                $partners = Partner::withoutFirmScope()
                    ->where('status', 'active')
                    ->orderBy('firm_name')
                    ->get();
            }
        } else {
            // Regular users see only their firm's partners (HasFirmScope trait handles this)
            $partners = Partner::where('status', 'active')->orderBy('firm_name')->get();
        }
        $caseTypes = \App\Models\CaseType::active()->orderBy('description')->get();
        $caseStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();
        $clients = \App\Models\Client::orderBy('name')->get();
        $fileTypes = \App\Models\FileType::active()->orderBy('description')->get();
        $categoryStatuses = \App\Models\CaseStatus::active()->orderBy('name')->get();

        // Get all firms for Super Administrator, current firm for others
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            $firms = Firm::orderBy('name')->get();
        } else {
            $firmId = session('current_firm_id') ?? $user->firm_id;
            $firms = Firm::where('id', $firmId)->get();
        }

        // Get dynamic section data with proper firm scope
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $sectionTypes = SectionType::forFirm(session('current_firm_id'))->active()->ordered()->get();
                $initiatingDocuments = CaseInitiatingDocument::forFirm(session('current_firm_id'))->with('sectionType')->active()->ordered()->get();
                $customFields = SectionCustomField::forFirm(session('current_firm_id'))->with('sectionType')->active()->ordered()->get();
            } else {
                $sectionTypes = SectionType::withoutFirmScope()->active()->ordered()->get();
                $initiatingDocuments = CaseInitiatingDocument::withoutFirmScope()->with('sectionType')->active()->ordered()->get();
                $customFields = SectionCustomField::withoutFirmScope()->with('sectionType')->active()->ordered()->get();
            }
        } else {
            $sectionTypes = SectionType::active()->ordered()->get();
            $initiatingDocuments = CaseInitiatingDocument::with('sectionType')->active()->ordered()->get();
            $customFields = SectionCustomField::with('sectionType')->active()->ordered()->get();
        }

        // Get existing custom field values for this case
        $existingCustomFieldValues = CaseCustomFieldValue::where('case_id', $case->id)
            ->with('customField')
            ->get()
            ->keyBy('custom_field_id');

        // Render the full create view in edit mode so all form scripts (section->documents) run properly
        return view('case-create', compact('case', 'partners', 'caseTypes', 'caseStatuses', 'clients', 'fileTypes', 'categoryStatuses', 'firms', 'sectionTypes', 'initiatingDocuments', 'customFields', 'existingCustomFieldValues'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $sessionFirmId = session('current_firm_id');
        $userFirmId = $user->firm_id;
        $isSuperAdmin = $user->hasRole('Super Administrator');

        // Validate reference data exists in current firm scope
        $caseType = null;
        $caseStatus = null;

        if ($isSuperAdmin && $sessionFirmId) {
            $caseType = \App\Models\CaseType::forFirm($sessionFirmId)->find($request->case_type_id);
            $caseStatus = \App\Models\CaseStatus::forFirm($sessionFirmId)->find($request->case_status_id);
        } elseif ($isSuperAdmin && !$sessionFirmId) {
            $caseType = \App\Models\CaseType::withoutFirmScope()->find($request->case_type_id);
            $caseStatus = \App\Models\CaseStatus::withoutFirmScope()->find($request->case_status_id);
        } else {
            $caseType = \App\Models\CaseType::find($request->case_type_id);
            $caseStatus = \App\Models\CaseStatus::find($request->case_status_id);
        }

        // Validate request with conditional rules - fix firm scope for exists validation
        // Note: case_ref is now auto-generated, so it's optional in request
        $rules = [
            'case_ref' => 'nullable|string|max:255', // Changed to nullable - will be auto-generated
            'person_in_charge' => 'required|string|max:255',
            'court_ref' => 'nullable|string|max:255',
            'jurisdiction' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'initiating_document' => 'nullable|string|max:255',
            'case_type_id' => 'required|integer',
            'case_status_id' => 'required|integer',
            'judge_name' => 'nullable|string|max:255',
            'court_name' => 'nullable|string|max:255',
            'claim_amount' => 'nullable|numeric|min:0',
            'case_description' => 'nullable|string',
            'priority_level' => 'nullable|in:low,medium,high,urgent',
            // Conveyancing specific fields
            'name_of_property' => 'nullable|string|max:500',
            'others_document' => 'nullable|string|max:500',
        ];

        // Dynamic validation based on section type
        $sectionType = null;
        if ($request->section) {
            $sectionType = SectionType::find($request->section);
        }

        // For dynamic forms, case title comes from custom fields, not a separate field
        // Remove case_title requirement since we use custom fields for title

        $request->validate($rules);

        // Custom validation for case_type_id and case_status_id with firm scope
        if (!$caseType) {
            return back()->withInput()->withErrors(['case_type_id' => 'The selected case type is invalid or not accessible.']);
        }

        if (!$caseStatus) {
            return back()->withInput()->withErrors(['case_status_id' => 'The selected case status is invalid or not accessible.']);
        }

        try {
            DB::beginTransaction();
            
            // Auto-assign firm based on session context
            $user = auth()->user();
            $firmId = session('current_firm_id') ?? $user->firm_id;

            // Generate case number with new format: YEAR-SECTIONCODE-RUNNINGNUMBER-CLIENTABBR
            // Example: 2025-CA-1-MFBAR
            $caseNumber = $request->case_ref; // Use provided case_ref if exists

            if (!$caseNumber) {
                // Get first plaintiff/applicant name for abbreviation
                $firstClientName = null;
                if ($request->has('plaintiffs') && is_array($request->plaintiffs)) {
                    foreach ($request->plaintiffs as $plaintiff) {
                        if (!empty($plaintiff['name'])) {
                            $firstClientName = $plaintiff['name'];
                            break;
                        }
                    }
                }

                // Generate case number with section and client info
                $caseNumber = CourtCase::generateCaseNumber(
                    $request->section,      // section_type_id
                    $firstClientName,       // client name for abbreviation
                    $firmId                 // firm_id
                );
            }

            // Determine title from custom fields or fallback
            $title = 'Case ' . $caseNumber; // Use case number as default title

            // Look for "Case Title" custom field
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'custom_field_') && !empty($value)) {
                    $fieldId = str_replace('custom_field_', '', $key);
                    $customField = \App\Models\SectionCustomField::find($fieldId);
                    if ($customField && strtolower($customField->field_name) === 'case title') {
                        $title = $value;
                        break;
                    }
                }
            }

            // Create the case (case_number will be auto-generated if null)
            try {
                $case = CourtCase::create([
                'case_number' => $caseNumber, // Will be auto-generated by boot method if null
                'title' => $title,
                'description' => $request->case_description,
                'case_type_id' => $request->case_type_id,
                'case_status_id' => $request->case_status_id,
                'priority_level' => $request->priority_level ?? 'medium',
                'judge_name' => $request->judge_name, // Fixed: was case_judge_name
                'court_location' => $request->court_name,
                'claim_amount' => $request->claim_amount,
                'created_by' => auth()->id(),
                'firm_id' => $firmId,
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



            } catch (\Exception $e) {
                throw $e; // Re-throw to be caught by outer try-catch
            }

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
                                'firm_id' => $case->firm_id,
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
                                'firm_id' => $case->firm_id,
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
                                'firm_id' => $case->firm_id,
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
                                'firm_id' => $case->firm_id,
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
                        
                        // Create document record with firm context
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
                            'firm_id' => $case->firm_id, // Use case's firm_id
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

            // Handle custom field values
            $this->handleCustomFieldValues($case, $request);

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
            // Find case with firm scope validation
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                // Super Admin with firm context - respect firm scope
                $case = CourtCase::forFirm($currentFirmId)->findOrFail($id);
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                // Super Admin without firm context - can change status for any case (for system management)
                $case = CourtCase::withoutFirmScope()->findOrFail($id);
            } else {
                // Regular users can only change status for cases from their firm (HasFirmScope trait handles this)
                $case = CourtCase::findOrFail($id);
            }

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
            // Find case with firm scope validation
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                // Super Admin with firm context - respect firm scope
                $case = CourtCase::forFirm($currentFirmId)->findOrFail($id);
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                // Super Admin without firm context - can add timeline to any case (for system management)
                $case = CourtCase::withoutFirmScope()->findOrFail($id);
            } else {
                // Regular users can only add timeline to cases from their firm (HasFirmScope trait handles this)
                $case = CourtCase::findOrFail($id);
            }

            // Validate request
            $request->validate([
                'title' => 'required|string|max:255',
                'event_type' => 'required|string|max:100',
                'event_date' => 'required|date',
                'event_time' => 'nullable|date_format:H:i',
                'description' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'status' => 'required|string',
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
                'firm_id' => $case->firm_id,
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
            // Find case with firm scope validation
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                // Super Admin with firm context - respect firm scope
                $case = CourtCase::forFirm($currentFirmId)->findOrFail($id);
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                // Super Admin without firm context - can update timeline for any case (for system management)
                $case = CourtCase::withoutFirmScope()->findOrFail($id);
            } else {
                // Regular users can only update timeline for cases from their firm (HasFirmScope trait handles this)
                $case = CourtCase::findOrFail($id);
            }

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
            // Find case with firm scope validation
            $user = auth()->user();

            if ($user->hasRole('Super Administrator')) {
                // Super Admin can delete timeline for any case
                $case = CourtCase::withoutFirmScope()->findOrFail($id);
            } else {
                // Regular users can only delete timeline for cases from their firm (HasFirmScope trait handles this)
                $case = CourtCase::findOrFail($id);
            }

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
            // Find case with firm scope validation
            $user = auth()->user();
            $currentFirmId = session('current_firm_id');

            if ($user->hasRole('Super Administrator') && $currentFirmId) {
                // Super Admin with firm context - respect firm scope
                $case = CourtCase::forFirm($currentFirmId)
                    ->with(['parties', 'partners', 'files', 'timeline'])
                    ->findOrFail($id);
            } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
                // Super Admin without firm context - can delete any case (for system management)
                $case = CourtCase::withoutFirmScope()
                    ->with(['parties', 'partners', 'files', 'timeline'])
                    ->findOrFail($id);
            } else {
                // Regular users can only delete cases from their firm (HasFirmScope trait handles this)
                $case = CourtCase::with(['parties', 'partners', 'files', 'timeline'])->findOrFail($id);
            }

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
        // Find case with firm scope validation
        $user = auth()->user();
        $currentFirmId = session('current_firm_id');

        if ($user->hasRole('Super Administrator') && $currentFirmId) {
            // Super Admin with firm context - respect firm scope
            $case = CourtCase::forFirm($currentFirmId)->findOrFail($id);
        } elseif ($user->hasRole('Super Administrator') && !$currentFirmId) {
            // Super Admin without firm context - can update any case (for system management)
            $case = CourtCase::withoutFirmScope()->findOrFail($id);
        } else {
            // Regular users can only update cases from their firm (HasFirmScope trait handles this)
            $case = CourtCase::findOrFail($id);
        }
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

        // Dynamic validation based on section type (same as store method)
        $sectionType = null;
        if ($request->section) {
            $sectionType = SectionType::find($request->section);
        }

        // For update method, case_title is not required since form doesn't have this field
        // Title is generated automatically from case_ref or custom fields

        $request->validate($rules);

        // Determine title from custom fields or fallback (same logic as store method)
        $title = 'Case ' . $request->case_ref; // Default fallback

        // Look for "Case Title" custom field
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'custom_field_') && !empty($value)) {
                $fieldId = str_replace('custom_field_', '', $key);
                $customField = \App\Models\SectionCustomField::find($fieldId);
                if ($customField && strtolower($customField->field_name) === 'case title') {
                    $title = $value;
                    break;
                }
            }
        }

        // For conveyancing, use name_of_property if available
        if ($sectionType && strtolower($sectionType->code) === 'conveyancing' && $request->name_of_property) {
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
                            'firm_id' => $case->firm_id,
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
                            'firm_id' => $case->firm_id,
                        ]);
                    }
                }
            }

            // Update partners
            // Delete existing partners first
            $case->partners()->delete();

            // Add updated partners with firm validation
            if ($request->has('partners')) {
                foreach ($request->partners as $partner) {
                    if (!empty($partner['partner_id'])) {
                        // Validate that partner belongs to the same firm as the case
                        $partnerModel = Partner::find($partner['partner_id']);
                        if ($partnerModel && $partnerModel->firm_id === $case->firm_id) {
                            CasePartner::create([
                                'case_id' => $case->id,
                                'partner_id' => $partner['partner_id'],
                                'role' => $partner['role'] ?? '',
                            ]);
                        }
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

            // Handle custom field values
            $this->handleCustomFieldValues($case, $request);

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

    /**
     * Handle custom field values for case creation/update
     */
    private function handleCustomFieldValues($case, $request)
    {
        // Get section type to determine which custom fields to process
        if (!$request->section) {
            return;
        }

        // Try to get section type by ID first, then by code for backward compatibility
        $sectionType = null;
        $user = auth()->user();

        if (is_numeric($request->section)) {
            if ($user->hasRole('Super Administrator')) {
                if (session('current_firm_id')) {
                    $sectionType = SectionType::forFirm(session('current_firm_id'))->find($request->section);
                } else {
                    $sectionType = SectionType::withoutFirmScope()->find($request->section);
                }
            } else {
                $sectionType = SectionType::find($request->section);
            }
        } else {
            // Backward compatibility: find by code
            if ($user->hasRole('Super Administrator')) {
                if (session('current_firm_id')) {
                    $sectionType = SectionType::forFirm(session('current_firm_id'))->where('code', $this->mapSectionToCode($request->section))->first();
                } else {
                    $sectionType = SectionType::withoutFirmScope()->where('code', $this->mapSectionToCode($request->section))->first();
                }
            } else {
                $sectionType = SectionType::where('code', $this->mapSectionToCode($request->section))->first();
            }
        }

        if (!$sectionType) {
            return;
        }

        // Get active custom fields for this section with proper firm scope
        $user = auth()->user();
        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $customFields = SectionCustomField::forFirm(session('current_firm_id'))
                    ->where('section_type_id', $sectionType->id)
                    ->where('status', 'active')
                    ->get();
            } else {
                $customFields = SectionCustomField::withoutFirmScope()
                    ->where('section_type_id', $sectionType->id)
                    ->where('status', 'active')
                    ->get();
            }
        } else {
            $customFields = SectionCustomField::where('section_type_id', $sectionType->id)
                ->where('status', 'active')
                ->get();
        }

        foreach ($customFields as $customField) {
            $fieldKey = 'custom_field_' . $customField->id;
            $fieldValue = $request->input($fieldKey);

            // Skip if no value provided and field is not required
            if (empty($fieldValue) && !$customField->is_required) {
                continue;
            }

            // Format value based on field type
            $formattedValue = $this->formatCustomFieldValue($fieldValue, $customField->field_type);

            // Update or create custom field value
            CaseCustomFieldValue::updateOrCreate(
                [
                    'case_id' => $case->id,
                    'custom_field_id' => $customField->id,
                ],
                [
                    'field_value' => $formattedValue,
                ]
            );
        }
    }

    /**
     * Map section form value to section type code
     */
    private function mapSectionToCode($section)
    {
        $mapping = [
            'civil' => 'CA',
            'criminal' => 'CR',
            'conveyancing' => 'CVY',
            'probate' => 'PB',
        ];

        return $mapping[$section] ?? $section;
    }

    /**
     * Format custom field value based on field type
     */
    private function formatCustomFieldValue($value, $fieldType)
    {
        switch ($fieldType) {
            case 'number':
                return is_numeric($value) ? (float) $value : null;
            case 'date':
                return $value ? date('Y-m-d', strtotime($value)) : null;
            case 'time':
                return $value ? date('H:i:s', strtotime($value)) : null;
            case 'datetime':
                return $value ? date('Y-m-d H:i:s', strtotime($value)) : null;
            case 'checkbox':
                return is_array($value) ? json_encode($value) : $value;
            default:
                return $value;
        }
    }
}
