<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RfidCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfidValidationController extends Controller
{
    /**
     * Validate RFID card and check access permission.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'card_number' => ['required', 'string'],
        ]);

        $cardNumber = $request->input('card_number');

        // Find the RFID card
        $rfidCard = RfidCard::where('card_number', $cardNumber)
            ->with(['member.activeSubscription'])
            ->first();

        // Card not found
        if (! $rfidCard) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found',
                'access_granted' => false,
            ], 404);
        }

        // Check if card is assigned to a member
        if (! $rfidCard->member_id) {
            return response()->json([
                'success' => false,
                'message' => 'Card not assigned to any member',
                'access_granted' => false,
            ], 403);
        }

        // Check if card is active
        if ($rfidCard->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Card is '.$rfidCard->status,
                'access_granted' => false,
                'card_status' => $rfidCard->status,
            ], 403);
        }

        // Check if card is expired
        if ($rfidCard->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Card has expired',
                'access_granted' => false,
                'expires_at' => $rfidCard->expires_at?->format('Y-m-d'),
            ], 403);
        }

        // Check if member has active subscription
        $member = $rfidCard->member;
        $activeSubscription = $member->activeSubscription;

        if (! $activeSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'Member has no active subscription',
                'access_granted' => false,
                'member_name' => $member->full_name,
            ], 403);
        }

        // All checks passed - access granted
        return response()->json([
            'success' => true,
            'message' => 'Access granted',
            'access_granted' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->full_name,
                'email' => $member->email,
            ],
            'card' => [
                'number' => $rfidCard->card_number,
                'type' => $rfidCard->type,
                'issued_at' => $rfidCard->issued_at->format('Y-m-d'),
                'expires_at' => $rfidCard->expires_at?->format('Y-m-d'),
            ],
            'subscription' => [
                'plan_name' => $activeSubscription->plan_name,
                'end_date' => $activeSubscription->end_date->format('Y-m-d'),
                'status' => $activeSubscription->status,
            ],
        ], 200);
    }

    /**
     * Health check endpoint for ESP32.
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'API is running',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }
}
