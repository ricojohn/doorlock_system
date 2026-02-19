<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardFilterRequest;
use App\Models\AccessLog;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPtPackage;
use App\Models\MemberSubscription;
use App\Models\PtSession;
use App\Models\PtSessionPlan;
use App\Models\RfidCard;
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

        // Calculate revenue from paid subscriptions created in date range
        $subscriptionRevenue = MemberSubscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDateString, $endDateString])
            ->sum('price');

        // Calculate revenue from PT session plans created in date range
        $ptSessionRevenue = PtSessionPlan::whereNotNull('price')
            ->whereBetween('created_at', [$startDateString, $endDateString])
            ->sum('price');

        // Calculate revenue from RFID cards/keyfobs issued in date range
        $rfidCardRevenue = RfidCard::whereNotNull('price')
            ->whereNotNull('issued_at')
            ->whereBetween('issued_at', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('price');

        // Total revenue
        $revenue = $subscriptionRevenue + $ptSessionRevenue + $rfidCardRevenue;

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

        $peakHours = (clone $grantedCheckins)
            ->selectRaw('HOUR(accessed_at) as hour, COUNT(*) as total')
            ->groupBy(DB::raw('HOUR(accessed_at)'))
            ->orderBy('hour')
            ->get();

        $peakHourSummary = null;
        $peakHourEstimatedPresent = null;
        $estimatedSessionMinutes = config('gym.estimated_session_minutes', 60);

        $peakHourRow = $peakHours->sortByDesc('total')->first();
        if ($peakHourRow) {
            $peakHourSummary = (object) [
                'hour' => (int) $peakHourRow->hour,
                'hour_label' => sprintf('%02d:00', $peakHourRow->hour),
                'checkins_count' => (int) $peakHourRow->total,
            ];

            $grantedTimestamps = (clone $grantedCheckins)
                ->pluck('accessed_at')
                ->map(fn ($t) => Carbon::parse($t));

            $maxConcurrent = 0;
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $hourStart = $current->copy()->setTime($peakHourRow->hour, 0, 0);
                $hourEnd = $current->copy()->setTime($peakHourRow->hour, 59, 59);
                if ($hourStart->lt($startDate)) {
                    $hourStart = $startDate->copy();
                }
                if ($hourEnd->gt($endDate)) {
                    $hourEnd = $endDate->copy();
                }
                for ($min = 0; $min < 60; $min += 5) {
                    $sampleTime = $current->copy()->setTime($peakHourRow->hour, $min, 0);
                    if ($sampleTime->lt($startDate) || $sampleTime->gt($endDate)) {
                        continue;
                    }
                    $windowStart = $sampleTime->copy()->subMinutes($estimatedSessionMinutes);
                    $count = $grantedTimestamps->filter(function ($t) use ($windowStart, $sampleTime) {
                        $ts = $t instanceof \DateTimeInterface ? Carbon::parse($t) : $t;

                        return $ts->gte($windowStart) && $ts->lte($sampleTime);
                    })->count();
                    if ($count > $maxConcurrent) {
                        $maxConcurrent = $count;
                    }
                }
                $current->addDay();
            }
            $peakHourEstimatedPresent = $maxConcurrent;
        }

        $checkinsPerDay = (clone $grantedCheckins)
            ->selectRaw('DATE(accessed_at) as date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(accessed_at)'))
            ->orderBy('date')
            ->get();

        $checkinsPerWeek = (clone $grantedCheckins)
            ->selectRaw("DATE_FORMAT(accessed_at, '%x-%v') as year_week, MIN(DATE(accessed_at)) as week_start, COUNT(*) as total")
            ->groupByRaw("DATE_FORMAT(accessed_at, '%x-%v')")
            ->orderByRaw("DATE_FORMAT(accessed_at, '%x-%v')")
            ->get();

        return view('dashboard.analytics', [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'membersCount' => $membersCount,
            'revenue' => $revenue,
            'recentAccessLogs' => $recentAccessLogs,
            'mostActiveMember' => $mostActiveMember,
            'topCoach' => null,
            'peakHours' => $peakHours,
            'peakHourSummary' => $peakHourSummary,
            'peakHourEstimatedPresent' => $peakHourEstimatedPresent,
            'estimatedSessionMinutes' => $estimatedSessionMinutes,
            'checkinsPerDay' => $checkinsPerDay,
            'checkinsPerWeek' => $checkinsPerWeek,
            'totalCheckins' => $totalCheckins,
        ]);
    }

    /**
     * Sales dashboard: revenue trends (WoW/MoM) and revenue charts.
     */
    public function sales(DashboardFilterRequest $request): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();

        $revenue = $this->revenueForRange($startDate, $endDate);

        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $thisMonthStart = Carbon::now()->startOfMonth();
        $thisMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $revenueThisWeek = $this->revenueForRange($thisWeekStart, $thisWeekEnd);
        $revenueLastWeek = $this->revenueForRange($lastWeekStart, $lastWeekEnd);
        $revenueThisMonth = $this->revenueForRange($thisMonthStart, $thisMonthEnd);
        $revenueLastMonth = $this->revenueForRange($lastMonthStart, $lastMonthEnd);

        $revenueWoWChange = $revenueThisWeek - $revenueLastWeek;
        $revenueWoWPercent = $revenueLastWeek > 0
            ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
            : ($revenueThisWeek > 0 ? 100 : 0);
        $revenueMoMChange = $revenueThisMonth - $revenueLastMonth;
        $revenueMoMPercent = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);

        $revenuePerDay = MemberSubscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $ptRevenuePerDay = PtSessionPlan::whereNotNull('price')
            ->whereBetween('created_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('dashboard.sales', [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'revenue' => $revenue,
            'revenueThisWeek' => $revenueThisWeek,
            'revenueLastWeek' => $revenueLastWeek,
            'revenueWoWChange' => $revenueWoWChange,
            'revenueWoWPercent' => $revenueWoWPercent,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueMoMChange' => $revenueMoMChange,
            'revenueMoMPercent' => $revenueMoMPercent,
            'revenuePerDay' => $revenuePerDay,
            'ptRevenuePerDay' => $ptRevenuePerDay,
        ]);
    }

    /**
     * Member dashboard: member metrics and charts.
     */
    public function members(DashboardFilterRequest $request): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();
        $startDateString = $startDate->toDateTimeString();
        $endDateString = $endDate->toDateTimeString();

        $membersCount = Member::whereBetween('created_at', [$startDateString, $endDateString])->count();
        $totalMembers = Member::count();
        $newMembersPerDay = Member::whereBetween('created_at', [$startDateString, $endDateString])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('dashboard.members', [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'membersCount' => $membersCount,
            'totalMembers' => $totalMembers,
            'newMembersPerDay' => $newMembersPerDay,
        ]);
    }

    /**
     * Coach dashboard: coach metrics and charts.
     */
    public function coaches(DashboardFilterRequest $request): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();
        $selectedCoachId = $validated['coach_id'] ?? null;

        $totalCoaches = Coach::count();
        $activeCoaches = Coach::where('status', 'active')->count();
        $allCoaches = Coach::orderBy('first_name')->get();

        // Get member PT packages with remaining sessions, grouped by coach
        $memberPtPackagesQuery = MemberPtPackage::with(['member', 'coach', 'ptPackage', 'ptSessions'])
            ->where('status', 'active')
            ->where(function ($q) use ($endDate) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $endDate->toDateString());
            });

        if ($selectedCoachId) {
            $memberPtPackagesQuery->where('coach_id', $selectedCoachId);
        }

        $memberPtPackages = $memberPtPackagesQuery->get();

        // Calculate remaining sessions per member per coach
        $remainingSessionsData = [];
        foreach ($memberPtPackages as $mpp) {
            if (!$mpp->coach_id) continue;
            
            $coachId = $mpp->coach_id;
            $memberId = $mpp->member_id;
            $key = "{$coachId}_{$memberId}";

            if (!isset($remainingSessionsData[$key])) {
                $remainingSessionsData[$key] = [
                    'coach_id' => $coachId,
                    'coach_name' => $mpp->coach->full_name ?? 'N/A',
                    'member_id' => $memberId,
                    'member_name' => $mpp->member->full_name ?? 'N/A',
                    'remaining_sessions' => 0,
                    'package_name' => $mpp->ptPackage->name ?? 'N/A',
                ];
            }

            $sessionsUsed = $mpp->ptSessions()->sum('sessions_used');
            $remaining = max(0, $mpp->sessions_total - $sessionsUsed);
            $remainingSessionsData[$key]['remaining_sessions'] += $remaining;
        }

        // Get PT sessions conducted in the selected month, grouped by coach and member
        $ptSessionsQuery = PtSession::with(['memberPtPackage.member', 'memberPtPackage.coach'])
            ->whereBetween('conducted_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()]);

        if ($selectedCoachId) {
            $ptSessionsQuery->whereHas('memberPtPackage', function ($q) use ($selectedCoachId) {
                $q->where('coach_id', $selectedCoachId);
            });
        }

        $ptSessions = $ptSessionsQuery->get();

        // Group sessions by coach and member
        $sessionsByCoachMember = [];
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (!$mpp || !$mpp->coach_id) continue;

            $coachId = $mpp->coach_id;
            $memberId = $mpp->member_id;
            $key = "{$coachId}_{$memberId}";

            if (!isset($sessionsByCoachMember[$key])) {
                $sessionsByCoachMember[$key] = [
                    'coach_id' => $coachId,
                    'coach_name' => $mpp->coach->full_name ?? 'N/A',
                    'member_id' => $memberId,
                    'member_name' => $mpp->member->full_name ?? 'N/A',
                    'sessions_conducted' => 0,
                    'sessions_used_total' => 0,
                ];
            }

            $sessionsByCoachMember[$key]['sessions_conducted']++;
            $sessionsByCoachMember[$key]['sessions_used_total'] += $session->sessions_used;
        }

        // Calculate commission per coach
        $commissionByCoach = [];
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (!$mpp || !$mpp->coach_id) continue;

            $coachId = $mpp->coach_id;
            $commissionPerSession = $mpp->commission_per_session ?? 0;
            $sessionsUsed = $session->sessions_used;

            if (!isset($commissionByCoach[$coachId])) {
                $commissionByCoach[$coachId] = [
                    'coach_id' => $coachId,
                    'coach_name' => $mpp->coach->full_name ?? 'N/A',
                    'total_commission' => 0,
                    'total_sessions' => 0,
                ];
            }

            $commissionByCoach[$coachId]['total_commission'] += ($commissionPerSession * $sessionsUsed);
            $commissionByCoach[$coachId]['total_sessions'] += $sessionsUsed;
        }

        // Sort data
        usort($remainingSessionsData, fn($a, $b) => strcmp($a['coach_name'], $b['coach_name']) ?: strcmp($a['member_name'], $b['member_name']));
        usort($sessionsByCoachMember, fn($a, $b) => strcmp($a['coach_name'], $b['coach_name']) ?: strcmp($a['member_name'], $b['member_name']));
        usort($commissionByCoach, fn($a, $b) => strcmp($a['coach_name'], $b['coach_name']));

        return view('dashboard.coaches', [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'coach_id' => $selectedCoachId,
            ],
            'totalCoaches' => $totalCoaches,
            'activeCoaches' => $activeCoaches,
            'allCoaches' => $allCoaches,
            'remainingSessionsData' => $remainingSessionsData,
            'sessionsByCoachMember' => $sessionsByCoachMember,
            'commissionByCoach' => $commissionByCoach,
        ]);
    }

    /**
     * Calculate total revenue (subscriptions + PT plans + keyfobs) for a date range.
     */
    private function revenueForRange(Carbon $start, Carbon $end): float
    {
        $startStr = $start->toDateTimeString();
        $endStr = $end->toDateTimeString();
        $startDateStr = $start->toDateString();
        $endDateStr = $end->toDateString();

        $sub = MemberSubscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startStr, $endStr])
            ->sum('price');
        $pt = PtSessionPlan::whereNotNull('price')
            ->whereBetween('created_at', [$startStr, $endStr])
            ->sum('price');
        $rfid = RfidCard::whereNotNull('price')
            ->whereNotNull('issued_at')
            ->whereBetween('issued_at', [$startDateStr, $endDateStr])
            ->sum('price');

        return (float) ($sub + $pt + $rfid);
    }
}
