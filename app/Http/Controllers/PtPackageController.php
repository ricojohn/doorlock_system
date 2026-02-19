<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePtPackageRequest;
use App\Http\Requests\UpdatePtPackageRequest;
use App\Models\PtPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PtPackageController extends Controller
{
    public function index(): View
    {
        $ptPackages = PtPackage::with(['coach', 'exercises'])
            ->orderBy('name')
            ->get();

        return view('pt-packages.index', compact('ptPackages'));
    }

    public function create(): View
    {
        return view('pt-packages.create');
    }

    public function store(StorePtPackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $exercises = $data['exercises'] ?? [];
        unset($data['exercises']);

        $ptPackage = PtPackage::create($data);

        foreach ($exercises as $index => $item) {
            if (! empty(trim($item['exercise_name'] ?? ''))) {
                $item['order'] = $index;
                $ptPackage->exercises()->create($item);
            }
        }

        return redirect()
            ->route('pt-packages.index')
            ->with('success', 'PT Package created successfully.');
    }

    public function show(PtPackage $ptPackage): View
    {
        $ptPackage->load(['coach', 'exercises', 'memberPtPackages.member']);

        return view('pt-packages.show', compact('ptPackage'));
    }

    public function edit(PtPackage $ptPackage): View
    {
        $ptPackage->load('exercises');

        return view('pt-packages.edit', compact('ptPackage'));
    }

    public function update(UpdatePtPackageRequest $request, PtPackage $ptPackage): RedirectResponse
    {
        $data = $request->validated();
        $exercises = $data['exercises'] ?? [];
        unset($data['exercises']);

        $ptPackage->update($data);
        $ptPackage->exercises()->delete();

        foreach ($exercises as $index => $item) {
            if (! empty(trim($item['exercise_name'] ?? ''))) {
                $item['order'] = $index;
                $ptPackage->exercises()->create($item);
            }
        }

        return redirect()
            ->route('pt-packages.index')
            ->with('success', 'PT Package updated successfully.');
    }

    public function destroy(PtPackage $ptPackage): RedirectResponse
    {
        $ptPackage->delete();

        return redirect()
            ->route('pt-packages.index')
            ->with('success', 'PT Package deleted successfully.');
    }
}
