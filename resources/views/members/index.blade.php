
@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Member Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Members</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column gap-2">
                            <h5 class="card-title">Members List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Member
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="members-table" style="border-collapse: collapse;">
                            <thead>
                                <tr class="table-light">
                                    <th style="width: 20%; border: 1px solid #dee2e6;">Member</th>
                                    <th style="width: 25%; border: 1px solid #dee2e6;">Subscription</th>
                                    <th style="width: 30%; border: 1px solid #dee2e6;">PT Package</th>
                                    <th style="width: 25%; border: 1px solid #dee2e6;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    @php
                                        $activeCard = $member->activeRfidCard;
                                        $activeSubscription = $member->activeSubscription;
                                        $subscriptions = $member->activeMemberSubscription;
                                    @endphp

                                    <tr>
                                        <!-- Member Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <div class="row g-2">
                                                <!-- First Column: Member Info -->
                                                <div class="col-6">
                                                    <div class="mb-2">
                                                        <strong>{{ $member->full_name }}</strong>
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-envelope"></i> {{ $member->email }}
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-telephone"></i> {{ $member->phone ?? 'N/A' }}
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-credit-card"></i> 
                                                        @if($activeCard)
                                                            <span class="badge bg-primary">{{ $activeCard->card_number }}</span>
                                                        @else
                                                            <span class="text-muted">No RFID</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Subscription Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th style="border: 1px solid #dee2e6;">Subscription</th>
                                                        <th style="border: 1px solid #dee2e6;">Status</th>
                                                        <th style="border: 1px solid #dee2e6;">Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($subscriptions)
                                                        <tr>
                                                            <td style="border: 1px solid #dee2e6;">{{ $subscriptions->subscription->name ?? 'N/A' }}</td>
                                                            <td style="border: 1px solid #dee2e6;">
                                                                @if($subscriptions->status === 'active')
                                                                    <span class="badge bg-success">Active</span>
                                                                @elseif($subscriptions->status === 'expired')
                                                                    <span class="badge bg-danger">Expired</span>
                                                                @endif
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6;">{{ $subscriptions->start_date->format('M d, Y') }} - {{ $subscriptions->end_date->format('M d, Y') }}</td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">No Subscription</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>

                                        <!-- PT Package Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th style="border: 1px solid #dee2e6;">PT Package</th>
                                                        <th style="border: 1px solid #dee2e6;">Used Sessions</th>
                                                        <th style="border: 1px solid #dee2e6;">Total Sessions</th>
                                                        <th style="border: 1px solid #dee2e6;">Remaining Sessions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($member->activeMemberPtPackage)
                                                        <tr>
                                                            <td style="border: 1px solid #dee2e6;">
                                                                {{ $member->activeMemberPtPackage->ptPackage->name ?? 'N/A' }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6;">
                                                                {{ $member->activeMemberPtPackage->sessions_used }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6;">
                                                                {{ $member->activeMemberPtPackage->sessions_total }}
                                                            </td>
                                                            <td style="border: 1px solid #dee2e6;">
                                                                <span class="badge bg-primary">{{ $member->activeMemberPtPackage->remaining_sessions }}</span>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">No PT Package</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        
                                        
                                        <!-- Action Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('members.show', $member) }}" class="btn btn-info" title="View">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <a href="{{ route('members.edit', $member) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                @if(!$activeCard)
                                                    <a href="{{ route('members.assign-keyfob', $member) }}" class="btn btn-primary" title="Add Keyfob">
                                                        <i class="bi bi-credit-card"></i> Add Keyfob
                                                    </a>
                                                @endif
                                                <a href="{{ route('subscriptions.create-for-member', $member) }}" class="btn btn-success" title="Add Subscription">
                                                    <i class="bi bi-calendar-check"></i> Add Subscription
                                                </a>
                                                @if(!$member->activeMemberPtPackage)
                                                <a href="{{ route('members.subscribe-pt-package', $member) }}" class="btn btn-secondary" title="Add PT Package">
                                                    <i class="bi bi-activity"></i> Add PT Package
                                                </a>
                                                @endif
                                                @if($member->activeMemberPtPackage && $member->activeMemberPtPackage->remaining_sessions > 0)
                                                <a href="{{ route('members.log-pt-session', $member) }}" class="btn btn-primary" title="Log PT Session">
                                                    <i class="bi bi-clock"></i> Log PT Session
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection
