@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Coaches</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Coaches</li>
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
                            <h5 class="card-title">Coach List</h5>
                        </div>
                        <a href="{{ route('coaches.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Coach
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Members</th>
                                    <th style="width: 14%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coaches as $coach)
                                    <tr>
                                        <td>{{ $coach->first_name }} {{ $coach->last_name }}</td>
                                        <td>{{ $coach->email }}</td>
                                        <td>{{ $coach->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $coach->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($coach->status) }}
                                            </span>
                                        </td>
                                        <td><span class="badge bg-info">{{ $coach->members_count }}</span></td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('coaches.show', $coach) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('coaches.edit', $coach) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('coaches.destroy', $coach) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No coaches found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $coaches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
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


