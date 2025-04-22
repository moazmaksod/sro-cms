<?php

namespace App\Models\SRO\Portal;

use App\Models\SRO\Account\TbUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MuUser extends Model
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
    protected $table = 'dbo.MU_User';

    /**
     * The table primary Key
     *
     * @var string
     */
    protected $primaryKey = 'JID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserID',
        'UserPwd',
        'Gender',
        'Birthday',
        'NickName',
        'CountryCode',
        'AbusingCount',
    ];

    protected $hidden = [
        'password'
    ];

    public static function setPortalAccount($username, $password)
    {
        return self::create([
            'UserID' => $username,
            'UserPwd' => md5($password),
            'Gender' => 'M',
            'Birthday' => now(),
            'NickName' => $username,
            'CountryCode' => 'EG',
            'AbusingCount' => 0,
        ]);
    }

    public function getJCash()
    {
        return Cache::remember('account_j_cash_'.$this->JID, config('global.general.cache.data.silk'), function () {
            return collect(DB::select("
            Declare @ReturnValue Int
            Declare @PremiumSilk Int
            Declare @Silk Int;
            Declare @VipLevel Int
            Declare @UsageMonth Int
            Declare @Usage3Month Int;
            SET NOCOUNT ON;

            Execute @ReturnValue = [GB_JoymaxPortal].[dbo].[B_GetJCash]
                ".$this->JID.",
                @PremiumSilk Output,
                @Silk Output,
                @VipLevel Output,
                @UsageMonth Output,
                @Usage3Month Output;

            Select
                @ReturnValue AS 'ErrorCode',
                @PremiumSilk AS 'PremiumSilk',
                @Silk AS 'Silk',
                @UsageMonth AS 'MonthUsage',
                @Usage3Month AS 'ThreeMonthUsage'
            "
            ))->first();
        });
    }

    public function getEmailUser()
    {
        return $this->hasOne(MuEmail::class, 'JID', 'JID');
    }

    public function getVipLevel()
    {
        return $this->hasOne(MuVIPInfo::class, 'JID', 'JID');
    }

    public function getChangedSilk()
    {
        return $this->hasMany(AphChangedSilk::class, 'JID', 'JID');
    }

    public function getTbUser()
    {
        return $this->belongsTo(TbUser::class, 'PortalJID', 'JID');
    }

    public function getWebUser()
    {
        return $this->belongsTo(User::class, 'jid', 'JID');
    }
}
