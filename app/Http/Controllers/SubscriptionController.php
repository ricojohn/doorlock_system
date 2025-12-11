<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $subscriptions = Subscription::with(['member', 'plan'])->latest()->paginate(10);

        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $members = Member::orderBy('first_name')->orderBy('last_name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();
        $selectedMemberId = request()->query('member_id');
        $selectedPlanId = request()->query('plan_id');

        return view('subscriptions.create', compact('members', 'plans', 'selectedMemberId', 'selectedPlanId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // If plan_id is provided, auto-fill plan_name and price from plan
        if (! empty($data['plan_id'])) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $data['plan_name'] = $plan->name;
                if (empty($data['price']) || $data['price'] == 0) {
                    $data['price'] = $plan->price;
                }
            }
        }

        // Determine subscription type: 'renew' if member has existing subscriptions, 'new' otherwise
        $member = Member::find($data['member_id']);
        $hasExistingSubscriptions = $member && $member->subscriptions()->exists();
        $data['subscription_type'] = $hasExistingSubscriptions ? 'renew' : 'new';

        Subscription::create($data);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load(['member', 'plan']);

        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription): View
    {
        $members = Member::orderBy('first_name')->orderBy('last_name')->get();
        $plans = Plan::where('is_active', true)->orderBy('name')->get();

        return view('subscriptions.edit', compact('subscription', 'members', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        $data = $request->validated();

        // If plan_id is provided, auto-fill plan_name and price from plan
        if (! empty($data['plan_id'])) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $data['plan_name'] = $plan->name;
                if (empty($data['price']) || $data['price'] == 0) {
                    $data['price'] = $plan->price;
                }
            }
        }

        $subscription->update($data);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Renew a subscription for a member - redirects to create page to choose plan.
     */
    public function renew(Member $member): RedirectResponse
    {
        $latestSubscription = $member->subscriptions()->latest()->first();

        $params = ['member_id' => $member->id];

        // Pre-select the previous plan if it exists
        if ($latestSubscription && $latestSubscription->plan_id) {
            $params['plan_id'] = $latestSubscription->plan_id;
        }

        return redirect()->route('subscriptions.create', $params)
            ->with('info', 'Please select a plan to renew the subscription.');
    }
}
