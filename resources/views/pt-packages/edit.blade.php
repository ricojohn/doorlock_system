@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Edit PT Package</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pt-packages.index') }}">PT Packages</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">PT Package: {{ $ptPackage->name }}</h5>
                        <a href="{{ route('pt-packages.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>

                    <form action="{{ route('pt-packages.update', $ptPackage) }}" method="POST" class="row g-3" id="pt-package-form">
                        @csrf
                        @method('PUT')

                        <div class="col-12"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-info-circle"></i> Basic Information</h6></div>

                        <div class="col-md-6">
                            <label class="form-label">Package Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $ptPackage->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Package Type <span class="text-danger">*</span></label>
                            <select name="package_type" class="form-select @error('package_type') is-invalid @enderror" required>
                                <option value="New" @selected(old('package_type', $ptPackage->package_type) === 'New')>New</option>
                                <option value="Renewal" @selected(old('package_type', $ptPackage->package_type) === 'Renewal')>Renewal</option>
                            </select>
                            @error('package_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Package Rate (₱) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="package_rate" id="package_rate" class="form-control @error('package_rate') is-invalid @enderror" value="{{ old('package_rate', $ptPackage->package_rate) }}" required>
                            @error('package_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Session Count <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="session_count" id="session_count" class="form-control @error('session_count') is-invalid @enderror" value="{{ old('session_count', $ptPackage->session_count) }}" required>
                            @error('session_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Rate per Session (₱)</label>
                            <input type="number" step="0.01" min="0" name="rate_per_session" id="rate_per_session" class="form-control @error('rate_per_session') is-invalid @enderror" value="{{ old('rate_per_session', $ptPackage->rate_per_session) }}" placeholder="Auto">
                            @error('rate_per_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Commission %</label>
                            <input type="number" step="0.01" min="0" max="100" name="commission_percentage" id="commission_percentage" class="form-control @error('commission_percentage') is-invalid @enderror" value="{{ old('commission_percentage', $ptPackage->commission_percentage) }}" placeholder="e.g. 10">
                            @error('commission_percentage')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Commission per Session (₱)</label>
                            <input type="number" step="0.01" min="0" name="commission_per_session" id="commission_per_session" class="form-control @error('commission_per_session') is-invalid @enderror" value="{{ old('commission_per_session', $ptPackage->commission_per_session) }}" placeholder="Auto">
                            @error('commission_per_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" @selected(old('status', $ptPackage->status) === 'active')>Active</option>
                                <option value="inactive" @selected(old('status', $ptPackage->status) === 'inactive')>Inactive</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $ptPackage->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-primary border-bottom pb-2 mb-0"><i class="bi bi-activity"></i> Exercises</h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-exercise"><i class="bi bi-plus-circle"></i> Add Exercise</button>
                            </div>
                        </div>
                        <div class="col-12" id="exercises-container">
                            @foreach(old('exercises', $ptPackage->exercises) as $i => $ex)
                                @php $ex = is_object($ex) ? $ex : (object) $ex; @endphp
                                <div class="border rounded p-3 mb-3 exercise-item">
                                    <div class="d-flex justify-content-between mb-2"><strong>Exercise #{{ $i + 1 }}</strong><button type="button" class="btn btn-sm btn-danger remove-exercise">Remove</button></div>
                                    <div class="row g-2">
                                        <div class="col-md-6"><label class="form-label">Exercise Name</label><input type="text" name="exercises[{{ $i }}][exercise_name]" class="form-control" value="{{ old("exercises.{$i}.exercise_name", $ex->exercise_name ?? '') }}"></div>
                                        <div class="col-md-2"><label class="form-label">Sets</label><input type="number" min="0" name="exercises[{{ $i }}][sets]" class="form-control" value="{{ old("exercises.{$i}.sets", $ex->sets ?? '') }}"></div>
                                        <div class="col-md-2"><label class="form-label">Reps</label><input type="number" min="0" name="exercises[{{ $i }}][reps]" class="form-control" value="{{ old("exercises.{$i}.reps", $ex->reps ?? '') }}"></div>
                                        <div class="col-md-2"><label class="form-label">Weight</label><input type="number" step="0.01" min="0" name="exercises[{{ $i }}][weight]" class="form-control" value="{{ old("exercises.{$i}.weight", $ex->weight ?? '') }}"></div>
                                        <div class="col-md-3"><label class="form-label">Duration (min)</label><input type="number" min="0" name="exercises[{{ $i }}][duration_minutes]" class="form-control" value="{{ old("exercises.{$i}.duration_minutes", $ex->duration_minutes ?? '') }}"></div>
                                        <div class="col-md-3"><label class="form-label">Rest (sec)</label><input type="number" min="0" name="exercises[{{ $i }}][rest_period_seconds]" class="form-control" value="{{ old("exercises.{$i}.rest_period_seconds", $ex->rest_period_seconds ?? '') }}"></div>
                                        <div class="col-md-6"><label class="form-label">Notes</label><input type="text" name="exercises[{{ $i }}][notes]" class="form-control" value="{{ old("exercises.{$i}.notes", $ex->notes ?? '') }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update PT Package</button>
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
