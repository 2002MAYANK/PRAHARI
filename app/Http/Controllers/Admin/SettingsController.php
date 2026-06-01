<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        // $challanAmount = Setting::get('challan_amount', '500');

        // Dashboard visibility
        $showDashboardStats = Setting::get('show_dashboard_stats', '1');
        $showDashboardCasesChart = Setting::get('show_dashboard_cases_chart', '1');
        $showDashboardRevenueChart = Setting::get('show_dashboard_revenue_chart', '1');
        $showDashboardChallanChart = Setting::get('show_dashboard_challan_chart', '1');

        // Reports visibility
        $showReportsStats = Setting::get('show_reports_stats', '1');
        $showReportsCasesTrend = Setting::get('show_reports_cases_trend', '1');
        $showReportsChallanStatus = Setting::get('show_reports_challan_status', '1');
        $showReportsPerformance = Setting::get('show_reports_performance', '1');

        return view('admin.settings', compact(
            
            'showDashboardStats',
            'showDashboardCasesChart',
            'showDashboardRevenueChart',
            'showDashboardChallanChart',
            'showReportsStats',
            'showReportsCasesTrend',
            'showReportsChallanStatus',
            'showReportsPerformance'
        ));
    }

    public function update(Request $request)
    {
        // Setting::set('challan_amount', $request->challan_amount);

        // Dashboard visibility
        Setting::set('show_dashboard_stats', $request->has('show_dashboard_stats') ? '1' : '0');
        Setting::set('show_dashboard_cases_chart', $request->has('show_dashboard_cases_chart') ? '1' : '0');
        Setting::set('show_dashboard_revenue_chart', $request->has('show_dashboard_revenue_chart') ? '1' : '0');
        Setting::set('show_dashboard_challan_chart', $request->has('show_dashboard_challan_chart') ? '1' : '0');

        // Reports visibility
        Setting::set('show_reports_stats', $request->has('show_reports_stats') ? '1' : '0');
        Setting::set('show_reports_cases_trend', $request->has('show_reports_cases_trend') ? '1' : '0');
        Setting::set('show_reports_challan_status', $request->has('show_reports_challan_status') ? '1' : '0');
        Setting::set('show_reports_performance', $request->has('show_reports_performance') ? '1' : '0');

        return redirect('/admin/settings')->with('success', 'Settings updated successfully!');
    }
}
