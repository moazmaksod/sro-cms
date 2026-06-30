<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jid',
        'site',
        'ip',
        'fingerprint',
        'expire',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expire' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(fn () => Cache::forget('votes_count'));
        static::deleted(fn () => Cache::forget('votes_count'));
    }

    public static function getVotes(?string $ip, ?string $fingerprint): Collection
    {
        $voteSites = collect(config('vote'));

        if (!$ip || !$fingerprint) {
            return $voteSites->map(fn($site) => (object) [
                ...$site,
                'expire' => null,
            ]);
        }

        $logs = self::whereIn('site', $voteSites->pluck('route'))
            ->where(function ($q) use ($ip, $fingerprint) {
                $q->where('ip', $ip)
                    ->orWhere('fingerprint', $fingerprint);
            })
            ->get()
            ->keyBy('site');

        return $voteSites->map(function ($site) use ($logs) {
            $log = $logs->get($site['route']);

            return (object) [
                ...$site,
                'expire' => $log?->expire,
            ];
        });
    }

    public static function activeVote(string $site, string $ip, string $fingerprint): ?self
    {
        return self::where('site', $site)
            ->where(function ($q) use ($ip, $fingerprint) {
                $q->where('ip', $ip)
                    ->orWhere('fingerprint', $fingerprint);
            })
            ->where('expire', '>', now())
            ->first();
    }

    public static function getVotesCount()
    {
        return Cache::remember('votes_count', 60, function () {
            return self::whereNotNull('expire')->count();
        });
    }
}
