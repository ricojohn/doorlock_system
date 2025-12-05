@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Edit WiFi Configuration</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('wifi-configurations.index') }}">WiFi Configuration</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">WiFi Configuration Information</h5>
                    <p class="text-muted">Update the WiFi configuration that will be available for ESP32 devices.</p>

                    <form action="{{ route('wifi-configurations.update', $wifiConfiguration) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="ssid" class="form-label">WiFi Name (SSID) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ssid') is-invalid @enderror" id="ssid" name="ssid" value="{{ old('ssid', $wifiConfiguration->ssid) }}" placeholder="e.g., MyWiFiNetwork" required>
                            @error('ssid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">The name of your WiFi network</small>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">WiFi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password', $wifiConfiguration->password) }}" placeholder="Enter WiFi password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">The password for your WiFi network</small>
                        </div>

                        <div class="col-md-6">
                            <label for="is_active" class="form-label">Set as Active</label>
                            <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                <option value="1" {{ old('is_active', $wifiConfiguration->is_active) == '1' ? 'selected' : '' }}>Yes (Active)</option>
                                <option value="0" {{ old('is_active', $wifiConfiguration->is_active) == '0' ? 'selected' : '' }}>No (Inactive)</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Only one configuration can be active at a time. Active configuration will be sent to ESP32 devices.</small>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Optional description (e.g., Main office WiFi, Guest network, etc.)">{{ old('description', $wifiConfiguration->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update WiFi Configuration</button>
                            <a href="{{ route('wifi-configurations.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');

    togglePassword.addEventListener('click', function() {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      if (type === 'password') {
        togglePasswordIcon.className = 'bi bi-eye';
      } else {
        togglePasswordIcon.className = 'bi bi-eye-slash';
      }
    });
  });
</script>
@endpush
