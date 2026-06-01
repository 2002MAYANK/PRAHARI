<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Challan;
use App\Models\CaseModel;
use App\Models\Prahari;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $balance = Payment::sum('amount');

        return response()->json([

            'status' => true,

            'data' => [

                'wallet_balance' => $balance

            ]

        ]);
    }

    public function transactions()
    {
        $transactions = Payment::with('prahari')->get();

        return response()->json([

            'status' => true,
            'data' => $transactions

        ]);
    }

    public function withdrawRequest(Request $request)
    {
        $prahariId = Auth::id();

        // Fetch prahari with bank details
        $prahari = Prahari::find($prahariId);

        if (!$prahari) {
            return response()->json([
                'status' => false,
                'message' => 'Prahari not found'
            ], 404);
        }

        // Get all pending challans for this prahari with their case details
        $pendingChallans = Challan::with('case')
            ->where('prahari_id', $prahariId)
            ->where('status', 'Pending')
            ->get();

        if ($pendingChallans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No pending challans found for withdrawal'
            ]);
        }

        // Auto-sum all pending challan amounts
        $totalAmount = $pendingChallans->sum('amount');

        // Extract unique case types from pending challans
        $caseTypes = $pendingChallans
            ->pluck('case')
            ->filter()
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();

        // Create a withdrawal (debit) payment record
        $payment = Payment::create([
            'prahari_id' => $prahariId,
            'amount' => $totalAmount,
            'bank_account' => $prahari->account_number,
            'type' => 'debit',
            'status' => 'Pending'
        ]);

        // Mark all pending challans as "Processing"
        Challan::where('prahari_id', $prahariId)
            ->where('status', 'Pending')
            ->update(['status' => 'Processing']);

        return response()->json([

            'status' => true,
            'message' => 'Withdrawal request submitted successfully',

            'data' => [

                'withdrawal' => [
                    'payment_id' => $payment->id,
                    'total_amount' => $totalAmount,
                    'challan_count' => $pendingChallans->count(),
                    'status' => $payment->status,
                    'created_at' => $payment->created_at
                ],

                'prahari_bank_details' => [
                    'name' => $prahari->name,
                    'bank_name' => $prahari->bank_name,
                    'account_number' => $prahari->account_number,
                    'ifsc_code' => $prahari->ifsc_code
                ],

                'case_types' => $caseTypes,

                'challans' => $pendingChallans->map(function ($challan) {
                    return [
                        'challan_id' => $challan->id,
                        'amount' => $challan->amount,
                        'case_type' => $challan->case ? $challan->case->type : null,
                        'status' => 'Processing',
                        'created_at' => $challan->created_at
                    ];
                })

            ]

        ]);
    }
}
