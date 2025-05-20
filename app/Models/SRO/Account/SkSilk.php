<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;

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
            ['silk_own' => 0, 'silk_gift' => 0, 'silk_point' => 0]
        );

        return self::where('JID', $jid)->increment($types[$type], $amount);
    }
}
