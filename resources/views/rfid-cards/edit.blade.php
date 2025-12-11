@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Edit RFID Card / Key Fob</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('rfid-cards.index') }}">RFID Cards</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Card / Key Fob Information</h5>

                    <form action="{{ route('rfid-cards.update', $rfidCard) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="card_number" class="form-label">Card Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('card_number') is-invalid @enderror" id="card_number" name="card_number" value="{{ old('card_number', $rfidCard->card_number) }}" placeholder="Enter card number" required>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Enter the unique RFID card or key fob number</small>
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="card" {{ old('type', $rfidCard->type) == 'card' ? 'selected' : '' }}>RFID Card</option>
                                <option value="keyfob" {{ old('type', $rfidCard->type) == 'keyfob' ? 'selected' : '' }}>Key Fob</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', $rfidCard->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $rfidCard->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="lost" {{ old('status', $rfidCard->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                <option value="stolen" {{ old('status', $rfidCard->status) == 'stolen' ? 'selected' : '' }}>Stolen</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $rfidCard->price) }}" placeholder="0.00">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <input type="text" class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" value="{{ old('payment_method', $rfidCard->payment_method) }}" placeholder="e.g., Cash, Card, GCash">
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="issued_at" class="form-label">Issued Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('issued_at') is-invalid @enderror" id="issued_at" name="issued_at" value="{{ old('issued_at', $rfidCard->issued_at->format('Y-m-d')) }}" required>
                            @error('issued_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="expires_at" class="form-label">Expires Date (Optional)</label>
                            <input type="date" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at', $rfidCard->expires_at?->format('Y-m-d')) }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Leave empty for no expiration</small>
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Additional notes about this card/key fob">{{ old('notes', $rfidCard->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Card / Key Fob</button>
                            <a href="{{ route('rfid-cards.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

