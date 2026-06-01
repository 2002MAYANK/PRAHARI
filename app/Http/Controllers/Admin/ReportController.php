<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prahari;
use App\Models\CaseModel;
use App\Models\Challan;
use App\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $totalCases = CaseModel::count();
        $totalChallans = Challan::count();
        $totalEarnings = Challan::where('status', 'Paid')->sum('amount');

        // Prahari performance (simple)
        $praharis = Prahari::withCount('cases')->get();

        // Cases trend for the last 7 months.
        $caseMonths = collect(range(6, 0))->map(function ($monthBack) {
            return Carbon::now()->subMonths($monthBack);
        });

        $casesTrend = $caseMonths->map(function ($month) {
            return CaseModel::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });

        $casesTrendLabels = $caseMonths->map(function ($month) {
            return $month->format('M');
        });

        $maxCasesTrend = max($casesTrend->max(), 1);

        // CHALLAN STATUS
        $paid = Challan::where('status', 'Paid')->count();
        $pending = Challan::where('status', 'Pending')->count();
        $cancelled = Challan::where('status', 'Cancelled')->count();

        $totalStatus = $paid + $pending + $cancelled;

        // percentage calculation
        $paidPercent = $totalStatus ? ($paid / $totalStatus) * 100 : 0;
        $pendingPercent = $totalStatus ? ($pending / $totalStatus) * 100 : 0;
        $cancelledPercent = $totalStatus ? ($cancelled / $totalStatus) * 100 : 0;
        $pendingEndPercent = $paidPercent + $pendingPercent;

        return view('admin.reports', compact(
            'totalCases',
            'totalChallans',
            'totalEarnings',
            'praharis',
            'casesTrend',
            'casesTrendLabels',
            'maxCasesTrend',
            'paid',
            'pending',
            'cancelled',
            'paidPercent',
            'pendingPercent',
            'cancelledPercent',
            'pendingEndPercent',
            'totalStatus'
        ));
    }

    public function export()
    {
        $totalCases = CaseModel::count();
        $totalChallans = Challan::count();
        // $totalEarnings = Challan::where('status', 'Paid')->sum('amount');
        $totalEarnings = Payment::where('status', 'Approved')->sum('amount');

        $paid = Challan::where('status', 'Paid')->count();
        $pending = Challan::where('status', 'Pending')->count();
        $cancelled = Challan::where('status', 'Cancelled')->count();

        $caseMonths = collect(range(6, 0))->map(function ($monthBack) {
            return Carbon::now()->subMonths($monthBack);
        });

        $casesTrend = $caseMonths->map(function ($month) {
            return [
                'month' => $month->format('M Y'),
                'cases' => CaseModel::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        });

        $praharis = Prahari::withCount('cases')->orderBy('name')->get();
        $fileName = 'prahari-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use (
            $totalCases,
            $totalChallans,
            $totalEarnings,
            $paid,
            $pending,
            $cancelled,
            $casesTrend,
            $praharis
        ) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Prahari Report']);
            fputcsv($file, ['Generated At', now()->format('d M Y h:i A')]);
            fputcsv($file, []);

            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Cases', $totalCases]);
            fputcsv($file, ['Total Challans', $totalChallans]);
            fputcsv($file, ['Total Earnings', $totalEarnings]);
            fputcsv($file, []);

            fputcsv($file, ['Challan Status']);
            fputcsv($file, ['Paid', $paid]);
            fputcsv($file, ['Pending', $pending]);
            fputcsv($file, ['Cancelled', $cancelled]);
            fputcsv($file, []);

            fputcsv($file, ['Cases Trend']);
            fputcsv($file, ['Month', 'Cases']);
            foreach ($casesTrend as $row) {
                fputcsv($file, [$row['month'], $row['cases']]);
            }
            fputcsv($file, []);

            fputcsv($file, ['Prahari Performance']);
            fputcsv($file, ['ID', 'Name', 'Phone', 'Total Cases', 'Status']);
            foreach ($praharis as $prahari) {
                fputcsv($file, [
                    'P' . $prahari->id,
                    $prahari->name,
                    $prahari->phone,
                    $prahari->cases_count,
                    $prahari->is_active ? 'Active' : 'Inactive',
                ]);
            }

            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
