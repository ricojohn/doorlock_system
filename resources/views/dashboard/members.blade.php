@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Member Dashboard</h1>
    <p class="mb-0 text-muted">Member counts and new sign-ups for the selected date range.</p>
</div>

<section class="section dashboard">
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <span class="text-muted ms-auto small">Range applies to every report below.</span>
            <form class="row g-3 align-items-end" method="GET" action="{{ route('dashboard.members') }}">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', request('start_date', $filters['start_date'])) }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', request('end_date', $filters['end_date'])) }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply range</button>
                    <a href="{{ route('dashboard.members') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Total Members</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalMembers ?? 0) }}</h6>
                            <span class="text-muted small pt-1 d-block">All time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">New Members <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($membersCount ?? 0) }}</h6>
                            <span class="text-muted small pt-1 d-block">Created in selected range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">New members per day (selected range)</h5>
                    <div id="newMembersChart"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const labels = @json($newMembersPerDay->pluck('date'));
        const data = @json($newMembersPerDay->pluck('total'));

        const el = document.querySelector('#newMembersChart');
        if (!el) return;
        const chart = new ApexCharts(el, {
            chart: { type: 'bar', height: 320, toolbar: { show: false } },
            series: [{ name: 'New members', data: data }],
            xaxis: { type: 'category', categories: labels },
            plotOptions: { bar: { borderRadius: 4 } },
            colors: ['#4154f1']
        });
        chart.render();
    });
</script>
@endpush
