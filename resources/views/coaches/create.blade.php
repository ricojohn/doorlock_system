@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Create Coach</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('coaches.index') }}">Coaches</a></li>
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
                        <h5 class="card-title mb-0">Coach Information</h5>
                        <a href="{{ route('coaches.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('coaches.store') }}" method="POST" class="row g-3" id="coach-form">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person"></i> Basic Information
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" @selected(old('gender') === 'male')>Male</option>
                                <option value="female" @selected(old('gender') === 'female')>Female</option>
                                <option value="other" @selected(old('gender') === 'other')>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Specialty</label>
                            <input type="text" name="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty') }}" placeholder="e.g., Personal Training, Yoga, CrossFit">
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Information Section -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-geo-alt"></i> Additional Information
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">House Number</label>
                            <input type="text" name="house_number" class="form-control @error('house_number') is-invalid @enderror" value="{{ old('house_number') }}">
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Street</label>
                            <input type="text" name="street" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}">
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Barangay</label>
                            <input type="text" name="barangay" class="form-control @error('barangay') is-invalid @enderror" value="{{ old('barangay') }}">
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}">
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Work History Section -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary border-bottom pb-2 mb-0">
                                    <i class="bi bi-briefcase"></i> Work History
                                </h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-work-history">
                                    <i class="bi bi-plus-circle"></i> Add Work History
                                </button>
                            </div>
                        </div>

                        <div id="work-histories-container">
                            <!-- Work histories will be added here dynamically -->
                        </div>

                        <!-- Certificates Section -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-primary border-bottom pb-2 mb-0">
                                    <i class="bi bi-award"></i> Certificates
                                </h6>
                                <button type="button" class="btn btn-sm btn-success" id="add-certificate">
                                    <i class="bi bi-plus-circle"></i> Add Certificate
                                </button>
                            </div>
                        </div>

                        <div id="certificates-container">
                            <!-- Certificates will be added here dynamically -->
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Create Coach
                                </button>
                                <a href="{{ route('coaches.index') }}" class="btn btn-secondary">
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
        let workHistoryCount = 0;
        let certificateCount = 0;

        // Work History Template
        function getWorkHistoryTemplate(index) {
            return `
                <div class="col-12 work-history-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Work History #${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-work-history">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="work_histories[${index}][company_name]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <input type="text" name="work_histories[${index}][position]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="work_histories[${index}][start_date]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date (Leave blank if current)</label>
                            <input type="date" name="work_histories[${index}][end_date]" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="work_histories[${index}][description]" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            `;
        }

        // Certificate Template
        function getCertificateTemplate(index) {
            return `
                <div class="col-12 certificate-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Certificate #${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-certificate">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Certificate Name</label>
                            <input type="text" name="certificates[${index}][certificate_name]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Issuing Organization</label>
                            <input type="text" name="certificates[${index}][issuing_organization]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Issue Date</label>
                            <input type="date" name="certificates[${index}][issue_date]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" name="certificates[${index}][expiry_date]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Certificate Number</label>
                            <input type="text" name="certificates[${index}][certificate_number]" class="form-control">
                        </div>
                    </div>
                </div>
            `;
        }

        // Add Work History
        document.getElementById('add-work-history').addEventListener('click', function() {
            const container = document.getElementById('work-histories-container');
            container.insertAdjacentHTML('beforeend', getWorkHistoryTemplate(workHistoryCount));
            workHistoryCount++;
        });

        // Remove Work History
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-work-history')) {
                e.target.closest('.work-history-item').remove();
            }
        });

        // Add Certificate
        document.getElementById('add-certificate').addEventListener('click', function() {
            const container = document.getElementById('certificates-container');
            container.insertAdjacentHTML('beforeend', getCertificateTemplate(certificateCount));
            certificateCount++;
        });

        // Remove Certificate
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-certificate')) {
                e.target.closest('.certificate-item').remove();
            }
        });
    });
</script>
@endpush

