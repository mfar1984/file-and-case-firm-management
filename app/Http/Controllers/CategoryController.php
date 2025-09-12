<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use App\Models\CaseStatus;
use App\Models\EventStatus;
use App\Models\ExpenseCategory;
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
                $caseStatuses = CaseStatus::forFirm(session('current_firm_id'))->orderBy('name')->get();
                $eventStatuses = EventStatus::forFirm(session('current_firm_id'))->active()->ordered()->get();
                $fileTypes = \App\Models\FileType::forFirm(session('current_firm_id'))->orderBy('code')->get();
                $specializations = \App\Models\Specialization::forFirm(session('current_firm_id'))->orderBy('specialist_name')->get();
                $expenseCategories = ExpenseCategory::forFirm(session('current_firm_id'))->ordered()->get();
            } else {
                $caseTypes = CaseType::withoutFirmScope()->orderBy('code')->get();
                $caseStatuses = CaseStatus::withoutFirmScope()->orderBy('name')->get();
                $eventStatuses = EventStatus::withoutFirmScope()->active()->ordered()->get();
                $fileTypes = \App\Models\FileType::withoutFirmScope()->orderBy('code')->get();
                $specializations = \App\Models\Specialization::withoutFirmScope()->orderBy('specialist_name')->get();
                $expenseCategories = ExpenseCategory::withoutFirmScope()->ordered()->get();
            }
        } else {
            $caseTypes = CaseType::orderBy('code')->get();
            $caseStatuses = CaseStatus::orderBy('name')->get();
            $eventStatuses = EventStatus::active()->ordered()->get();
            $fileTypes = \App\Models\FileType::orderBy('code')->get();
            $specializations = \App\Models\Specialization::orderBy('specialist_name')->get();
            $expenseCategories = ExpenseCategory::ordered()->get();
        }

        return view('settings.category', compact('caseTypes', 'caseStatuses', 'eventStatuses', 'fileTypes', 'specializations', 'expenseCategories'));
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
}
