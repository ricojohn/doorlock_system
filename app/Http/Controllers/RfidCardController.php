<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRfidCardRequest;
use App\Http\Requests\UpdateRfidCardRequest;
use App\Models\RfidCard;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RfidCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $rfidCards = RfidCard::with('member')->latest()->paginate(10);

        return view('rfid-cards.index', compact('rfidCards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('rfid-cards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRfidCardRequest $request): RedirectResponse
    {
        RfidCard::create($request->validated());

        return redirect()->route('rfid-cards.index')
            ->with('success', 'RFID card registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RfidCard $rfidCard): View
    {
        $rfidCard->load('member');

        return view('rfid-cards.show', compact('rfidCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RfidCard $rfidCard): View
    {
        $rfidCard->load('member');

        return view('rfid-cards.edit', compact('rfidCard'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRfidCardRequest $request, RfidCard $rfidCard): RedirectResponse
    {
        $rfidCard->update($request->validated());

        return redirect()->route('rfid-cards.index')
            ->with('success', 'RFID card updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RfidCard $rfidCard): RedirectResponse
    {
        $rfidCard->delete();

        return redirect()->route('rfid-cards.index')
            ->with('success', 'RFID card deleted successfully.');
    }
}
