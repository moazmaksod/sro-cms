<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
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
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'SerialNo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo._Punishment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserJID',
        'Type',
        'Executor',
        'Shard',
        'CharName',
        'CharInfo',
        'PosInfo',
        'Guide',
        'Description',
        'RaiseTime',
        'BlockStartTime',
        'BlockEndTime',
        'Punishtime',
        'Status'
    ];

    public static function setPunishment($jid, $reason, $now, $end)
    {
        return self::create([
            'UserJID' => $jid,
            'Type' => 1,
            'Executor' => 'Website',
            'Shard' => 0,
            'CharName' => ' ',
            'CharInfo' => '',
            'PosInfo' => '',
            'Guide' => $reason,
            'Description' => $reason,
            'RaiseTime' => $now,
            'BlockStartTime' => $now,
            'BlockEndTime' => $end,
            'Punishtime' => $now,
            'Status' => 1
        ]);
    }
}
