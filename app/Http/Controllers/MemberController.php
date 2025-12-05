<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Models\Plan;
use App\Models\RfidCard;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $members = Member::with(['activeSubscription', 'activeRfidCard'])->orderBy('first_name')->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)->orderBy('name')->get();
        $availableKeyfobs = RfidCard::available()->where('type', 'keyfob')->orderBy('card_number')->get();

        return view('members.create', compact('plans', 'availableKeyfobs'));
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
        ]);

        $member = Member::create($memberData);

        $subscriptionEndDate = null;

        // Create subscription if plan_id is provided
        if ($request->filled('subscription_plan_id')) {
            $plan = Plan::find($request->subscription_plan_id);
            $startDate = now()->toDateString();
            $endDate = now()->addMonths($plan->duration_months)->toDateString();

            $subscriptionEndDate = $endDate;

            Subscription::create([
                'member_id' => $member->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price' => $plan->price,
                'status' => $request->subscription_status ?? 'active',
                'payment_status' => $request->subscription_payment_status ?? 'pending',
                'notes' => $request->subscription_notes,
            ]);
        }

        // Assign keyfob if provided
        if ($request->filled('keyfob_id')) {
            $keyfob = RfidCard::find($request->keyfob_id);
            if ($keyfob && $keyfob->member_id === null) {
                $keyfob->update([
                    'member_id' => $member->id,
                    'issued_at' => now()->toDateString(),
                    'expires_at' => $subscriptionEndDate,
                ]);
            }
        }

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member): View
    {
        $member->load('subscriptions');

        return view('members.show', compact('member'));
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

        $member->load('activeSubscription');
        $expiresAt = $member->activeSubscription?->end_date;

        $keyfob->update([
            'member_id' => $member->id,
            'issued_at' => now()->toDateString(),
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Keyfob assigned successfully.');
    }
}
