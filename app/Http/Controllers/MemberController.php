<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Member;
use App\Models\Plan;
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
        $members = Member::orderBy('first_name')->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)->orderBy('name')->get();

        return view('members.create', compact('plans'));
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

        // Create subscription if plan_id is provided
        if ($request->filled('subscription_plan_id')) {
            $plan = Plan::find($request->subscription_plan_id);
            $startDate = now()->toDateString();
            $endDate = now()->addMonths($plan->duration_months)->toDateString();

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
}
