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
</div>

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
                        <div class="col-lg-3 col-md-4 label">Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $subscription->name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Price</div>
                        <div class="col-lg-9 col-md-8">₱{{ number_format($subscription->price, 2) }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Duration (Months)</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->duration_months }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($subscription->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Description</div>
                        <div class="col-lg-9 col-md-8">{{ $subscription->description ?? 'N/A' }}</div>
                    </div>

                    @if($subscription->memberSubscriptions->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">Members with this Subscription</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Type</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subscription->memberSubscriptions as $memberSubscription)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('members.show', $memberSubscription->member) }}">
                                                        {{ $memberSubscription->member->full_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $memberSubscription->subscription_type }}</td>
                                                <td>{{ $memberSubscription->start_date->format('M d, Y') }}</td>
                                                <td>{{ $memberSubscription->end_date->format('M d, Y') }}</td>
                                                <td>
                                                    @if($memberSubscription->payment_status === 'paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @elseif($memberSubscription->payment_status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

