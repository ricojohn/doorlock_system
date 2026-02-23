@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Create PT Package</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pt-packages.index') }}">PT Packages</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">PT Package Information</h5>
                        <a href="{{ route('pt-packages.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>

                    <form action="{{ route('pt-packages.store') }}" method="POST" class="row g-3" id="pt-package-form">
                        @csrf

                        <div class="col-12"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-info-circle"></i> Basic Information</h6></div>

                        <div class="col-md-6">
                            <label class="form-label">Package Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Package Type <span class="text-danger">*</span></label>
                            <select name="package_type" class="form-select @error('package_type') is-invalid @enderror" required>
                                <option value="New" @selected(old('package_type') === 'New')>New</option>
                                <option value="Renewal" @selected(old('package_type') === 'Renewal')>Renewal</option>
                            </select>
                            @error('package_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Package Rate (₱) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="package_rate" id="package_rate" class="form-control @error('package_rate') is-invalid @enderror" value="{{ old('package_rate') }}" required>
                            @error('package_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Session Count <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="session_count" id="session_count" class="form-control @error('session_count') is-invalid @enderror" value="{{ old('session_count', 1) }}" required>
                            @error('session_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rate per Session (₱)</label>
                            <input type="number" step="0.01" min="0" name="rate_per_session" id="rate_per_session" class="form-control @error('rate_per_session') is-invalid @enderror" value="{{ old('rate_per_session') }}" placeholder="Auto">
                            @error('rate_per_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Commission %</label>
                            <input type="number" step="0.01" min="0" max="100" name="commission_percentage" id="commission_percentage" class="form-control @error('commission_percentage') is-invalid @enderror" value="{{ old('commission_percentage') }}" placeholder="e.g. 10">
                            @error('commission_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commission per Session (₱)</label>
                            <input type="number" step="0.01" min="0" name="commission_per_session" id="commission_per_session" class="form-control @error('commission_per_session') is-invalid @enderror" value="{{ old('commission_per_session') }}" placeholder="Auto">
                            @error('commission_per_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-primary border-bottom pb-2 mb-0"><i class="bi bi-activity"></i> Exercises</h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-exercise"><i class="bi bi-plus-circle"></i> Add Exercise</button>
                            </div>
                        </div>
                        <div class="col-12" id="exercises-container"></div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Create PT Package</button>
                            <a href="{{ route('pt-packages.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/pt-package-form.js') }}"></script>
@endpush
