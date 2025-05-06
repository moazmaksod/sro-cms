<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebItemCertifyKey extends Model
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
    protected $table = 'dbo.WEB_ITEM_CERTIFYKEY';

    /**
     * The table primary Key
     *
     * @var string JID
     */
    protected $primaryKey = 'UserJID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idx',
        'UserJID',
        'Certifykey',
        'ShardID',
        'reg_date',
        'CharLevel'
    ];

    public static function getCertifyKey($JID)
    {
        return self::where('UserJID', $JID)->orderByDesc('reg_date')->limit(1)->first();
    }

    public function tbUser()
    {
        return $this->belongsTo(TbUser::class, 'JID', 'UserJID');
    }
}
