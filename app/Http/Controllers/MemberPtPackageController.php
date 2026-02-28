<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberPtPackageRequest;
use App\Models\Coach;
use App\Models\Commission;
use App\Models\Member;
use App\Models\MemberPtPackage;
use App\Models\PtPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberPtPackageController extends Controller
{
    public function index(): View
    {
        $subscriptions = MemberPtPackage::with(['member', 'ptPackage', 'coach'])
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        return view('member-pt-packages.index', compact('subscriptions'));
    }

    public function show(MemberPtPackage $memberPtPackage): View
    {
        $memberPtPackage->load(['member', 'ptPackage', 'coach']);

        return view('member-pt-packages.show', compact('memberPtPackage'));
    }

    public function subscribe(Member $member): View
    {
        $ptPackages = PtPackage::where('status', 'active')->with('coach')->orderBy('name')->get();
        $coaches = Coach::where('status', 'active')->get();

        return view('members.subscribe-pt-package', compact('member', 'ptPackages', 'coaches'));
    }

    public function storeSubscribe(StoreMemberPtPackageRequest $request, Member $member): RedirectResponse
    {
        if (! $member->activeMemberSubscription) {
            return redirect()->route('members.show', $member)
                ->with('error', 'Member must have an active gym subscription to subscribe to a PT package.');
        }

        if ($member->activeMemberPtPackage) {
            return redirect()->route('members.show', $member)
                ->with('error', 'Member already has an active PT package. It must expire or be fully used before subscribing to a new one.');
        }

        $package = PtPackage::findOrFail($request->validated('pt_package_id'));

        $ratePerSession = $package->rate_per_session ?? ($package->package_rate && $package->session_count ? $package->package_rate / $package->session_count : null);
        $commissionPerSession = $package->commission_per_session ?? ($ratePerSession && $package->commission_percentage !== null ? $ratePerSession * (float) $package->commission_percentage / 100 : null);

        $receiptImagePath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptImagePath = $request->file('receipt_image')->store('receipts/pt-packages', 'public');
        }

        $memberPtPackage = MemberPtPackage::create([
            'member_id' => $member->id,
            'pt_package_id' => $package->id,
            'coach_id' => $request->validated('coach_id') ?? $package->coach_id,
            'start_date' => $request->validated('start_date'),
            'end_date' => $request->validated('end_date'),
            'status' => 'active',
            'payment_type' => $request->validated('payment_type') ?? $package->payment_type,
            'price_paid' => $package->package_rate,
            'rate_per_session' => $ratePerSession,
            'commission_percentage' => $package->commission_percentage,
            'commission_per_session' => $commissionPerSession,
            'sessions_total' => $package->session_count,
            'receipt_number' => $request->validated('receipt_number'),
            'receipt_image' => $receiptImagePath,
        ]);

        if ($memberPtPackage->coach_id && $memberPtPackage->commission_per_session !== null && $memberPtPackage->sessions_total) {
            $amount = (float) $memberPtPackage->commission_per_session * (int) $memberPtPackage->sessions_total;

            Commission::create([
                'member_id' => $memberPtPackage->member_id,
                'coach_id' => $memberPtPackage->coach_id,
                'pt_package_id' => $memberPtPackage->pt_package_id,
                'member_pt_package_id' => $memberPtPackage->id,
                'pt_session_id' => null,
                'amount' => $amount,
                'status' => 'pending',
                'earned_at' => now(),
            ]);
        }

        return redirect()->route('members.show', $member)
            ->with('success', 'Member subscribed to PT package successfully.');
    }
}
