<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileManagementController extends Controller
{
    /**
     * Display the file management page
     */
    public function index(Request $request)
    {
        $query = CaseFile::query();

        // Filter by case reference
        if ($request->filled('case_ref')) {
            $query->byCase($request->case_ref);
        }

        // Filter by file type
        if ($request->filled('file_type')) {
            $query->byType($request->file_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $files = $query->orderBy('created_at', 'desc')->get();

        // Get available cases for dropdown
        $cases = CaseFile::distinct()->pluck('case_ref')->filter()->values();

        return view('file-management', compact('files', 'cases'));
    }

    /**
     * Store a newly uploaded file
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'case_ref' => 'required|string',
            'file_type' => 'required|string',
            'files.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('case-files', $fileName, 'public');

                $caseFile = CaseFile::create([
                    'case_ref' => $request->case_ref,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $request->file_type,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'description' => $request->description,
                    'status' => 'IN',
                    'rack_location' => 'Rack A-01', // Default location
                ]);

                $uploadedFiles[] = $caseFile;
            }
        }

        return redirect()->route('file-management.index')
            ->with('success', count($uploadedFiles) . ' file(s) uploaded successfully.');
    }

    /**
     * Download a file
     */
    public function download($id)
    {
        $file = CaseFile::findOrFail($id);

        if (!Storage::disk('public')->exists($file->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }

    /**
     * Update file status (IN/OUT)
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:IN,OUT',
            'taken_by' => 'required_if:status,OUT|nullable|string',
            'purpose' => 'required_if:status,OUT|nullable|string',
            'expected_return' => 'required_if:status,OUT|nullable|date',
            'rack_location' => 'required_if:status,IN|nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = CaseFile::findOrFail($id);
        
        $file->update([
            'status' => $request->status,
            'taken_by' => $request->status === 'OUT' ? $request->taken_by : null,
            'purpose' => $request->status === 'OUT' ? $request->purpose : null,
            'expected_return' => $request->status === 'OUT' ? $request->expected_return : null,
            'rack_location' => $request->status === 'IN' ? $request->rack_location : null,
            'actual_return' => $request->status === 'IN' ? now() : null,
        ]);

        $statusText = $request->status === 'IN' ? 'returned' : 'taken out';
        return back()->with('success', "File has been {$statusText} successfully.");
    }

    /**
     * Delete a file
     */
    public function destroy($id)
    {
        $file = CaseFile::findOrFail($id);

        // Delete physical file
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete database record
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    /**
     * Get available cases for dropdown
     */
    public function getCases()
    {
        $cases = CaseFile::distinct()->pluck('case_ref')->filter()->values();
        return response()->json($cases);
    }

    /**
     * Get file statistics
     */
    public function getStats()
    {
        $stats = [
            'total_files' => CaseFile::count(),
            'files_in' => CaseFile::where('status', 'IN')->count(),
            'files_out' => CaseFile::where('status', 'OUT')->count(),
            'overdue_files' => CaseFile::where('status', 'OUT')
                ->where('expected_return', '<', now())
                ->count(),
        ];

        return response()->json($stats);
    }
}
