<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberPtPackageRequest;
use App\Models\Coach;
use App\Models\Member;
use App\Models\MemberPtPackage;
use App\Models\PtPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberPtPackageController extends Controller
{
    public function subscribe(Member $member): View
    {
        $ptPackages = PtPackage::where('status', 'active')->with('coach')->orderBy('name')->get();
        $coaches = Coach::where('status', 'active')->orderBy('first_name')->get();

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

        MemberPtPackage::create([
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

        return redirect()->route('members.show', $member)
            ->with('success', 'Member subscribed to PT package successfully.');
    }
}
