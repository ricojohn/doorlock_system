@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>PT Package Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pt-packages.index') }}">PT Packages</a></li>
            <li class="breadcrumb-item active">{{ $ptPackage->name }}</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">{{ $ptPackage->name }}</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pt-packages.edit', $ptPackage) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                            <a href="{{ route('pt-packages.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-info-circle"></i> Package Info</h6></div>
                        <div class="col-md-4"><strong>Package Type:</strong> <span class="badge bg-secondary">{{ $ptPackage->package_type }}</span></div>
                        <div class="col-md-4"><strong>Package Rate:</strong> ₱{{ number_format($ptPackage->package_rate, 2) }}</div>
                        <div class="col-md-4"><strong>Session Count:</strong> {{ $ptPackage->session_count }}</div>
                        <div class="col-md-4 mt-2"><strong>Rate per Session:</strong> {{ $ptPackage->rate_per_session ? '₱' . number_format($ptPackage->rate_per_session, 2) : '—' }}</div>
                        <div class="col-md-4 mt-2"><strong>Commission %:</strong> {{ $ptPackage->commission_percentage !== null ? $ptPackage->commission_percentage . '%' : '—' }}</div>
                        <div class="col-md-4 mt-2"><strong>Commission per Session:</strong> {{ $ptPackage->commission_per_session ? '₱' . number_format($ptPackage->commission_per_session, 2) : '—' }}</div>
                        <div class="col-md-4 mt-2"><strong>Coach:</strong> {{ $ptPackage->coach?->full_name ?? '—' }}</div>
                        <div class="col-md-4 mt-2"><strong>Status:</strong>
                            @if($ptPackage->status === 'active')<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif
                        </div>
                        <div class="col-md-4 mt-2"><strong>Payment Type:</strong> {{ $ptPackage->payment_type ?? '—' }}</div>
                        @if($ptPackage->description)
                        <div class="col-12 mt-2"><strong>Description:</strong><br>{{ $ptPackage->description }}</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-activity"></i> Exercises</h6>
                        @if($ptPackage->exercises->isEmpty())
                            <p class="text-muted mb-0">No exercises defined.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Exercise</th>
                                            <th>Sets</th>
                                            <th>Reps</th>
                                            <th>Weight</th>
                                            <th>Duration</th>
                                            <th>Rest</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ptPackage->exercises as $i => $ex)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $ex->exercise_name }}</td>
                                            <td>{{ $ex->sets ?? '—' }}</td>
                                            <td>{{ $ex->reps ?? '—' }}</td>
                                            <td>{{ $ex->weight !== null ? $ex->weight : '—' }}</td>
                                            <td>{{ $ex->duration_minutes ? $ex->duration_minutes . ' min' : '—' }}</td>
                                            <td>{{ $ex->rest_period_seconds ? $ex->rest_period_seconds . ' s' : '—' }}</td>
                                            <td>{{ $ex->notes ?? '—' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    @if($ptPackage->memberPtPackages->isNotEmpty())
                    <div>
                        <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-people"></i> Subscribed Members</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Start</th>
                                        <th>Status</th>
                                        <th>Sessions Used</th>
                                        <th>Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ptPackage->memberPtPackages as $mpp)
                                    <tr>
                                        <td><a href="{{ route('members.show', $mpp->member) }}">{{ $mpp->member->full_name ?? $mpp->member->first_name }}</a></td>
                                        <td>{{ $mpp->start_date?->format('M d, Y') ?? '—' }}</td>
                                        <td><span class="badge bg-{{ $mpp->status === 'active' ? 'success' : ($mpp->status === 'exhausted' ? 'warning' : 'secondary') }}">{{ $mpp->status }}</span></td>
                                        <td>{{ $mpp->sessions_used }}</td>
                                        <td>{{ $mpp->remaining_sessions }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
