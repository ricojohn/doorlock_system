@extends('layout.app')

@section('title', 'Settings - '.($settings['app_name'] ?? config('app.name')))

@section('content')

<div class="pagetitle">
    <h1>Settings</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Settings</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Branding &amp; Theme</h5>
                    <p class="text-muted small">Changes apply to all admin pages after save. Logo and favicon are optional.</p>

                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-palette"></i> Branding</h6>
                        </div>

                        <div class="col-md-6">
                            <label for="app_name" class="form-label">App name <span class="text-danger">*</span></label>
                            <input type="text" id="app_name" name="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ old('app_name', $settings['app_name'] ?? '') }}" required maxlength="100">
                            @error('app_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" id="logo" name="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpeg,.jpg,.png,.gif,.webp,.svg">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!empty($settings['logo_path']))
                                <div class="mt-2">
                                    <span class="text-muted small">Current:</span>
                                    <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Logo" class="img-thumbnail ms-2" style="max-height: 40px;">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="favicon" class="form-label">Favicon (optional)</label>
                            <input type="file" id="favicon" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept=".ico,.png,.gif">
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(!empty($settings['favicon_path']))
                                <div class="mt-2">
                                    <span class="text-muted small">Current:</span>
                                    <img src="{{ asset('storage/'.$settings['favicon_path']) }}" alt="Favicon" class="ms-2" style="max-height: 24px; vertical-align: middle;">
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="bi bi-brightness-high"></i> Theme</h6>
                        </div>

                        <div class="col-md-4">
                            <label for="primary_color" class="form-label">Primary color <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" id="primary_color_swatch" class="form-control form-control-color p-1" style="width: 3rem; height: 2.5rem;" value="{{ old('primary_color', $settings['primary_color'] ?? '#4154f1') }}" title="Choose color">
                                <input type="text" id="primary_color" name="primary_color" class="form-control @error('primary_color') is-invalid @enderror" value="{{ old('primary_color', $settings['primary_color'] ?? '#4154f1') }}" placeholder="#4154f1" maxlength="20">
                            </div>
                            @error('primary_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="theme_mode" class="form-label">Default theme mode <span class="text-danger">*</span></label>
                            <select id="theme_mode" name="theme_mode" class="form-select @error('theme_mode') is-invalid @enderror" required>
                                <option value="light" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                                <option value="system" {{ old('theme_mode', $settings['theme_mode'] ?? 'light') === 'system' ? 'selected' : '' }}>System (follow device)</option>
                            </select>
                            @error('theme_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Save settings
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var swatch = document.getElementById('primary_color_swatch');
    var input = document.getElementById('primary_color');
    if (swatch && input) {
        swatch.addEventListener('input', function() { input.value = swatch.value; });
        input.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(input.value)) swatch.value = input.value;
        });
    }
});
</script>
@endpush

@endsection
