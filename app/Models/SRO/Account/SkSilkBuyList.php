<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class SkSilkBuyList extends Model
{
    /**
     * The Database connection name for the model.
     *
     * @var string
     */
    protected $connection = 'account';

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
    protected $table = 'dbo.SK_SilkBuyList';

    /**
     * The table primary Key
     *
     * @var string JID
     */
    protected $primaryKey = 'BuyNo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'BuyNo',
        'UserJID',
        'Silk_Type',
        'Silk_Reason',
        'Silk_Offset',
        'Silk_Remain',
        'ID',
        'BuyQuantity',
        'OrderNumber',
        'PGCompany',
        'PayMethod',
        'PGUniqueNo',
        'AuthNumber',
        'AuthDate',
        'SubJID',
        'srID',
        'SlipPaper',
        'MngID',
        'IP',
        'RegDate'
    ];

    protected static function booted()
    {
        static::saved(fn (self $model) => Cache::forget("account_info_vsro_donate_history_{$model->UserJID}_10_1"));
    }

    public static function setSilkBuyList($userJID, $numSilk, $silkRemain = null, $silkType = 0, $pkgId = 0, $orderId = 'Website')
    {
        return self::create([
            'UserJID' => $userJID,
            'Silk_Type' => $silkType,
            'Silk_Reason' => 0,
            'Silk_Offset' => $numSilk,
            'Silk_Remain' => $silkRemain ?? $numSilk,
            'ID' => $pkgId,
            'BuyQuantity' => 1,
            'OrderNumber' => $orderId,
            'SlipPaper' => 'User Purchase Silk from Website',
            'RegDate' => now(),
        ]);
    }

    public static function getSilkBuyList($jid, $paginate = 10, $page = 1): LengthAwarePaginator
    {
        $data = Cache::remember("account_info_vsro_donate_history_{$jid}_{$paginate}_{$page}", config('global.cache.account_info', 600), function () use ($paginate, $page, $jid) {
            return self::select(
                'BuyNo',
                'OrderNumber',
                'Silk_Offset',
                'Silk_Remain',
                'Silk_Type',
                'RegDate'
            )
                ->where('UserJID', $jid)
                ->orderBy('RegDate', 'desc')
                ->get();
        });

        return new LengthAwarePaginator(
            $data->forPage($page, $paginate)->values(),
            $data->count(),
            $paginate,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
