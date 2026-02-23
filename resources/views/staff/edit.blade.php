@extends('layout.app')

@section('content')

@php
    $currentRole = $staff->getRoleNames()->first() ?? 'admin';
    $coach = $staff->coach;
@endphp

<div class="pagetitle">
    <h1>Edit staff</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
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
                        <h5 class="card-title mb-0">Edit staff member</h5>
                        <a href="{{ route('staff.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>

                    <form action="{{ route('staff.update', $staff) }}" method="POST" class="row g-3" id="staff-form">
                        @csrf
                        @method('PUT')

                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person-badge"></i> Role & account</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="admin" @selected(old('role', $currentRole) === 'admin')>Admin</option>
                                <option value="coach" @selected(old('role', $currentRole) === 'coach')>Coach</option>
                                <option value="frontdesk" @selected(old('role', $currentRole) === 'frontdesk')>Front desk</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $staff->first_name ?? $staff->name) }}" required>
                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $staff->last_name) }}" required>
                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $staff->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">New password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            <small class="text-muted">Leave blank to keep current</small>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>

                        <div id="coach-fields" class="col-12 mt-4" style="display: {{ old('role', $currentRole) === 'coach' ? 'block' : 'none' }};">
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person-badge"></i> Coach details</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $coach?->phone) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date of birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $coach?->date_of_birth?->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select</option>
                                        <option value="male" @selected(old('gender', $coach?->gender) === 'male')>Male</option>
                                        <option value="female" @selected(old('gender', $coach?->gender) === 'female')>Female</option>
                                        <option value="other" @selected(old('gender', $coach?->gender) === 'other')>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Specialty</label>
                                    <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $coach?->specialty) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" @selected(old('status', $coach?->status ?? 'active') === 'active')>Active</option>
                                        <option value="inactive" @selected(old('status', $coach?->status) === 'inactive')>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-2"><h6 class="text-secondary border-bottom pb-2 mb-2"><i class="bi bi-geo-alt"></i> Address</h6></div>
                                <div class="col-md-4"><label class="form-label">House number</label><input type="text" name="house_number" class="form-control" value="{{ old('house_number', $coach?->house_number) }}"></div>
                                <div class="col-md-4"><label class="form-label">Street</label><input type="text" name="street" class="form-control" value="{{ old('street', $coach?->street) }}"></div>
                                <div class="col-md-4"><label class="form-label">Barangay</label><input type="text" name="barangay" class="form-control" value="{{ old('barangay', $coach?->barangay) }}"></div>
                                <div class="col-md-4"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city', $coach?->city) }}"></div>
                                <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state', $coach?->state) }}"></div>
                                <div class="col-md-4"><label class="form-label">Postal code</label><input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $coach?->postal_code) }}"></div>
                                <div class="col-md-4"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', $coach?->country) }}"></div>
                                <div class="col-12 mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-secondary mb-0"><i class="bi bi-briefcase"></i> Work history</h6>
                                        <button type="button" class="btn btn-sm btn-success" id="add-work-history"><i class="bi bi-plus"></i> Add</button>
                                    </div>
                                    <div id="work-histories-container">
                                        @foreach (old('work_histories', $coach?->workHistories ?? []) as $i => $wh)
                                        <div class="border rounded p-3 mb-2 work-history-item">
                                            <div class="row g-2">
                                                <div class="col-md-4"><label class="form-label small">Company</label><input type="text" name="work_histories[{{ $i }}][company_name]" class="form-control form-control-sm" value="{{ $wh['company_name'] ?? $wh->company_name ?? '' }}"></div>
                                                <div class="col-md-3"><label class="form-label small">Position</label><input type="text" name="work_histories[{{ $i }}][position]" class="form-control form-control-sm" value="{{ $wh['position'] ?? $wh->position ?? '' }}"></div>
                                                <div class="col-md-2"><label class="form-label small">Start</label><input type="date" name="work_histories[{{ $i }}][start_date]" class="form-control form-control-sm" value="{{ isset($wh->start_date) ? $wh->start_date?->format('Y-m-d') : ($wh['start_date'] ?? '') }}"></div>
                                                <div class="col-md-2"><label class="form-label small">End</label><input type="date" name="work_histories[{{ $i }}][end_date]" class="form-control form-control-sm" value="{{ isset($wh->end_date) ? $wh->end_date?->format('Y-m-d') : ($wh['end_date'] ?? '') }}"></div>
                                                <div class="col-md-1"><label class="form-label small">&nbsp;</label><button type="button" class="btn btn-sm btn-outline-danger remove-wh">×</button></div>
                                                <div class="col-12"><label class="form-label small">Description</label><textarea name="work_histories[{{ $i }}][description]" class="form-control form-control-sm" rows="1">{{ $wh['description'] ?? $wh->description ?? '' }}</textarea></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-secondary mb-0"><i class="bi bi-award"></i> Certificates</h6>
                                        <button type="button" class="btn btn-sm btn-success" id="add-certificate"><i class="bi bi-plus"></i> Add</button>
                                    </div>
                                    <div id="certificates-container">
                                        @foreach (old('certificates', $coach?->certificates ?? []) as $i => $cert)
                                        <div class="border rounded p-3 mb-2 certificate-item">
                                            <div class="row g-2">
                                                <div class="col-md-3"><label class="form-label small">Name</label><input type="text" name="certificates[{{ $i }}][certificate_name]" class="form-control form-control-sm" value="{{ $cert['certificate_name'] ?? $cert->certificate_name ?? '' }}"></div>
                                                <div class="col-md-3"><label class="form-label small">Organization</label><input type="text" name="certificates[{{ $i }}][issuing_organization]" class="form-control form-control-sm" value="{{ $cert['issuing_organization'] ?? $cert->issuing_organization ?? '' }}"></div>
                                                <div class="col-md-2"><label class="form-label small">Issue date</label><input type="date" name="certificates[{{ $i }}][issue_date]" class="form-control form-control-sm" value="{{ isset($cert->issue_date) ? $cert->issue_date?->format('Y-m-d') : ($cert['issue_date'] ?? '') }}"></div>
                                                <div class="col-md-2"><label class="form-label small">Expiry</label><input type="date" name="certificates[{{ $i }}][expiry_date]" class="form-control form-control-sm" value="{{ isset($cert->expiry_date) ? $cert->expiry_date?->format('Y-m-d') : ($cert['expiry_date'] ?? '') }}"></div>
                                                <div class="col-md-1"><label class="form-label small">&nbsp;</label><button type="button" class="btn btn-sm btn-outline-danger remove-cert">×</button></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Update</button>
                            <a href="{{ route('staff.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var roleSelect = document.getElementById('role');
    var coachFields = document.getElementById('coach-fields');
    function toggleCoach() { coachFields.style.display = roleSelect.value === 'coach' ? 'block' : 'none'; }
    roleSelect.addEventListener('change', toggleCoach);

    var whCount = {{ count(old('work_histories', $coach?->workHistories ?? [])) }};
    var certCount = {{ count(old('certificates', $coach?->certificates ?? [])) }};
    function workHistoryHtml(i) {
        return '<div class="border rounded p-3 mb-2 work-history-item"><div class="row g-2">' +
            '<div class="col-md-4"><label class="form-label small">Company</label><input type="text" name="work_histories['+i+'][company_name]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-3"><label class="form-label small">Position</label><input type="text" name="work_histories['+i+'][position]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label small">Start</label><input type="date" name="work_histories['+i+'][start_date]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label small">End</label><input type="date" name="work_histories['+i+'][end_date]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-1"><label class="form-label small">&nbsp;</label><button type="button" class="btn btn-sm btn-outline-danger remove-wh">×</button></div>' +
            '<div class="col-12"><label class="form-label small">Description</label><textarea name="work_histories['+i+'][description]" class="form-control form-control-sm" rows="1"></textarea></div></div></div>';
    }
    function certHtml(i) {
        return '<div class="border rounded p-3 mb-2 certificate-item"><div class="row g-2">' +
            '<div class="col-md-3"><label class="form-label small">Name</label><input type="text" name="certificates['+i+'][certificate_name]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-3"><label class="form-label small">Organization</label><input type="text" name="certificates['+i+'][issuing_organization]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label small">Issue date</label><input type="date" name="certificates['+i+'][issue_date]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-2"><label class="form-label small">Expiry</label><input type="date" name="certificates['+i+'][expiry_date]" class="form-control form-control-sm"></div>' +
            '<div class="col-md-1"><label class="form-label small">&nbsp;</label><button type="button" class="btn btn-sm btn-outline-danger remove-cert">×</button></div></div></div>';
    }
    document.getElementById('add-work-history').addEventListener('click', function() {
        document.getElementById('work-histories-container').insertAdjacentHTML('beforeend', workHistoryHtml(whCount++));
    });
    document.getElementById('add-certificate').addEventListener('click', function() {
        document.getElementById('certificates-container').insertAdjacentHTML('beforeend', certHtml(certCount++));
    });
    document.getElementById('work-histories-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-wh')) e.target.closest('.work-history-item').remove();
    });
    document.getElementById('certificates-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-cert')) e.target.closest('.certificate-item').remove();
    });
});
</script>
@endpush
@endsection
