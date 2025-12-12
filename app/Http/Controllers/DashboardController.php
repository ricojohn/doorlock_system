<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardFilterRequest;
use App\Models\AccessLog;
use App\Models\Coach;
use App\Models\Member;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with reporting data.
     */
    public function index(DashboardFilterRequest $request): View
    {
        $validated = $request->validated();

        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())
            ->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())
            ->endOfDay();
        $startDateString = $startDate->toDateTimeString();
        $endDateString = $endDate->toDateTimeString();

        $membersCount = Member::whereBetween('created_at', [$startDateString, $endDateString])->count();

        $revenue = Subscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDateString, $endDateString])
            ->sum('price');

        $accessLogsInRange = AccessLog::whereBetween('accessed_at', [$startDateString, $endDateString]);

        $grantedCheckins = (clone $accessLogsInRange)->where('access_granted', 'granted');

        $totalCheckins = (clone $grantedCheckins)->count();

        $recentAccessLogs = (clone $accessLogsInRange)
            ->with('member')
            ->orderByDesc('accessed_at')
            ->limit(10)
            ->get();

        $mostActiveMember = Member::select(
            'members.id',
            'members.first_name',
            'members.last_name',
            DB::raw('COUNT(access_logs.id) as checkins_count')
        )
            ->join('access_logs', 'access_logs.member_id', '=', 'members.id')
            ->whereBetween('access_logs.accessed_at', [$startDate, $endDate])
            ->where('access_logs.access_granted', 'granted')
            ->groupBy('members.id', 'members.first_name', 'members.last_name')
            ->orderByDesc('checkins_count')
            ->first();

        $topCoach = Coach::select(
            'coaches.id',
            'coaches.first_name',
            'coaches.last_name',
            DB::raw('COUNT(members.id) as member_count')
        )
            ->join('members', 'members.coach_id', '=', 'coaches.id')
            ->whereBetween('members.created_at', [$startDate, $endDate])
            ->groupBy('coaches.id', 'coaches.first_name', 'coaches.last_name')
            ->orderByDesc('member_count')
            ->first();

        $peakHours = (clone $grantedCheckins)
            ->selectRaw('HOUR(accessed_at) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $checkinsPerDay = (clone $grantedCheckins)
            ->selectRaw('DATE(accessed_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $checkinsPerWeek = (clone $grantedCheckins)
            ->selectRaw("DATE_FORMAT(accessed_at, '%x-%v') as year_week, MIN(DATE(accessed_at)) as week_start, COUNT(*) as total")
            ->groupByRaw("DATE_FORMAT(accessed_at, '%x-%v')")
            ->orderByRaw("DATE_FORMAT(accessed_at, '%x-%v')")
            ->get();


        return view('dashboard.index', [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'membersCount' => $membersCount,
            'revenue' => $revenue,
            'recentAccessLogs' => $recentAccessLogs,
            'mostActiveMember' => $mostActiveMember,
            'topCoach' => $topCoach,
            'peakHours' => $peakHours,
            'checkinsPerDay' => $checkinsPerDay,
            'checkinsPerWeek' => $checkinsPerWeek,
            'totalCheckins' => $totalCheckins,
        ]);
    }
}
