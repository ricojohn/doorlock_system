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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Subscriptions</h5>
                        <a href="{{ route('subscriptions.create-for-member', $member) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Add Subscription
                        </a>
                    </div>
                    @if($member->memberSubscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="table-light">
                                        <th>Subscription Name</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Price</th>
                                        <th>Payment Type</th>
                                        <th>Payment Status</th>
                                        <th>Notes</th>
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
                                            <td>{{ $memberSubscription->notes ?? 'N/A' }}</td>
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
            </div>
        </div>

        <!-- Reports Section -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-graph-up"></i> Reports & Analytics
                    </h5>

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
                        <div class="col-md-6">
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

                        <!-- Weekly Attendance -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">
                                        <i class="bi bi-calendar-week"></i> Attendance per Week (Last 4 Weeks)
                                    </h6>
                                    @if(count($weeklyAttendance) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr class="table-light">
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

        <!-- Subscription History -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-clock-history"></i> Subscription History
                    </h5>
                    @if($subscriptionHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="table-light">
                                        <th>Subscription Name</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Payment Type</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subscriptionHistory as $subscription)
                                        @php
                                            $isActive = $subscription->end_date >= now()->toDateString();
                                            $durationMonths = $subscription->duration_months ?? ($subscription->subscription->duration_months ?? null);
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $subscription->subscription->name ?? 'N/A' }}</strong></td>
                                            <td><span class="badge bg-info">{{ $subscription->subscription_type }}</span></td>
                                            <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                            <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                            <td>{{ $durationMonths ? $durationMonths . ' month' . ($durationMonths !== 1 ? 's' : '') : 'N/A' }}</td>
                                            <td>₱{{ number_format($subscription->price, 2) }}</td>
                                            <td>{{ $subscription->payment_type ?? 'N/A' }}</td>
                                            <td>
                                                @if($subscription->payment_status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($subscription->payment_status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isActive)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Expired</span>
                                                @endif
                                            </td>
                                            <td>{{ $subscription->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> No subscription history found.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- PT Session Plans History -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-activity"></i> PT Session Plans History
                    </h5>
                    @if($ptSessionPlansHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr class="table-light">
                                        <th>Plan Name</th>
                                        <th>Coach</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Exercises</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ptSessionPlansHistory as $plan)
                                        <tr>
                                            <td><strong>{{ $plan->name }}</strong></td>
                                            <td>{{ $plan->coach->full_name ?? 'N/A' }}</td>
                                            <td>{{ $plan->start_date->format('M d, Y') }}</td>
                                            <td>{{ $plan->end_date ? $plan->end_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($plan->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($plan->status === 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @else
                                                    <span class="badge bg-secondary">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>{{ $plan->price ? '₱' . number_format($plan->price, 2) : 'N/A' }}</td>
                                            <td>{{ $plan->items->count() }} exercise{{ $plan->items->count() !== 1 ? 's' : '' }}</td>
                                            <td>{{ $plan->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('pt-session-plans.show', $plan) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> No PT session plans found for this member.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

