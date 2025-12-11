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
        $coaches = Coach::withCount('members')->latest()->paginate(10);

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
        Coach::create($request->validated());

        return redirect()->route('coaches.index')
            ->with('success', 'Coach created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach): View
    {
        $coach->load('members');

        return view('coaches.show', compact('coach'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coach $coach): View
    {
        return view('coaches.edit', compact('coach'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCoachRequest $request, Coach $coach): RedirectResponse
    {
        $coach->update($request->validated());

        return redirect()->route('coaches.index')
            ->with('success', 'Coach updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coach $coach): RedirectResponse
    {
        $coach->delete();

        return redirect()->route('coaches.index')
            ->with('success', 'Coach deleted successfully.');
    }
}
