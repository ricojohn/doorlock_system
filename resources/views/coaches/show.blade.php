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
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Coach Information</h5>
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
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Full Name</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $coach->full_name }}</strong></div>
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
                        <div class="col-lg-3 col-md-4 label">Date of Birth</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->date_of_birth ? $coach->date_of_birth->format('F d, Y') : 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Gender</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->gender)
                                <span class="badge bg-info">{{ ucfirst($coach->gender) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Specialty</div>
                        <div class="col-lg-9 col-md-8">{{ $coach->specialty ?? 'N/A' }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Address</div>
                        <div class="col-lg-9 col-md-8">
                            @if($coach->house_number || $coach->street || $coach->barangay || $coach->city)
                                {{ $coach->house_number }} {{ $coach->street }}, {{ $coach->barangay }}, {{ $coach->city }}, {{ $coach->state }} {{ $coach->postal_code }}, {{ $coach->country }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

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

        @if($coach->workHistories->count() > 0)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Work History</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-light">
                                    <th>Company Name</th>
                                    <th>Position</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coach->workHistories as $workHistory)
                                    <tr>
                                        <td><strong>{{ $workHistory->company_name }}</strong></td>
                                        <td>{{ $workHistory->position }}</td>
                                        <td>{{ $workHistory->start_date->format('M d, Y') }}</td>
                                        <td>{{ $workHistory->end_date ? $workHistory->end_date->format('M d, Y') : 'Current' }}</td>
                                        <td>{{ $workHistory->description ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($coach->certificates->count() > 0)
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Certificates</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-light">
                                    <th>Certificate Name</th>
                                    <th>Issuing Organization</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Certificate Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coach->certificates as $certificate)
                                    <tr>
                                        <td><strong>{{ $certificate->certificate_name }}</strong></td>
                                        <td>{{ $certificate->issuing_organization }}</td>
                                        <td>{{ $certificate->issue_date->format('M d, Y') }}</td>
                                        <td>{{ $certificate->expiry_date ? $certificate->expiry_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $certificate->certificate_number ?? 'N/A' }}</td>
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

