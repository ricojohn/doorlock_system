<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DoorControlController extends Controller
{
    private const DOOR_OPEN_TTL_SECONDS = 30;

    public function index(): View
    {
        return view('door-control.index');
    }

    public function open(): RedirectResponse
    {
        Cache::put('door_open_main', true, self::DOOR_OPEN_TTL_SECONDS);

        return redirect()
            ->route('door-control.index')
            ->with('success', 'Main door open command sent. The door will unlock when the device polls.');
    }
}
