@extends('admin.layout')

@section('content')

    <h3>Cases</h3>

    <a href="/admin/cases/create">Add Case</a>

    <hr>
    {{-- SEARCH & FILTER BAR --}}
    <div class="payment-toolbar">
        <div class="search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <input type="text" id="payment-search" placeholder="Search by Prahari, Case ID, Status..." />
        </div>
        <button class="btn filter-btn" id="filter-toggle">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
            Filter
        </button>
    </div>

    @if (isset($cases))
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Case ID</th>
                    <th>Prahari</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>

                @foreach ($cases as $case)
                    <tr>
                        <td>{{ $case->id }}</td>
                        <td>{{ $case->prahari->name ?? 'Not assigned' }}</td>
                        <td>{{ $case->type ?? $case->title }}</td>
                        <td>{{ $case->location ?? 'N/A' }}</td>
                        <td><span class="status-badge">{{ $case->status }}</span></td>
                        <td>{{ $case->created_at ? $case->created_at->format('d M Y') : 'N/A' }}</td>
                        <td>
                            @php
                                $videoChallan = $case->challans->firstWhere('video_path', '!=', null);
                            @endphp
                            <button type="button" title="View Video Challan"
                                onclick="openVideoModal('{{ $videoChallan ? asset('storage/' . $videoChallan->video_path) : '' }}')"
                                style="background:none;border:none;cursor:pointer;padding:0 6px 0 0;vertical-align:middle;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>

                            <a href="/admin/cases/{{ $case->id }}/edit">Edit</a>

                            <form method="POST" action="/admin/cases/{{ $case->id }}" style="display:inline;">
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

    {{-- CREATE / EDIT FORM --}}
    {{-- @if (isset($case) || request()->is('admin/cases/create')) --}}
    @if (request()->is('admin/cases/create') || request()->is('admin/cases/*/edit'))
        @if ($errors->any())
            <div style="color:red; margin-bottom:15px;">

                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach

            </div>
        @endif
        <form method="POST" action="{{ isset($case) ? '/admin/cases/' . $case->id : '/admin/cases' }}">

            @csrf

            @if (isset($case))
                @method('PUT')
            @endif

            <input type="number" name="prahari_id" placeholder="Prahari ID" value="{{ $case->prahari_id ?? '' }}"><br><br>

            {{-- <input type="text" name="title" placeholder="Title" value="{{ $case->title ?? '' }}"><br><br> --}}

            @php
                $selectedType = old('type', $case->type ?? '');
                $selectedAmount = $challanTypes[$selectedType] ?? '';
            @endphp
            <select name="type" id="case-type" required>
                <option value="">Select Challan Type</option>
                @foreach (($challanTypes ?? []) as $type => $amount)
                    <option value="{{ $type }}" data-amount="{{ $amount }}" {{ $selectedType === $type ? 'selected' : '' }}>
                        {{ $type }} - ₹ {{ number_format($amount, 0) }}
                    </option>
                @endforeach
            </select><br><br>

            <input type="number" id="case-amount" placeholder="Amount" value="{{ $selectedAmount }}" readonly><br><br>

            <input type="text" name="location" placeholder="Location" value="{{ $case->location ?? '' }}"><br><br>

            <textarea name="description" placeholder="Description">{{ $case->description ?? '' }}</textarea><br><br>

            <select name="status">
                <option>Open</option>
                <option>In Progress</option>
                <option>Closed</option>
            </select><br><br>

            <button type="submit">
                {{ isset($case) ? 'Update' : 'Create' }}
            </button>

        </form>
    @endif

    {{-- VIDEO CHALLAN MODAL --}}
    <div id="videoChallanModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;justify-content:center;align-items:center;"
        onclick="closeVideoChallanModal(event)">
        <div style="position:relative;background:#fff;padding:20px;border-radius:8px;max-width:640px;width:92%;">
            <button class="vcm-close" onclick="closeVideoChallanModal(event)"
                style="position:absolute;top:8px;right:8px;width:28px;height:28px;border:none;border-radius:50%;background:#eee;cursor:pointer;font-size:18px;font-weight:bold;line-height:1;">&times;</button>
            <div id="vcmVideoWrap">
                <video id="vcmVideo" controls style="width:100%;max-height:65vh;display:block;"></video>
            </div>
            <div id="vcmNoVideo" style="display:none;text-align:center;padding:30px;color:#666;">No video challan uploaded
                for this case.</div>
        </div>
    </div>

    <script>
        function openVideoModal(url) {
            var m = document.getElementById('videoChallanModal');
            var v = document.getElementById('vcmVideo');
            var vw = document.getElementById('vcmVideoWrap');
            var nv = document.getElementById('vcmNoVideo');
            if (url && url.trim() !== '') {
                v.src = url;
                vw.style.display = 'block';
                nv.style.display = 'none';
            } else {
                v.src = '';
                vw.style.display = 'none';
                nv.style.display = 'block';
            }
            m.style.display = 'flex';
        }

        function closeVideoChallanModal(e) {
            if (e.target.id === 'videoChallanModal' || e.currentTarget.classList.contains('vcm-close')) {
                var v = document.getElementById('vcmVideo');
                v.pause();
                v.src = '';
                document.getElementById('videoChallanModal').style.display = 'none';
            }
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var m = document.getElementById('videoChallanModal');
                if (m.style.display === 'flex') {
                    var v = document.getElementById('vcmVideo');
                    v.pause();
                    v.src = '';
                    m.style.display = 'none';
                }
            }
        });

        var caseType = document.getElementById('case-type');
        var caseAmount = document.getElementById('case-amount');
        if (caseType && caseAmount) {
            caseType.addEventListener('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                caseAmount.value = selectedOption ? selectedOption.getAttribute('data-amount') || '' : '';
            });
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

@endsection
