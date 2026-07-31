<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps  = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType    = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['key', 'value'];

    /*
    |--------------------------------------------------------------------------
    | Cache Keys
    |--------------------------------------------------------------------------
    */

    protected static function itemCacheKey(string $key): string
    {
        return "setting_{$key}";
    }

    protected static function allCacheKey(): string
    {
        return 'settings_all';
    }

    /*
    |--------------------------------------------------------------------------
    | Getters
    |--------------------------------------------------------------------------
    */

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(
            static::itemCacheKey($key),
            fn () => optional(static::where('key', $key)->first())->value ?? $default
        );
    }

    public static function cached(): Collection
    {
        return Cache::rememberForever(
            static::allCacheKey(),
            fn () => static::pluck('value', 'key')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Setters
    |--------------------------------------------------------------------------
    */

    public static function set(string $key, mixed $value): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        Cache::forget(static::itemCacheKey($key));
        Cache::forget(static::allCacheKey());

        return $setting;
    }

    public static function saveMany(array $items): void
    {
        foreach ($items as $key => $value) {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );

            Cache::forget(static::itemCacheKey($key));
        }

        Cache::forget(static::allCacheKey());
    }

    /*
    |--------------------------------------------------------------------------
    | Cache Helpers
    |--------------------------------------------------------------------------
    */

    public static function flushCache(): void
    {
        Cache::forget(static::allCacheKey());
    }
}
