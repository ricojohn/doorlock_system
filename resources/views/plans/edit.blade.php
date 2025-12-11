@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Edit Plan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">Plans</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Plan Information</h5>

                    <form action="{{ route('plans.update', $plan) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-4">
                            <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $plan->name) }}" placeholder="e.g., Monthly, Yearly, 3-Month" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="promo_name" class="form-label">Promo Name</label>
                            <input type="text" class="form-control @error('promo_name') is-invalid @enderror" id="promo_name" name="promo_name" value="{{ old('promo_name', $plan->promo_name) }}" placeholder="e.g., Holiday Promo">
                            @error('promo_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $plan->price) }}" placeholder="0.00" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="discount_percent" class="form-label">Discount (%)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('discount_percent') is-invalid @enderror" id="discount_percent" name="discount_percent" value="{{ old('discount_percent', $plan->discount_percent) }}" placeholder="e.g., 10 for 10%">
                            @error('discount_percent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-4">
                            <label for="duration_months" class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control @error('duration_months') is-invalid @enderror" id="duration_months" name="duration_months" value="{{ old('duration_months', $plan->duration_months) }}" placeholder="e.g., 1 for monthly" required>
                            @error('duration_months')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Number of months the plan is valid for</small>
                        </div>

                        <div class="col-md-4">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $plan->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $plan->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Optional description of the plan">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Plan</button>
                            <a href="{{ route('plans.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const discountPercentInput = document.getElementById('discount_percent');
        const priceInput = document.getElementById('price');
        if (discountPercentInput && priceInput) {
            discountPercentInput.addEventListener('input', function() {
                const discountPercent = parseFloat(discountPercentInput.value);
                const price = parseFloat(priceInput.value);
                const discountedPrice = price - (price * discountPercent / 100);
                priceInput.value = discountedPrice.toFixed(2);
            });
        }
    });
</script>
@endsection

