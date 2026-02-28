<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePtSessionRequest;
use App\Models\Member;
use App\Models\MemberPtPackage;
use App\Models\PtSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PtSessionController extends Controller
{
    /**
     * Show form to log a PT session for a member.
     */
    public function create(Member $member): View
    {
        $member->load(['memberPtPackages' => function ($q) {
            $q->where('status', 'active')->with(['ptPackage', 'coach']);
        }]);
        $activePackages = $member->memberPtPackages->filter(function (MemberPtPackage $mpp) {
            return $mpp->remaining_sessions > 0 && ! $mpp->is_expired;
        });

        return view('members.log-pt-session', compact('member', 'activePackages'));
    }

    /**
     * Store a PT session (consume sessions from member's package).
     */
    public function store(StorePtSessionRequest $request, Member $member): RedirectResponse
    {
        $mpp = MemberPtPackage::findOrFail($request->validated('member_pt_package_id'));
        if ($mpp->member_id !== $member->id) {
            return redirect()->route('members.show', $member)
                ->with('error', 'Invalid PT package for this member.');
        }
        if ($mpp->status !== 'active' || $mpp->remaining_sessions < (int) $request->validated('sessions_used')) {
            return redirect()->route('members.show', $member)
                ->with('error', 'Not enough remaining sessions or package is not active.');
        }

        $ptSession = PtSession::create([
            'member_pt_package_id' => $mpp->id,
            'conducted_at' => $request->validated('conducted_at'),
            'sessions_used' => $request->validated('sessions_used'),
            'notes' => $request->validated('notes'),
        ]);

        $used = $mpp->ptSessions()->sum('sessions_used');
        if ($used >= $mpp->sessions_total) {
            $mpp->update(['status' => 'exhausted']);
        }

        return redirect()->route('members.show', $member)
            ->with('success', 'PT session logged successfully.');
    }
}
