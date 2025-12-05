<?php

namespace App\Events;

use App\Models\AccessLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RfidAccessAttempted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public AccessLog $accessLog
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('rfid-access'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'access.attempted';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->accessLog->id,
            'card_number' => $this->accessLog->card_number,
            'member_name' => $this->accessLog->member_name ?? 'Unknown',
            'access_granted' => $this->accessLog->access_granted,
            'reason' => $this->accessLog->reason,
            'accessed_at' => $this->accessLog->accessed_at->format('Y-m-d H:i:s'),
            'member_id' => $this->accessLog->member_id,
        ];
    }
}

