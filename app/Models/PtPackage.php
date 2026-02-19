<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PtPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'package_rate',
        'session_count',
        'rate_per_session',
        'commission_percentage',
        'commission_per_session',
        'description',
        'package_type',
        'coach_id',
        'status',
        'payment_type',
    ];

    protected function casts(): array
    {
        return [
            'package_rate' => 'decimal:2',
            'rate_per_session' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
            'commission_per_session' => 'decimal:2',
        ];
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(PtPackageExercise::class)->orderBy('order');
    }

    public function memberPtPackages(): HasMany
    {
        return $this->hasMany(MemberPtPackage::class);
    }
}
