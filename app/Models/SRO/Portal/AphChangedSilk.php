<?php

namespace App\Models\SRO\Portal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
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

    public static function getSilkHistory($jid)
    {
        return Cache::remember('donate_history'.$jid, now()->addMinutes(config('global.general.cache.data.account')), function () use ($jid) {
            return self::leftJoin('APH_CPItemSaleDetails', 'APH_CPItemSaleDetails.PTInvoiceID', '=', 'APH_ChangedSilk.PTInvoiceID')
                ->leftJoin('M_CPItem', 'M_CPItem.CPItemID', '=', 'APH_CPItemSaleDetails.CPItemID')
                ->select(
                    'M_CPItem.CPItemCode',
                    'M_CPItem.CPItemName',
                    'APH_ChangedSilk.PTInvoiceID',
                    'APH_ChangedSilk.RemainedSilk',
                    'APH_ChangedSilk.ChangedSilk',
                    'APH_ChangedSilk.SilkType',
                    'APH_ChangedSilk.ChangeDate',
                    'APH_ChangedSilk.AvailableStatus'
                )
                ->where('APH_ChangedSilk.JID', $jid)
                ->orderBy('APH_ChangedSilk.ChangeDate', 'desc')
                ->get();
        });
    }

    public static function getSilkSum()
    {
        return Cache::remember('silk_sum', config('global.general.cache.data.account'), function () {
            return self::all()->sum('RemainedSilk');
        });
    }

    public function muUser()
    {
        return $this->belongsTo(MuUser::class, 'JID', 'JID');
    }
}
