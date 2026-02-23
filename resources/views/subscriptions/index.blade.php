@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Subscription Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Subscriptions</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column gap-2">
                            <h5 class="card-title">Subscriptions List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create Subscription
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="subscriptions-table">
                            <thead>
                                <tr class="table-light">
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Duration (Months)</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr>
                                        <td><strong>{{ $subscription->name }}</strong></td>
                                        <td>₱{{ number_format($subscription->price, 2) }}</td>
                                        <td>{{ $subscription->duration_months }}</td>
                                        <td>
                                            @if($subscription->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $subscription->description ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-info" title="View">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
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
