<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignKeyfobRequest;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\Coach;
use App\Models\Member;
use App\Models\RfidCard;
use App\Services\MemberProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $members = Member::with(['activeRfidCard', 'memberSubscriptions.subscription'])->orderBy('first_name')->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $coaches = Coach::with('user')->get()->sortBy('full_name');
        $members = Member::orderBy('first_name')->get();

        return view('members.create', compact('coaches', 'members'));
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
            'status',
            'house_number',
            'street',
            'barangay',
            'city',
            'state',
            'postal_code',
            'country',
            'invited_by_type',
            'invited_by_id',
        ]);

        $member = Member::create(array_merge(
            [
                'status' => $memberData['status'] ?? 'active',
                'converted_by_user_id' => auth()->id(),
            ],
            $memberData
        ));

        return redirect()->route('members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member, MemberProfileService $profileService): View
    {
        $data = $profileService->getDataForShow($member);

        return view('members.show', $data);
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
    public function storeKeyfob(AssignKeyfobRequest $request, Member $member): RedirectResponse
    {
        $data = $request->validated();
        $keyfob = RfidCard::findOrFail($data['keyfob_id']);

        $updateData = [
            'member_id' => $member->id,
            'issued_at' => $data['issued_at'],
            'expires_at' => null,
        ];

        if (isset($data['price']) && $data['price'] !== '' && $data['price'] !== null) {
            $updateData['price'] = $data['price'];
        }

        if (isset($data['payment_method']) && $data['payment_method'] !== '') {
            $updateData['payment_method'] = $data['payment_method'];
        }

        $keyfob->update($updateData);

        return redirect()->route('members.show', $member)
            ->with('success', 'Keyfob assigned successfully.');
    }
}
