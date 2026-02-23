<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWifiConfigurationRequest;
use App\Http\Requests\UpdateWifiConfigurationRequest;
use App\Models\WifiConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WifiConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $configurations = WifiConfiguration::orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wifi-configurations.index', compact('configurations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wifi-configurations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWifiConfigurationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['is_active'])) {
            WifiConfiguration::where('is_active', true)->update(['is_active' => false]);
        }

        WifiConfiguration::create($validated);

        return redirect()->route('wifi-configurations.index')
            ->with('success', 'WiFi configuration created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WifiConfiguration $wifiConfiguration): View
    {
        return view('wifi-configurations.edit', compact('wifiConfiguration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWifiConfigurationRequest $request, WifiConfiguration $wifiConfiguration): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['is_active'])) {
            WifiConfiguration::where('id', '!=', $wifiConfiguration->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $wifiConfiguration->update($validated);

        return redirect()->route('wifi-configurations.index')
            ->with('success', 'WiFi configuration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WifiConfiguration $wifiConfiguration): RedirectResponse
    {
        $wifiConfiguration->delete();

        return redirect()->route('wifi-configurations.index')
            ->with('success', 'WiFi configuration deleted successfully.');
    }
}
