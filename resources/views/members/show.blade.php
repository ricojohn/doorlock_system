@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Member Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Member Information</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('members.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 ">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Member Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">ID</div>
                            <div class="fw-semibold">{{ $member->id }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Full Name</div>
                            <div class="fw-semibold">{{ $member->full_name }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Status</div>
                            @if($member->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($member->status === 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-warning">Suspended</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Email</div>
                            <div>{{ $member->email }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Phone</div>
                            <div>{{ $member->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Date of Birth</div>
                            <div>{{ $member->date_of_birth ? $member->date_of_birth->format('F d, Y') : 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Gender</div>
                            @if($member->gender)
                                <span class="badge bg-info">{{ ucfirst($member->gender) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                        
                        <div class="col-md-4">
                            <div class="small text-muted">Invited by</div>
                            <div>
                                @if ($member->invitedBy)
                                    @if ($member->invitedBy instanceof \App\Models\Coach)
                                        <a href="{{ route('coaches.show', $member->invitedBy) }}">{{ $member->invitedBy->full_name }}</a>
                                        <span class="badge bg-secondary">Coach</span>
                                    @else
                                        <a href="{{ route('members.show', $member->invitedBy) }}">{{ $member->invitedBy->full_name }}</a>
                                        <span class="badge bg-secondary">Member</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Converted by</div>
                            <div>
                                @if ($member->convertedByUser)
                                    {{ $member->convertedByUser->full_name ?? $member->convertedByUser->name }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                        </div>
                        @if ($member->guest)
                        <div class="col-md-4">
                            <div class="small text-muted">Converted from guest</div>
                            <div>
                                <a href="{{ route('guests.show', $member->guest) }}">Guest #{{ $member->guest->id }}</a>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="small text-muted">Created At</div>
                            <div>{{ $member->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Updated At</div>
                            <div>{{ $member->updated_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Additional Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">House Number</div>
                            <div>{{ $member->house_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Street</div>
                            <div>{{ $member->street ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Barangay</div>
                            <div>{{ $member->barangay ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">City</div>
                            <div>{{ $member->city ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">State</div>
                            <div>{{ $member->state ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Postal Code</div>
                            <div>{{ $member->postal_code ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Country</div>
                            <div>{{ $member->country ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($member->activeRfidCard)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Keyfob Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Keyfob Number</div>
                            <div>{{ $member->activeRfidCard->card_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Issued At</div>
                            <div>{{ $member->activeRfidCard->issued_at ? $member->activeRfidCard->issued_at->format('F d, Y h:i A') : 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Expires At</div>
                            <div>{{ $member->activeRfidCard->expires_at ? $member->activeRfidCard->expires_at->format('F d, Y h:i A') : 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Price</div>
                            <div>{{ $member->activeRfidCard->price ? '₱' . number_format($member->activeRfidCard->price, 2) : 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Payment Method</div>
                            <div>{{ $member->activeRfidCard->payment_method ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Notes</div>
                            <div>{{ $member->activeRfidCard->notes ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Tabs: PT Packages & Sessions, Subscriptions, Reports & Analytics -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <h5 class="card-title mb-0">Member Details</h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('members.subscribe-pt-package', $member) }}" class="btn btn-success btn-sm"><i class="bi bi-plus-circle"></i> Subscribe to PT Package</a>
                            @if($member->activeMemberPtPackage && $member->activeMemberPtPackage->remaining_sessions > 0)
                                <a href="{{ route('members.log-pt-session', $member) }}" class="btn btn-primary btn-sm"><i class="bi bi-activity"></i> Log Session</a>
                            @endif
                            <a href="{{ route('subscriptions.create-for-member', $member) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Add Subscription
                            </a>
                        </div>
                    </div>

                    <!-- Main Tabs Navigation -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="main-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pt-packages-tab" data-bs-toggle="tab" data-bs-target="#pt-packages-content" type="button" role="tab">
                                <i class="bi bi-box-seam"></i> PT Packages & Sessions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="subscriptions-tab" data-bs-toggle="tab" data-bs-target="#subscriptions-content" type="button" role="tab">
                                <i class="bi bi-calendar-check"></i> Subscriptions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports-content" type="button" role="tab">
                                <i class="bi bi-graph-up"></i> Reports & Analytics
                            </button>
                        </li>
                    </ul>``

                    <!-- Main Tabs Content -->
                    <div class="tab-content pt-2" id="main-tab-content">
                        <!-- PT Packages & Sessions Tab -->
                        <div class="tab-pane fade show active" id="pt-packages-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">PT Packages & Sessions</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('pt-packages')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>

                            <!-- Active Package Section -->
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-check-circle"></i> Active PT Package</h6>
                            @if($member->activeMemberPtPackage && $member->activeMemberPtPackage->remaining_sessions > 0)
                                <div class="alert alert-success mb-3">
                                    <h6 class="alert-heading"><i class="bi bi-check-circle"></i> Active PT Package</h6>
                                    <hr>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <strong>Package:</strong> {{ $member->activeMemberPtPackage->ptPackage->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Coach:</strong> {{ $member->activeMemberPtPackage->coach?->full_name ?? '—' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Start Date:</strong> {{ $member->activeMemberPtPackage->start_date?->format('M d, Y') ?? '—' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>End Date:</strong> {{ $member->activeMemberPtPackage->end_date?->format('M d, Y') ?? 'No expiry' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Sessions Used:</strong> {{ $member->activeMemberPtPackage->sessions_used }} / {{ $member->activeMemberPtPackage->sessions_total }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Remaining Sessions:</strong> 
                                            <span class="badge bg-info fs-6">{{ $member->activeMemberPtPackage->remaining_sessions }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No active PT package with remaining sessions. 
                                    <a href="{{ route('members.subscribe-pt-package', $member) }}" class="alert-link">Subscribe to a PT package</a> to get started.
                                </div>
                            @endif

                            <!-- Package History Section -->
                            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="bi bi-clock-history"></i> PT Package History</h6>
                            @if($member->memberPtPackages->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Package</th>
                                                <th>Coach</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Status</th>
                                                <th>Sessions Used</th>
                                                <th>Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($member->memberPtPackages as $mpp)
                                                <tr>
                                                    <td>{{ $mpp->ptPackage->name ?? 'N/A' }}</td>
                                                    <td>{{ $mpp->coach?->full_name ?? '—' }}</td>
                                                    <td>{{ $mpp->start_date?->format('M d, Y') ?? '—' }}</td>
                                                    <td>{{ $mpp->end_date?->format('M d, Y') ?? '—' }}</td>
                                                    <td><span class="badge bg-{{ $mpp->status === 'active' ? 'success' : ($mpp->status === 'exhausted' ? 'warning' : 'secondary') }}">{{ $mpp->status }}</span></td>
                                                    <td>{{ $mpp->sessions_used }}</td>
                                                    <td>{{ $mpp->remaining_sessions }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> No PT packages yet. Subscribe the member to a PT package to start.</p>
                            @endif

                            <!-- PT Sessions Section -->
                            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="bi bi-activity"></i> PT Sessions History</h6>
                            @if($ptSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date & Time</th>
                                                <th>Package</th>
                                                <th>Coach</th>
                                                <th>Sessions Used</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ptSessions as $session)
                                                <tr>
                                                    <td>{{ $session->conducted_at?->format('M d, Y h:i A') ?? '—' }}</td>
                                                    <td>{{ $session->memberPtPackage->ptPackage->name ?? 'N/A' }}</td>
                                                    <td>{{ $session->memberPtPackage->coach?->full_name ?? '—' }}</td>
                                                    <td><span class="badge bg-primary">{{ $session->sessions_used }}</span></td>
                                                    <td>{{ $session->notes ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total Sessions Used:</strong></td>
                                                <td colspan="2"><strong>{{ $ptSessions->sum('sessions_used') }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted mb-0"><i class="bi bi-info-circle"></i> No PT sessions recorded yet.</p>
                            @endif
                        </div>

                        <!-- Subscriptions Tab -->
                        <div class="tab-pane fade" id="subscriptions-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Subscriptions</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('subscriptions')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                            @if($member->memberSubscriptions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subscription Name</th>
                                                <th>Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Price</th>
                                                <th>Payment Type</th>
                                                <th>Payment Status</th>
                                                <th>Status</th>
                                                <th>Frozen</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($member->memberSubscriptions as $memberSubscription)
                                                <tr>
                                                    <td><strong>{{ $memberSubscription->subscription->name ?? 'N/A' }}</strong></td>
                                                    <td>{{ $memberSubscription->subscription_type }}</td>
                                                    <td>{{ $memberSubscription->start_date->format('M d, Y') }}</td>
                                                    <td>{{ $memberSubscription->end_date->format('M d, Y') }}</td>
                                                    <td>₱{{ number_format($memberSubscription->price, 2) }}</td>
                                                    <td>{{ $memberSubscription->payment_type ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($memberSubscription->payment_status === 'paid')
                                                            <span class="badge bg-success">Paid</span>
                                                        @elseif($memberSubscription->payment_status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @else
                                                            <span class="badge bg-danger">Overdue</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($memberSubscription->status === 'active')
                                                            @if($memberSubscription->is_frozen)
                                                                <span class="badge bg-secondary">Frozen</span>
                                                            @else
                                                                <span class="badge bg-success">Active</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-danger">Expired</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($memberSubscription->frozen_until)
                                                            Until {{ $memberSubscription->frozen_until->format('M d, Y') }}
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($memberSubscription->status === 'active' && $memberSubscription->end_date->gte(now()))
                                                            @if($memberSubscription->is_frozen)
                                                                <form action="{{ route('member-subscriptions.unfreeze', $memberSubscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Unfreeze this subscription?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                                        <i class="bi bi-play-circle"></i> Unfreeze
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <a href="{{ route('member-subscriptions.freeze', $memberSubscription) }}" class="btn btn-sm btn-outline-warning">
                                                                    <i class="bi bi-pause-circle"></i> Freeze
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="bi bi-info-circle"></i> No subscriptions found for this member.
                                </div>
                            @endif
                        </div>

                        <!-- Reports & Analytics Tab -->
                        <div class="tab-pane fade" id="reports-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Reports & Analytics</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTab('reports')">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                            </div>
                            <div class="row g-3">
                                <!-- Peak Hour -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Peak Hour</h6>
                                            <h4 class="text-primary mb-1">{{ $peakHour }}</h4>
                                            <small class="text-muted">{{ $peakHourCount }} access{{ $peakHourCount !== 1 ? 'es' : '' }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Active Hours Count -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Active Hours</h6>
                                            <h4 class="text-info mb-1">{{ count($activeHours) }}</h4>
                                            <small class="text-muted">Different hours active</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Access Count -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Total Access</h6>
                                            <h4 class="text-success mb-1">{{ $accessLogs->count() }}</h4>
                                            <small class="text-muted">Total granted access</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Active Subscription Status -->
                                <div class="col-md-6 col-lg-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Active Subscription</h6>
                                            @if($activeSubscription)
                                                <h4 class="text-warning mb-1">
                                                    <span class="badge bg-success">Active</span>
                                                </h4>
                                                <small class="text-muted">{{ $activeSubscription->subscription->name ?? 'N/A' }}</small>
                                            @else
                                                <h4 class="text-warning mb-1">
                                                    <span class="badge bg-secondary">None</span>
                                                </h4>
                                                <small class="text-muted">No active subscription</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Active Hours List -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="bi bi-clock"></i> Active Times in a Day
                                            </h6>
                                            @if(count($activeHours) > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($activeHours as $hour)
                                                        <span class="badge bg-info">{{ $hour }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle"></i> No access logs found.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Weekly Attendance -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">
                                                <i class="bi bi-calendar-week"></i> Attendance per Week (Last 4 Weeks)
                                            </h6>
                                            @if(count($weeklyAttendance) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Week</th>
                                                                <th>Days Attended</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($weeklyAttendance as $week)
                                                                <tr>
                                                                    <td>{{ $week['week'] }}</td>
                                                                    <td><strong>{{ $week['count'] }}</strong> day{{ $week['count'] !== 1 ? 's' : '' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle"></i> No attendance data available.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle main tab change
    const mainTabButtons = document.querySelectorAll('#main-tabs button[data-bs-toggle="tab"]');
    
    mainTabButtons.forEach(function(button) {
        button.addEventListener('shown.bs.tab', function(event) {
            const targetTab = event.target.getAttribute('data-bs-target');
            console.log('Main tab changed to:', targetTab);
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

