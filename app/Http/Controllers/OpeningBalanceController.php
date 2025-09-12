<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpeningBalance;
use Illuminate\Support\Facades\Validator;

class OpeningBalanceController extends Controller
{
    public function index()
    {
        try {
            $openingBalances = OpeningBalance::active()->orderBy('bank_code')->get();
            return response()->json($openingBalances);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load opening balances: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Get current firm context for validation
            $user = auth()->user();
            $firmId = session('current_firm_id') ?? $user->firm_id;

            $validator = Validator::make($request->all(), [
                'bank_code' => 'required|string|max:20|unique:opening_balances,bank_code,NULL,id,firm_id,' . $firmId,
                'bank_name' => 'required|string|max:255',
                'currency' => 'required|string|max:10',
                'debit' => 'nullable|numeric|min:0',
                'credit' => 'nullable|numeric|min:0',
                'exchange_rate' => 'nullable|numeric|min:0.0001'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!$firmId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No firm context available'
                ], 400);
            }

            $data = $request->all();
            $data['firm_id'] = $firmId;
            $data['debit'] = $data['debit'] ?? 0;
            $data['credit'] = $data['credit'] ?? 0;
            $data['exchange_rate'] = $data['exchange_rate'] ?? 1.0000;

            // Calculate MYR amounts
            if ($data['currency'] !== 'MYR') {
                $data['debit_myr'] = $data['debit'] * $data['exchange_rate'];
                $data['credit_myr'] = $data['credit'] * $data['exchange_rate'];
            } else {
                $data['debit_myr'] = $data['debit'];
                $data['credit_myr'] = $data['credit'];
            }

            $openingBalance = OpeningBalance::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Opening balance created successfully',
                'data' => $openingBalance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create opening balance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $openingBalance = OpeningBalance::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'bank_code' => 'required|string|max:20|unique:opening_balances,bank_code,' . $id,
                'bank_name' => 'required|string|max:255',
                'currency' => 'required|string|max:10',
                'debit' => 'nullable|numeric|min:0',
                'credit' => 'nullable|numeric|min:0',
                'exchange_rate' => 'nullable|numeric|min:0.0001'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            $data['debit'] = $data['debit'] ?? 0;
            $data['credit'] = $data['credit'] ?? 0;
            $data['exchange_rate'] = $data['exchange_rate'] ?? 1.0000;

            // Calculate MYR amounts
            if ($data['currency'] !== 'MYR') {
                $data['debit_myr'] = $data['debit'] * $data['exchange_rate'];
                $data['credit_myr'] = $data['credit'] * $data['exchange_rate'];
            } else {
                $data['debit_myr'] = $data['debit'];
                $data['credit_myr'] = $data['credit'];
            }

            $openingBalance->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Opening balance updated successfully',
                'data' => $openingBalance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update opening balance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $openingBalance = OpeningBalance::findOrFail($id);
            $openingBalance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Opening balance deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete opening balance: ' . $e->getMessage()
            ], 500);
        }
    }
}
