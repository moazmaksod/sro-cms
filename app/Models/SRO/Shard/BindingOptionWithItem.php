<?php

namespace App\Models\SRO\Shard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BindingOptionWithItem extends Model
{
    use HasFactory;

    /**
     * The Database connection name for the model.
     *
     * @var string
     */
    protected $connection = 'shard';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo._BindingOptionWithItem';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nItemDBID'
    ];

    public static function getBindingOption($ItemDBID)
    {
        $minutes = config('global.cache.character_info', 1440);

        return Cache::remember("character_info_binding_{$ItemDBID}", now()->addMinutes($minutes), static function () use ($ItemDBID) {
            return self::where('nItemDBID', $ItemDBID)->get()->toArray();
        });
    }
}
