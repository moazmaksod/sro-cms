<?php

namespace App\Models\SRO\Portal;

use App\Models\SRO\Account\TbUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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

    public function getGetSilkAttribute()
    {
        return Cache::remember("mu_user_silk_{$this->JID}", config('global.cache.account_info', 600), function () {
            return (object) [
                'PremiumSilk' => AphChangedSilk::getChangedSilk($this->JID, 3),
                'Silk' => AphChangedSilk::getChangedSilk($this->JID, 1),
            ];
        });
    }

    public function getGetSilkUsageAttribute()
    {
        return Cache::remember("mu_user_silk_usage_{$this->JID}", config('global.cache.account_info', 600), function () {
            return (object) [
                'MonthUsage' => AphCpItemSaleDetails::getSilkUsage($this->JID, 3, 0),
                'ThreeMonthUsage' => AphCpItemSaleDetails::getSilkUsage($this->JID, 3, 2),
            ];
        });
    }

    public function getMuVIPInfoAttribute()
    {
        return Cache::remember("mu_user_vip_{$this->JID}", config('global.cache.account_info', 600), function () {
            return $this->muVIPInfo()->first();
        });
    }

    public function getMuEmailAttribute()
    {
        return Cache::remember("mu_user_email_{$this->JID}", config('global.cache.account_info', 600), function () {
            return $this->muEmail()->first();
        });
    }

    public function muEmail()
    {
        return $this->hasOne(MuEmail::class, 'JID', 'JID');
    }

    public function muAlteredInfo()
    {
        return $this->hasOne(MuhAlteredInfo::class, 'JID', 'JID');
    }

    public function muVIPInfo()
    {
        return $this->hasOne(MuVIPInfo::class, 'JID', 'JID');
    }

    public function aphChangedSilk()
    {
        return $this->hasMany(AphChangedSilk::class, 'JID', 'JID');
    }

    public function aphCpItemSaleDetails()
    {
        return $this->hasMany(AphCpItemSaleDetails::class, 'JID', 'JID');
    }

    public function tbUser()
    {
        return $this->belongsTo(TbUser::class, 'PortalJID', 'JID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'jid', 'JID');
    }
}
