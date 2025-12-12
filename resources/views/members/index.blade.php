
@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Member Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Members</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column gap-2">
                            <h5 class="card-title">Members List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Member
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="members-table" style="border-collapse: collapse;">
                            <thead>
                                <tr class="table-light">
                                    <th style="width: 35%; border: 1px solid #dee2e6;">Member</th>
                                    <th style="width: 35%; border: 1px solid #dee2e6;">Subscription</th>
                                    <th style="width: 30%; border: 1px solid #dee2e6;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    @php
                                        $activeCard = $member->activeRfidCard;
                                        $activeSubscription = $member->activeSubscription;
                                        $subscriptions = $member->memberSubscriptions;
                                    @endphp
                                    <tr>
                                        <!-- Member Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <div class="row g-2">
                                                <!-- First Column: Member Info -->
                                                <div class="col-6">
                                                    <div class="mb-2">
                                                        <strong>{{ $member->full_name }}</strong>
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-envelope"></i> {{ $member->email }}
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-telephone"></i> {{ $member->phone ?? 'N/A' }}
                                                    </div>
                                                    <div class="mb-1 small">
                                                        <i class="bi bi-credit-card"></i> 
                                                        @if($activeCard)
                                                            <span class="badge bg-primary">{{ $activeCard->card_number }}</span>
                                                        @else
                                                            <span class="text-muted">No RFID</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Second Column: Coach Info -->
                                                <div class="col-6">
                                                    <div class="mb-2">
                                                        <strong>Coach Information</strong>
                                                    </div>
                                                    <div class="text-muted small">
                                                        N/A
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Subscription Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="border: 1px solid #dee2e6;">Subscription</th>
                                                        <th style="border: 1px solid #dee2e6;">Status</th>
                                                        <th style="border: 1px solid #dee2e6;">Duration</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($subscriptions->count() > 0)
                                                        @foreach($subscriptions as $memberSubscription)
                                                            <tr>
                                                                <td style="border: 1px solid #dee2e6;">{{ $memberSubscription->subscription->name ?? 'N/A' }}</td>
                                                                <td style="border: 1px solid #dee2e6;">
                                                                    @if($memberSubscription->payment_status === 'paid')
                                                                        <span class="badge bg-success">Paid</span>
                                                                    @elseif($memberSubscription->payment_status === 'pending')
                                                                        <span class="badge bg-warning">Pending</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Overdue</span>
                                                                    @endif
                                                                </td>
                                                                <td style="border: 1px solid #dee2e6;">
                                                                    {{ $memberSubscription->start_date->format('M d, Y') }} - {{ $memberSubscription->end_date->format('M d, Y') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">No Subscription</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                            <td class="text-muted" style="border: 1px solid #dee2e6;">-</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        
                                        <!-- Action Column -->
                                        <td style="border: 1px solid #dee2e6;">
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('members.show', $member) }}" class="btn btn-info" title="View">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                                <a href="{{ route('members.edit', $member) }}" class="btn btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                @if(!$activeCard)
                                                    <a href="{{ route('members.assign-keyfob', $member) }}" class="btn btn-primary" title="Add Keyfob">
                                                        <i class="bi bi-credit-card"></i> Add Keyfob
                                                    </a>
                                                @endif
                                                <a href="{{ route('subscriptions.create-for-member', $member) }}" class="btn btn-success" title="Add Subscription">
                                                    <i class="bi bi-calendar-check"></i> Add Subscription
                                                </a>
                                                <button type="button" class="btn btn-secondary" title="Add PT" disabled>
                                                    <i class="bi bi-activity"></i> Add PT
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
  // Delete confirmation with SweetAlert2
  document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    
    deleteForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush

