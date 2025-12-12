@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Key Fob Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rfid-cards.index') }}">RFID Cards</a></li>
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
                        <h5 class="card-title">Key Fob Information</h5>
                        <div>
                            <a href="{{ route('rfid-cards.edit', $rfidCard) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('rfid-cards.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">ID</div>
                        <div class="col-lg-9 col-md-8">{{ $rfidCard->id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Key Fob Number</div>
                        <div class="col-lg-9 col-md-8"><strong>{{ $rfidCard->card_number }}</strong></div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Member</div>
                        <div class="col-lg-9 col-md-8">
                            @if($rfidCard->member)
                                <a href="{{ route('members.show', $rfidCard->member) }}">
                                    <strong>{{ $rfidCard->member->full_name }}</strong>
                                </a>
                                <br>
                                <small class="text-muted">{{ $rfidCard->member->email }}</small>
                            @else
                                <span class="badge bg-secondary">Unassigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Status</div>
                        <div class="col-lg-9 col-md-8">
                            @if($rfidCard->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($rfidCard->status == 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @elseif($rfidCard->status == 'lost')
                                <span class="badge bg-warning">Lost</span>
                            @else
                                <span class="badge bg-danger">Stolen</span>
                            @endif
                            @if($rfidCard->isExpired())
                                <span class="badge bg-danger ms-2">Expired</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Price</div>
                        <div class="col-lg-9 col-md-8">
                            @if($rfidCard->price !== null)
                                ₱{{ number_format($rfidCard->price, 2) }}
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Issued Date</div>
                        <div class="col-lg-9 col-md-8">{{ $rfidCard->issued_at->format('F d, Y') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Expires Date</div>
                        <div class="col-lg-9 col-md-8">
                            @if($rfidCard->expires_at)
                                {{ $rfidCard->expires_at->format('F d, Y') }}
                                @if($rfidCard->isExpired())
                                    <span class="badge bg-danger ms-2">Expired</span>
                                @endif
                            @else
                                <span class="text-muted">No expiration</span>
                            @endif
                        </div>
                    </div>

                    @if($rfidCard->notes)
                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Notes</div>
                        <div class="col-lg-9 col-md-8">{{ $rfidCard->notes }}</div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Created At</div>
                        <div class="col-lg-9 col-md-8">{{ $rfidCard->created_at->format('F d, Y h:i A') }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 label">Updated At</div>
                        <div class="col-lg-9 col-md-8">{{ $rfidCard->updated_at->format('F d, Y h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

