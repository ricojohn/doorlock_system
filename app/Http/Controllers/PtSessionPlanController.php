<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePtSessionPlanRequest;
use App\Http\Requests\UpdatePtSessionPlanRequest;
use App\Models\Coach;
use App\Models\Member;
use App\Models\PtSessionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PtSessionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $ptSessionPlans = PtSessionPlan::with(['coach', 'member', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pt-session-plans.index', compact('ptSessionPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $coaches = Coach::where('status', 'active')->orderBy('first_name')->get();
        $members = Member::where('status', 'active')->orderBy('first_name')->get();

        return view('pt-session-plans.create', compact('coaches', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePtSessionPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Extract items
        $items = $data['items'] ?? [];
        unset($data['items']);

        // Create PT session plan
        $ptSessionPlan = PtSessionPlan::create($data);

        // Create items
        foreach ($items as $index => $item) {
            if (! empty($item['exercise_name'])) {
                $item['order'] = $index;
                $ptSessionPlan->items()->create($item);
            }
        }

        return redirect()
            ->route('pt-session-plans.index')
            ->with('success', 'PT Session Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PtSessionPlan $ptSessionPlan): View
    {
        $ptSessionPlan->load(['coach', 'member', 'items']);

        return view('pt-session-plans.show', compact('ptSessionPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PtSessionPlan $ptSessionPlan): View
    {
        $ptSessionPlan->load(['items']);
        $coaches = Coach::where('status', 'active')->orderBy('first_name')->get();
        $members = Member::where('status', 'active')->orderBy('first_name')->get();

        return view('pt-session-plans.edit', compact('ptSessionPlan', 'coaches', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePtSessionPlanRequest $request, PtSessionPlan $ptSessionPlan): RedirectResponse
    {
        $data = $request->validated();

        // Extract items
        $items = $data['items'] ?? [];
        unset($data['items']);

        // Update PT session plan
        $ptSessionPlan->update($data);

        // Delete existing items
        $ptSessionPlan->items()->delete();

        // Create new items
        foreach ($items as $index => $item) {
            if (! empty($item['exercise_name'])) {
                $item['order'] = $index;
                $ptSessionPlan->items()->create($item);
            }
        }

        return redirect()
            ->route('pt-session-plans.index')
            ->with('success', 'PT Session Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PtSessionPlan $ptSessionPlan): RedirectResponse
    {
        $ptSessionPlan->delete();

        return redirect()
            ->route('pt-session-plans.index')
            ->with('success', 'PT Session Plan deleted successfully.');
    }
}
