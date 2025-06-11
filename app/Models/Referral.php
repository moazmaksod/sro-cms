<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'code',
        'name',
        'ip',
        'fingerprint',
        'jid',
        'invited_jid',
        'points',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'jid', 'jid');
    }

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_jid', 'jid');
    }
}
