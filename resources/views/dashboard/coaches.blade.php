@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Coach Dashboard</h1>
    <p class="mb-0 text-muted">Coach counts and status overview.</p>
</div>

<section class="section dashboard">
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <span class="text-muted ms-auto small">Range applies to every report below.</span>
            <form class="row g-3 align-items-end" method="GET" action="{{ route('dashboard.coaches') }}">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', request('start_date', $filters['start_date'])) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', request('end_date', $filters['end_date'])) }}">
                </div>
                <div class="col-md-3">
                    <label for="coach_id" class="form-label">Coach</label>
                    <select id="coach_id" name="coach_id" class="form-select">
                        <option value="">All Coaches</option>
                        @foreach($allCoaches as $coach)
                            <option value="{{ $coach->id }}" @selected(request('coach_id', $filters['coach_id'] ?? null) == $coach->id)>
                                {{ $coach->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply filters</button>
                    <a href="{{ route('dashboard.coaches') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Total Coaches</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalCoaches ?? 0) }}</h6>
                            <span class="text-muted small pt-1 d-block">All coaches</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Active Coaches</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($activeCoaches ?? 0) }}</h6>
                            <span class="text-muted small pt-1 d-block">Status: active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Remaining PT Sessions per Member -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Remaining PT Sessions per Member</h5>
                    @if(count($remainingSessionsData) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Coach</th>
                                        <th>Member</th>
                                        <th>Package</th>
                                        <th>Remaining Sessions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($remainingSessionsData as $data)
                                        <tr>
                                            <td><strong>{{ $data['coach_name'] }}</strong></td>
                                            <td>{{ $data['member_name'] }}</td>
                                            <td>{{ $data['package_name'] }}</td>
                                            <td><span class="badge bg-info">{{ $data['remaining_sessions'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(empty($filters['coach_id']))
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total Remaining Sessions:</strong></td>
                                        <td><strong>{{ array_sum(array_column($remainingSessionsData, 'remaining_sessions')) }}</strong></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No active PT packages with remaining sessions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions Conducted per Member in Selected Month -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sessions Conducted per Member ({{ \Carbon\Carbon::parse($filters['start_date'])->format('M Y') }})</h5>
                    @if(count($sessionsByCoachMember) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Coach</th>
                                        <th>Member</th>
                                        <th>Sessions Conducted</th>
                                        <th>Total Sessions Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessionsByCoachMember as $data)
                                        <tr>
                                            <td><strong>{{ $data['coach_name'] }}</strong></td>
                                            <td>{{ $data['member_name'] }}</td>
                                            <td><span class="badge bg-success">{{ $data['sessions_conducted'] }}</span></td>
                                            <td><span class="badge bg-primary">{{ $data['sessions_used_total'] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(empty($filters['coach_id']))
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>{{ array_sum(array_column($sessionsByCoachMember, 'sessions_conducted')) }}</strong></td>
                                        <td><strong>{{ array_sum(array_column($sessionsByCoachMember, 'sessions_used_total')) }}</strong></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No PT sessions conducted in the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Commission per Coach -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Commission per Coach ({{ \Carbon\Carbon::parse($filters['start_date'])->format('M Y') }})</h5>
                    @if(count($commissionByCoach) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Coach</th>
                                        <th>Total Sessions</th>
                                        <th>Total Commission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissionByCoach as $data)
                                        <tr>
                                            <td><strong>{{ $data['coach_name'] }}</strong></td>
                                            <td><span class="badge bg-info">{{ $data['total_sessions'] }}</span></td>
                                            <td><span class="badge bg-success">₱{{ number_format($data['total_commission'], 2) }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if(empty($filters['coach_id']))
                                <tfoot class="table-light">
                                    <tr>
                                        <td class="text-end"><strong>Total:</strong></td>
                                        <td><strong>{{ array_sum(array_column($commissionByCoach, 'total_sessions')) }}</strong></td>
                                        <td><strong>₱{{ number_format(array_sum(array_column($commissionByCoach, 'total_commission')), 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No commission data available for the selected period.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
