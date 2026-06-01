@extends('admin.layout')

@section('content')

<h3>Prahari</h3>

<a href="/admin/praharis/create">Add Prahari</a>

<hr>
{{-- SEARCH & FILTER BAR --}}
    <div class="payment-toolbar">
        <div class="search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                <circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />
            </svg>
            <input type="text" id="payment-search" placeholder="Search by Name, Phone, Aadhaar..." />
        </div>
        <button class="btn filter-btn" id="filter-toggle">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
            Filter
        </button>
    </div>

{{-- LIST --}}
@if(isset($data))

<div class="table-wrap">
<table>
    <tr>
        <th>Prahari ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Aadhaar</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

    @foreach($data as $p)
    <tr>
        <td>{{ $p->id }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->phone }}</td>
        <td>{{ $p->aadhaar }}</td>
        <td>
            <span class="status-badge {{ $p->is_active ? 'success' : 'danger' }}">
                {{ $p->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td>{{ $p->created_at ? $p->created_at->format('d M Y') : 'N/A' }}</td>
        <td>
            <a href="/admin/praharis/{{ $p->id }}/edit">Edit</a>

            <form method="POST" action="/admin/praharis/{{ $p->id }}" style="display:inline;">
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
@if(isset($prahari) || request()->is('admin/praharis/create'))

<form method="POST"
    action="{{ isset($prahari) ? '/admin/praharis/'.$prahari->id : '/admin/praharis' }}">

    @csrf

    @if(isset($prahari))
        @method('PUT')
    @endif

    <input type="text" name="name" placeholder="Name"
        value="{{ $prahari->name ?? '' }}"><br><br>

    <input type="text" name="phone" placeholder="Phone"
        value="{{ $prahari->phone ?? '' }}"><br><br>

    <input type="text" name="aadhaar" placeholder="Aadhaar"
        value="{{ $prahari->aadhaar ?? '' }}"><br><br>

    <select name="is_active">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select><br><br>

    <button type="submit">
        {{ isset($prahari) ? 'Update' : 'Create' }}
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
