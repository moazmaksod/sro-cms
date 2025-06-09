<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SkSilk extends Model
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
    protected $table = 'dbo.SK_Silk';

    /**
     * The table primary Key
     *
     * @var string JID
     */
    protected $primaryKey = 'JID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'JID',
        'silk_own',
        'silk_gift',
        'silk_point'
    ];

    public static function setSkSilk($jid, $type, $amount)
    {
        $types = [
            '0' => 'silk_own',
            '1' => 'silk_gift',
            '2' => 'silk_point'
        ];

        self::firstOrCreate(
            ['JID' => $jid],
            [
                'silk_own' => 0,
                'silk_gift' => 0,
                'silk_point' => 0
            ]
        );

        return self::where('JID', $jid)->increment($types[$type], $amount);
    }

    public static function getSilkSum()
    {
        $minutes = config('global.cache.account_info', 5);

        return Cache::remember('account_info_vsro_silk_sum', now()->addMinutes($minutes), function () {
            try {
                return self::selectRaw('SUM(CAST(silk_own AS BIGINT)) as total')->value('total');
            } catch (\Exception $e) {
                return 0;
            }
        });
    }
}
