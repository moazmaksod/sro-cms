<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;
#
# This model represents blocked users in the SRO account database.
#
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

