<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgencyController extends Controller
{
    public function index()
    {
        return response()->json(['success'=>true,'data'=>Agency::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name',
            'status' => 'nullable|in:active,inactive',
        ]);
        $agency = Agency::create([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? 'active',
        ]);
        return response()->json(['success'=>true,'message'=>'Agency created','data'=>$agency]);
    }

    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name,' . $agency->id,
            'status' => 'nullable|in:active,inactive',
        ]);
        $agency->update([
            'name' => $validated['name'],
            'status' => $validated['status'] ?? $agency->status,
        ]);
        return response()->json(['success'=>true,'message'=>'Agency updated','data'=>$agency]);
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return response()->json(['success'=>true,'message'=>'Agency deleted']);
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'names' => 'required|array|min:1|max:50',
            'names.*' => 'required|string|max:255',
        ]);
        $inserted = [];
        DB::beginTransaction();
        try {
            foreach ($validated['names'] as $name) {
                $inserted[] = Agency::firstOrCreate(['name' => $name], ['status' => 'active']);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 500);
        }
        return response()->json(['success'=>true,'message'=>'Agencies imported','data'=>$inserted]);
    }
} 