<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CaseModel;
use App\Models\Challan;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function dashboardStats()
    {
        $userId = Auth::id();

        $totalCases = CaseModel::where('prahari_id', $userId)->count();

        $totalChallans = Challan::where('prahari_id', $userId)->count();

        $totalEarnings = Payment::where('prahari_id', $userId)
            ->where('type', 'credit')
            ->sum('amount');

        return response()->json([

            'status' => true,

            'data' => [

                'total_cases' => $totalCases,
                'total_challans' => $totalChallans,
                'total_earnings' => $totalEarnings

            ]

        ]);
    }

    public function recentCases()
    {
        $cases = CaseModel::where('prahari_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return response()->json([

            'status' => true,
            'data' => $cases

        ]);
    }

    public function recentChallans()
    {
        $challans = Challan::where('prahari_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return response()->json([

            'status' => true,
            'data' => $challans

        ]);
    }
}
