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
     * Get the member subscriptions for the member.
     */
    public function memberSubscriptions(): HasMany
    {
        return $this->hasMany(MemberSubscription::class);
    }

    /**
     * Get the active subscription for the member.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(MemberSubscription::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get the active member subscription for the member.
     */
    public function activeMemberSubscription(): HasOne
    {
        return $this->hasOne(MemberSubscription::class)
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get the PT session plans for the member.
     */
    public function ptSessionPlans(): HasMany
    {
        return $this->hasMany(PtSessionPlan::class);
    }

    /**
     * Get the access logs for the member.
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(AccessLog::class);
    }
}
