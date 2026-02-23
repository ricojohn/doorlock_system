<?php

namespace App\Services;

use App\Models\RfidCard;

/**
 * Decides whether an RFID card should be granted access.
 * Returns a result array for the API response and logging.
 */
class RfidAccessPolicy
{
    /**
     * Check access for the given card number.
     *
     * @return array{
     *   granted: bool,
     *   reason: string,
     *   http_status: int,
     *   response: array<string, mixed>,
     *   rfid_card_id: int|null,
     *   member_id: int|null,
     *   member_name: string|null
     * }
     */
    public function check(string $cardNumber): array
    {
        $rfidCard = RfidCard::where('card_number', $cardNumber)->with(['member'])->first();

        if (! $rfidCard) {
            return [
                'granted' => false,
                'reason' => 'Card not found',
                'http_status' => 404,
                'response' => [
                    'success' => false,
                    'message' => 'Card not found',
                    'access_granted' => false,
                ],
                'rfid_card_id' => null,
                'member_id' => null,
                'member_name' => null,
            ];
        }

        if (! $rfidCard->member_id) {
            return [
                'granted' => false,
                'reason' => 'Card not assigned to any member',
                'http_status' => 403,
                'response' => [
                    'success' => false,
                    'message' => 'Card not assigned to any member',
                    'access_granted' => false,
                ],
                'rfid_card_id' => $rfidCard->id,
                'member_id' => null,
                'member_name' => null,
            ];
        }

        $member = $rfidCard->member;
        $memberId = $member->id;
        $memberName = $member->full_name;
        $activeSubscription = $member->activeMemberSubscription;

        if ($rfidCard->status !== 'active') {
            return [
                'granted' => false,
                'reason' => 'Card is '.$rfidCard->status,
                'http_status' => 403,
                'response' => [
                    'success' => false,
                    'message' => 'Card is '.$rfidCard->status,
                    'access_granted' => false,
                    'card_status' => $rfidCard->status,
                ],
                'rfid_card_id' => $rfidCard->id,
                'member_id' => $memberId,
                'member_name' => $memberName,
            ];
        }

        if ($rfidCard->isExpired()) {
            return [
                'granted' => false,
                'reason' => 'Card has expired',
                'http_status' => 403,
                'response' => [
                    'success' => false,
                    'message' => 'Card has expired',
                    'access_granted' => false,
                    'expires_at' => optional($rfidCard->expires_at)->format('Y-m-d'),
                ],
                'rfid_card_id' => $rfidCard->id,
                'member_id' => $memberId,
                'member_name' => $memberName,
            ];
        }

        if (! $activeSubscription) {
            return [
                'granted' => false,
                'reason' => 'Member has no active subscription',
                'http_status' => 403,
                'response' => [
                    'success' => false,
                    'message' => 'Member has no active subscription',
                    'access_granted' => false,
                    'member_name' => $memberName,
                ],
                'rfid_card_id' => $rfidCard->id,
                'member_id' => $memberId,
                'member_name' => $memberName,
            ];
        }

        return [
            'granted' => true,
            'reason' => 'Access granted',
            'http_status' => 200,
            'response' => [
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
                    'issued_at' => optional($rfidCard->issued_at)->format('Y-m-d'),
                    'expires_at' => optional($rfidCard->expires_at)->format('Y-m-d'),
                ],
            ],
            'rfid_card_id' => $rfidCard->id,
            'member_id' => $memberId,
            'member_name' => $memberName,
        ];
    }
}
