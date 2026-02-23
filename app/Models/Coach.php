<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Coach extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'gender',
        'specialty',
        'house_number',
        'street',
        'barangay',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
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
     * Get the coach's full name (from linked user).
     */
    public function getFullNameAttribute(): string
    {
        return $this->user?->full_name ?? 'N/A';
    }

    /**
     * Get the user that owns the coach profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the work histories for the coach.
     */
    public function workHistories(): HasMany
    {
        return $this->hasMany(CoachWorkHistory::class);
    }

    /**
     * Get the certificates for the coach.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(CoachCertificate::class);
    }

    /**
     * Get the PT session plans for the coach.
     */
    public function ptSessionPlans(): HasMany
    {
        return $this->hasMany(PtSessionPlan::class);
    }

    /**
     * Get the member PT package subscriptions assigned to this coach.
     */
    public function memberPtPackages(): HasMany
    {
        return $this->hasMany(MemberPtPackage::class);
    }

    /**
     * Get the guests invited by this coach.
     */
    public function invitedGuests(): MorphMany
    {
        return $this->morphMany(Guest::class, 'inviter');
    }

    /**
     * Get the members invited by this coach (member's invited_by points to this coach).
     */
    public function invitedMembers(): MorphMany
    {
        return $this->morphMany(Member::class, 'invited_by');
    }
}
