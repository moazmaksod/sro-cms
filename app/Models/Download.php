<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'desc', 'url', 'image',
    ];

    public static function getDownloads()
    {
        $minutes = config('global.general.cache.data.download', 10080);

        return Cache::remember('download', now()->addMinutes($minutes), function () {
            return self::all();
        });
    }
}
