@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Staff: {{ $staff->full_name ?? $staff->name }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Account</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('staff.index') }}" class="btn btn-secondary btn-sm">Back</a>
                            <a href="{{ route('staff.edit', $staff) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if ($staff->hasRole('coach') && $staff->coach)
                                <a href="{{ route('coaches.show', $staff->coach) }}" class="btn btn-primary btn-sm">Coach dashboard</a>
                            @endif
                        </div>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $staff->full_name ?? $staff->name }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $staff->email }}</dd>
                        <dt class="col-sm-3">Role</dt>
                        <dd class="col-sm-9">
                            @php $role = $staff->getRoleNames()->first(); @endphp
                            @if ($role === 'admin')<span class="badge bg-danger">Admin</span>
                            @elseif ($role === 'coach')<span class="badge bg-primary">Coach</span>
                            @elseif ($role === 'frontdesk')<span class="badge bg-info">Front desk</span>
                            @else<span class="badge bg-secondary">{{ $role ?? '—' }}</span>@endif
                        </dd>
                    </dl>
                </div>
            </div>
            @if ($staff->coach)
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Coach profile</h5>
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9">{{ $staff->coach->phone ?? '—' }}</dd>
                        <dt class="col-sm-3">Specialty</dt>
                        <dd class="col-sm-9">{{ $staff->coach->specialty ?? '—' }}</dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @if ($staff->coach->status === 'active')<span class="badge bg-success">Active</span>
                            @else<span class="badge bg-secondary">Inactive</span>@endif
                        </dd>
                    </dl>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
