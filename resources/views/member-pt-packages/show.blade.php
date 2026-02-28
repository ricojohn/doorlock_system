@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>PT Package Subscription Details</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('member-pt-packages.index') }}">PT Package Subscriptions</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
    </nav>
</div>

@php
    $sub = $memberPtPackage;
@endphp

<section class="section">
    <div class="row g-3">
        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Member Information</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $sub->member?->full_name ?? 'Unknown member' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $sub->member?->email ?? '—' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $sub->member?->phone ?? '—' }}</p>
                    <a href="{{ $sub->member ? route('members.show', $sub->member) : '#' }}" class="btn btn-outline-primary btn-sm mt-2" @if(!$sub->member) disabled @endif>
                        <i class="bi bi-person"></i> View member profile
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">PT Package</h5>
                    <p class="mb-1"><strong>Package:</strong> {{ $sub->ptPackage?->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Coach:</strong> {{ $sub->coach?->full_name ?? '—' }}</p>
                    <p class="mb-1"><strong>Sessions:</strong> {{ $sub->sessions_total }}</p>
                    <p class="mb-1"><strong>Sessions used:</strong> {{ $sub->sessions_used }}</p>
                    <p class="mb-1"><strong>Remaining:</strong> {{ $sub->remaining_sessions }}</p>
                    <p class="mb-1">
                        <strong>Status:</strong>
                        @if ($sub->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif ($sub->status === 'exhausted')
                            <span class="badge bg-warning text-dark">Exhausted</span>
                        @else
                            <span class="badge bg-secondary text-capitalize">{{ $sub->status }}</span>
                        @endif
                    </p>
                    <p class="mb-1"><strong>Start date:</strong> {{ $sub->start_date?->format('M d, Y') ?? '—' }}</p>
                    <p class="mb-0"><strong>End date:</strong> {{ $sub->end_date?->format('M d, Y') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Payment & Commission</h5>
                    <p class="mb-1"><strong>Price paid:</strong> ₱{{ number_format($sub->price_paid, 2) }}</p>
                    <p class="mb-1"><strong>Payment type:</strong> {{ $sub->payment_type ?? '—' }}</p>
                    <p class="mb-1"><strong>Receipt number:</strong> {{ $sub->receipt_number ?? '—' }}</p>
                    <p class="mb-1"><strong>Commission %:</strong> {{ $sub->commission_percentage !== null ? $sub->commission_percentage . '%' : '—' }}</p>
                    <p class="mb-1"><strong>Commission/session:</strong> {{ $sub->commission_per_session !== null ? '₱' . number_format($sub->commission_per_session, 2) : '—' }}</p>
                    @php
                        $totalCommission = $sub->commission_per_session !== null && $sub->sessions_total
                            ? (float) $sub->commission_per_session * (int) $sub->sessions_total
                            : null;
                    @endphp
                    <p class="mb-1"><strong>Total commission (full package):</strong> {{ $totalCommission !== null ? '₱' . number_format($totalCommission, 2) : '—' }}</p>

                    @if ($sub->receipt_image)
                        <div class="mt-3">
                            <p class="mb-1"><strong>Receipt image:</strong></p>
                            <a href="{{ asset('storage/' . $sub->receipt_image) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-receipt"></i> View receipt
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

