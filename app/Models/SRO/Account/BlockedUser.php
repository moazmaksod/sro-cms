<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $connection = 'account';
    protected $table = '_BlockedUser';
    public $timestamps = false;
    protected $primaryKey = 'UserJID';
    public $incrementing = false;

    protected $fillable = [
        'UserJID', 'UserID', 'Type', 'SerialNo', 'timeBegin', 'timeEnd'
    ];
}

