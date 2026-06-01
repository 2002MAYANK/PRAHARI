@extends('admin.layout')

@section('content')
    <style>
        .dashboard-container {
            padding: 5px;
            background: #f4f6f9;
            min-height: 100vh;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #1e293b;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card h4 {
            font-size: 15px;
            color: gray;
            margin-bottom: 10px;
        }

        .card h2 {
            font-size: 28px;
            color: #0f172a;
        }

        .graph-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .graph-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .graph-title {
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
    </style>

    <div class="dashboard-container">

        <div class="dashboard-title">
            Dashboard
        </div>

        {{-- CARDS --}}
        @if(\App\Models\Setting::get('show_dashboard_stats', '1') == '1')
        <div class="card-container">

            <div class="card">
                <h4>Total Prahari</h4>
                <h2>{{ $praharis }}</h2>
            </div>

            <div class="card">
                <h4>Total Cases</h4>
                <h2>{{ $cases }}</h2>
            </div>

            <div class="card">
                <h4>Total Challans</h4>
                <h2>{{ $challans }}</h2>
            </div>

            <div class="card">
                <h4>Total Revenue</h4>
                <h2>₹ {{ $revenue }}</h2>
            </div>
            <div class="card">
                <h4>Pending Withdrawals</h4>
                <h2>{{ $pendingWithdrawals }}</h2>
            </div>
            <div class="card">
                <h4>Today's Cases</h4>
                <h2>{{ $todaysCases }}</h2>
            </div>
            <div class="card">
                <h4>Today's Challans</h4>
                <h2>{{ $todaysChallans }}</h2>
            </div>
            <div class="card">
                <h4>Active Prahari</h4>
                <h2>{{ $activePrahari }}</h2>
            </div>

        </div>
        @endif

        {{-- GRAPHS --}}
        <div class="graph-container">

            @if(\App\Models\Setting::get('show_dashboard_cases_chart', '1') == '1')
            <div class="graph-box">
                <div class="graph-title">Cases Overview</div>
                <canvas id="casesChart"></canvas>
            </div>
            @endif

            @if(\App\Models\Setting::get('show_dashboard_revenue_chart', '1') == '1')
            <div class="graph-box">
                <div class="graph-title">Revenue Overview</div>
                <canvas id="revenueChart"></canvas>
            </div>
            @endif
            @if(\App\Models\Setting::get('show_dashboard_challan_chart', '1') == '1')
            <div class="graph-box">
                <div class="graph-title">Challan Status</div>
                <canvas id="challanStatusChart"></canvas>
            </div>
            @endif

        </div>

    </div>

    {{-- CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // CASES GRAPH
        @if(\App\Models\Setting::get('show_dashboard_cases_chart', '1') == '1')
        const casesChart = document.getElementById('casesChart');

        new Chart(casesChart, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Cases',
                    data: @json($casesData),
                    borderWidth: 3,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true
            }
        });
        @endif

        // REVENUE GRAPH
        @if(\App\Models\Setting::get('show_dashboard_revenue_chart', '1') == '1')
        const revenueChart = document.getElementById('revenueChart');

        new Chart(revenueChart, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenueData),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
        @endif
        // CHALLAN STATUS GRAPH
        @if(\App\Models\Setting::get('show_dashboard_challan_chart', '1') == '1')
        const challanStatusChart = document.getElementById('challanStatusChart');

        new Chart(challanStatusChart, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [{{ $paid }}, {{ $pending }}, {{ $cancelled }}],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
        @endif
    </script>
@endsection
