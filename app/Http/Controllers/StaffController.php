<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $users = User::with(['roles', 'coach'])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('staff.index', compact('users'));
    }

    public function create(): View
    {
        return view('staff.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'password' => Hash::make($data['password']),
        ];

        $user = User::create($userData);
        $user->assignRole($data['role']);

        if ($data['role'] === 'coach') {
            $workHistories = $data['work_histories'] ?? [];
            $certificates = $data['certificates'] ?? [];
            unset($data['role'], $data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['password_confirmation'], $data['work_histories'], $data['certificates']);
            $data['user_id'] = $user->id;
            $data['status'] = $data['status'] ?? 'active';
            $coach = Coach::create($data);
            foreach ($workHistories as $workHistory) {
                if (! empty($workHistory['company_name'])) {
                    $coach->workHistories()->create($workHistory);
                }
            }
            foreach ($certificates as $certificate) {
                if (! empty($certificate['certificate_name'])) {
                    $coach->certificates()->create($certificate);
                }
            }
        }

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member added successfully.');
    }

    public function show(User $staff): View
    {
        $staff->load('roles', 'coach.workHistories', 'coach.certificates');

        return view('staff.show', compact('staff'));
    }

    public function edit(User $staff): View
    {
        $staff->load('roles', 'coach.workHistories', 'coach.certificates');

        return view('staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, User $staff): RedirectResponse
    {
        $data = $request->validated();

        $userPayload = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
        ];
        if (! empty($data['password'])) {
            $userPayload['password'] = Hash::make($data['password']);
        }
        $staff->update($userPayload);

        $staff->syncRoles([$data['role']]);

        if ($data['role'] === 'coach') {
            $workHistories = $data['work_histories'] ?? [];
            $certificates = $data['certificates'] ?? [];
            unset($data['role'], $data['first_name'], $data['last_name'], $data['email'], $data['password'], $data['password_confirmation'], $data['work_histories'], $data['certificates']);
            $data['status'] = $data['status'] ?? 'active';

            if ($staff->coach) {
                $staff->coach->update($data);
                $staff->coach->workHistories()->delete();
                $staff->coach->certificates()->delete();
                $coach = $staff->coach;
            } else {
                $data['user_id'] = $staff->id;
                $coach = Coach::create($data);
            }
            foreach ($workHistories as $workHistory) {
                if (! empty($workHistory['company_name'])) {
                    $coach->workHistories()->create($workHistory);
                }
            }
            foreach ($certificates as $certificate) {
                if (! empty($certificate['certificate_name'])) {
                    $coach->certificates()->create($certificate);
                }
            }
        } elseif ($staff->coach) {
            $staff->coach->delete();
        }

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }
        $staff->delete();

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff member removed successfully.');
    }
}
