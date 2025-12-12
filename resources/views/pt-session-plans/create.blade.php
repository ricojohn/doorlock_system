@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Create PT Session Plan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pt-session-plans.index') }}">PT Session Plans</a></li>
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
                        <h5 class="card-title mb-0">PT Session Plan Information</h5>
                        <a href="{{ route('pt-session-plans.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('pt-session-plans.store') }}" method="POST" class="row g-3" id="pt-session-plan-form">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle"></i> Basic Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Coach <span class="text-danger">*</span></label>
                            <select name="coach_id" class="form-select @error('coach_id') is-invalid @enderror" required>
                                <option value="">Select Coach</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" @selected(old('coach_id') == $coach->id)>
                                        {{ $coach->full_name }} - {{ $coach->specialty ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coach_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Member <span class="text-danger">*</span></label>
                            <select name="member_id" class="form-select @error('member_id') is-invalid @enderror" required>
                                <option value="">Select Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                                        {{ $member->full_name }} - {{ $member->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g., Strength Training Plan">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                                <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="0.00">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Plan description...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Exercises Section -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary border-bottom pb-2 mb-0">
                                    <i class="bi bi-activity"></i> Exercises
                                </h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-exercise">
                                    <i class="bi bi-plus-circle"></i> Add Exercise
                                </button>
                            </div>
                        </div>

                        <div id="exercises-container">
                            <!-- Exercises will be added here dynamically -->
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create PT Session Plan
                                </button>
                                <a href="{{ route('pt-session-plans.index') }}" class="btn btn-secondary">
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
        let exerciseCount = 0;

        // Exercise Template
        function getExerciseTemplate(index) {
            return `
                <div class="col-12 exercise-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Exercise #${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-exercise">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Exercise Name <span class="text-danger">*</span></label>
                            <input type="text" name="items[${index}][exercise_name]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sets</label>
                            <input type="number" min="1" name="items[${index}][sets]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Reps</label>
                            <input type="number" min="1" name="items[${index}][reps]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" name="items[${index}][weight]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" min="1" name="items[${index}][duration_minutes]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Rest Period (seconds)</label>
                            <input type="number" min="0" name="items[${index}][rest_period_seconds]" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="items[${index}][notes]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add Exercise
        document.getElementById('add-exercise').addEventListener('click', function() {
            const container = document.getElementById('exercises-container');
            container.insertAdjacentHTML('beforeend', getExerciseTemplate(exerciseCount));
            exerciseCount++;
        });

        // Remove Exercise
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-exercise')) {
                e.target.closest('.exercise-item').remove();
            }
        });
    });
</script>
@endpush

