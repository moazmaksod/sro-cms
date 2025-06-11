<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    protected $fillable = [
        'jid',
        'site',
        'ip',
        'fingerprint',
        'expire',
    ];

    protected $casts = [
        'expire' => 'datetime',
    ];
}
