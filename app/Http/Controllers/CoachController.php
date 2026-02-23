<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\MemberPtPackage;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\View\View;

class CoachController extends Controller
{
    /**
     * Display the coach dashboard (profile, PT sessions, commission).
     */
    public function show(Coach $coach): View
    {
        $coach->load(['user', 'workHistories', 'certificates']);

        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $memberPtPackages = MemberPtPackage::with(['member', 'ptPackage', 'ptSessions'])
            ->where('coach_id', $coach->id)
            ->where('status', 'active')
            ->where(function ($q) use ($endDate) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $endDate->toDateString());
            })
            ->get();

        $remainingSessionsData = [];
        foreach ($memberPtPackages as $mpp) {
            $memberId = $mpp->member_id;
            $key = "member_{$memberId}";

            if (!isset($remainingSessionsData[$key])) {
                $remainingSessionsData[$key] = [
                    'member_id' => $memberId,
                    'member_name' => $mpp->member->full_name ?? 'N/A',
                    'remaining_sessions' => 0,
                    'packages' => [],
                ];
            }

            $sessionsUsed = $mpp->ptSessions()->sum('sessions_used');
            $remaining = max(0, $mpp->sessions_total - $sessionsUsed);
            $remainingSessionsData[$key]['remaining_sessions'] += $remaining;
            $remainingSessionsData[$key]['packages'][] = [
                'package_name' => $mpp->ptPackage->name ?? 'N/A',
                'remaining' => $remaining,
                'total' => $mpp->sessions_total,
                'used' => $sessionsUsed,
            ];
        }

        $ptSessions = PtSession::with(['memberPtPackage.member'])
            ->whereHas('memberPtPackage', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->whereBetween('conducted_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->get();

        $sessionsByMember = [];
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (!$mpp) continue;

            $memberId = $mpp->member_id;
            $key = "member_{$memberId}";

            if (!isset($sessionsByMember[$key])) {
                $sessionsByMember[$key] = [
                    'member_id' => $memberId,
                    'member_name' => $mpp->member->full_name ?? 'N/A',
                    'sessions_conducted' => 0,
                    'sessions_used_total' => 0,
                ];
            }

            $sessionsByMember[$key]['sessions_conducted']++;
            $sessionsByMember[$key]['sessions_used_total'] += $session->sessions_used;
        }

        $totalCommission = 0;
        $totalSessions = 0;
        foreach ($ptSessions as $session) {
            $mpp = $session->memberPtPackage;
            if (!$mpp) continue;

            $commissionPerSession = $mpp->commission_per_session ?? 0;
            $sessionsUsed = $session->sessions_used;
            $totalCommission += ($commissionPerSession * $sessionsUsed);
            $totalSessions += $sessionsUsed;
        }

        usort($remainingSessionsData, fn($a, $b) => strcmp($a['member_name'], $b['member_name']));
        usort($sessionsByMember, fn($a, $b) => strcmp($a['member_name'], $b['member_name']));

        return view('coaches.show', compact(
            'coach',
            'remainingSessionsData',
            'sessionsByMember',
            'totalCommission',
            'totalSessions',
            'startDate',
            'endDate'
        ));
    }
}
