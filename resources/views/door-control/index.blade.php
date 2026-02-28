@extends('layout.app')

@section('title', 'Door Control')

@section('content')
<div class="pagetitle">
    <h1><i class="bi bi-key me-2"></i>Door Control</h1>
    <p class="mb-0 text-muted">Remotely unlock gym entrance doors</p>
</div>

<section class="section">
    <div class="row gap-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <span class="bg-success rounded-circle" style="width: 12px; height: 12px;"></span>
                    <span class="fw-semibold">System Online</span>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-unlock-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Main Door</h5>
                                <p class="text-muted small mb-0">Main Entrance</p>
                            </div>
                            <span class="badge bg-success align-self-start">READY</span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('door-control.open') }}" method="POST" class="door-open-form">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center gap-2" id="btn-open-door">
                                    <i class="bi bi-key"></i>
                                    <span class="btn-open-text">Open Door</span>
                                </button>
                            </form>
                            <p class="text-muted small mb-0 text-center text-md-end" id="auto-lock-message" style="display: none;">Auto-locking in <span id="countdown">5</span> seconds...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.door-open-form');
    const btn = document.getElementById('btn-open-door');
    const btnText = btn.querySelector('.btn-open-text');
    const autoLockEl = document.getElementById('auto-lock-message');
    const countdownEl = document.getElementById('countdown');

    form.addEventListener('submit', function () {
        btn.disabled = true;
        btnText.textContent = 'Opening...';
        autoLockEl.style.display = 'block';
        var sec = 5;
        countdownEl.textContent = sec;
        var t = setInterval(function () {
            sec--;
            countdownEl.textContent = sec;
            if (sec <= 0) {
                clearInterval(t);
                btn.disabled = false;
                btnText.textContent = 'Open Door';
                autoLockEl.style.display = 'none';
            }
        }, 1000);
    });
});
</script>
@endpush
@endsection
