<?php

namespace App\Models;

use App\Models\SRO\Account\TbUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\SRO\Portal\MuUser;
use App\Models\SRO\Portal\MuVIPInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'jid',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getUserCount()
    {
        return Cache::remember('account_count', config('global.general.cache.data.account'), function () {
            return self::count();
        });
    }

    public function getMuUser()
    {
        return $this->hasOne(MuUser::class, 'jid', 'JID');
    }

    public function getTbUser()
    {
        return $this->hasOne(TbUser::class, 'jid', 'PortalJID');
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function role()
    {
        return $this->hasOne(UserRole::class);
    }
}
