@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Sales Dashboard</h1>
    <p class="mb-0 text-muted">Revenue trends, week-on-week and month-on-month, and revenue by source.</p>
</div>

<section class="section dashboard">
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <span class="text-muted ms-auto small">Range applies to every report below.</span>
            <form class="row g-3 align-items-end" method="GET" action="{{ route('dashboard.sales') }}">
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
                    <a href="{{ route('dashboard.sales') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6>₱{{ number_format($revenue ?? 0, 2) }}</h6>
                            <span class="text-muted small pt-1 d-block">In selected range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">This week vs last week</h6>
                    <p class="mb-1 fw-semibold">₱{{ number_format($revenueThisWeek ?? 0, 2) }}</p>
                    <p class="mb-0 small">Last week: ₱{{ number_format($revenueLastWeek ?? 0, 2) }}</p>
                    @php $wow = $revenueWoWChange ?? 0; $wowPct = $revenueWoWPercent ?? 0; @endphp
                    @if ($wow != 0)
                        <span class="badge {{ $wow >= 0 ? 'bg-success' : 'bg-danger' }} mt-1">
                            {{ $wow >= 0 ? '+' : '' }}₱{{ number_format($wow, 2) }} ({{ $wow >= 0 ? '+' : '' }}{{ $wowPct }}%)
                        </span>
                    @else
                        <span class="badge bg-secondary mt-1">No change</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">This month vs last month</h6>
                    <p class="mb-1 fw-semibold">₱{{ number_format($revenueThisMonth ?? 0, 2) }}</p>
                    <p class="mb-0 small">Last month: ₱{{ number_format($revenueLastMonth ?? 0, 2) }}</p>
                    @php $mom = $revenueMoMChange ?? 0; $momPct = $revenueMoMPercent ?? 0; @endphp
                    @if ($mom != 0)
                        <span class="badge {{ $mom >= 0 ? 'bg-success' : 'bg-danger' }} mt-1">
                            {{ $mom >= 0 ? '+' : '' }}₱{{ number_format($mom, 2) }} ({{ $mom >= 0 ? '+' : '' }}{{ $momPct }}%)
                        </span>
                    @else
                        <span class="badge bg-secondary mt-1">No change</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Subscription revenue per day (selected range)</h5>
                    <div id="revenuePerDayChart"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var revenueLabels = @json($revenuePerDay->pluck('date'));
    var revenueData = @json($revenuePerDay->pluck('total'));
    var el = document.querySelector('#revenuePerDayChart');
    if (!el) return;
    var chart = new ApexCharts(el, {
        chart: { type: 'area', height: 320, toolbar: { show: false } },
        series: [{ name: 'Revenue', data: revenueData }],
        xaxis: { type: 'category', categories: revenueLabels },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#28a745']
    });
    chart.render();
});
</script>
@endpush
