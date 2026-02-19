<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'subscription_id',
        'subscription_type',
        'start_date',
        'end_date',
        'price',
        'payment_type',
        'payment_status',
        'status',
        'notes',
        'frozen_at',
        'frozen_until',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'frozen_at' => 'date',
            'frozen_until' => 'date',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Whether the subscription is currently frozen (today is between frozen_at and frozen_until).
     */
    public function getIsFrozenAttribute(): bool
    {
        if (! $this->frozen_at || ! $this->frozen_until) {
            return false;
        }
        $today = now()->toDateString();

        return $today >= $this->frozen_at->toDateString() && $today <= $this->frozen_until->toDateString();
    }

    /**
     * Whether the subscription is currently active for access (not expired, not frozen).
     */
    public function getIsCurrentlyActiveAttribute(): bool
    {
        if ($this->status !== 'active' || $this->end_date->lt(now())) {
            return false;
        }

        return ! $this->is_frozen;
    }

    /**
     * Get the member that owns the subscription.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the subscription template.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
