<?php

namespace App\Models\SRO\Portal;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AphChangedSilk extends Model
{
    use HasFactory;

    /**
     * The Database connection name for the model.
     *
     * @var string
     */
    protected $connection = 'portal';

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
    protected $table = 'dbo.APH_ChangedSilk';

    /**
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'CSID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'InvoiceID',
        'PTInvoiceID',
        'JID',
        'RemainedSilk',
        'ChangedSilk',
        'SilkType',
        'SellingTypeID',
        'ChangeDate',
        'AvailableDate',
        'AvailableStatus',
    ];

    public static function setChangedSilk($jid, $type, $amount)
    {
        return self::create([
            'JID' => $jid,
            'PTInvoiceID' => null,
            'RemainedSilk' => $amount,
            'ChangedSilk' => 0,
            'SilkType' => $type,
            'SellingTypeID' => 2,
            'ChangeDate' => now(),
            'AvailableDate' => now()->addYears(1),
            'AvailableStatus' => 'Y',
        ]);
    }

    public static function getSilkHistory($jid, $paginate = 10, $page = 1): LengthAwarePaginator
    {
        $minutes = config('global.general.cache.account_info', 5);

        $data = Cache::remember("account_info_donate_history_{$jid}_{$paginate}_{$page}", now()->addMinutes($minutes), function () use ($paginate, $page, $jid) {
            return self::select(
                'M_CPItem.CPItemCode',
                'M_CPItem.CPItemName',
                'APH_ChangedSilk.PTInvoiceID',
                'APH_ChangedSilk.RemainedSilk',
                'APH_ChangedSilk.ChangedSilk',
                'APH_ChangedSilk.SilkType',
                'APH_ChangedSilk.ChangeDate',
                'APH_ChangedSilk.AvailableStatus'
            )
            ->leftJoin('APH_CPItemSaleDetails', 'APH_CPItemSaleDetails.PTInvoiceID', '=', 'APH_ChangedSilk.PTInvoiceID')
            ->leftJoin('M_CPItem', 'M_CPItem.CPItemID', '=', 'APH_CPItemSaleDetails.CPItemID')
            ->where('APH_ChangedSilk.JID', $jid)
            ->orderBy('APH_ChangedSilk.ChangeDate', 'desc')
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

    public static function getSilkSum()
    {
        $minutes = config('global.general.cache.account_info', 5);

        return Cache::remember('account_info_silk_sum', now()->addMinutes($minutes), function () {
            try {
                return self::selectRaw('SUM(CAST(RemainedSilk AS BIGINT)) as total')->value('total');
            } catch (\Exception $e) {
                return 0;
            }
        });
    }

    public function muUser()
    {
        return $this->belongsTo(MuUser::class, 'JID', 'JID');
    }
}
