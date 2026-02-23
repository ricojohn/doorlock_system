@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Guest: {{ $guest->full_name }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('guests.index') }}">Guests</a></li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Guest details</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('guests.index') }}" class="btn btn-secondary btn-sm">Back</a>
                            @if (!$guest->isConverted())
                                <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('guests.convert-to-member.form', $guest) }}" class="btn btn-success btn-sm">Convert to member</a>
                            @elseif ($guest->member)
                                <a href="{{ route('members.show', $guest->member) }}" class="btn btn-primary btn-sm">View member</a>
                            @endif
                        </div>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $guest->full_name }}</dd>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $guest->email }}</dd>
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9">{{ $guest->phone ?? '—' }}</dd>
                        <dt class="col-sm-3">Invited by</dt>
                        <dd class="col-sm-9">
                            @if ($guest->inviter)
                                {{ $guest->inviter_type === 'App\Models\User' ? 'Frontdesk' : class_basename($guest->inviter_type) }}: {{ $guest->inviter->full_name }}
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @if ($guest->status === 'converted')
                                <span class="badge bg-success">Converted</span>
                                @if ($guest->member)
                                    → <a href="{{ route('members.show', $guest->member) }}">{{ $guest->member->full_name }}</a>
                                @endif
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </dd>
                        @if ($guest->notes)
                        <dt class="col-sm-3">Notes</dt>
                        <dd class="col-sm-9">{{ $guest->notes }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
