@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>PT Session Plans</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">PT Session Plans</li>
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
                            <h5 class="card-title">PT Session Plans List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('pt-session-plans.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create PT Session Plan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="pt-session-plans-table">
                            <thead>
                                <tr class="table-light">
                                    <th>Plan Name</th>
                                    <th>Coach</th>
                                    <th>Member</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ptSessionPlans as $plan)
                                    <tr>
                                        <td><strong>{{ $plan->name }}</strong></td>
                                        <td>{{ $plan->coach->full_name }}</td>
                                        <td>{{ $plan->member->full_name }}</td>
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
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('pt-session-plans.show', $plan) }}" class="btn btn-info" title="View">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <a href="{{ route('pt-session-plans.edit', $plan) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('pt-session-plans.destroy', $plan) }}" method="POST" class="d-inline delete-form">
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
