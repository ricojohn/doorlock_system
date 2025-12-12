@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>PT Session Plan Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pt-session-plans.index') }}">PT Session Plans</a></li>
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
                        <h5 class="card-title mb-0">PT Session Plan Information</h5>
                        <div>
                            <a href="{{ route('pt-session-plans.edit', $ptSessionPlan) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('pt-session-plans.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Plan Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $ptSessionPlan->name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Coach</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->coach->full_name }} - {{ $ptSessionPlan->coach->specialty ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Member</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->member->full_name }} - {{ $ptSessionPlan->member->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Start Date</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->start_date->format('F d, Y') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">End Date</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->end_date ? $ptSessionPlan->end_date->format('F d, Y') : 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($ptSessionPlan->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($ptSessionPlan->status === 'completed')
                                <span class="badge bg-info">Completed</span>
                            @else
                                <span class="badge bg-secondary">Cancelled</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Price</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->price ? '₱' . number_format($ptSessionPlan->price, 2) : 'N/A' }}</div>
                    </div>

                    @if($ptSessionPlan->description)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Description</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->description }}</div>
                    </div>
                    @endif

                    @if($ptSessionPlan->notes)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Notes</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->notes }}</div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $ptSessionPlan->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($ptSessionPlan->items->count() > 0)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Exercises</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-light">
                                    <th>#</th>
                                    <th>Exercise Name</th>
                                    <th>Sets</th>
                                    <th>Reps</th>
                                    <th>Weight (kg)</th>
                                    <th>Duration (min)</th>
                                    <th>Rest Period (sec)</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ptSessionPlan->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $item->exercise_name }}</strong></td>
                                        <td>{{ $item->sets ?? 'N/A' }}</td>
                                        <td>{{ $item->reps ?? 'N/A' }}</td>
                                        <td>{{ $item->weight ? number_format($item->weight, 2) : 'N/A' }}</td>
                                        <td>{{ $item->duration_minutes ?? 'N/A' }}</td>
                                        <td>{{ $item->rest_period_seconds ?? 'N/A' }}</td>
                                        <td>{{ $item->notes ?? 'N/A' }}</td>
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

