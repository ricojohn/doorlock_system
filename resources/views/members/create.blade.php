@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Add New Member</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Member Information</h5>

                    <form action="{{ route('members.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <hr>
                            <h5 class="card-title">Keyfob Information (Optional)</h5>
                        </div>

                        <div class="col-md-12">
                            <label for="keyfob_id" class="form-label">Assign Keyfob</label>
                            <select class="form-select @error('keyfob_id') is-invalid @enderror" id="keyfob_id" name="keyfob_id">
                                <option value="">Select Keyfob (Optional)</option>
                                @foreach($availableKeyfobs as $keyfob)
                                    <option value="{{ $keyfob->id }}" {{ old('keyfob_id') == $keyfob->id ? 'selected' : '' }}>
                                        {{ $keyfob->card_number }} - {{ $keyfob->type == 'keyfob' ? 'Key Fob' : 'Card' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('keyfob_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Select an available keyfob to assign to this member</small>
                        </div>

                        <div class="col-12">
                            <hr>
                            <h5 class="card-title">Subscription Information (Optional)</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="subscription_plan_id" class="form-label">Plan</label>
                            <select class="form-select @error('subscription_plan_id') is-invalid @enderror" id="subscription_plan_id" name="subscription_plan_id">
                                <option value="">Select Plan (Optional)</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                        data-price="{{ $plan->price }}" 
                                        data-duration="{{ $plan->duration_months }}"
                                        {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }} ({{ $plan->duration_months }} {{ $plan->duration_months == 1 ? 'month' : 'months' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="subscription_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control @error('subscription_start_date') is-invalid @enderror" id="subscription_start_date" name="subscription_start_date" value="{{ old('subscription_start_date', now()->toDateString()) }}" readonly disabled>
                            <input type="hidden" name="subscription_start_date" value="{{ now()->toDateString() }}">
                            @error('subscription_start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Automatically set to today</small>
                        </div>

                        <div class="col-md-3">
                            <label for="subscription_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control @error('subscription_end_date') is-invalid @enderror" id="subscription_end_date" name="subscription_end_date" value="{{ old('subscription_end_date') }}" readonly>
                            @error('subscription_end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Auto-calculated from plan</small>
                        </div>

                        <div class="col-md-4">
                            <label for="subscription_price" class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('subscription_price') is-invalid @enderror" id="subscription_price" name="subscription_price" value="{{ old('subscription_price') }}" placeholder="0.00" readonly disabled>
                            <input type="hidden" id="subscription_price_hidden" name="subscription_price" value="{{ old('subscription_price') }}">
                            @error('subscription_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Auto-filled from plan</small>
                        </div>

                        <div class="col-md-4">
                            <label for="subscription_status" class="form-label">Status</label>
                            <select class="form-select @error('subscription_status') is-invalid @enderror" id="subscription_status" name="subscription_status">
                                <option value="">Select Status</option>
                                <option value="active" {{ old('subscription_status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('subscription_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('subscription_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('subscription_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="subscription_payment_status" class="form-label">Payment Status</label>
                            <select class="form-select @error('subscription_payment_status') is-invalid @enderror" id="subscription_payment_status" name="subscription_payment_status">
                                <option value="">Select Payment Status</option>
                                <option value="pending" {{ old('subscription_payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('subscription_payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('subscription_payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                            @error('subscription_payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="subscription_notes" class="form-label">Subscription Notes</label>
                            <textarea class="form-control @error('subscription_notes') is-invalid @enderror" id="subscription_notes" name="subscription_notes" rows="2">{{ old('subscription_notes') }}</textarea>
                            @error('subscription_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Member</button>
                            <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
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
    const planSelect = document.getElementById('subscription_plan_id');
    const startDateInput = document.getElementById('subscription_start_date');
    const endDateInput = document.getElementById('subscription_end_date');
    const priceInput = document.getElementById('subscription_price');
    const priceHiddenInput = document.getElementById('subscription_price_hidden');

    // Set start date to today
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const todayStr = `${year}-${month}-${day}`;
    
    if (startDateInput) {
        startDateInput.value = todayStr;
    }

    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const duration = parseInt(selectedOption.getAttribute('data-duration'));
            
            if (price) {
                priceInput.value = price;
                priceHiddenInput.value = price;
            }
            
            if (duration) {
                const startDate = new Date(todayStr);
                const endDate = new Date(startDate);
                // Convert months to days (approximately 30 days per month)
                endDate.setMonth(endDate.getMonth() + duration);
                
                const endYear = endDate.getFullYear();
                const endMonth = String(endDate.getMonth() + 1).padStart(2, '0');
                const endDay = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${endYear}-${endMonth}-${endDay}`;
            }
        } else {
            priceInput.value = '';
            priceHiddenInput.value = '';
            endDateInput.value = '';
        }
    });

    // Trigger change on page load if plan is already selected
    if (planSelect.value) {
        planSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

