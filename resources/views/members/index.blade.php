
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
                                    <th style="width: 15%;">Member</th>
                                    <th style="width: 15%;">Active Membership</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $member)

                                    <tr>
                                        <td>
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th>Information</th>
                                                        <th>Coach</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $member->full_name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $member->email ?? 'N/A' }}</small>
                                                            <br>
                                                            <small class="text-muted">{{ $member->phone ?? 'N/A' }}</small>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">{{ $member->coach->full_name ?? 'N/A' }}</small>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td>
                                            <div class="card">
                                                <div class="card-body">
                                                    <table class="table table-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th>Subscription</th>
                                                                <th>Status</th>
                                                                <th>Duration</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if($member->activeSubscription)
                                                            <tr>
                                                                <td>
                                                                    <small class="text-muted">{{ $member->activeSubscription->plan->name ?? 'N/A' }}</small>
                                                                </td>
                                                                <td>
                                                                    @if($member->activeSubscription->status == 'active')
                                                                        <span class="badge bg-success">Active</span>
                                                                    @elseif($member->activeSubscription->status == 'expired')
                                                                        <span class="badge bg-danger">Expired</span>
                                                                    @else
                                                                        <span class="badge bg-warning">Pending</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <small class="text-muted">
                                                                        <strong>Start Date:</strong> {{ $member->activeSubscription->start_date->format('M d, Y') }} - 
                                                                        <br>
                                                                        <strong>End Date:</strong> {{ $member->activeSubscription->end_date->format('M d, Y') }}
                                                                        <br>
                                                                        <strong>Duration:</strong> {{ $member->activeSubscription->duration_months }} months
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">No active subscription</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if(! $member->activeRfidCard)
                                                    <a href="{{ route('members.assign-keyfob', $member) }}" class="btn btn-sm btn-primary" title="Register Keyfob">
                                                        <i class="bi bi-credit-card"></i> Register Keyfob
                                                    </a>
                                                @endif
                                                @if(! $member->activeSubscription)
                                                    <form action="{{ route('members.renew', $member) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Renew Subscription">
                                                            <i class="bi bi-arrow-clockwise"></i> Renew
                                                        </button>
                                                    </form>
                                                @endif
                                                {{-- <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline delete-form">
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

