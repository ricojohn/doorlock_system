@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>PT Packages</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">PT Packages</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">PT Packages List</h5>
                        <a href="{{ route('pt-packages.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Create PT Package
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Package Name</th>
                                    <th>Type</th>
                                    <th>Rate</th>
                                    <th>Sessions</th>
                                    <th>Rate/Session</th>
                                    <th>Commission %</th>
                                    <th>Commission/Session</th>
                                    <th>Coach</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ptPackages as $pkg)
                                    <tr>
                                        <td><strong>{{ $pkg->name }}</strong></td>
                                        <td><span class="badge bg-secondary">{{ $pkg->package_type }}</span></td>
                                        <td>₱{{ number_format($pkg->package_rate, 2) }}</td>
                                        <td>{{ $pkg->session_count }}</td>
                                        <td>{{ $pkg->rate_per_session ? '₱' . number_format($pkg->rate_per_session, 2) : '—' }}</td>
                                        <td>{{ $pkg->commission_percentage !== null ? $pkg->commission_percentage . '%' : '—' }}</td>
                                        <td>{{ $pkg->commission_per_session ? '₱' . number_format($pkg->commission_per_session, 2) : '—' }}</td>
                                        <td>{{ $pkg->coach?->full_name ?? '—' }}</td>
                                        <td>
                                            @if($pkg->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('pt-packages.show', $pkg) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> View</a>
                                                <a href="{{ route('pt-packages.edit', $pkg) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                                <form action="{{ route('pt-packages.destroy', $pkg) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No PT packages yet.</td>
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
