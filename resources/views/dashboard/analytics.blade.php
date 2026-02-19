@extends('layout.app')

@section('content')
<div class="pagetitle">
    <h1>Analytics Dashboard</h1>
    <p class="mb-0 text-muted">Check-ins, peak hours, and total revenue for the selected date range.</p>
</div>

<section class="section dashboard">
    <div class="card mb-4 pt-3">
        <div class="card-body">
            <span class="text-muted ms-auto small">Range applies to every report below.</span>
            <form class="row g-3 align-items-end" method="GET" action="{{ route('dashboard.analytics') }}">
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
                    <a href="{{ route('dashboard.analytics') }}" class="btn btn-outline-secondary">Reset</a>
                    
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xxl-3 col-md-6">
            <div class="card info-card customers-card">
                <div class="card-body">
                    <h5 class="card-title">Members <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($membersCount) }}</h6>
                            <span class="text-muted small pt-1 d-block">Created in selected range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card info-card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue <span>| Range</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="ps-3">
                            <h6>₱{{ number_format($revenue, 2) }}</h6>
                            <span class="text-muted small pt-1 d-block">Subscriptions, PT, keyfobs in range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Total Check-ins <span>| Granted</span></h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ number_format($totalCheckins) }}</h6>
                            <span class="text-muted small pt-1 d-block">Access granted within range</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card info-card peak-hour-card">
                <div class="card-body">
                    <h5 class="card-title">Peak Hour</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="ps-3">
                            <h6>{{ $peakHourSummary?->hour_label ?? 'N/A' }} — {{ $peakHourEstimatedPresent ?? 0 }} people</h6>
                            <span class="text-muted small pt-1 d-block">{{ $peakHourSummary?->checkins_count ?? 0 }} check-ins · {{ $estimatedSessionMinutes ?? 60 }} min session</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Most Active Member</h5>
                    @if ($mostActiveMember)
                        <p class="mb-1 fw-semibold">{{ $mostActiveMember->first_name }} {{ $mostActiveMember->last_name }}</p>
                        <p class="mb-0 text-muted small">{{ $mostActiveMember->checkins_count }} check-ins</p>
                    @else
                        <p class="mb-0 text-muted">No check-ins in the selected range.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Check-ins Per Day</h5>
                    <div id="dailyCheckinsChart"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Peak Hours</h5>
                    <p class="text-muted small mb-2">Check-ins per hour of day (entries, not concurrent)</p>
                    <div id="peakHoursChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <h5 class="card-title">Recent Activity Access Log</h5>
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Status</th>
                                <th class="text-end">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentAccessLogs as $log)
                                <tr>
                                    <td class="fw-semibold">{{ $log->member?->full_name ?? ($log->member_name ?? 'Unknown') }}</td>
                                    <td>
                                        @if ($log->access_granted === 'granted')
                                            <span class="badge bg-success">Granted</span>
                                        @else
                                            <span class="badge bg-danger">Denied</span>
                                        @endif
                                    </td>
                                    <td class="text-end text-muted">{{ $log->accessed_at?->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No access logs for this range.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Check-ins Per Week</h5>
                    <ul class="list-group list-group-flush">
                        @forelse ($checkinsPerWeek as $week)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Week of {{ \Illuminate\Support\Carbon::parse($week->week_start)->format('M d, Y') }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $week->total }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center">No weekly data in range.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Most Client Coach</h5>
                    @if ($topCoach)
                        <p class="mb-1 fw-semibold">{{ $topCoach->first_name }} {{ $topCoach->last_name }}</p>
                        <p class="mb-0 text-muted small">{{ $topCoach->member_count }} members in range</p>
                    @else
                        <p class="mb-0 text-muted">No coaches with members in this range.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dailyLabels = @json($checkinsPerDay->pluck('date'));
        const dailyData = @json($checkinsPerDay->pluck('total'));

        const peakHourLabels = @json($peakHours->pluck('hour'));
        const peakHourData = @json($peakHours->pluck('total'));

        const renderChart = (selector, options) => {
            const element = document.querySelector(selector);
            if (!element) {
                return;
            }

            const chart = new ApexCharts(element, options);
            chart.render();
        };

        renderChart('#dailyCheckinsChart', {
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false }
            },
            series: [{
                name: 'Check-ins',
                data: dailyData
            }],
            xaxis: {
                type: 'category',
                categories: dailyLabels
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            colors: ['#4154f1']
        });


        renderChart('#peakHoursChart', {
            chart: {
                type: 'bar',
                height: 320,
                toolbar: { show: false }
            },
            series: [{
                name: 'Check-ins',
                data: peakHourData
            }],
            xaxis: {
                categories: peakHourLabels.map(hour => `${hour}:00`),
                title: { text: 'Hour of day' }
            },
            plotOptions: {
                bar: { borderRadius: 4 }
            },
            colors: ['#ff771d']
        });
    });
</script>
@endpush
