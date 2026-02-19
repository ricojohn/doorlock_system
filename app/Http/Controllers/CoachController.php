<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Models\Coach;
use App\Models\MemberPtPackage;
use App\Models\PtSession;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $coaches = Coach::with(['workHistories', 'certificates'])
            ->orderBy('first_name')
            ->get();

        return view('coaches.index', compact('coaches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('coaches.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoachRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Extract work histories and certificates
        $workHistories = $data['work_histories'] ?? [];
        $certificates = $data['certificates'] ?? [];

        // Remove from main data
        unset($data['work_histories'], $data['certificates']);

        // Create coach
        $coach = Coach::create($data);

        // Create work histories
        foreach ($workHistories as $workHistory) {
            if (! empty($workHistory['company_name'])) {
                $coach->workHistories()->create($workHistory);
            }
        }

        // Create certificates
        foreach ($certificates as $certificate) {
            if (! empty($certificate['certificate_name'])) {
                $coach->certificates()->create($certificate);
            }
        }

        return redirect()
            ->route('coaches.index')
            ->with('success', 'Coach created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach): View
    {
        $coach->load(['workHistories', 'certificates']);

        // Get current month for default filter
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Get member PT packages with remaining sessions for this coach
        $memberPtPackages = MemberPtPackage::with(['member', 'ptPackage', 'ptSessions'])
            ->where('coach_id', $coach->id)
            ->where('status', 'active')
            ->where(function ($q) use ($endDate) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $endDate->toDateString());
            })
            ->get();

        // Calculate remaining sessions per member
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

        // Get PT sessions conducted in current month
        $ptSessions = PtSession::with(['memberPtPackage.member'])
            ->whereHas('memberPtPackage', function ($q) use ($coach) {
                $q->where('coach_id', $coach->id);
            })
            ->whereBetween('conducted_at', [$startDate->toDateTimeString(), $endDate->toDateTimeString()])
            ->get();

        // Group sessions by member
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

        // Calculate total commission for current month
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

        // Sort data
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coach $coach): View
    {
        $coach->load(['workHistories', 'certificates']);

        return view('coaches.edit', compact('coach'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoachRequest $request, Coach $coach): RedirectResponse
    {
        $data = $request->validated();

        // Extract work histories and certificates
        $workHistories = $data['work_histories'] ?? [];
        $certificates = $data['certificates'] ?? [];

        // Remove from main data
        unset($data['work_histories'], $data['certificates']);

        // Update coach
        $coach->update($data);

        // Delete existing work histories and certificates
        $coach->workHistories()->delete();
        $coach->certificates()->delete();

        // Create new work histories
        foreach ($workHistories as $workHistory) {
            if (! empty($workHistory['company_name'])) {
                $coach->workHistories()->create($workHistory);
            }
        }

        // Create new certificates
        foreach ($certificates as $certificate) {
            if (! empty($certificate['certificate_name'])) {
                $coach->certificates()->create($certificate);
            }
        }

        return redirect()
            ->route('coaches.index')
            ->with('success', 'Coach updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coach $coach): RedirectResponse
    {
        $coach->delete();

        return redirect()
            ->route('coaches.index')
            ->with('success', 'Coach deleted successfully.');
    }
}
