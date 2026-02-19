@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Subscribe to PT Package</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->full_name }}</a></li>
            <li class="breadcrumb-item active">Subscribe to PT Package</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Subscribe {{ $member->full_name }} to a PT Package</h5>
                        <a href="{{ route('members.show', $member) }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>

                    @if(!$member->activeMemberSubscription)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> This member does not have an active gym subscription. They must have an active subscription to subscribe to a PT package.
                        </div>
                    @endif
                    @if($member->activeMemberPtPackage)
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> This member already has an active PT package. Subscribe to a new one only after the current package expires or is fully used.
                        </div>
                    @endif

                    <form action="{{ route('members.store-subscribe-pt-package', $member) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                        @csrf

                        <div class="col-12"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-person"></i> Member</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Member</label>
                            <input type="text" class="form-control" value="{{ $member->full_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="{{ $member->email }}" readonly>
                        </div>

                        <div class="col-12 mt-3"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-box-seam"></i> PT Package</h6></div>
                        <div class="col-12">
                            <label class="form-label">PT Package <span class="text-danger">*</span></label>
                            <select name="pt_package_id" id="pt_package_id" class="form-select @error('pt_package_id') is-invalid @enderror" required>
                                <option value="">— Select PT Package —</option>
                                @foreach($ptPackages as $pkg)
                                    <option value="{{ $pkg->id }}" data-rate="{{ $pkg->package_rate }}" data-sessions="{{ $pkg->session_count }}" data-coach-id="{{ $pkg->coach_id ?? '' }}" @selected(old('pt_package_id') == $pkg->id)>
                                        {{ $pkg->name }} — ₱{{ number_format($pkg->package_rate, 2) }} ({{ $pkg->session_count }} sessions)
                                    </option>
                                @endforeach
                            </select>
                            @error('pt_package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Coach</label>
                            <select name="coach_id" id="coach_id" class="form-select @error('coach_id') is-invalid @enderror">
                                <option value="">— Select —</option>
                                @foreach($coaches as $c)
                                    <option value="{{ $c->id }}" @selected(old('coach_id') == $c->id)>{{ $c->full_name }}</option>
                                @endforeach
                            </select>
                            @error('coach_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Type</label>
                            <input type="text" name="payment_type" class="form-control @error('payment_type') is-invalid @enderror" value="{{ old('payment_type') }}" placeholder="e.g. Cash, Card">
                            @error('payment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date (optional)</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-3"><h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-receipt"></i> Receipt</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Receipt Number</label>
                            <input type="text" name="receipt_number" class="form-control @error('receipt_number') is-invalid @enderror" value="{{ old('receipt_number') }}" placeholder="e.g. OR-12345">
                            @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Receipt Image</label>
                            <input type="file" name="receipt_image" class="form-control @error('receipt_image') is-invalid @enderror" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            <small class="text-muted">JPEG, PNG, GIF or WebP. Max 5MB.</small>
                            @error('receipt_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary" @if(!$member->activeMemberSubscription || $member->activeMemberPtPackage) disabled @endif><i class="bi bi-check-circle"></i> Subscribe to PT Package</button>
                            <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">Cancel</a>
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
    var pkgSelect = document.getElementById('pt_package_id');
    var coachSelect = document.getElementById('coach_id');
    if (pkgSelect && coachSelect) {
        pkgSelect.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            var coachId = opt && opt.dataset.coachId ? opt.dataset.coachId : '';
            if (coachId && coachSelect) {
                for (var i = 0; i < coachSelect.options.length; i++) {
                    if (coachSelect.options[i].value === coachId) {
                        coachSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        });
    }
});
</script>
@endpush
