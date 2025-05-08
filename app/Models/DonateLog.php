<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonateLog extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'method',
        'transaction_id',
        'status',
        'amount',
        'value',
        'desc',
        'jid',
        'ip',
    ];

    public static function setDonateLog($method, $transaction_id, $status, $amount, $value, $desc, $jid, $ip)
    {
        return self::create([
            'method' => $method,
            'transaction_id' => $transaction_id,
            'status' => $status,
            'amount' => $amount,
            'value' => $value,
            'desc' => $desc,
            'jid' => $jid,
            'ip' => $ip,
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'jid', 'jid');
    }
}
