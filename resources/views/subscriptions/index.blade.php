@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Subscription Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Subscriptions</li>
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
                            <h5 class="card-title">Subscriptions List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Subscription
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="subscriptions-table">
                            <thead>
                                <tr>
                                    <th scope="col">Member</th>
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription->member->full_name }}</td>
                                        <td>
                                            @if($subscription->plan)
                                                <strong>{{ $subscription->plan->name }}</strong>
                                            @else
                                                {{ $subscription->plan_name ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription->subscription_type == 'new')
                                                <span class="badge bg-primary">New</span>
                                            @else
                                                <span class="badge bg-info">Renew</span>
                                            @endif
                                        </td>
                                        <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                        <td>{{ $subscription->end_date->format('M d, Y') }}</td>
                                        <td>₱{{ number_format($subscription->price, 2) }}</td>
                                        <td>
                                            @if($subscription->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($subscription->status == 'expired')
                                                <span class="badge bg-danger">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($subscription->payment_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                {{-- <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form> --}}
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

