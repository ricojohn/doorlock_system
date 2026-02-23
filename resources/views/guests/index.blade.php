@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Guests</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('members.index') }}">Members</a></li>
            <li class="breadcrumb-item active">Guests</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Guests List</h5>
                        <a href="{{ route('guests.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Guest
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Invited by</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($guests as $guest)
                                    <tr>
                                        <td><strong>{{ $guest->full_name }}</strong></td>
                                        <td>{{ $guest->email }}</td>
                                        <td>
                                            @if ($guest->inviter)
                                                {{ $guest->inviter_type === 'App\Models\User' ? 'Frontdesk' : class_basename($guest->inviter_type) }}: {{ $guest->inviter->full_name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($guest->status === 'converted')
                                                <span class="badge bg-success">Converted</span>
                                                @if ($guest->member)
                                                    <a href="{{ route('members.show', $guest->member) }}">View member</a>
                                                @endif
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('guests.show', $guest) }}" class="btn btn-info btn-sm">View</a>
                                                @if (!$guest->isConverted())
                                                    <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="{{ route('guests.convert-to-member.form', $guest) }}" class="btn btn-success btn-sm">Convert to member</a>
                                                    <form action="{{ route('guests.destroy', $guest) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this guest?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No guests yet.</td>
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
