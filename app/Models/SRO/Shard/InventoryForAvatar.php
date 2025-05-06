<?php

namespace App\Models\SRO\Shard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class InventoryForAvatar extends Model
{
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
    protected $table = 'dbo._InventoryForAvatar';

    /**
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'CharID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CharID',
        'slot',
        'ItemID'
    ];

    public static function getInventoryForAvatar($characterId): array
    {
        $minutes = config('global.cache.character_info', 1440);

        return Cache::remember("character_info_inventory_avatar_{$characterId}", now()->addMinutes($minutes), static function () use ($characterId) {
            return self::where('CharID', '=', $characterId)
            ->join('_Items as Items', 'Items.ID64', 'ItemID')
            ->leftJoin('_BindingOptionWithItem as Binding', static function ($join) {
                $join->on('Binding.nItemDBID', 'Items.ID64');
                $join->where('Binding.nOptValue', '>', '0');
            })
            ->join('_RefObjCommon as Common', 'Items.RefItemId', 'Common.ID')
            ->join('_RefObjItem as ObjItem', 'Common.Link', 'ObjItem.ID')
            ->where('ItemID', '>', 1)
            ->get()
            ->toArray();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getChar()
    {
        return $this->hasMany(Char::class, 'CharID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getItem()
    {
        return $this->belongsTo(Items::class, 'ItemID', 'ID64');
    }
}
