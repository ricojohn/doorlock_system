@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Gross vs Expenses</h1>
    <p class="mb-0 text-muted">Compare total gross income against expenses for a selected date range.</p>
</div>

<section class="section dashboard">
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <span class="text-muted ms-auto small">Range applies to the summary and chart below.</span>
            <form class="row g-3 align-items-end" method="GET" action="{{ route('finance.gross-dashboard') }}">
                <div class="col-md-3">
                    <label for="from" class="form-label">Start date</label>
                    <input type="date" id="from" name="from" class="form-control" value="{{ old('from', request('from', $from)) }}">
                </div>
                <div class="col-md-3">
                    <label for="to" class="form-label">End date</label>
                    <input type="date" id="to" name="to" class="form-control" value="{{ old('to', request('to', $to)) }}">
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply range</button>
                    <a href="{{ route('finance.gross-dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Total Gross <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="ps-3">
                            <h6>₱{{ number_format($grossTotal, 2) }}</h6>
                            <span class="text-muted small pt-1 d-block">All gross entries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="ps-3">
                            <h6>₱{{ number_format($expenseTotal, 2) }}</h6>
                            <span class="text-muted small pt-1 d-block">All expense entries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Net <span>| Gross - Expenses</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-balance-scale"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="{{ $netTotal >= 0 ? 'text-success' : 'text-danger' }}">₱{{ number_format($netTotal, 2) }}</h6>
                            <span class="text-muted small pt-1 d-block">Positive means profit; negative means loss.</span>
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
                    <h5 class="card-title">Daily gross vs expenses</h5>
                    <div id="grossVsExpenseChart"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var labels = @json($dates);
    var grossData = @json($grossSeries);
    var expenseData = @json($expenseSeries);
    var netData = @json($netSeries);

    var el = document.querySelector('#grossVsExpenseChart');
    if (!el || typeof ApexCharts === 'undefined') {
        return;
    }

    var chart = new ApexCharts(el, {
        chart: { type: 'line', height: 340, toolbar: { show: false } },
        series: [
            { name: 'Gross', data: grossData },
            { name: 'Expenses', data: expenseData },
            { name: 'Net', data: netData }
        ],
        xaxis: { type: 'category', categories: labels },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        colors: ['#198754', '#dc3545', '#0d6efd'],
        tooltip: { y: { formatter: function (val) { return '₱' + Number(val).toFixed(2); } } }
    });
    chart.render();
});
</script>
@endpush

