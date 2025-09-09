<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\FileType;
use Illuminate\Support\Str;

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

        $files = $query->with('fileType')->orderBy('created_at', 'desc')->get();

        // Get available cases for dropdown
        $cases = \App\Models\CourtCase::pluck('case_number')->filter()->values();

        // Get file types from DB for dropdowns
        $fileTypes = FileType::active()->orderBy('description')->get(['id','code','description']);

        return view('file-management', compact('files', 'cases', 'fileTypes'));
    }

    /**
     * Store a newly uploaded file
     */
    public function store(Request $request)
    {
        // Debug logging
        \Log::info('File upload request received', [
            'all_data' => $request->all(),
            'case_ref' => $request->case_ref,
            'file_type' => $request->file_type,
            'has_files' => $request->hasFile('files'),
            'files_count' => $request->hasFile('files') ? count($request->file('files')) : 0
        ]);

        $validator = Validator::make($request->all(), [
            'case_ref' => 'required|string',
            'file_type' => 'required|string',
            'files.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip,rar|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            \Log::error('File upload validation failed', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        $uploadedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('case-files', $fileName, 'public');

                \Log::info('Creating CaseFile record', [
                    'case_ref' => $request->case_ref,
                    'category_id' => $request->file_type,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath
                ]);

                $caseFile = CaseFile::create([
                    'case_ref' => $request->case_ref,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'category_id' => $request->file_type,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'description' => $request->description,
                    'status' => 'IN',
                    'rack_location' => 'Rack A-01', // Default location
                ]);

                // Log file upload
                activity()
                    ->performedOn($caseFile)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'ip' => request()->ip(),
                        'case_ref' => $caseFile->case_ref,
                        'file_name' => $caseFile->file_name,
                        'file_size' => $caseFile->file_size
                    ])
                    ->log("File {$caseFile->file_name} uploaded for case {$caseFile->case_ref}");

                // Generate a public view hash and save (without changing schema, reuse id-based hash)
                $caseFile->public_hash = hash('sha256', $caseFile->id . '|' . $caseFile->file_path . '|' . config('app.key'));
                // Note: if model doesn't have column, we won't persist; we'll compute on the fly in view()

                \Log::info('CaseFile created successfully', [
                    'id' => $caseFile->id,
                    'category_id' => $caseFile->category_id
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
     * Secure view by hash (stream inline)
     */
    public function view($hash)
    {
        // Iterate minimal to find match without exposing path
        $file = CaseFile::latest('id')->get()->first(function ($f) use ($hash) {
            $computed = hash('sha256', $f->id . '|' . $f->file_path . '|' . config('app.key'));
            return hash_equals($computed, $hash);
        });

        if (!$file) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404);
        }

        // Stream inline with correct headers
        $mime = $file->mime_type ?: Storage::disk('public')->mimeType($file->file_path);
        $contents = Storage::disk('public')->get($file->file_path);
        return response($contents, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . ($file->file_name ?? basename($file->file_path)) . '"'
        ]);
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
        $oldStatus = $file->status;

        $file->update([
            'status' => $request->status,
            'taken_by' => $request->status === 'OUT' ? $request->taken_by : null,
            'purpose' => $request->status === 'OUT' ? $request->purpose : null,
            'expected_return' => $request->status === 'OUT' ? $request->expected_return : null,
            'rack_location' => $request->status === 'IN' ? $request->rack_location : null,
            'actual_return' => $request->status === 'IN' ? now() : null,
        ]);

        // Log status change
        activity()
            ->performedOn($file)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'taken_by' => $request->taken_by,
                'purpose' => $request->purpose
            ])
            ->log("File {$file->file_name} status changed from {$oldStatus} to {$request->status}");

        $statusText = $request->status === 'IN' ? 'returned' : 'taken out';
        return back()->with('success', "File has been {$statusText} successfully.");
    }

    /**
     * Delete a file
     */
    public function destroy($id)
    {
        $file = CaseFile::findOrFail($id);

        // Log file deletion before deleting
        activity()
            ->performedOn($file)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'case_ref' => $file->case_ref,
                'file_name' => $file->file_name,
                'file_size' => $file->file_size,
                'status' => $file->status
            ])
            ->log("File {$file->file_name} deleted from case {$file->case_ref}");

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
