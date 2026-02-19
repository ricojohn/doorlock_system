@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Log PT Session</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}">{{ $member->full_name }}</a></li>
            <li class="breadcrumb-item active">Log PT Session</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Log PT Session for {{ $member->full_name }}</h5>
                        <a href="{{ route('members.show', $member) }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
                    </div>

                    @if($activePackages->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No active PT package with remaining sessions. Subscribe the member to a PT package first.
                        </div>
                    @else
                    <form action="{{ route('members.pt-sessions.store', $member) }}" method="POST" class="row g-3">
                        @csrf

                        <div class="col-12">
                            <label class="form-label">PT Package <span class="text-danger">*</span></label>
                            <select name="member_pt_package_id" class="form-select @error('member_pt_package_id') is-invalid @enderror" required>
                                <option value="">— Select package —</option>
                                @foreach($activePackages as $mpp)
                                    <option value="{{ $mpp->id }}" @selected(old('member_pt_package_id') == $mpp->id)>
                                        {{ $mpp->ptPackage->name }} — {{ $mpp->remaining_sessions }} remaining
                                    </option>
                                @endforeach
                            </select>
                            @error('member_pt_package_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="conducted_at" class="form-control @error('conducted_at') is-invalid @enderror" value="{{ old('conducted_at', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('conducted_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sessions used <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="sessions_used" class="form-control @error('sessions_used') is-invalid @enderror" value="{{ old('sessions_used', 1) }}" required>
                            @error('sessions_used')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Log Session</button>
                            <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
