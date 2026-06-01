@extends('admin.layout')

@section('content')

    <h3>Payments / Withdrawals</h3>

    {{-- TAB NAVIGATION --}}
    <div class="payment-tabs">
        <button class="payment-tab active" data-tab="all-transactions" id="tab-all-transactions">All Transactions</button>
        <button class="payment-tab" data-tab="withdrawal-requests" id="tab-withdrawal-requests">Withdrawal Requests</button>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="payment-toolbar">
        <div class="search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                <circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />
            </svg>
            <input type="text" id="payment-search" placeholder="Search by Prahari, Amount, Status..." />
        </div>
        <button class="btn filter-btn" id="filter-toggle">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
            Filter
        </button>
    </div>

    {{-- ALL TRANSACTIONS TAB --}}
    <div class="tab-content active" id="content-all-transactions">
        @if (isset($payments))
            <div class="table-wrap">
                <table id="table-all-transactions">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Prahari</th>
                            <th>Amount</th>
                            <th>Bank Account</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $p)
                            <tr>
                                <td>WO{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $p->prahari->name ?? 'Not assigned' }}</td>
                                <td>₹ {{ number_format($p->amount, 0) }}</td>
                                <td>{{ $p->bank_account ? '••••••••' . substr($p->bank_account, -4) : 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ in_array($p->status, ['Approved', 'Paid']) ? 'success' : ($p->status == 'Rejected' ? 'danger' : 'warning') }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                                <td>
                                    <form method="POST" action="/admin/payments/{{ $p->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn" title="Delete">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($payments->isEmpty())
                <div class="empty-state">No transactions found.</div>
            @endif
        @endif
    </div>

    {{-- WITHDRAWAL REQUESTS TAB --}}
    <div class="tab-content" id="content-withdrawal-requests">
        @if (isset($pendingPayments))
            <div class="table-wrap">
                <table id="table-withdrawal-requests">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Prahari</th>
                            <th>Amount</th>
                            <th>Bank Account</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingPayments as $p)
                            <tr>
                                <td>WO{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $p->prahari->name ?? 'Not assigned' }}</td>
                                <td>₹ {{ number_format($p->amount, 0) }}</td>
                                <td>{{ $p->bank_account ? '••••••••' . substr($p->bank_account, -4) : 'N/A' }}</td>
                                <td>
                                    <span class="status-badge warning">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>{{ $p->created_at->format('d M Y') }}</td>
                                <td class="action-cell">
                                    <form method="POST" action="/admin/payments/{{ $p->id }}/approve" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn approve-btn" title="Approve">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="/admin/payments/{{ $p->id }}/reject" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn reject-btn" title="Reject">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pendingPayments->isEmpty())
                <div class="empty-state">No pending withdrawal requests.</div>
            @endif
        @endif
    </div>

    <style>
        /* Tabs */
        .payment-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .payment-tab {
            position: relative;
            min-height: 42px;
            padding: 0 20px;
            border: none;
            border-radius: 0;
            background: transparent;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .payment-tab:hover {
            color: #233142;
            background: transparent;
            transform: none;
            box-shadow: none;
        }

        .payment-tab.active {
            color: #20384d;
            background: transparent;
            transform: none;
            box-shadow: none;
        }

        .payment-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #20384d;
            border-radius: 2px 2px 0 0;
        }

        /* Toolbar */
        .payment-toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            max-width: 420px;
            min-height: 40px;
            padding: 0 14px;
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 8px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-box:focus-within {
            border-color: #7fa6bf;
            box-shadow: 0 0 0 3px rgba(127, 166, 191, 0.18);
        }

        .search-box input {
            flex: 1;
            min-height: 38px;
            padding: 0;
            border: none;
            background: transparent;
            font-size: 13px;
            outline: none;
        }

        .search-box input:focus {
            border: none;
            box-shadow: none;
        }

        .filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 40px;
            padding: 0 14px;
            font-size: 13px;
        }

        /* Tab content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Action buttons */
        .action-cell {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            min-height: 32px;
            padding: 0;
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .approve-btn {
            color: #166534;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
        }

        .approve-btn:hover {
            background: #dcfce7;
            box-shadow: 0 4px 12px rgba(22, 101, 52, 0.15);
            transform: translateY(-1px);
        }

        .reject-btn {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .reject-btn:hover {
            background: #fee2e2;
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.15);
            transform: translateY(-1px);
        }

        .delete-btn {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
            margin-left: 0;
        }

        .delete-btn:hover {
            background: #fee2e2;
            box-shadow: 0 4px 12px rgba(153, 27, 27, 0.15);
            transform: translateY(-1px);
        }

        /* Empty state */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            background: #ffffff;
            border: 1px solid #dfe7ef;
            border-radius: 8px;
            margin-top: 16px;
        }
    </style>

    <script>
        // Tab switching
        document.querySelectorAll('.payment-tab').forEach(function(tab) {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.payment-tab').forEach(function(t) { t.classList.remove('active'); });
                document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });

                tab.classList.add('active');
                document.getElementById('content-' + tab.dataset.tab).classList.add('active');
            });
        });

        // Search functionality
        document.getElementById('payment-search').addEventListener('input', function() {
            var query = this.value.toLowerCase();
            var activeTab = document.querySelector('.tab-content.active');
            var rows = activeTab.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>

@endsection
