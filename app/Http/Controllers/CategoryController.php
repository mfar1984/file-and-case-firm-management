<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use App\Models\CaseStatus;
use App\Models\EventStatus;
use App\Models\ExpenseCategory;
use App\Models\SectionType;
use App\Models\CaseInitiatingDocument;
use App\Models\SectionCustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        // Apply firm scope filtering
        $user = auth()->user();

        if ($user->hasRole('Super Administrator')) {
            if (session('current_firm_id')) {
                $caseTypes = CaseType::forFirm(session('current_firm_id'))->orderBy('code')->get();
                $taxCategories = \App\Models\TaxCategory::forFirm(session('current_firm_id'))->ordered()->get();
                $caseStatuses = CaseStatus::forFirm(session('current_firm_id'))->orderBy('name')->get();
                $eventStatuses = EventStatus::forFirm(session('current_firm_id'))->active()->ordered()->get();
                $fileTypes = \App\Models\FileType::forFirm(session('current_firm_id'))->orderBy('code')->get();
                $specializations = \App\Models\Specialization::forFirm(session('current_firm_id'))->orderBy('specialist_name')->get();
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))->ordered()->get();
                $sectionTypes = SectionType::forFirm(session('current_firm_id'))->ordered()->get();
            } else {
                $caseTypes = CaseType::withoutFirmScope()->orderBy('code')->get();
                $taxCategories = \App\Models\TaxCategory::withoutFirmScope()->ordered()->get();
                $caseStatuses = CaseStatus::withoutFirmScope()->orderBy('name')->get();
                $eventStatuses = EventStatus::withoutFirmScope()->active()->ordered()->get();
                $fileTypes = \App\Models\FileType::withoutFirmScope()->orderBy('code')->get();
                $specializations = \App\Models\Specialization::withoutFirmScope()->orderBy('specialist_name')->get();
                $expenseCategories = ExpenseCategory::withoutFirmScope()->ordered()->get();
                $sectionTypes = SectionType::withoutFirmScope()->with(['initiatingDocuments', 'customFields'])->ordered()->get();
                // Force load relationships and make them visible in JSON
                $sectionTypes->load(['initiatingDocuments', 'customFields']);
                $sectionTypes->each(function($sectionType) {
                    $sectionType->makeVisible(['initiatingDocuments', 'customFields']);
                    $sectionType->append(['initiatingDocuments', 'customFields']);
                });
            }
        } else {
            $caseTypes = CaseType::orderBy('code')->get();
            $taxCategories = \App\Models\TaxCategory::ordered()->get();
            $caseStatuses = CaseStatus::orderBy('name')->get();
            $eventStatuses = EventStatus::active()->ordered()->get();
            $fileTypes = \App\Models\FileType::orderBy('code')->get();
            $specializations = \App\Models\Specialization::orderBy('specialist_name')->get();
            $expenseCategories = ExpenseCategory::ordered()->get();
            $sectionTypes = SectionType::with(['initiatingDocuments', 'customFields'])->ordered()->get();
            // Force load relationships and make them visible in JSON
            $sectionTypes->load(['initiatingDocuments', 'customFields']);
            $sectionTypes->each(function($sectionType) {
                $sectionType->makeVisible(['initiatingDocuments', 'customFields']);
            });
        }

        return view('settings.category', compact('caseTypes', 'taxCategories', 'caseStatuses', 'eventStatuses', 'fileTypes', 'specializations', 'expenseCategories', 'sectionTypes'));
    }

    // Case Type Methods
    public function storeType(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:case_types,code,NULL,id,firm_id,' . $firmId,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $caseType = CaseType::create([
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'status' => $request->status,
            ]);

            DB::commit();

            // Log case type creation
            activity()
                ->performedOn($caseType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'code' => $caseType->code,
                    'description' => $caseType->description
                ])
                ->log("Case type {$caseType->code} created");

            return response()->json([
                'success' => true,
                'message' => 'Case type created successfully',
                'data' => $caseType
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create case type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateType(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:case_types,code,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $caseType = CaseType::findOrFail($id);
            $caseType->update([
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'status' => $request->status,
            ]);

            DB::commit();

            // Log case type update
            activity()
                ->performedOn($caseType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'code' => $caseType->code,
                    'description' => $caseType->description
                ])
                ->log("Case type {$caseType->code} updated");

            return response()->json([
                'success' => true,
                'message' => 'Case type updated successfully',
                'data' => $caseType
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update case type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyType($id)
    {
        try {
            DB::beginTransaction();

            $caseType = CaseType::findOrFail($id);
            $caseType->delete();

            DB::commit();

            // Log case type deletion
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'code' => $caseType->code,
                    'description' => $caseType->description
                ])
                ->log("Case type {$caseType->code} deleted");

            return response()->json([
                'success' => true,
                'message' => 'Case type deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete case type: ' . $e->getMessage()
            ], 500);
        }
    }

    // Case Status Methods
    public function storeStatus(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:case_statuses,name,NULL,id,firm_id,' . $firmId,
            'description' => 'required|string',
            'color' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $caseStatus = CaseStatus::create([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color,
                'status' => $request->status,
            ]);

            DB::commit();

            // Log case status creation
            activity()
                ->performedOn($caseStatus)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $caseStatus->name,
                    'description' => $caseStatus->description,
                    'color' => $caseStatus->color
                ])
                ->log("Case status {$caseStatus->name} created");

            return response()->json([
                'success' => true,
                'message' => 'Case status created successfully',
                'data' => $caseStatus
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create case status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:case_statuses,name,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'required|string',
            'color' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $caseStatus = CaseStatus::findOrFail($id);
            $caseStatus->update([
                'name' => $request->name,
                'description' => $request->description,
                'color' => $request->color,
                'status' => $request->status,
            ]);

            DB::commit();

            // Log case status update
            activity()
                ->performedOn($caseStatus)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $caseStatus->name,
                    'description' => $caseStatus->description,
                    'color' => $caseStatus->color
                ])
                ->log("Case status {$caseStatus->name} updated");

            return response()->json([
                'success' => true,
                'message' => 'Case status updated successfully',
                'data' => $caseStatus
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update case status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyStatus($id)
    {
        try {
            DB::beginTransaction();

            $caseStatus = CaseStatus::findOrFail($id);
            $caseStatus->delete();

            DB::commit();

            // Log case status deletion
            activity()
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $caseStatus->name,
                    'description' => $caseStatus->description,
                    'color' => $caseStatus->color
                ])
                ->log("Case status {$caseStatus->name} deleted");

            return response()->json([
                'success' => true,
                'message' => 'Case status deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete case status: ' . $e->getMessage()
            ], 500);
        }
    }

    // File Type Methods
    public function storeFileType(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $request->validate([
            'code' => 'required|string|max:20|unique:file_types,code,NULL,id,firm_id,' . $firmId,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $fileType = \App\Models\FileType::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Log file type creation
        activity()
            ->performedOn($fileType)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'code' => $fileType->code,
                'description' => $fileType->description
            ])
            ->log("File type {$fileType->code} created");

        return response()->json(['success' => true, 'message' => 'File type created successfully', 'data' => $fileType]);
    }

    public function updateFileType(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $request->validate([
            'code' => 'required|string|max:20|unique:file_types,code,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $fileType = \App\Models\FileType::findOrFail($id);
        $fileType->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        // Log file type update
        activity()
            ->performedOn($fileType)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'code' => $fileType->code,
                'description' => $fileType->description
            ])
            ->log("File type {$fileType->code} updated");

        return response()->json(['success' => true, 'message' => 'File type updated successfully', 'data' => $fileType]);
    }

    public function destroyFileType($id)
    {
        $fileType = \App\Models\FileType::findOrFail($id);

        // Log file type deletion
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'code' => $fileType->code,
                'description' => $fileType->description
            ])
            ->log("File type {$fileType->code} deleted");

        $fileType->delete();
        return response()->json(['success' => true, 'message' => 'File type deleted successfully']);
    }

    // Specialization Methods
    public function storeSpecialization(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $request->validate([
            'specialist_name' => 'required|string|max:255|unique:specializations,specialist_name,NULL,id,firm_id,' . $firmId,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $specialization = \App\Models\Specialization::create($request->all());

        // Log specialization creation
        activity()
            ->performedOn($specialization)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'specialist_name' => $specialization->specialist_name,
                'description' => $specialization->description
            ])
            ->log("Specialization {$specialization->specialist_name} created");

        return response()->json(['success' => true, 'message' => 'Specialization created successfully', 'data' => $specialization]);
    }

    public function updateSpecialization(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $request->validate([
            'specialist_name' => 'required|string|max:255|unique:specializations,specialist_name,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $specialization = \App\Models\Specialization::findOrFail($id);
        $specialization->update($request->all());

        // Log specialization update
        activity()
            ->performedOn($specialization)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'specialist_name' => $specialization->specialist_name,
                'description' => $specialization->description
            ])
            ->log("Specialization {$specialization->specialist_name} updated");

        return response()->json(['success' => true, 'message' => 'Specialization updated successfully', 'data' => $specialization]);
    }

    public function destroySpecialization($id)
    {
        $specialization = \App\Models\Specialization::findOrFail($id);

        // Log specialization deletion
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'specialist_name' => $specialization->specialist_name,
                'description' => $specialization->description
            ])
            ->log("Specialization {$specialization->specialist_name} deleted");

        $specialization->delete();
        return response()->json(['success' => true, 'message' => 'Specialization deleted successfully']);
    }

    // Event Status Methods
    public function storeEventStatus(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:event_statuses,name,NULL,id,firm_id,' . $firmId,
            'description' => 'nullable|string|max:255',
            'background_color' => 'required|string|max:50',
            'icon' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $eventStatus = EventStatus::create([
                'name' => strtolower(str_replace(' ', '_', $request->name)),
                'description' => $request->description,
                'background_color' => $request->background_color,
                'icon' => $request->icon,
                'status' => $request->status,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            // Log event status creation
            activity()
                ->performedOn($eventStatus)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $eventStatus->name,
                    'description' => $eventStatus->description,
                    'background_color' => $eventStatus->background_color,
                    'icon' => $eventStatus->icon
                ])
                ->log("Event status {$eventStatus->name} created");

            return response()->json([
                'success' => true,
                'message' => 'Event status created successfully',
                'data' => $eventStatus
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateEventStatus(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:event_statuses,name,' . $id . ',id,firm_id,' . $firmId,
            'description' => 'nullable|string|max:255',
            'background_color' => 'required|string|max:50',
            'icon' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $eventStatus = EventStatus::findOrFail($id);
            $eventStatus->update([
                'name' => strtolower(str_replace(' ', '_', $request->name)),
                'description' => $request->description,
                'background_color' => $request->background_color,
                'icon' => $request->icon,
                'status' => $request->status,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            // Log event status update
            activity()
                ->performedOn($eventStatus)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $eventStatus->name,
                    'description' => $eventStatus->description,
                    'background_color' => $eventStatus->background_color,
                    'icon' => $eventStatus->icon
                ])
                ->log("Event status {$eventStatus->name} updated");

            return response()->json([
                'success' => true,
                'message' => 'Event status updated successfully',
                'data' => $eventStatus
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyEventStatus($id)
    {
        try {
            $eventStatus = EventStatus::findOrFail($id);

            // Check if event status is being used
            $timelineCount = \App\Models\CaseTimeline::where('status', $eventStatus->name)->count();
            if ($timelineCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete event status. It is being used by ' . $timelineCount . ' timeline events.'
                ], 422);
            }

            $eventStatus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event status deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event status: ' . $e->getMessage()
            ], 500);
        }
    }

    // Expense Category Methods
    public function storeExpenseCategory(Request $request)
    {
        $validator = Validator::make($request->all(), ExpenseCategory::validationRules());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $expenseCategory = ExpenseCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            // Log expense category creation
            activity()
                ->performedOn($expenseCategory)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $expenseCategory->name,
                    'description' => $expenseCategory->description
                ])
                ->log("Expense category {$expenseCategory->name} created");

            return response()->json([
                'success' => true,
                'message' => 'Expense category created successfully',
                'data' => $expenseCategory
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create expense category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateExpenseCategory(Request $request, ExpenseCategory $expenseCategory)
    {
        $validator = Validator::make($request->all(), ExpenseCategory::updateValidationRules($expenseCategory->id));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $expenseCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'sort_order' => $request->sort_order ?? $expenseCategory->sort_order,
            ]);

            DB::commit();

            // Log expense category update
            activity()
                ->performedOn($expenseCategory)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'name' => $expenseCategory->name,
                    'description' => $expenseCategory->description
                ])
                ->log("Expense category {$expenseCategory->name} updated");

            return response()->json([
                'success' => true,
                'message' => 'Expense category updated successfully',
                'data' => $expenseCategory->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update expense category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyExpenseCategory(ExpenseCategory $expenseCategory)
    {
        try {
            // Check if category is being used in bills or voucher items
            $billsCount = \App\Models\Bill::where('category', $expenseCategory->name)->count();
            $voucherItemsCount = \App\Models\VoucherItem::where('category', $expenseCategory->name)->count();

            if ($billsCount > 0 || $voucherItemsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete expense category. It is being used in ' . ($billsCount + $voucherItemsCount) . ' transaction(s).'
                ], 422);
            }

            $expenseCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Expense category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete expense category: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tax Category Methods
    public function storeTaxCategory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'tax_rate' => 'required|numeric|min:0|max:100',
                'sort_order' => 'nullable|integer|min:0',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $firmId = session('current_firm_id') ?? auth()->user()->firm_id;

            $taxCategory = \App\Models\TaxCategory::create([
                'name' => $request->name,
                'tax_rate' => $request->tax_rate,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status,
                'firm_id' => $firmId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax category created successfully',
                'data' => $taxCategory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tax category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateTaxCategory(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'tax_rate' => 'required|numeric|min:0|max:100',
                'sort_order' => 'nullable|integer|min:0',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $taxCategory = \App\Models\TaxCategory::findOrFail($id);

            $taxCategory->update([
                'name' => $request->name,
                'tax_rate' => $request->tax_rate,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tax category updated successfully',
                'data' => $taxCategory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tax category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyTaxCategory($id)
    {
        try {
            $taxCategory = \App\Models\TaxCategory::findOrFail($id);
            $taxCategory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tax category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tax category: ' . $e->getMessage()
            ], 500);
        }
    }

    // Section Type Methods
    public function storeSectionType(Request $request)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:section_types,code,NULL,id,firm_id,' . $firmId,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'documents' => 'nullable|array',
            'documents.*.document_name' => 'required_with:documents|string|max:255',
            'documents.*.document_code' => 'required_with:documents|string|max:50',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.field_name' => 'required_with:custom_fields|string|max:255',
            'custom_fields.*.field_type' => 'required_with:custom_fields|in:text,number,dropdown,checkbox,date,time,datetime',
            'custom_fields.*.placeholder' => 'nullable|string|max:255',
            'custom_fields.*.is_required' => 'nullable|boolean',
            'custom_fields.*.field_options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $sectionType = SectionType::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'firm_id' => $firmId,
            ]);

            // Create documents if provided
            if ($request->has('documents')) {
                foreach ($request->documents as $index => $documentData) {
                    if (!empty($documentData['document_name']) && !empty($documentData['document_code'])) {
                        CaseInitiatingDocument::create([
                            'section_type_id' => $sectionType->id,
                            'document_name' => $documentData['document_name'],
                            'document_code' => $documentData['document_code'],
                            'sort_order' => $index + 1,
                            'status' => 'active',
                            'firm_id' => $firmId,
                        ]);
                    }
                }
            }

            // Create custom fields if provided
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $index => $fieldData) {
                    if (!empty($fieldData['field_name']) && !empty($fieldData['field_type'])) {
                        SectionCustomField::create([
                            'section_type_id' => $sectionType->id,
                            'field_name' => $fieldData['field_name'],
                            'field_type' => $fieldData['field_type'],
                            'placeholder' => $fieldData['placeholder'] ?? '',
                            'is_required' => isset($fieldData['is_required']) ? (bool)$fieldData['is_required'] : false,
                            'field_options' => isset($fieldData['field_options']) ? $fieldData['field_options'] : null,
                            'conditional_document_code' => $fieldData['conditional_document_code'] ?? null,
                            'sort_order' => $index + 1,
                            'status' => 'active',
                            'firm_id' => $firmId,
                        ]);
                    }
                }
            }

            DB::commit();

            // Log section type creation
            activity()
                ->performedOn($sectionType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'code' => $sectionType->code,
                    'name' => $sectionType->name,
                ])
                ->log('Section type created');

            return response()->json([
                'success' => true,
                'message' => 'Section type created successfully',
                'data' => $sectionType
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create section type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSectionType(Request $request, $id)
    {
        // Get current firm context
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:section_types,code,' . $id . ',id,firm_id,' . $firmId,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
            'documents' => 'nullable|array',
            'documents.*.document_name' => 'required_with:documents|string|max:255',
            'documents.*.document_code' => 'required_with:documents|string|max:50',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.field_name' => 'required_with:custom_fields|string|max:255',
            'custom_fields.*.field_type' => 'required_with:custom_fields|in:text,number,dropdown,checkbox,date,time,datetime',
            'custom_fields.*.placeholder' => 'nullable|string|max:255',
            'custom_fields.*.is_required' => 'nullable|boolean',
            'custom_fields.*.field_options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $sectionType = SectionType::findOrFail($id);
            $sectionType->update([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Update documents - delete existing and create new ones
            if ($request->has('documents')) {
                // Delete existing documents
                $sectionType->initiatingDocuments()->delete();

                // Create new documents
                foreach ($request->documents as $index => $documentData) {
                    if (!empty($documentData['document_name']) && !empty($documentData['document_code'])) {
                        CaseInitiatingDocument::create([
                            'section_type_id' => $sectionType->id,
                            'document_name' => $documentData['document_name'],
                            'document_code' => $documentData['document_code'],
                            'sort_order' => $index + 1,
                            'status' => 'active',
                            'firm_id' => $firmId,
                        ]);
                    }
                }
            }

            // Update custom fields - delete existing and create new ones
            if ($request->has('custom_fields')) {
                // Delete existing custom fields
                $sectionType->customFields()->delete();

                // Create new custom fields
                foreach ($request->custom_fields as $index => $fieldData) {
                    if (!empty($fieldData['field_name']) && !empty($fieldData['field_type'])) {
                        SectionCustomField::create([
                            'section_type_id' => $sectionType->id,
                            'field_name' => $fieldData['field_name'],
                            'field_type' => $fieldData['field_type'],
                            'placeholder' => $fieldData['placeholder'] ?? '',
                            'is_required' => isset($fieldData['is_required']) ? (bool)$fieldData['is_required'] : false,
                            'field_options' => isset($fieldData['field_options']) ? $fieldData['field_options'] : null,
                            'conditional_document_code' => $fieldData['conditional_document_code'] ?? null,
                            'sort_order' => $index + 1,
                            'status' => 'active',
                            'firm_id' => $firmId,
                        ]);
                    }
                }
            }

            DB::commit();

            // Log section type update
            activity()
                ->performedOn($sectionType)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'code' => $sectionType->code,
                    'name' => $sectionType->name,
                ])
                ->log('Section type updated');

            return response()->json([
                'success' => true,
                'message' => 'Section type updated successfully',
                'data' => $sectionType
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroySectionType($id)
    {
        $sectionType = SectionType::findOrFail($id);

        // Log section type deletion
        activity()
            ->performedOn($sectionType)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'code' => $sectionType->code,
                'name' => $sectionType->name,
            ])
            ->log('Section type deleted');

        $sectionType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section type deleted successfully'
        ]);
    }

    // Case Initiating Document Methods
    public function getDocuments($sectionTypeId)
    {
        try {
            $sectionType = SectionType::findOrFail($sectionTypeId);
            $documents = $sectionType->initiatingDocuments()->ordered()->get();

            return response()->json([
                'success' => true,
                'documents' => $documents,
                'section_type' => $sectionType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch documents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'section_type_id' => 'required|exists:section_types,id',
            'document_name' => 'required|string|max:255',
            'document_code' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Get current firm context
            $firmId = session('current_firm_id') ?? auth()->user()->firm_id;

            $document = CaseInitiatingDocument::create([
                'section_type_id' => $request->section_type_id,
                'document_name' => $request->document_name,
                'document_code' => $request->document_code,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status,
                'firm_id' => $firmId,
            ]);

            // Log activity
            activity('case_initiating_document')
                ->performedOn($document)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'section_type_id' => $document->section_type_id,
                    'document_name' => $document->document_name,
                ])
                ->log('Case initiating document created');

            return response()->json([
                'success' => true,
                'message' => 'Document created successfully',
                'document' => $document->load('sectionType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_code' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $document = CaseInitiatingDocument::findOrFail($id);

            $document->update([
                'document_name' => $request->document_name,
                'document_code' => $request->document_code,
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status,
            ]);

            // Log activity
            activity('case_initiating_document')
                ->performedOn($document)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'document_name' => $document->document_name,
                ])
                ->log('Case initiating document updated');

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully',
                'document' => $document->load('sectionType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyDocument($id)
    {
        try {
            $document = CaseInitiatingDocument::findOrFail($id);

            // Log activity before deletion
            activity('case_initiating_document')
                ->performedOn($document)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'document_name' => $document->document_name,
                ])
                ->log('Case initiating document deleted');

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    // Section Custom Field Methods
    public function getCustomFields($sectionTypeId)
    {
        try {
            $sectionType = SectionType::findOrFail($sectionTypeId);
            $customFields = $sectionType->customFields()->ordered()->get();

            return response()->json([
                'success' => true,
                'custom_fields' => $customFields,
                'section_type' => $sectionType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch custom fields: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeCustomField(Request $request)
    {
        $request->validate([
            'section_type_id' => 'required|exists:section_types,id',
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,dropdown,checkbox,date,time,datetime',
            'placeholder' => 'nullable|string|max:255',
            'field_options' => 'nullable|array',
            'field_options.*.label' => 'required_with:field_options|string|max:255',
            'field_options.*.value' => 'required_with:field_options|string|max:255',
            'is_required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            // Get current firm context
            $firmId = session('current_firm_id') ?? auth()->user()->firm_id;

            $customField = SectionCustomField::create([
                'section_type_id' => $request->section_type_id,
                'field_name' => $request->field_name,
                'field_type' => $request->field_type,
                'placeholder' => $request->placeholder,
                'field_options' => $request->field_options,
                'is_required' => $request->boolean('is_required'),
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status,
                'firm_id' => $firmId,
            ]);

            // Log activity
            activity('section_custom_field')
                ->performedOn($customField)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'section_type_id' => $customField->section_type_id,
                    'field_name' => $customField->field_name,
                    'field_type' => $customField->field_type,
                ])
                ->log('Section custom field created');

            return response()->json([
                'success' => true,
                'message' => 'Custom field created successfully',
                'custom_field' => $customField->load('sectionType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create custom field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCustomField(Request $request, $id)
    {
        $request->validate([
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|in:text,number,dropdown,checkbox,date,time,datetime',
            'placeholder' => 'nullable|string|max:255',
            'field_options' => 'nullable|array',
            'field_options.*.label' => 'required_with:field_options|string|max:255',
            'field_options.*.value' => 'required_with:field_options|string|max:255',
            'is_required' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $customField = SectionCustomField::findOrFail($id);

            $customField->update([
                'field_name' => $request->field_name,
                'field_type' => $request->field_type,
                'placeholder' => $request->placeholder,
                'field_options' => $request->field_options,
                'is_required' => $request->boolean('is_required'),
                'sort_order' => $request->sort_order ?? 0,
                'status' => $request->status,
            ]);

            // Log activity
            activity('section_custom_field')
                ->performedOn($customField)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'field_name' => $customField->field_name,
                    'field_type' => $customField->field_type,
                ])
                ->log('Section custom field updated');

            return response()->json([
                'success' => true,
                'message' => 'Custom field updated successfully',
                'custom_field' => $customField->load('sectionType')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update custom field: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyCustomField($id)
    {
        try {
            $customField = SectionCustomField::findOrFail($id);

            // Check if custom field is being used in any cases
            $usageCount = $customField->caseValues()->count();
            if ($usageCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete custom field. It is being used in {$usageCount} case(s)."
                ], 400);
            }

            // Log activity before deletion
            activity('section_custom_field')
                ->performedOn($customField)
                ->causedBy(auth()->user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'field_name' => $customField->field_name,
                    'field_type' => $customField->field_type,
                ])
                ->log('Section custom field deleted');

            $customField->delete();

            return response()->json([
                'success' => true,
                'message' => 'Custom field deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete custom field: ' . $e->getMessage()
            ], 500);
        }
    }
}
