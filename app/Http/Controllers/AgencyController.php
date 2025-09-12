<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index()
    {
        // Agency model now has HasFirmScope trait, so it will automatically filter by firm
        $agencies = Agency::orderBy('name')->get();
        return response()->json(['success' => true, 'data' => $agencies]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Check for duplicate name within the same firm
        $existingAgency = Agency::where('name', $validated['name'])
            ->where('firm_id', $firmId)
            ->first();

        if ($existingAgency) {
            return response()->json([
                'success' => false,
                'message' => 'Agency name already exists in this firm'
            ], 422);
        }

        $agency = Agency::create([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? 'active',
            'firm_id' => $firmId,
        ]);

        // Log agency creation
        activity()
            ->performedOn($agency)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'name' => $agency->name,
                'status' => $agency->status
            ])
            ->log("Agency {$agency->name} created");

        return response()->json([
            'success' => true,
            'message' => 'Agency created successfully',
            'data' => $agency
        ]);
    }

    public function update(Request $request, Agency $agency)
    {
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Check for duplicate name within the same firm (excluding current agency)
        $existingAgency = Agency::where('name', $validated['name'])
            ->where('firm_id', $firmId)
            ->where('id', '!=', $agency->id)
            ->first();

        if ($existingAgency) {
            return response()->json([
                'success' => false,
                'message' => 'Agency name already exists in this firm'
            ], 422);
        }

        $agency->update([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? $agency->status,
        ]);

        // Log agency update
        activity()
            ->performedOn($agency)
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'name' => $agency->name,
                'status' => $agency->status
            ])
            ->log("Agency {$agency->name} updated");

        return response()->json([
            'success' => true,
            'message' => 'Agency updated successfully',
            'data' => $agency
        ]);
    }

    public function destroy(Agency $agency)
    {
        // Log agency deletion
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'name' => $agency->name,
                'status' => $agency->status
            ])
            ->log("Agency {$agency->name} deleted");

        $agency->delete();
        return response()->json([
            'success' => true,
            'message' => 'Agency deleted successfully'
        ]);
    }

    public function bulkStore(Request $request)
    {
        $user = auth()->user();
        $firmId = session('current_firm_id') ?? $user->firm_id;

        $validated = $request->validate([
            'names' => 'required|array|min:1|max:50',
            'names.*' => 'required|string|max:255',
        ]);

        $inserted = [];
        $skipped = [];

        DB::beginTransaction();
        try {
            foreach ($validated['names'] as $name) {
                $name = trim($name);
                if (empty($name)) continue;

                // Check if agency already exists in this firm
                $existing = Agency::where('name', $name)
                    ->where('firm_id', $firmId)
                    ->first();

                if ($existing) {
                    $skipped[] = $name;
                    continue;
                }

                $agency = Agency::create([
                    'name' => $name,
                    'status' => 'active',
                    'firm_id' => $firmId,
                ]);

                $inserted[] = $agency;
            }
            DB::commit();

            // Log bulk import
            if (count($inserted) > 0) {
                activity()
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'ip' => request()->ip(),
                        'imported_count' => count($inserted),
                        'skipped_count' => count($skipped),
                        'agency_names' => collect($inserted)->pluck('name')->toArray()
                    ])
                    ->log("Bulk imported " . count($inserted) . " agencies");
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error importing agencies: ' . $e->getMessage()
            ], 500);
        }

        $message = count($inserted) . ' agencies imported successfully';
        if (count($skipped) > 0) {
            $message .= ', ' . count($skipped) . ' skipped (already exist)';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'inserted' => $inserted,
                'skipped' => $skipped
            ]
        ]);
    }
} 