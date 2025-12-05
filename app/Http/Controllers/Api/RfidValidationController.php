<?php

namespace App\Http\Controllers\Api;

use App\Events\RfidAccessAttempted;
use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\RfidCard;
use App\Models\WifiConfiguration;
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
        $ipAddress = $request->ip();

        // Find the RFID card
        $rfidCard = RfidCard::where('card_number', $cardNumber)
            ->with(['member.activeSubscription'])
            ->first();

        $accessGranted = false;
        $reason = null;
        $memberName = null;
        $memberId = null;

        // Card not found
        if (! $rfidCard) {
            $reason = 'Card not found';
            $this->logAccess($cardNumber, null, null, 'denied', $reason, $ipAddress);

            return response()->json([
                'success' => false,
                'message' => $reason,
                'access_granted' => false,
            ], 404);
        }

        // Check if card is assigned to a member
        if (! $rfidCard->member_id) {
            $reason = 'Card not assigned to any member';
            $this->logAccess($cardNumber, $rfidCard->id, null, 'denied', $reason, $ipAddress);

            return response()->json([
                'success' => false,
                'message' => $reason,
                'access_granted' => false,
            ], 403);
        }

        $member = $rfidCard->member;
        $memberId = $member->id;
        $memberName = $member->full_name;

        // Check if card is active
        if ($rfidCard->status !== 'active') {
            $reason = 'Card is '.$rfidCard->status;
            $this->logAccess($cardNumber, $rfidCard->id, $memberId, 'denied', $reason, $ipAddress, $memberName);

            return response()->json([
                'success' => false,
                'message' => $reason,
                'access_granted' => false,
                'card_status' => $rfidCard->status,
            ], 403);
        }

        // Check if card is expired
        if ($rfidCard->isExpired()) {
            $reason = 'Card has expired';
            $this->logAccess($cardNumber, $rfidCard->id, $memberId, 'denied', $reason, $ipAddress, $memberName);

            return response()->json([
                'success' => false,
                'message' => $reason,
                'access_granted' => false,
                'expires_at' => $rfidCard->expires_at?->format('Y-m-d'),
            ], 403);
        }

        // Check if member has active subscription
        $activeSubscription = $member->activeSubscription;

        if (! $activeSubscription) {
            $reason = 'Member has no active subscription';
            $this->logAccess($cardNumber, $rfidCard->id, $memberId, 'denied', $reason, $ipAddress, $memberName);

            return response()->json([
                'success' => false,
                'message' => $reason,
                'access_granted' => false,
                'member_name' => $memberName,
            ], 403);
        }

        // All checks passed - access granted
        $accessGranted = true;
        $reason = 'Access granted';
        $this->logAccess($cardNumber, $rfidCard->id, $memberId, 'granted', $reason, $ipAddress, $memberName);

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

    /**
     * Get WiFi configuration for ESP32.
     */
    public function getWifiConfig(): JsonResponse
    {
        $wifiConfig = WifiConfiguration::getActive();

        if (! $wifiConfig) {
            return response()->json([
                'success' => false,
                'message' => 'No active WiFi configuration found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ssid' => $wifiConfig->ssid,
            'password' => $wifiConfig->password,
            'description' => $wifiConfig->description,
        ], 200);
    }

    /**
     * Log access attempt and broadcast event.
     */
    private function logAccess(
        string $cardNumber,
        ?int $rfidCardId,
        ?int $memberId,
        string $accessGranted,
        string $reason,
        ?string $ipAddress,
        ?string $memberName = null
    ): void {
        $accessLog = AccessLog::create([
            'card_number' => $cardNumber,
            'rfid_card_id' => $rfidCardId,
            'member_id' => $memberId,
            'member_name' => $memberName,
            'access_granted' => $accessGranted,
            'reason' => $reason,
            'ip_address' => $ipAddress,
            'accessed_at' => now(),
        ]);

        // Broadcast the event for real-time notifications
        broadcast(new RfidAccessAttempted($accessLog));
    }
}
