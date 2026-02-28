@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>PT Package Subscriptions</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">PT Package Subscriptions</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Subscriptions List</h5>
                    <p class="text-muted small mb-3">All members subscribed to PT packages, with payment and coach details.</p>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th>Member</th>
                                    <th>PT Package</th>
                                    <th>Coach</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                    <th>Price Paid</th>
                                    <th>Sessions</th>
                                    <th>Receipt #</th>
                                    <th style="width: 130px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscriptions as $sub)
                                    <tr>
                                        <td>
                                            <a href="{{ route('members.show', $sub->member) }}">
                                                {{ $sub->member?->full_name ?? 'Unknown member' }}
                                            </a>
                                        </td>
                                        <td>{{ $sub->ptPackage?->name ?? 'N/A' }}</td>
                                        <td>{{ $sub->coach?->full_name ?? '—' }}</td>
                                        <td>{{ $sub->start_date?->format('M d, Y') ?? '—' }}</td>
                                        <td>{{ $sub->end_date?->format('M d, Y') ?? '—' }}</td>
                                        <td>
                                            @if ($sub->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif ($sub->status === 'exhausted')
                                                <span class="badge bg-warning text-dark">Exhausted</span>
                                            @else
                                                <span class="badge bg-secondary text-white text-capitalize">{{ $sub->status }}</span>
                                            @endif
                                        </td>
                                        <td>₱{{ number_format($sub->price_paid, 2) }}</td>
                                        <td>
                                            {{ $sub->sessions_used }} / {{ $sub->sessions_total }}
                                            <span class="text-muted small d-block">{{ $sub->remaining_sessions }} remaining</span>
                                        </td>
                                        <td>{{ $sub->receipt_number ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('member-pt-packages.show', $sub) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">No PT package subscriptions yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

