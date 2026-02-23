@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Staff</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Staff</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Staff members</h5>
                        <a href="{{ route('staff.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-person-plus"></i> Add staff
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td><strong>{{ $user->full_name ?? $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php $role = $user->getRoleNames()->first(); @endphp
                                            @if ($role === 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @elseif ($role === 'coach')
                                                <span class="badge bg-primary">Coach</span>
                                            @elseif ($role === 'frontdesk')
                                                <span class="badge bg-info">Front desk</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $role ?? '—' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('staff.show', $user) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('staff.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                                @if ($user->hasRole('coach') && $user->coach)
                                                    <a href="{{ route('coaches.show', $user->coach) }}" class="btn btn-secondary btn-sm">Coach dashboard</a>
                                                @endif
                                                @if ($user->id !== auth()->id())
                                                    <form action="{{ route('staff.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this staff member?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No staff members yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
