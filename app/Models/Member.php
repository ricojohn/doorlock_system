<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'status',
        'coach_id',
        'pt_billing_type',
        'pt_rate',
        'house_number',
        'street',
        'barangay',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'pt_rate' => 'decimal:2',
        ];
    }

    /**
     * Get the member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the subscriptions for the member.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription for the member.
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->latest();
    }

    /**
     * Get the latest subscription for the member (regardless of status).
     */
    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /**
     * Get the RFID cards for the member.
     */
    public function rfidCards(): HasMany
    {
        return $this->hasMany(RfidCard::class);
    }

    /**
     * Get the active RFID card for the member.
     */
    public function activeRfidCard(): HasOne
    {
        return $this->hasOne(RfidCard::class)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now()->toDateString());
            })
            ->latest();
    }

    /**
     * Get the coach assigned to the member.
     */
    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
