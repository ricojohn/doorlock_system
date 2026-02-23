<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\Member;
use App\Models\PtSession;
use Illuminate\Support\Facades\DB;

class MemberProfileService
{
    /**
     * Get all data required for the member show view.
     *
     * @return array{member: Member, peakHour: string, peakHourCount: int, activeHours: array, weeklyAttendance: array, subscriptionHistory: \Illuminate\Database\Eloquent\Collection, activeSubscription: \App\Models\MemberSubscription|null, ptSessionPlansHistory: \Illuminate\Database\Eloquent\Collection, accessLogs: \Illuminate\Database\Eloquent\Collection, ptSessions: \Illuminate\Database\Eloquent\Collection}
     */
    public function getDataForShow(Member $member): array
    {
        $member->load([
            'activeRfidCard',
            'memberSubscriptions.subscription',
            'ptSessionPlans.coach',
            'memberPtPackages.ptPackage',
            'memberPtPackages.coach',
            'activeMemberPtPackage',
            'invitedBy',
            'convertedByUser',
            'guest',
        ]);

        $accessLogs = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->orderBy('accessed_at', 'desc')
            ->get();

        $peakHourData = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->selectRaw('HOUR(accessed_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->first();

        $peakHour = $peakHourData ? sprintf('%02d:00', $peakHourData->hour) : 'N/A';
        $peakHourCount = $peakHourData ? (int) $peakHourData->count : 0;

        $activeHours = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->selectRaw('DISTINCT HOUR(accessed_at) as hour')
            ->orderBy('hour')
            ->pluck('hour')
            ->map(fn ($hour) => sprintf('%02d:00', $hour))
            ->toArray();

        $weeklyAttendance = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();

            $count = AccessLog::where('member_id', $member->id)
                ->where('access_granted', 'granted')
                ->whereBetween('accessed_at', [$weekStart, $weekEnd])
                ->selectRaw('DATE(accessed_at) as date')
                ->groupBy(DB::raw('DATE(accessed_at)'))
                ->count();

            $weeklyAttendance[] = [
                'week' => $weekStart->format('M d').' - '.$weekEnd->format('M d'),
                'count' => $count,
            ];
        }

        $subscriptionHistory = $member->memberSubscriptions()
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSubscription = $member->activeMemberSubscription;

        $ptSessionPlansHistory = $member->ptSessionPlans()
            ->with(['coach', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        $ptSessions = PtSession::with(['memberPtPackage.ptPackage', 'memberPtPackage.coach'])
            ->whereHas('memberPtPackage', fn ($q) => $q->where('member_id', $member->id))
            ->orderBy('conducted_at', 'desc')
            ->get();

        return [
            'member' => $member,
            'peakHour' => $peakHour,
            'peakHourCount' => $peakHourCount,
            'activeHours' => $activeHours,
            'weeklyAttendance' => $weeklyAttendance,
            'subscriptionHistory' => $subscriptionHistory,
            'activeSubscription' => $activeSubscription,
            'ptSessionPlansHistory' => $ptSessionPlansHistory,
            'accessLogs' => $accessLogs,
            'ptSessions' => $ptSessions,
        ];
    }
}
