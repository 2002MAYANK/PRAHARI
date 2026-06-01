@extends('admin.layout')

@section('content')

    <h3>Challans</h3>

    <a href="/admin/challans/create">Add Challan</a>

    <hr>
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

    {{-- LIST --}}
    @if (isset($challans))
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Prahari ID</th>
                    <th>Case ID</th>
                    <th>Prahari</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                @foreach ($challans as $c)
                    <tr>
                        <td>{{ $c->prahari_id }}</td>
                        <td>{{ $c->case_model_id }}</td>
                        <td>{{ $c->prahari->name ?? ($c->case->prahari->name ?? 'Not assigned') }}</td>
                        <td>{{ $c->amount }}</td>
                        <td>
                            <span
                                class="status-badge {{ $c->status == 'Paid' ? 'success' : ($c->status == 'Cancelled' ? 'danger' : 'warning') }}">
                                {{ $c->status }}
                            </span>
                        </td>
                        <td>{{ $c->created_at ? $c->created_at->format('d M Y') : 'N/A' }}</td>
                        <td>
                            <a href="/admin/challans/{{ $c->id }}/edit">Edit</a>

                            <form method="POST" action="/admin/challans/{{ $c->id }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    @endif

    <hr>

    {{-- CREATE / EDIT --}}
    @if (isset($challan) || request()->is('admin/challans/create'))
        @if ($errors->any())
            <div style="color:red; margin-bottom:15px;">

                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach

            </div>
        @endif
        <form method="POST" action="{{ isset($challan) ? '/admin/challans/' . $challan->id : '/admin/challans' }}">

            @csrf

            @if (isset($challan))
                @method('PUT')
            @endif

            <input type="number" name="prahari_id" placeholder="Prahari ID"
                value="{{ old('prahari_id', $challan->prahari_id ?? '') }}"><br><br>

            <input type="number" name="case_model_id" placeholder="Case ID"
                value="{{ old('case_model_id', $challan->case_model_id ?? '') }}" id="case-model-id"><br><br>

            <input type="number" name="amount" placeholder="Amount"
                value="{{ old('amount', $challan->amount ?? '') }}" id="challan-amount" readonly><br><br>

            <select name="status">
                @php
                    $selectedStatus = old('status', $challan->status ?? 'Pending');
                @endphp
                <option {{ $selectedStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option {{ $selectedStatus === 'Paid' ? 'selected' : '' }}>Paid</option>
                <option {{ $selectedStatus === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select><br><br>

            <button type="submit">
                {{ isset($challan) ? 'Update' : 'Create' }}
            </button>

        </form>
    @endif
    <style>
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

    </style>

    <script>
        var caseAmounts = @json($caseAmounts ?? []);
        var caseInput = document.getElementById('case-model-id');
        var amountInput = document.getElementById('challan-amount');

        function updateChallanAmount() {
            if (!caseInput || !amountInput) {
                return;
            }

            amountInput.value = caseAmounts[caseInput.value] || '';
        }

        if (caseInput && amountInput) {
            caseInput.addEventListener('input', updateChallanAmount);
            if (!amountInput.value) {
                updateChallanAmount();
            }
        }

         // Search functionality
        document.getElementById('payment-search').addEventListener('input', function() {
            var query = this.value.toLowerCase();
            var activeTab = document.querySelector('.table-wrap'); 
            var rows = activeTab.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>

@endsection
