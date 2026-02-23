<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertGuestToMemberRequest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Coach;
use App\Models\Guest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function index(): View
    {
        $guests = Guest::with('inviter')->orderByDesc('created_at')->get();

        return view('guests.index', compact('guests'));
    }

    public function create(): View
    {
        $coaches = Coach::with('user')->get()->sortBy('full_name');
        $members = Member::orderBy('first_name')->get();

        return view('guests.create', compact('coaches', 'members'));
    }

    public function store(StoreGuestRequest $request): RedirectResponse
    {
        Guest::create($request->validated());

        return redirect()->route('guests.index')->with('success', 'Guest created successfully.');
    }

    public function show(Guest $guest): View
    {
        $guest->load('inviter', 'member');

        return view('guests.show', compact('guest'));
    }

    /**
     * @return View|RedirectResponse
     */
    public function edit(Guest $guest)
    {
        if ($guest->isConverted()) {
            return redirect()->route('guests.show', $guest)
                ->with('info', 'Converted guests cannot be edited.');
        }
        $guest->load('inviter');
        $coaches = Coach::with('user')->get()->sortBy('full_name');
        $members = Member::orderBy('first_name')->get();

        return view('guests.edit', compact('guest', 'coaches', 'members'));
    }

    public function update(UpdateGuestRequest $request, Guest $guest): RedirectResponse
    {
        if ($guest->isConverted()) {
            return redirect()->route('guests.show', $guest);
        }
        $guest->update($request->validated());

        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }

    public function destroy(Guest $guest): RedirectResponse
    {
        if ($guest->isConverted()) {
            return redirect()->route('guests.index')->with('error', 'Cannot delete a converted guest.');
        }
        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Guest deleted successfully.');
    }

    public function convertToMemberForm(Guest $guest): View|RedirectResponse
    {
        if ($guest->isConverted()) {
            return redirect()->route('guests.show', $guest)->with('info', 'Guest already converted.');
        }
        $guest->load('inviter');

        return view('guests.convert-to-member', compact('guest'));
    }

    public function convertToMember(ConvertGuestToMemberRequest $request, Guest $guest): RedirectResponse
    {
        if ($guest->isConverted()) {
            return redirect()->route('guests.show', $guest);
        }

        $data = $request->validated();
        $data['guest_id'] = $guest->id;
        $data['invited_by_type'] = $guest->inviter_type;
        $data['invited_by_id'] = $guest->inviter_id;
        $data['converted_by_user_id'] = auth()->id();
        $data['status'] = $data['status'] ?? 'active';

        $member = Member::create($data);

        $guest->update([
            'status' => 'converted',
            'member_id' => $member->id,
        ]);

        return redirect()->route('members.show', $member)->with('success', 'Guest converted to member successfully.');
    }
}
