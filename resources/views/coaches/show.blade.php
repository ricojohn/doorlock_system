@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Coach Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('coaches.index') }}">Coaches</a></li>
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
                        <h5 class="card-title">Coach Information</h5>
                        <div>
                            <a href="{{ route('coaches.edit', $coach) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('coaches.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $coach->first_name }} {{ $coach->last_name }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Email</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->email }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Phone</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->phone ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Specialty</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->specialty ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            <span class="badge bg-{{ $coach->status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($coach->status) }}
                            </span>
                        </div>
                    </div>

                    @if($coach->notes)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Notes</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->notes }}</div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Assigned Members</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>PT Billing</th>
                                    <th>PT Rate</th>
                                    <th style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coach->members as $member)
                                    <tr>
                                        <td>{{ $member->full_name }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($member->status ?? 'active') === 'active' ? 'success' : (($member->status ?? '') === 'suspended' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($member->status ?? 'active') }}
                                            </span>
                                        </td>
                                        <td>{{ $member->pt_billing_type ? str_replace('_', ' ', ucfirst($member->pt_billing_type)) : 'N/A' }}</td>
                                        <td>
                                            @if($member->pt_rate !== null)
                                                ₱{{ number_format($member->pt_rate, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info" title="View member">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No members assigned.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


