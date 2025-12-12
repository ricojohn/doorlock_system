@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Add Subscription to Member</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->full_name }}</a></li>
            <li class="breadcrumb-item active">Add Subscription</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Subscription Information</h5>
                        <a href="{{ route('members.show', $member) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('subscriptions.store-for-member', $member) }}" method="POST" class="row g-3" id="subscription-form">
                        @csrf

                        <!-- Member Information -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person"></i> Member Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Member</label>
                            <input type="text" class="form-control" value="{{ $member->full_name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $member->email }}" readonly>
                        </div>

                        <!-- Subscription Details -->
                        <div class="col-12 mt-3">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-card-list"></i> Subscription Details
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subscription Type <span class="text-danger">*</span></label>
                            <select name="subscription_id" id="subscription_id" class="form-select @error('subscription_id') is-invalid @enderror" required>
                                <option value="">Select Subscription</option>
                                @foreach($subscriptions as $subscription)
                                    <option value="{{ $subscription->id }}" 
                                        data-price="{{ $subscription->price }}"
                                        data-duration="{{ $subscription->duration_months }}"
                                        @selected(old('subscription_id') == $subscription->id)>
                                        {{ $subscription->name }} - ₱{{ number_format($subscription->price, 2) }} ({{ $subscription->duration_months }} months)
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Subscription Type <span class="text-danger">*</span></label>
                            <select name="subscription_type" class="form-select @error('subscription_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="New" @selected(old('subscription_type') === 'New')>New</option>
                                <option value="Renewal" @selected(old('subscription_type') === 'Renewal')>Renewal</option>
                            </select>
                            @error('subscription_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" disabled placeholder="Auto-calculated">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select @error('payment_type') is-invalid @enderror">
                                <option value="">Select Payment Type</option>
                                <option value="Cash" @selected(old('payment_type') === 'Cash')>Cash</option>
                                <option value="Card" @selected(old('payment_type') === 'Card')>Card</option>
                                <option value="GCash" @selected(old('payment_type') === 'GCash')>GCash</option>
                            </select>
                            @error('payment_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
                                <option value="pending" @selected(old('payment_status', 'pending') === 'pending')>Pending</option>
                                <option value="paid" @selected(old('payment_status') === 'paid')>Paid</option>
                                <option value="overdue" @selected(old('payment_status') === 'overdue')>Overdue</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Additional notes">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Add Subscription
                                </button>
                                <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subscriptionSelect = document.getElementById('subscription_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const priceInput = document.getElementById('price');

        function calculateEndDate() {
            const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
            const duration = selectedOption.dataset.duration;
            const startDate = startDateInput.value;

            if (duration && startDate) {
                const start = new Date(startDate);
                const end = new Date(start);
                end.setMonth(end.getMonth() + parseInt(duration));
                endDateInput.value = end.toISOString().split('T')[0];
            }
        }

        function updatePrice() {
            const selectedOption = subscriptionSelect.options[subscriptionSelect.selectedIndex];
            const price = selectedOption.dataset.price;
            if (price) {
                priceInput.value = price;
            }
        }

        subscriptionSelect.addEventListener('change', function() {
            updatePrice();
            calculateEndDate();
        });

        startDateInput.addEventListener('change', function() {
            calculateEndDate();
        });
    });
</script>
@endpush

