<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use App\Models\CaseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $caseTypes = CaseType::orderBy('code')->get();
        $caseStatuses = CaseStatus::orderBy('name')->get();
        $fileTypes = \App\Models\FileType::orderBy('code')->get();
        
        return view('settings.category', compact('caseTypes', 'caseStatuses', 'fileTypes'));
    }

    // Case Type Methods
    public function storeType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:case_types,code',
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:case_types,code,' . $id,
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:case_statuses,name',
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:case_statuses,name,' . $id,
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
        $request->validate([
            'code' => 'required|string|max:20|unique:file_types,code',
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $fileType = \App\Models\FileType::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'File type created successfully', 'data' => $fileType]);
    }

    public function updateFileType(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:file_types,code,' . $id,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $fileType = \App\Models\FileType::findOrFail($id);
        $fileType->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'File type updated successfully', 'data' => $fileType]);
    }

    public function destroyFileType($id)
    {
        $fileType = \App\Models\FileType::findOrFail($id);
        $fileType->delete();
        return response()->json(['success' => true, 'message' => 'File type deleted successfully']);
    }
}
