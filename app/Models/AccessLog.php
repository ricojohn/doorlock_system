<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'card_number',
        'rfid_card_id',
        'member_id',
        'member_name',
        'access_granted',
        'reason',
        'ip_address',
        'accessed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'accessed_at' => 'datetime',
        ];
    }

    /**
     * Get the RFID card that was used.
     */
    public function rfidCard(): BelongsTo
    {
        return $this->belongsTo(RfidCard::class);
    }

    /**
     * Get the member who attempted access.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

