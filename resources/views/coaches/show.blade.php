@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Coach Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('coaches.index') }}">Coaches</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Coach Information</h5>
                        <div>
                            <a href="{{ route('coaches.edit', $coach) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('coaches.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Full Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $coach->full_name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Phone</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->date_of_birth ? $coach->date_of_birth->format('F d, Y') : 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->gender)
                                <span class="badge bg-info">{{ ucfirst($coach->gender) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Specialty</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->specialty ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Address</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->house_number || $coach->street || $coach->barangay || $coach->city)
                                {{ $coach->house_number }} {{ $coach->street }}, {{ $coach->barangay }}, {{ $coach->city }}, {{ $coach->state }} {{ $coach->postal_code }}, {{ $coach->country }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- PT Sessions & Commission Tabs -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">PT Sessions & Commission</h5>
                    
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="pt-sessions-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="remaining-tab" data-bs-toggle="tab" data-bs-target="#remaining" type="button" role="tab">
                                <i class="bi bi-clock-history"></i> Remaining Sessions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="conducted-tab" data-bs-toggle="tab" data-bs-target="#conducted" type="button" role="tab">
                                <i class="bi bi-activity"></i> Sessions Conducted
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab">
                                <i class="bi bi-cash-coin"></i> Commission Summary
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content pt-2" id="pt-sessions-tab-content">
                        <!-- Remaining Sessions Tab -->
                        <div class="tab-pane fade show active" id="remaining" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Remaining PT Sessions per Member</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('remaining')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                            @if(count($remainingSessionsData) > 0)
                                <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Package</th>
                                        <th>Remaining Sessions</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($remainingSessionsData as $data)
                                        <tr>
                                            <td><strong>{{ $data['member_name'] }}</strong></td>
                                            <td>
                                                @foreach($data['packages'] as $pkg)
                                                    <div>{{ $pkg['package_name'] }}</div>
                                                @endforeach
                                            </td>
                                            <td><span class="badge bg-info">{{ $data['remaining_sessions'] }}</span></td>
                                            <td>
                                                @foreach($data['packages'] as $pkg)
                                                    <small class="text-muted">
                                                        {{ $pkg['used'] }}/{{ $pkg['total'] }} used
                                                        @if($pkg['remaining'] > 0)
                                                            <span class="badge bg-success">{{ $pkg['remaining'] }} remaining</span>
                                                        @endif
                                                    </small><br>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total Remaining Sessions:</strong></td>
                                        <td colspan="2"><strong>{{ array_sum(array_column($remainingSessionsData, 'remaining_sessions')) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No active PT packages with remaining sessions found.</p>
                            @endif
                        </div>

                        <!-- Sessions Conducted Tab -->
                        <div class="tab-pane fade" id="conducted" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Sessions Conducted per Member ({{ $startDate->format('M Y') }})</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('conducted')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                            @if(count($sessionsByMember) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Member</th>
                                                <th>Sessions Conducted</th>
                                                <th>Total Sessions Used</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sessionsByMember as $data)
                                                <tr>
                                                    <td><strong>{{ $data['member_name'] }}</strong></td>
                                                    <td><span class="badge bg-success">{{ $data['sessions_conducted'] }}</span></td>
                                                    <td><span class="badge bg-primary">{{ $data['sessions_used_total'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td class="text-end"><strong>Total:</strong></td>
                                                <td><strong>{{ array_sum(array_column($sessionsByMember, 'sessions_conducted')) }}</strong></td>
                                                <td><strong>{{ array_sum(array_column($sessionsByMember, 'sessions_used_total')) }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0">No PT sessions conducted in {{ $startDate->format('F Y') }}.</p>
                            @endif
                        </div>

                        <!-- Commission Summary Tab -->
                        <div class="tab-pane fade" id="commission" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Commission Summary ({{ $startDate->format('M Y') }})</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('commission')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Total Sessions</h6>
                                            <h4 class="text-primary mb-1">{{ $totalSessions }}</h4>
                                            <small class="text-muted">Sessions conducted this month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Total Commission</h6>
                                            <h4 class="text-success mb-1">₱{{ number_format($totalCommission, 2) }}</h4>
                                            <small class="text-muted">Earned this month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Active Members</h6>
                                            <h4 class="text-info mb-1">{{ count($remainingSessionsData) }}</h4>
                                            <small class="text-muted">With remaining sessions</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        @if($coach->workHistories->count() > 0)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Work History</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-light">
                                    <th>Company Name</th>
                                    <th>Position</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coach->workHistories as $workHistory)
                                    <tr>
                                        <td><strong>{{ $workHistory->company_name }}</strong></td>
                                        <td>{{ $workHistory->position }}</td>
                                        <td>{{ $workHistory->start_date->format('M d, Y') }}</td>
                                        <td>{{ $workHistory->end_date ? $workHistory->end_date->format('M d, Y') : 'Current' }}</td>
                                        <td>{{ $workHistory->description ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($coach->certificates->count() > 0)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Certificates</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-light">
                                    <th>Certificate Name</th>
                                    <th>Issuing Organization</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Certificate Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coach->certificates as $certificate)
                                    <tr>
                                        <td><strong>{{ $certificate->certificate_name }}</strong></td>
                                        <td>{{ $certificate->issuing_organization }}</td>
                                        <td>{{ $certificate->issue_date->format('M d, Y') }}</td>
                                        <td>{{ $certificate->expiry_date ? $certificate->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $certificate->certificate_number ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab change and refresh
    const tabButtons = document.querySelectorAll('#pt-sessions-tabs button[data-bs-toggle="tab"]');
    
    tabButtons.forEach(function(button) {
        button.addEventListener('shown.bs.tab', function(event) {
            // Tab has been shown, you can add any refresh logic here if needed
            const targetTab = event.target.getAttribute('data-bs-target');
            console.log('Tab changed to:', targetTab);
        });
    });
});

function refreshTab(tabName) {
    // Show loading state
    const refreshBtn = event.target.closest('button');
    if (refreshBtn) {
        const originalHtml = refreshBtn.innerHTML;
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Refreshing...';
        
        // Reload the page to refresh all data
        setTimeout(function() {
            window.location.reload();
        }, 300);
    }
}

// Add spin animation for refresh button
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
`;
document.head.appendChild(style);
</script>
@endpush

