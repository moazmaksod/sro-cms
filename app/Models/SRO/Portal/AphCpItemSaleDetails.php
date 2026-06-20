<?php

namespace App\Models\SRO\Portal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AphCpItemSaleDetails extends Model
{
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
    protected $table = 'dbo.APH_CPItemSaleDetails';

    /**
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'PTInvoiceID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'CPJCIInvoiceID',
        'ServiceCode',
        'CPItemCount',
        'Price',
        'SilkType',
        'JCISCode',
        'JID',
        'UserIP',
        'CountryCode',
        'CPPaymentDate',
        'CPItemID',
        'ServerName',
        'CharName',
        'CharID',
    ];

    public function muUser()
    {
        return $this->belongsTo(MuUser::class, 'JID', 'JID');
    }

    public static function getSilkUsage(int $jid, int $type, int $backMonths): int
    {
        return (int) self::where('JID', $jid)
            ->where('CPPaymentDate', '>=', now()->startOfMonth()->subMonths($backMonths))
            ->where('SilkType', $type)
            ->sum('Price');
    }
}
