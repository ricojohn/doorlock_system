@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Access Logs</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Access Logs</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">RFID Access History</h5>
                    <div class="table-responsive">
                        <table class="table table-hover datatable" id="access-logs-table">
                            <thead>
                                <tr>
                                    <th scope="col">Time</th>
                                    <th scope="col">Card Number</th>
                                    <th scope="col">Member</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Reason</th>
                                    <th scope="col">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accessLogs as $log)
                                    <tr>
                                        <td>{{ $log->accessed_at->format('M d, Y H:i:s') }}</td>
                                        <td><strong>{{ $log->card_number }}</strong></td>
                                        <td>
                                            @if($log->member)
                                                <a href="{{ route('members.show', $log->member) }}">
                                                    {{ $log->member_name ?? $log->member->full_name }}
                                                </a>
                                            @else
                                                {{ $log->member_name ?? 'Unknown' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->access_granted === 'granted')
                                                <span class="badge bg-success">Granted</span>
                                            @else
                                                <span class="badge bg-danger">Denied</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->reason }}</td>
                                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
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

