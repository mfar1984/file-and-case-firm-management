<?php

namespace App\Http\Controllers;

use App\Models\Payee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayeeController extends Controller
{
    public function index()
    {
        $payees = Payee::orderBy('name')->get();
        return view('settings.payee.index', compact('payees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'category' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payee = Payee::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Payee added successfully',
                'payee' => $payee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'category' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payee = Payee::findOrFail($id);
            $payee->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Payee updated successfully',
                'payee' => $payee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payee = Payee::findOrFail($id);
            $payee->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Payee deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $payee = Payee::findOrFail($id);
            $payee->is_active = !$payee->is_active;
            $payee->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Payee status updated successfully',
                'is_active' => $payee->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payee status: ' . $e->getMessage()
            ], 500);
        }
    }
}
