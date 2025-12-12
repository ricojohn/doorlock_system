<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Models\Coach;
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

        return view('coaches.show', compact('coach'));
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
