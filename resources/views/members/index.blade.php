
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
                                <div id="exportButtons"></div>
                                <a href="{{ route('members.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Member
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="members-table">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Date of Birth</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Subscription</th>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)
                                    <tr>
                                        <td>{{ $member->full_name }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->phone ?? 'N/A' }}</td>
                                        <td>{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            @if($member->gender)
                                                <span class="badge bg-info">{{ ucfirst($member->gender) }}</span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->activeSubscription)
                                                <span class="badge bg-success">{{ ucfirst($member->activeSubscription->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">No Active Subscription</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->activeSubscription)
                                                <span class="badge bg-success">{{ ucfirst($member->activeSubscription->plan_name) }}</span>
                                            @else
                                                <span class="badge bg-danger">No Active Subscription</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($member->activeSubscription)
                                                <span class="badge bg-success">{{ ucfirst($member->activeSubscription->payment_status) }}</span>
                                            @else
                                                <span class="badge bg-danger">No Active Subscription</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if(! $member->activeSubscription)
                                                    <a href="{{ route('subscriptions.create', ['member_id' => $member->id]) }}" class="btn btn-sm btn-success" title="Renew Subscription">
                                                        <i class="bi bi-arrow-clockwise"></i> Renew
                                                    </a>
                                                @endif
                                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline delete-form">
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

