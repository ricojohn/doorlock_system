@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>WiFi Configuration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">WiFi Configuration</li>
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
                            <h5 class="card-title">WiFi Configurations</h5>
                            <p class="text-muted mb-0">Manage WiFi credentials for ESP32 devices. Only one configuration can be active at a time.</p>
                        </div>
                        <div>
                            <a href="{{ route('wifi-configurations.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Add WiFi Configuration
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="wifi-configurations-table">
                            <thead>
                                <tr>
                                    <th scope="col">SSID</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($configurations as $config)
                                    <tr>
                                        <td><strong>{{ $config->ssid }}</strong></td>
                                        <td>
                                            <span class="password-field" data-password="{{ $config->password }}">
                                                <i class="bi bi-eye-slash"></i> <span class="masked">••••••••</span>
                                            </span>
                                        </td>
                                        <td>{{ $config->description ? Str::limit($config->description, 50) : 'N/A' }}</td>
                                        <td>
                                            @if($config->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $config->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group gap-2" role="group">
                                                <a href="{{ route('wifi-configurations.edit', $config) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('wifi-configurations.destroy', $config) }}" method="POST" class="d-inline delete-form">
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
  document.addEventListener('DOMContentLoaded', function() {
    // Password toggle visibility
    const passwordFields = document.querySelectorAll('.password-field');
    passwordFields.forEach(field => {
      field.style.cursor = 'pointer';
      field.addEventListener('click', function() {
        const masked = this.querySelector('.masked');
        const icon = this.querySelector('i');
        const password = this.getAttribute('data-password');
        
        if (masked.style.display === 'none') {
          masked.style.display = 'inline';
          icon.className = 'bi bi-eye-slash';
          this.innerHTML = '<i class="bi bi-eye-slash"></i> <span class="masked">••••••••</span>';
        } else {
          masked.style.display = 'none';
          icon.className = 'bi bi-eye';
          this.innerHTML = '<i class="bi bi-eye"></i> ' + password;
        }
      });
    });
  });
</script>
@endpush
