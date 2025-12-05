<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $plans = Plan::withCount('subscriptions')->latest()->paginate(10);

        return view('plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request): RedirectResponse
    {
        Plan::create($request->validated());

        return redirect()->route('plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plan $plan): View
    {
        $plan->load('subscriptions');

        return view('plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan): View
    {
        return view('plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()->route('plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
