<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['scope', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public const CACHE_KEY = 'app_settings';

    public const SCOPE_APP = 'app';

    /**
     * Default values when no settings exist in DB.
     */
    public static function defaults(): array
    {
        return [
            'app_name' => config('app.name', 'Gym Admin'),
            'logo_path' => null,
            'favicon_path' => null,
            'primary_color' => '#4154f1',
            'theme_mode' => 'light', // light, dark, system
        ];
    }

    /**
     * Get app settings (branding + theme). Cached.
     */
    public static function getCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            $row = self::where('scope', self::SCOPE_APP)->first();

            return $row
                ? array_merge(self::defaults(), $row->payload)
                : self::defaults();
        });
    }

    /**
     * Update app settings and clear cache.
     */
    public static function setAppPayload(array $payload): void
    {
        self::updateOrCreate(
            ['scope' => self::SCOPE_APP],
            ['payload' => $payload]
        );
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Clear settings cache (e.g. after update).
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
