<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pages extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
    ];

    protected $casts = [];

    public static function getPageNames()
    {
        return Cache::remember("pages_names", now()->addSeconds(10), function () {
            return self::select('title', 'slug')->get();
        });
    }

    public static function getPage($slug)
    {
        $minutes = config('global.cache.pages', 10080);

        return Cache::remember("pages_view_{$slug}", now()->addMinutes($minutes), function () use ($slug) {
            return self::where('slug', $slug)->first();
        });
    }
}
