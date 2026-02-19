@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Freeze Subscription</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.show', $memberSubscription->member) }}">{{ $memberSubscription->member->full_name }}</a></li>
            <li class="breadcrumb-item active">Freeze Subscription</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Freeze subscription</h5>
                        <a href="{{ route('members.show', $memberSubscription->member) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <p class="text-muted mb-4">The subscription will be paused from today until the selected end date. The member will not have access during this period.</p>

                    <div class="mb-4">
                        <strong>Member:</strong> {{ $memberSubscription->member->full_name }}<br>
                        <strong>Subscription:</strong> {{ $memberSubscription->subscription->name ?? 'N/A' }}<br>
                        <strong>End date (current):</strong> {{ $memberSubscription->end_date->format('M d, Y') }}
                    </div>

                    <form action="{{ route('member-subscriptions.freeze.store', $memberSubscription) }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="frozen_until" class="form-label">Freeze until (date) <span class="text-danger">*</span></label>
                            <input type="date" name="frozen_until" id="frozen_until" class="form-control @error('frozen_until') is-invalid @enderror" value="{{ old('frozen_until', now()->addWeek()->toDateString()) }}" min="{{ now()->toDateString() }}">
                            @error('frozen_until')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Freeze subscription</button>
                            <a href="{{ route('members.show', $memberSubscription->member) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
