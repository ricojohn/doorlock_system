<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Guest extends Model
{
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
        'inviter_type',
        'inviter_id',
        'status',
        'notes',
    ];

    /**
     * Get the inviter (Coach, Member, or User/frontdesk).
     */
    public function inviter(): MorphTo
    {
        return $this->morphTo('inviter');
    }

    /**
     * Get the member this guest was converted to (if any).
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the guest's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check if guest has been converted to a member.
     */
    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }
}
