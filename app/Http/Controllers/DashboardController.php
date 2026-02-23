<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardFilterRequest;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with reporting data.
     */
    public function index(DashboardFilterRequest $request, DashboardService $dashboardService): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();

        $data = $dashboardService->getAnalyticsData($startDate, $endDate);

        return view('dashboard.analytics', $data);
    }

    /**
     * Sales dashboard: revenue trends (WoW/MoM) and revenue charts.
     */
    public function sales(DashboardFilterRequest $request, DashboardService $dashboardService): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();

        $data = $dashboardService->getSalesData($startDate, $endDate);

        return view('dashboard.sales', $data);
    }

    /**
     * Member dashboard: member metrics and charts.
     */
    public function members(DashboardFilterRequest $request, DashboardService $dashboardService): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();

        $data = $dashboardService->getMembersData($startDate, $endDate);

        return view('dashboard.members', $data);
    }

    /**
     * Coach dashboard: coach metrics and charts.
     */
    public function coaches(DashboardFilterRequest $request, DashboardService $dashboardService): View
    {
        $validated = $request->validated();
        $startDate = Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->startOfDay();
        $endDate = Carbon::parse($validated['end_date'] ?? now())->endOfDay();
        $selectedCoachId = isset($validated['coach_id']) ? (int) $validated['coach_id'] : null;

        $data = $dashboardService->getCoachesData($startDate, $endDate, $selectedCoachId);

        return view('dashboard.coaches', $data);
    }
}
