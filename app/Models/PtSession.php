<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_pt_package_id',
        'conducted_at',
        'sessions_used',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'conducted_at' => 'datetime',
        ];
    }

    public function memberPtPackage(): BelongsTo
    {
        return $this->belongsTo(MemberPtPackage::class);
    }
}
