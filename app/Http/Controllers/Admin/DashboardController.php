<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prahari;
use App\Models\CaseModel;
use App\Models\Challan;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $praharis = Prahari::count();
        $cases = CaseModel::count();
        $challans = Challan::count();
        $revenue = Payment::sum('amount');
        $pendingWithdrawals = Payment::where('type', 'debit')
            ->where('status', 'Pending')
            ->count();
        $todaysCases = CaseModel::whereDate('created_at', today())->count();
        $todaysChallans = Challan::whereDate('created_at', today())->count();
        $activePrahari = Prahari::where('is_active', true)->count();

        // Challan Status Counts
        $paid = Challan::where('status', 'Paid')->count();
        $pending = Challan::where('status', 'Pending')->count();
        $cancelled = Challan::where('status', 'Cancelled')->count();

        // CASES GRAPH (monthly)
        $casesData = [
            CaseModel::whereMonth('created_at', 1)->count(),
            CaseModel::whereMonth('created_at', 2)->count(),
            CaseModel::whereMonth('created_at', 3)->count(),
            CaseModel::whereMonth('created_at', 4)->count(),
            CaseModel::whereMonth('created_at', 5)->count(),
            CaseModel::whereMonth('created_at', 6)->count(),
        ];

        // REVENUE GRAPH (monthly)
        $revenueData = [
            Payment::whereMonth('created_at', 1)->sum('amount'),
            Payment::whereMonth('created_at', 2)->sum('amount'),
            Payment::whereMonth('created_at', 3)->sum('amount'),
            Payment::whereMonth('created_at', 4)->sum('amount'),
            Payment::whereMonth('created_at', 5)->sum('amount'),
            Payment::whereMonth('created_at', 6)->sum('amount'),
        ];

        return view('admin.dashboard', compact(
            'praharis',
            'cases',
            'challans',
            'revenue',
            'pendingWithdrawals',
            'todaysCases',
            'todaysChallans',
            'activePrahari',
            'paid',
            'pending',
            'cancelled',
            'casesData',
            'revenueData'
        ));
    }
}
