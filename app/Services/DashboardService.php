<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\Coach;
use App\Models\Guest;
use App\Models\Member;
use App\Models\MemberPtPackage;
use App\Models\MemberSubscription;
use App\Models\PtSession;
use App\Models\PtSessionPlan;
use App\Models\RfidCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Calculate total revenue (subscriptions + PT plans + keyfobs) for a date range.
     */
    public function revenueForRange(Carbon $start, Carbon $end): float
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

    /**
     * Get analytics dashboard data for the given date range.
     *
     * @return array<string, mixed>
     */
    public function getAnalyticsData(Carbon $startDate, Carbon $endDate): array
    {
        $startDateString = $startDate->toDateTimeString();
        $endDateString = $endDate->toDateTimeString();

        $membersCount = Member::whereBetween('created_at', [$startDateString, $endDateString])->count();

        $subscriptionRevenue = MemberSubscription::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDateString, $endDateString])
            ->sum('price');
        $ptSessionRevenue = PtSessionPlan::whereNotNull('price')
            ->whereBetween('created_at', [$startDateString, $endDateString])
            ->sum('price');
        $rfidCardRevenue = RfidCard::whereNotNull('price')
            ->whereNotNull('issued_at')
            ->whereBetween('issued_at', [$startDate->toDateString(), $endDate->toDateString()])
            ->sum('price');
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

        return [
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
        ];
    }

    /**
     * Get sales dashboard data for the given date range.
     *
     * @return array<string, mixed>
     */
    public function getSalesData(Carbon $startDate, Carbon $endDate): array
    {
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

        return [
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
        ];
    }

    /**
     * Get members dashboard data for the given date range.
     *
     * @return array<string, mixed>
     */
    public function getMembersData(Carbon $startDate, Carbon $endDate): array
    {
        $startDateString = $startDate->toDateTimeString();
        $endDateString = $endDate->toDateTimeString();

        $membersCount = Member::whereBetween('created_at', [$startDateString, $endDateString])->count();
        $totalMembers = Member::count();
        $newMembersPerDay = Member::whereBetween('created_at', [$startDateString, $endDateString])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return [
            'filters' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'membersCount' => $membersCount,
            'totalMembers' => $totalMembers,
            'newMembersPerDay' => $newMembersPerDay,
        ];
    }

    /**
     * Get coaches dashboard data for the given date range and optional coach filter.
     *
     * @return array<string, mixed>
     */
    public function getCoachesData(Carbon $startDate, Carbon $endDate, ?int $selectedCoachId = null): array
    {
        $totalCoaches = Coach::count();
        $activeCoaches = Coach::where('status', 'active')->count();
        $allCoaches = Coach::orderBy('user_id')->get();

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

        $remainingSessionsData = [];
        foreach ($memberPtPackages as $mpp) {
            if (! $mpp->coach_id) {
                continue;
            }
            $coachId = $mpp->coach_id;
            $memberId = $mpp->member_id;
            $key = "{$coachId}_{$memberId}";

            if (! isset($remainingSessionsData[$key])) {
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

        $ptSessionsQuery = PtSession::with(['memberPtPackage.member', 'memberPtPackage.coach'])
            ->whereBetween('conducted_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()]);

        if ($selectedCoachId) {
            $ptSessionsQuery->whereHas('memberPtPackage', fn ($q) => $q->where('coach_id', $selectedCoachId));
        }

        $ptSessions = $ptSessionsQuery->get();

        $sessionsByCoachMember = [];
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (! $mpp || ! $mpp->coach_id) {
                continue;
            }
            $coachId = $mpp->coach_id;
            $memberId = $mpp->member_id;
            $key = "{$coachId}_{$memberId}";

            if (! isset($sessionsByCoachMember[$key])) {
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

        $commissionByCoach = [];
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (! $mpp || ! $mpp->coach_id) {
                continue;
            }
            $coachId = $mpp->coach_id;
            $commissionPerSession = $mpp->commission_per_session ?? 0;
            $sessionsUsed = $session->sessions_used;

            if (! isset($commissionByCoach[$coachId])) {
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

        $remainingSessionsData = array_values($remainingSessionsData);
        $sessionsByCoachMember = array_values($sessionsByCoachMember);
        $commissionByCoach = array_values($commissionByCoach);

        usort($remainingSessionsData, fn ($a, $b) => strcmp($a['coach_name'], $b['coach_name']) ?: strcmp($a['member_name'], $b['member_name']));
        usort($sessionsByCoachMember, fn ($a, $b) => strcmp($a['coach_name'], $b['coach_name']) ?: strcmp($a['member_name'], $b['member_name']));
        usort($commissionByCoach, fn ($a, $b) => strcmp($a['coach_name'], $b['coach_name']));

        $coachLeadStats = [];
        foreach ($allCoaches as $c) {
            $guestsInvited = Guest::where('inviter_type', Coach::class)->where('inviter_id', $c->id)->count();
            $guestsConverted = Guest::where('inviter_type', Coach::class)->where('inviter_id', $c->id)->where('status', 'converted')->count();
            $invitedMembers = Member::where('invited_by_type', Coach::class)->where('invited_by_id', $c->id)->count();
            $conversionPercent = $guestsInvited > 0 ? round(($guestsConverted / $guestsInvited) * 100, 1) : 0;

            $coachLeadStats[] = [
                'coach_id' => $c->id,
                'coach_name' => $c->full_name ?? 'N/A',
                'invited_members_count' => $invitedMembers,
                'guests_invited_count' => $guestsInvited,
                'guests_converted_count' => $guestsConverted,
                'conversion_rate_percent' => $conversionPercent,
            ];
        }
        usort($coachLeadStats, fn ($a, $b) => strcmp($a['coach_name'], $b['coach_name']));

        return [
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
            'coachLeadStats' => $coachLeadStats,
        ];
    }
}
