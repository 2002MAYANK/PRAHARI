@extends('admin.layout')

@section('content')

<div class="page-header">
    <div>
        <h3>Sub Admins</h3>
        <p>Manage all sub admin accounts.</p>
    </div>

    <a href="/admin/subadmins/create" class="btn primary">
        Add Sub Admin
    </a>
</div>

{{-- LIST --}}
@if(isset($subadmins))

<div class="table-wrap">

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            {{-- <th>Status</th> --}}
            <th>Action</th>
        </tr>

        @foreach($subadmins as $s)

        <tr>

            <td>
                SA{{ $s->id }}
            </td>

            <td>
                {{ $s->name }}
            </td>

            <td>
                {{ $s->email }}
            </td>

            {{-- <td>

                @if($s->is_active)

                    <span class="status-badge success">
                        Active
                    </span>

                @else

                    <span class="status-badge danger">
                        Inactive
                    </span>

                @endif

            </td> --}}

            <td>

                <a href="/admin/subadmins/{{ $s->id }}/edit">
                    Edit
                </a>

                <form method="POST"
                    action="/admin/subadmins/{{ $s->id }}"
                    style="display:inline;">

                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Delete
                    </button>

                </form>

            </td>

        </tr>

        @endforeach

    </table>

</div>

@endif

<hr>

{{-- CREATE / EDIT FORM --}}
@if(isset($subadmin) || request()->is('admin/subadmins/create'))

<form method="POST"
    action="{{ isset($subadmin) ? '/admin/subadmins/'.$subadmin->id : '/admin/subadmins' }}">

    @csrf

    @if(isset($subadmin))
        @method('PUT')
    @endif

    <h3>
        {{ isset($subadmin) ? 'Edit Sub Admin' : 'Create Sub Admin' }}
    </h3>

    <br>

    <input type="text"
        name="name"
        placeholder="Enter Name"
        value="{{ $subadmin->name ?? '' }}">

    <br><br>

    <input type="email"
        name="email"
        placeholder="Enter Email"
        value="{{ $subadmin->email ?? '' }}">

    <br><br>

    <input type="password"
        name="password"
        placeholder="Enter Password">

    <br><br>

    {{-- <select name="is_active">

        <option value="1"
            {{ isset($subadmin) && $subadmin->is_active == 1 ? 'selected' : '' }}>
            Active
        </option>

        <option value="0"
            {{ isset($subadmin) && $subadmin->is_active == 0 ? 'selected' : '' }}>
            Inactive
        </option>

    </select> --}}

    <br><br>

    <button type="submit">

        {{ isset($subadmin) ? 'Update' : 'Create' }}

    </button>

</form>

@endif

@endsection