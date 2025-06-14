<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Vote extends Model
{
    protected $fillable = [
        'title',
        'url',
        'site',
        'image',
        'ip',
        'param',
        'reward',
        'timeout',
        'active',
    ];

    public static function getVotes()
    {
        $minutes = config('global.cache.account_info', 5);

        return Cache::remember('votes', now()->addMinutes($minutes), function () {
            return self::all();
        });
    }
}
