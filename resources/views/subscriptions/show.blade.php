@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Subscription Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Subscriptions</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Subscription Information</h5>
                        <div>
                            <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Member</div>
                        <div class="col-lg-9 col-md-8">
                            <a href="{{ route('members.show', $subscription->member) }}">
                                <strong>{{ $subscription->member->full_name }}</strong>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Plan</div>
                        <div class="col-lg-9 col-md-8">
                            @if($subscription->plan)
                                <a href="{{ route('plans.show', $subscription->plan) }}">
                                    <strong>{{ $subscription->plan->name }}</strong>
                                </a>
                            @else
                                <strong>{{ $subscription->plan_name ?? 'N/A' }}</strong>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Start Date</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->start_date->format('F d, Y') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">End Date</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->end_date->format('F d, Y') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Price</div>
                        <div class="col-lg-9 col-md-8">₱{{ number_format($subscription->price, 2) }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($subscription->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($subscription->status == 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @else
                                <span class="badge bg-secondary">Cancelled</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Payment Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($subscription->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($subscription->payment_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-danger">Overdue</span>
                            @endif
                        </div>
                    </div>

                    @if($subscription->notes)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Notes</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->notes }}</div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

