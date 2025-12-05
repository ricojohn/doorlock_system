@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Add New Subscription</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Subscriptions</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Subscription Information</h5>

                    <form action="{{ route('subscriptions.store') }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label for="member_id" class="form-label">Member <span class="text-danger">*</span></label>
                            <select class="form-select @error('member_id') is-invalid @enderror" id="member_id" name="member_id" required>
                                <option value="">Select Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id', $selectedMemberId ?? '') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="plan_id" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select class="form-select @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id">
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" 
                                        data-price="{{ $plan->price }}" 
                                        data-duration="{{ $plan->duration_months }}"
                                        {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ₱{{ number_format($plan->price, 2) }} ({{ $plan->duration_months }} {{ $plan->duration_months == 1 ? 'month' : 'months' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Or enter custom plan details below</small>
                        </div>

                        <div class="col-md-6">
                            <label for="plan_name" class="form-label">Custom Plan Name (Optional)</label>
                            <input type="text" class="form-control @error('plan_name') is-invalid @enderror" id="plan_name" name="plan_name" value="{{ old('plan_name') }}" placeholder="e.g., Custom Plan">
                            @error('plan_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Create Subscription</button>
                            <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
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
    const planSelect = document.getElementById('plan_id');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const priceInput = document.getElementById('price');

    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            const duration = parseInt(selectedOption.getAttribute('data-duration'));
            
            if (price) {
                priceInput.value = price;
            }
            
            if (startDateInput.value && duration) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(startDate);
                // Convert months to date
                endDate.setMonth(endDate.getMonth() + duration);
                
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${year}-${month}-${day}`;
            }
        }
    });

    startDateInput.addEventListener('change', function() {
        if (planSelect.value) {
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            const duration = parseInt(selectedOption.getAttribute('data-duration'));
            
            if (this.value && duration) {
                const startDate = new Date(this.value);
                const endDate = new Date(startDate);
                // Convert months to date
                endDate.setMonth(endDate.getMonth() + duration);
                
                const year = endDate.getFullYear();
                const month = String(endDate.getMonth() + 1).padStart(2, '0');
                const day = String(endDate.getDate()).padStart(2, '0');
                endDateInput.value = `${year}-${month}-${day}`;
            }
        }
    });
});
</script>
@endpush

