@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Plan Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Plans</li>
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
                            <h5 class="card-title">Plans List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('plans.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Plan
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="plans-table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Duration</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Subscriptions</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td><strong>{{ $plan->name }}</strong></td>
                                        <td>₱{{ number_format($plan->price, 2) }}</td>
                                        <td>{{ $plan->duration_months }} {{ $plan->duration_months == 1 ? 'month' : 'months' }}</td>
                                        <td>{{ $plan->description ? Str::limit($plan->description, 50) : 'N/A' }}</td>
                                        <td>
                                            @if($plan->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $plan->subscriptions_count }}</td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('plans.show', $plan) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('plans.edit', $plan) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('plans.destroy', $plan) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
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

