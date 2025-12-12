<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtSessionPlanItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pt_session_plan_id',
        'exercise_name',
        'sets',
        'reps',
        'weight',
        'duration_minutes',
        'rest_period_seconds',
        'notes',
        'order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
        ];
    }

    /**
     * Get the PT session plan that owns the item.
     */
    public function ptSessionPlan(): BelongsTo
    {
        return $this->belongsTo(PtSessionPlan::class);
    }
}
