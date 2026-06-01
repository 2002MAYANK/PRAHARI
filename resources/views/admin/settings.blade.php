@extends('admin.layout')

@section('content')
    <h3>Settings</h3>

    <hr>

    @if (session('success'))
        <div
            style="padding: 12px 16px; margin-bottom: 18px; border-radius: 8px; color: #166534; background: #ecfdf3; border: 1px solid #bbf7d0; font-size: 14px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/admin/settings">
        @csrf

        {{-- Challan Amount --}}
        {{-- <label
            style="display: block; margin-bottom: 6px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px;">Challan
            Amount (₹)</label>
        <input type="number" name="challan_amount" placeholder="Enter challan amount" value="{{ $challanAmount ?? '500' }}"
            min="0" step="1">
        <p style="margin: 6px 0 20px; color: #94a3b8; font-size: 12px;">This amount will be used as the default challan
            amount across the system.</p> --}}

        {{-- Dashboard Visibility --}}
        <label
            style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px;">Dashboard
            Sections</label>
        <div style="display: grid; gap: 8px; margin-bottom: 6px;">
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_dashboard_stats" value="1"
                    {{ ($showDashboardStats ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Stats Cards (Prahari, Cases, Challans, Revenue, etc.)
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_dashboard_cases_chart" value="1"
                    {{ ($showDashboardCasesChart ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Cases Overview Chart
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_dashboard_revenue_chart" value="1"
                    {{ ($showDashboardRevenueChart ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Revenue Overview Chart
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_dashboard_challan_chart" value="1"
                    {{ ($showDashboardChallanChart ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Challan Status Chart
            </label>
        </div>
        <p style="margin: 6px 0 20px; color: #94a3b8; font-size: 12px;">Choose which sections to display on the dashboard
            page.</p>

        {{-- Reports Visibility --}}
        <label
            style="display: block; margin-bottom: 10px; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.3px;">Reports
            Sections</label>
        <div style="display: grid; gap: 8px; margin-bottom: 6px;">
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_reports_stats" value="1"
                    {{ ($showReportsStats ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Stats Cards (Total Cases, Challans, Earnings)
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_reports_cases_trend" value="1"
                    {{ ($showReportsCasesTrend ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Cases Trend Chart
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_reports_challan_status" value="1"
                    {{ ($showReportsChallanStatus ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Challan Status Donut
            </label>
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #233142; margin: 0;">
                <input type="checkbox" name="show_reports_performance" value="1"
                    {{ ($showReportsPerformance ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; min-height: 18px; cursor: pointer;">
                Prahari Performance Table
            </label>
        </div>
        <p style="margin: 6px 0 20px; color: #94a3b8; font-size: 12px;">Choose which sections to display on the reports
            page.</p>

        <button type="submit">
            Save Settings
        </button>

    </form>
@endsection
