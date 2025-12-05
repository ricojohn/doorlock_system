@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Register Keyfob for Member</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->full_name }}</a></li>
            <li class="breadcrumb-item active">Register Keyfob</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Select Keyfob for {{ $member->full_name }}</h5>

                    @if($availableKeyfobs->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No available keyfobs found. Please register a keyfob first.
                        </div>
                        <div class="text-center">
                            <a href="{{ route('rfid-cards.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Register New Keyfob
                            </a>
                            <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Member
                            </a>
                        </div>
                    @else
                        <form action="{{ route('members.store-keyfob', $member) }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-md-12">
                                <label for="keyfob_id" class="form-label">Select Keyfob <span class="text-danger">*</span></label>
                                <select class="form-select @error('keyfob_id') is-invalid @enderror" id="keyfob_id" name="keyfob_id" required>
                                    <option value="">Select a Keyfob</option>
                                    @foreach($availableKeyfobs as $keyfob)
                                        <option value="{{ $keyfob->id }}" {{ old('keyfob_id') == $keyfob->id ? 'selected' : '' }}>
                                            {{ $keyfob->card_number }} - {{ $keyfob->type == 'keyfob' ? 'Key Fob' : 'Card' }}
                                            @if($keyfob->issued_at)
                                                (Issued: {{ $keyfob->issued_at->format('M d, Y') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('keyfob_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Select an available keyfob to assign to {{ $member->full_name }}</small>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Member Information</h6>
                                        <p class="mb-1"><strong>Name:</strong> {{ $member->full_name }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $member->email }}</p>
                                        <p class="mb-0"><strong>Phone:</strong> {{ $member->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Assign Keyfob
                                </button>
                                <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

