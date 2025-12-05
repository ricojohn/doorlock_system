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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Member Information</h5>
                        <div>
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('members.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $member->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">First Name</div>
                        <div class="col-lg-9 col-md-8">{{ $member->first_name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Last Name</div>
                        <div class="col-lg-9 col-md-8">{{ $member->last_name }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Full Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $member->full_name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $member->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Phone</div>
                        <div class="col-lg-9 col-md-8">{{ $member->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                        <div class="col-lg-9 col-md-8">{{ $member->date_of_birth ? $member->date_of_birth->format('F d, Y') : 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8">
                            @if($member->gender)
                                <span class="badge bg-info">{{ ucfirst($member->gender) }}</span>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $member->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $member->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($member->subscriptions->count() > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Subscriptions</h5>
                        <a href="{{ route('subscriptions.create') }}?member_id={{ $member->id }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Subscription
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($member->subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->plan_name }}</td>
                                        <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                        <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                        <td>${{ number_format($subscription->price, 2) }}</td>
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
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
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
    @endif
</section>

@endsection

