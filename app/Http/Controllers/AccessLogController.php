<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AccessLogController extends Controller
{
    /**
     * Display a listing of access logs.
     */
    public function index(): View
    {
        $accessLogs = AccessLog::with(['rfidCard', 'member'])
            ->orderBy('accessed_at', 'asc')
            ->get();

        return view('access-logs.index', compact('accessLogs'));
    }


    /**
     * Get recent access logs (for polling fallback).
     */
    public function recent(): JsonResponse
    {
        $logs = AccessLog::with(['rfidCard', 'member'])
            ->orderBy('accessed_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'card_number' => $log->card_number,
                    'member_name' => $log->member_name ?? 'Unknown',
                    'access_granted' => $log->access_granted,
                    'reason' => $log->reason,
                    'accessed_at' => $log->accessed_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($logs);
    }
}

