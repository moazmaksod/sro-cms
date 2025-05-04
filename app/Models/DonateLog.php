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
        'created',
    ];

    protected $casts = [
        'created' => 'datetime',
    ];
}
