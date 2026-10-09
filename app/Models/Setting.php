<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static $cachedSettings = null;

    public static function getAllSettings(): array
    {
        if (static::$cachedSettings === null) {
            try {
                static::$cachedSettings = static::pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                static::$cachedSettings = [];
            }
        }
        return static::$cachedSettings;
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $all = static::getAllSettings();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        if (static::$cachedSettings !== null) {
            static::$cachedSettings[$key] = $value;
        }
    }
}
