@extends('admin.layout')

@section('content')
    <style>
        .report-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .stat-card {
            padding: 18px;
        }

        .stat-label {
            margin: 0 0 10px;
            color: #64748b;
            font-size: 13px;
            font-weight: 700;
        }

        .stat-value {
            margin: 0;
            color: #172536;
            font-size: 28px;
            font-weight: 800;
        }

        .stat-note {
            margin: 8px 0 0;
            color: #6b7f93;
            font-size: 13px;
        }

        .report-panels {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .panel {
            padding: 20px;
        }

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .panel-title h4 {
            margin: 0;
            color: #172536;
            font-size: 17px;
        }

        .muted {
            color: #64748b;
            font-size: 13px;
        }

        .line-chart {
            display: flex;
            align-items: end;
            gap: 12px;
            height: 210px;
            padding: 18px;
            border-radius: 8px;
            background:
                linear-gradient(#e8eef4 1px, transparent 1px) 0 0 / 100% 25%,
                #fbfdff;
            border: 1px solid #e1e8f0;
        }

        .chart-bar {
            flex: 1;
            min-width: 22px;
            border-radius: 7px 7px 0 0;
            background: linear-gradient(180deg, #7fa6bf, #20384d);
        }

        .chart-column {
            flex: 1;
            display: flex;
            min-width: 34px;
            flex-direction: column;
            align-items: center;
            justify-content: end;
            gap: 8px;
            height: 100%;
        }

        .chart-column .chart-bar {
            width: 100%;
            flex: none;
        }

        .chart-label {
            color: #64748b;
            font-size: 12px;
        }

        .donut-box {
            display: grid;
            grid-template-columns: 160px 1fr;
            align-items: center;
            gap: 22px;
            min-height: 210px;
        }

        .donut {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            position: relative;
        }

        .donut:after {
            content: "";
            position: absolute;
            inset: 36px;
            border-radius: 50%;
            background: #ffffff;
        }

        .legend {
            display: grid;
            gap: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            color: #44566c;
            font-size: 14px;
        }

        .legend-text {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-count {
            color: #172536;
            font-weight: 700;
        }

        .dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: #20384d;
        }

        .dot.light {
            background: #7fa6bf;
        }

        .dot.soft {
            background: #d7e0ea;
        }

        .export-row {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .performance-card {
            padding: 20px;
        }

        .id-badge {
            display: inline-flex;
            min-width: 46px;
            justify-content: center;
            padding: 6px 9px;
            border-radius: 20px;
            color: #20384d;
            font-size: 12px;
            font-weight: 800;
            background: #edf4f8;
        }

        .progress-track {
            width: 100%;
            height: 8px;
            overflow: hidden;
            border-radius: 20px;
            background: #edf2f7;
        }

        .progress-fill {
            height: 100%;
            border-radius: inherit;
            background: #2f526d;
        }

        @media (max-width: 980px) {

            .report-grid,
            .report-panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .donut-box {
                grid-template-columns: 1fr;
                justify-items: center;
                text-align: center;
            }

            .legend {
                justify-items: start;
            }
        }
    </style>

    @php
        $topCases = max($praharis->max('cases_count'), 1);
    @endphp

    <div class="page-header">
        <div>
            <h3>Reports</h3>
            <p>Quick summary of cases, challans, earnings and Prahari performance.</p>
        </div>

        <a class="btn primary" href="{{ route('reports.export') }}">Export Report</a>
    </div>

    @if(\App\Models\Setting::get('show_reports_stats', '1') == '1')
    <div class="report-grid">
        <div class="card stat-card">
            <p class="stat-label">Total Cases</p>
            <p class="stat-value">{{ number_format($totalCases) }}</p>
            <p class="stat-note">All registered cases</p>
        </div>

        <div class="card stat-card">
            <p class="stat-label">Total Challans</p>
            <p class="stat-value">{{ number_format($totalChallans) }}</p>
            <p class="stat-note">Created challan records</p>
        </div>

        <div class="card stat-card">
            <p class="stat-label">Total Earnings</p>
            <p class="stat-value">Rs {{ number_format($totalEarnings) }}</p>
            <p class="stat-note">Credit payments received</p>
        </div>
    </div>
    @endif

    <div class="report-panels">
        @if(\App\Models\Setting::get('show_reports_cases_trend', '1') == '1')
        <div class="card panel">
            <div class="panel-title">
                <h4>Cases Trend</h4>
                <span class="muted">This month</span>
            </div>

            <div class="line-chart">
                @foreach ($casesTrend as $index => $count)
                    <div class="chart-column" title="{{ $casesTrendLabels[$index] }}: {{ $count }} cases">
                        <div class="chart-bar" style="height: {{ ($count / $maxCasesTrend) * 100 }}%;"></div>
                        <span class="chart-label">{{ $casesTrendLabels[$index] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(\App\Models\Setting::get('show_reports_challan_status', '1') == '1')
        <div class="card panel">
            <div class="panel-title">
                <h4>Challan Status</h4>
                <span class="muted">Overview</span>
            </div>

            <div class="donut-box">
                <div class="donut" style="background:
                    @if ($totalStatus > 0)
                        conic-gradient(
                            #20384d 0 {{ $paidPercent }}%,
                            #7fa6bf {{ $paidPercent }}% {{ $pendingEndPercent }}%,
                            #d7e0ea {{ $pendingEndPercent }}% 100%
                        )
                    @else
                        #d7e0ea
                    @endif
                ;"></div>
                <div class="legend">
                    <div class="legend-item">
                        <span class="legend-text"><span class="dot"></span> Paid challans</span>
                        <span class="legend-count">{{ $paid }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-text"><span class="dot light"></span> Pending challans</span>
                        <span class="legend-count">{{ $pending }}</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-text"><span class="dot soft"></span> Cancelled challans</span>
                        <span class="legend-count">{{ $cancelled }}</span>
                    </div>
                </div>
            </div>

            <div class="export-row">
                <a class="btn" href="{{ route('reports.export') }}">CSV</a>
                {{-- <button class="btn" type="button">CSV</button> --}}
            </div>
        </div>
        @endif
    </div>

    @if(\App\Models\Setting::get('show_reports_performance', '1') == '1')
    <div class="card performance-card">
        <div class="panel-title">
            <h4>Prahari Performance</h4>
            <span class="muted">{{ $praharis->count() }} Prahari listed</span>
        </div>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Total Cases</th>
                    <th>Performance</th>
                </tr>

                @foreach ($praharis as $p)
                    <tr>
                        <td><span class="id-badge">P{{ $p->id }}</span></td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->cases_count }}</td>
                        <td>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ ($p->cases_count / $topCases) * 100 }}%;"></div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
@endsection
