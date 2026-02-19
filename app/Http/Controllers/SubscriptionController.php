<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreezeMemberSubscriptionRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subscriptions = Subscription::orderBy('name')
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        Subscription::create($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load('memberSubscriptions.member');

        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription): View
    {
        return view('subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        $subscription->update($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Show the form for adding a subscription to a member.
     */
    public function createForMember(Member $member): View
    {
        $subscriptions = Subscription::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('subscriptions.create-for-member', compact('member', 'subscriptions'));
    }

    /**
     * Store a subscription for a member.
     */
    public function storeForMember(StoreSubscriptionRequest $request, Member $member): RedirectResponse
    {
        $data = $request->validated();
        $subscription = Subscription::find($data['subscription_id']);

        if (! $subscription) {
            return redirect()->back()
                ->with('error', 'Selected subscription not found.');
        }

        // Calculate end date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = $startDate->copy()->addMonths($subscription->duration_months);

        // Create member subscription
        MemberSubscription::create([
            'member_id' => $member->id,
            'subscription_id' => $subscription->id,
            'subscription_type' => $data['subscription_type'],
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'price' => $data['price'] ?? $subscription->price,
            'payment_type' => $data['payment_type'],
            'payment_status' => $data['payment_status'] ?? 'pending',
            'notes' => $data['notes'],
        ]);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Subscription added to member successfully.');
    }

    /**
     * Show the form to freeze a member subscription.
     */
    public function freeze(MemberSubscription $memberSubscription): View
    {
        $memberSubscription->load(['member', 'subscription']);

        return view('subscriptions.freeze', compact('memberSubscription'));
    }

    /**
     * Store freeze for a member subscription.
     */
    public function storeFreeze(FreezeMemberSubscriptionRequest $request, MemberSubscription $memberSubscription): RedirectResponse
    {
        $member = $memberSubscription->member;

        $memberSubscription->update([
            'frozen_at' => now()->toDateString(),
            'frozen_until' => $request->validated('frozen_until'),
        ]);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Subscription frozen successfully.');
    }

    /**
     * Unfreeze a member subscription.
     */
    public function unfreeze(MemberSubscription $memberSubscription): RedirectResponse
    {
        $member = $memberSubscription->member;

        $memberSubscription->update([
            'frozen_at' => null,
            'frozen_until' => null,
        ]);

        return redirect()
            ->route('members.show', $member)
            ->with('success', 'Subscription unfrozen successfully.');
    }
}
