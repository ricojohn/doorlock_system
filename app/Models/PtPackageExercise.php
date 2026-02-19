<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtPackageExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'pt_package_id',
        'exercise_name',
        'sets',
        'reps',
        'weight',
        'duration_minutes',
        'rest_period_seconds',
        'notes',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
        ];
    }

    public function ptPackage(): BelongsTo
    {
        return $this->belongsTo(PtPackage::class);
    }
}
