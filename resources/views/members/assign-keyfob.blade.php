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
                                        <option 
                                            value="{{ $keyfob->id }}" 
                                            data-price="{{ $keyfob->price ?? '' }}"
                                            {{ old('keyfob_id') == $keyfob->id ? 'selected' : '' }}>
                                            {{ $keyfob->card_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('keyfob_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Select an available keyfob to assign to {{ $member->full_name }}</small>
                            </div>

                            <div class="col-md-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="0.00" readonly>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Auto-populated from selected keyfob</small>
                            </div>

                            <div class="col-md-6">
                                <label for="issued_at" class="form-label">Issued Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('issued_at') is-invalid @enderror" id="issued_at" name="issued_at" value="{{ old('issued_at', now()->format('Y-m-d')) }}" required readonly>
                                @error('issued_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Auto-populated with current date</small>
                            </div>

                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <input type="text" class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" value="{{ old('payment_method') }}" placeholder="e.g., Cash, Card, GCash">
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const keyfobSelect = document.getElementById('keyfob_id');
        const priceInput = document.getElementById('price');

        keyfobSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            
            if (price && price !== '') {
                priceInput.value = parseFloat(price).toFixed(2);
            } else {
                priceInput.value = '';
            }
        });

        // Trigger change on page load if keyfob is pre-selected
        if (keyfobSelect.value) {
            keyfobSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush

