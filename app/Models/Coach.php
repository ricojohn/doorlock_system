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
        'specialty',
        'status',
        'notes',
    ];

    /**
     * Get the members assigned to the coach.
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
