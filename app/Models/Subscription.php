<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'plan_id',
        'plan_name',
        'start_date',
        'end_date',
        'price',
        'status',
        'payment_status',
        'notes',
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
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the member that owns the subscription.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the plan for the subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
