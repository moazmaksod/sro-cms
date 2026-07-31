<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;

class SkSilkChangeByWeb extends Model
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
    protected $table = 'dbo.SK_SilkChange_BY_Web';

    /**
     * The table primary Key
     *
     * @var string JID
     */
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'JID',
        'silk_remain',
        'silk_offset',
        'silk_type',
        'reason'
    ];

    public static function setSilkChange($userJID, $silkRemain, $silkOffset = null, $silkType = 0, $reason = 0)
    {
        return self::create([
            'JID' => $userJID,
            'silk_remain' => $silkRemain,
            'silk_offset' => $silkOffset ?? $silkRemain,
            'silk_type' => $silkType,
            'reason' => $reason,
        ]);
    }
}
