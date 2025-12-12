<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coach extends Model
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
     * Get the member's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
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
}
