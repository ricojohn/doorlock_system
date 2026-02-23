<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::getCached();

        return view('settings.edit', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $payload = array_merge(Setting::getCached(), [
            'app_name' => $request->validated('app_name'),
            'primary_color' => $request->validated('primary_color'),
            'theme_mode' => $request->validated('theme_mode'),
        ]);

        if ($request->hasFile('logo')) {
            $this->deleteOldFile($payload['logo_path'] ?? null);
            $payload['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            $this->deleteOldFile($payload['favicon_path'] ?? null);
            $payload['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        Setting::setAppPayload($payload);

        return redirect()->route('settings.edit')
            ->with('success', 'Settings saved successfully. Refresh the page to see branding and theme applied.');
    }

    private function deleteOldFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
