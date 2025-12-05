@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Plan Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Plans</a></li>
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
                        <h5 class="card-title">Plan Information</h5>
                        <div>
                            <a href="{{ route('plans.edit', $plan) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('plans.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Plan Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $plan->name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Price</div>
                        <div class="col-lg-9 col-md-8">₱{{ number_format($plan->price, 2) }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Duration</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->duration_months }} {{ $plan->duration_months == 1 ? 'month' : 'months' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($plan->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    @if($plan->description)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Description</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->description }}</div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Total Subscriptions</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->subscriptions->count() }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $plan->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($plan->subscriptions->count() > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Subscriptions Using This Plan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Member</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plan->subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <a href="{{ route('members.show', $subscription->member) }}">
                                                {{ $subscription->member->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                        <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                        <td>
                                            @if($subscription->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($subscription->status == 'expired')
                                                <span class="badge bg-danger">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($subscription->payment_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
    @endif
</section>

@endsection

