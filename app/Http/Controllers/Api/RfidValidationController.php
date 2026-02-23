<?php

namespace App\Http\Controllers\Api;

use App\Events\RfidAccessAttempted;
use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\WifiConfiguration;
use App\Services\RfidAccessPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfidValidationController extends Controller
{
    /**
     * Validate RFID card and check access permission.
     */
    public function validate(Request $request, RfidAccessPolicy $accessPolicy): JsonResponse
    {
        $request->validate([
            'card_number' => ['required', 'string'],
        ]);

        $cardNumber = $request->input('card_number');
        $ipAddress = $request->ip();

        $result = $accessPolicy->check($cardNumber);

        $this->logAccess(
            $cardNumber,
            $result['rfid_card_id'],
            $result['member_id'],
            $result['granted'] ? 'granted' : 'denied',
            $result['reason'],
            $ipAddress,
            $result['member_name']
        );

        return response()->json($result['response'], $result['http_status']);
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

        broadcast(new RfidAccessAttempted($accessLog));
    }
}
