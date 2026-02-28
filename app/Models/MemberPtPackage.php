<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberPtPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'pt_package_id',
        'coach_id',
        'start_date',
        'end_date',
        'status',
        'payment_type',
        'price_paid',
        'rate_per_session',
        'commission_percentage',
        'commission_per_session',
        'sessions_total',
        'receipt_number',
        'receipt_image',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'price_paid' => 'decimal:2',
            'rate_per_session' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'commission_per_session' => 'decimal:2',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function ptPackage(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function ptSessions(): HasMany
    {
        return $this->hasMany(PtSession::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public function getSessionsUsedAttribute(): int
    {
        return (int) $this->ptSessions()->sum('sessions_used');
    }

    public function getRemainingSessionsAttribute(): int
    {
        return max(0, $this->sessions_total - $this->sessions_used);
    }

    public function getIsExhaustedAttribute(): bool
    {
        return $this->remaining_sessions <= 0;
    }

    public function getIsExpiredAttribute(): bool
    {
        if (! $this->end_date) {
            return false;
        }

        return $this->end_date->isPast();
    }

    public function getIsActiveAttribute(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        if ($this->is_exhausted) {
            return false;
        }
        if ($this->is_expired) {
            return false;
        }

        return true;
    }
}
