<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'amount',
        'type',
        'valid_date',
        'jid',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'valid_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::saved(fn (self $voucher) => Cache::forget("vouchers_user_{$voucher->jid}"));
        static::deleted(fn (self $voucher) => Cache::forget("vouchers_user_{$voucher->jid}"));
    }

    public static function getUserVoucher(int $jid)
    {
        return Cache::remember("vouchers_user_{$jid}", config('global.cache.account_info', 600), function () use ($jid) {
            return self::where('jid', $jid)->get();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'jid', 'jid');
    }
}
