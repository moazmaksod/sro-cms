<?php

namespace App\Models\SRO\Shard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Items extends Model
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
    protected $table = 'dbo._Items';

    /**
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'ID64';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ID64'
    ];

    /**
     * @var array
     */
    protected $casts = [
        // RefObjCommon
        'CashItem' => 'integer',
        'Bionic' => 'integer',
        'TypeID1' => 'integer',
        'TypeID2' => 'integer',
        'TypeID3' => 'integer',
        'TypeID4' => 'integer',
        'DecayTime' => 'integer',
        'Country' => 'integer',
        'Rarity' => 'integer',
        'CanTrade' => 'integer',
        'CanSell' => 'integer',
        'CanBuy' => 'integer',
        'CanBorrow' => 'integer',
        'CanDrop' => 'integer',
        'CanPick' => 'integer',
        'CanRepair' => 'integer',
        'CanUse' => 'integer',
        'CanThrow' => 'integer',
        'Price' => 'integer',
        'CostRepair' => 'integer',
        'CostRevive' => 'integer',
        'CostBorrow' => 'integer',
        'KeepingFee' => 'integer',
        'SellPrice' => 'integer',
        'ReqLevelType1' => 'integer',
        'ReqLevel1' => 'integer',
        'ReqLevelType2' => 'integer',
        'ReqLevel2' => 'integer',
        'ReqLevelType3' => 'integer',
        'ReqLevel3' => 'integer',
        'ReqLevelType4' => 'integer',
        'ReqLevel4' => 'integer',
        'MaxContain' => 'integer',

        // RefObjItem
        'MaxStack' => 'integer',
        'ReqGender' => 'integer',
        'ReqStr' => 'integer',
        'ReqInt' => 'integer',
        'ItemClass' => 'integer',
        'MaxMagicOptOption' => 'integer',

        // Items
        'OptLevel' => 'integer',
        'Variance' => 'integer',
        'MagParamNum' => 'integer',
        'MagParam1' => 'integer',
        'MagParam2' => 'integer',
        'MagParam3' => 'integer',
        'MagParam4' => 'integer',
        'MagParam5' => 'integer',
        'MagParam6' => 'integer',
        'MagParam7' => 'integer',
        'MagParam8' => 'integer',
        'MagParam9' => 'integer',
        'MagParam10' => 'integer',
        'MagParam11' => 'integer',
        'MagParam12' => 'integer',
    ];

    public static function getItemNameBySerial($serials): array
    {
        $minutes = config('global.general.cache.data.globals_history', 10);

        return Cache::remember("globals_history_serial_{$serials[0]}", now()->addMinutes($minutes), static function () use ($serials) {
            return self::select(
                '_Items.Serial64',
                '_Items.OptLevel',
                '_Rigid_ItemNameDesc.ENG as ItemName',
                DB::raw("REPLACE(REPLACE(_RefObjCommon.AssocFileIcon128, '\\', '/'), '.ddj', '') as IconPath")
            )
            ->join('_RefObjCommon', '_Items.RefItemID', '=', '_RefObjCommon.ID')
            ->leftJoin(DB::raw('SILKROAD_R_ACCOUNT.._Rigid_ItemNameDesc'), '_Rigid_ItemNameDesc.StrID', '=', '_RefObjCommon.NameStrID128')
            ->whereIn('Serial64', $serials)
            ->get()
            ->keyBy('Serial64')
            ->toArray();
        });
    }
}
