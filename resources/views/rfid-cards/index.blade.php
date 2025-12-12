@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Key Fob Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">RFID Cards</li>
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
                            <h5 class="card-title">RFID Cards / Key Fobs List</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{ route('rfid-cards.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Register Card/Key Fob
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="rfid-cards-table">
                            <thead>
                                <tr>
                                    <th scope="col">Key Fob Number</th>
                                    <th scope="col">Member</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Issued Date</th>
                                    <th scope="col">Expires Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rfidCards as $rfidCard)
                                    <tr>
                                        <td><strong>{{ $rfidCard->card_number }}</strong></td>
                                        <td>
                                            @if($rfidCard->member)
                                                <a href="{{ route('members.show', $rfidCard->member) }}">
                                                    {{ $rfidCard->member->full_name }}
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">Unassigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rfidCard->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($rfidCard->status == 'inactive')
                                                <span class="badge bg-secondary">Inactive</span>
                                            @elseif($rfidCard->status == 'lost')
                                                <span class="badge bg-warning">Lost</span>
                                            @else
                                                <span class="badge bg-danger">Stolen</span>
                                            @endif
                                        </td>
                                        <td>{{ $rfidCard->issued_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($rfidCard->expires_at)
                                                {{ $rfidCard->expires_at->format('M d, Y') }}
                                                @if($rfidCard->isExpired())
                                                    <span class="badge bg-danger">Expired</span>
                                                @endif
                                            @else
                                                <span class="text-muted">No expiration</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('rfid-cards.show', $rfidCard) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('rfid-cards.edit', $rfidCard) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('rfid-cards.destroy', $rfidCard) }}" method="POST" class="d-inline delete-form">
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

