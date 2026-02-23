@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Roles & Permissions</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Roles & Permissions</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Roles</h5>
                    <p class="text-muted small">Assign permissions to each role. Users with a role get the permissions assigned to that role.</p>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="table-light">
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <strong>{{ ucfirst($role->name) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $role->permissions_count }} permission(s)</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('roles-permissions.edit', $role) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-pencil"></i> Edit permissions
                                            </a>
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
