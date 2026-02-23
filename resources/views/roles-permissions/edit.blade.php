@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Edit role: {{ ucfirst($role->name) }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('roles-permissions.index') }}">Roles & Permissions</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Permissions for role "{{ ucfirst($role->name) }}"</h5>
                        <a href="{{ route('roles-permissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>

                    <form action="{{ route('roles-permissions.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            id="perm-{{ $permission->name }}"
                                            @checked(in_array($permission->id, $assignedIds))
                                        >
                                        <label class="form-check-label" for="perm-{{ $permission->name }}">
                                            {{ str_replace('_', ' ', ucfirst($permission->name)) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($permissions->isEmpty())
                            <p class="text-muted">No permissions defined. Run the roles seeder to create them.</p>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save permissions
                            </button>
                            <a href="{{ route('roles-permissions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
