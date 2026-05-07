<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ModuleSetting extends Model
{
    protected $fillable = ['module', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    /**
     * Check if a module is active. Cached for performance.
     */
    public static function isActive(string $module): bool
    {
        return Cache::remember("module_{$module}", 60, function () use ($module) {
            $setting = static::where('module', $module)->first();
            return $setting ? $setting->is_active : true;
        });
    }

    /**
     * Toggle a module and clear its cache.
     */
    public static function toggle(string $module): void
    {
        $setting = static::where('module', $module)->firstOrFail();
        $setting->update(['is_active' => !$setting->is_active]);
        Cache::forget("module_{$module}");
    }

    /**
     * Get all modules as key => bool array.
     */
    public static function allModules(): array
    {
        return static::all()->pluck('is_active', 'module')->toArray();
    }
}
