<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\AccessLog;
use App\Models\Member;
use App\Models\RfidCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $members = Member::with(['activeRfidCard', 'memberSubscriptions.subscription'])->orderBy('first_name')->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $memberData = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'gender',
            'status',
            'house_number',
            'street',
            'barangay',
            'city',
            'state',
            'postal_code',
            'country',
        ]);

        $member = Member::create(array_merge(
            ['status' => $memberData['status'] ?? 'active'],
            $memberData
        ));

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member): View
    {
        $member->load(['activeRfidCard', 'memberSubscriptions.subscription', 'ptSessionPlans.coach']);

        // Get access logs for this member (only granted access)
        $accessLogs = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->orderBy('accessed_at', 'desc')
            ->get();

        // Calculate Peak Hour (hour with most access)
        $peakHourData = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->selectRaw('HOUR(accessed_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->first();

        $peakHour = $peakHourData ? sprintf('%02d:00', $peakHourData->hour) : 'N/A';
        $peakHourCount = $peakHourData ? $peakHourData->count : 0;

        // Calculate Active Hours (all hours the member has been active)
        $activeHours = AccessLog::where('member_id', $member->id)
            ->where('access_granted', 'granted')
            ->selectRaw('DISTINCT HOUR(accessed_at) as hour')
            ->orderBy('hour')
            ->pluck('hour')
            ->map(fn ($hour) => sprintf('%02d:00', $hour))
            ->toArray();

        // Calculate Weekly Attendance Count (last 4 weeks) - count unique days
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

        // Get subscription history
        $subscriptionHistory = $member->memberSubscriptions()
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active subscription
        $activeSubscription = $member->activeMemberSubscription;

        // Get PT session plans history
        $ptSessionPlansHistory = $member->ptSessionPlans()
            ->with(['coach', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        // PT Packages & Subscriptions: load for views
        $member->load([
            'memberPtPackages.ptPackage',
            'memberPtPackages.coach',
            'activeMemberPtPackage',
            'memberSubscriptions.subscription',
        ]);

        // Get PT sessions for this member (from all their packages)
        $ptSessions = \App\Models\PtSession::with(['memberPtPackage.ptPackage', 'memberPtPackage.coach'])
            ->whereHas('memberPtPackage', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
            ->orderBy('conducted_at', 'desc')
            ->get();

        return view('members.show', compact(
            'member',
            'peakHour',
            'peakHourCount',
            'activeHours',
            'weeklyAttendance',
            'subscriptionHistory',
            'activeSubscription',
            'ptSessionPlansHistory',
            'accessLogs',
            'ptSessions'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $member->update($request->validated());

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }

    /**
     * Show the form for assigning a keyfob to a member.
     */
    public function assignKeyfob(Member $member): View
    {
        $availableKeyfobs = RfidCard::available()->where('type', 'keyfob')->orderBy('card_number')->get();

        return view('members.assign-keyfob', compact('member', 'availableKeyfobs'));
    }

    /**
     * Store the assigned keyfob to the member.
     */
    public function storeKeyfob(Member $member): RedirectResponse
    {
        $keyfobId = request()->input('keyfob_id');
        $price = request()->input('price');
        $issuedAt = request()->input('issued_at');
        $paymentMethod = request()->input('payment_method');

        if (! $keyfobId) {
            return redirect()->back()
                ->with('error', 'Please select a keyfob.');
        }

        $keyfob = RfidCard::find($keyfobId);

        if (! $keyfob) {
            return redirect()->back()
                ->with('error', 'Selected keyfob not found.');
        }

        if ($keyfob->member_id !== null) {
            return redirect()->back()
                ->with('error', 'This keyfob is already assigned to another member.');
        }

        $updateData = [
            'member_id' => $member->id,
            'issued_at' => $issuedAt ?? now()->toDateString(),
            'expires_at' => null,
        ];

        if ($price !== null && $price !== '') {
            $updateData['price'] = $price;
        }

        if ($paymentMethod !== null && $paymentMethod !== '') {
            $updateData['payment_method'] = $paymentMethod;
        }

        $keyfob->update($updateData);

        return redirect()->route('members.show', $member)
            ->with('success', 'Keyfob assigned successfully.');
    }
}
