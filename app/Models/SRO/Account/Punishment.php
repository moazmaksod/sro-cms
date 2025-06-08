<?php

namespace App\Models\SRO\Account;

use Illuminate\Database\Eloquent\Model;

class Punishment extends Model
{
    protected $connection = 'account'; // Passe ggf. an
    protected $table = '_Punishment';
    protected $primaryKey = 'SerialNo';
    public $timestamps = false;

    protected $fillable = [
        'UserJID', 'Type', 'Executor', 'Shard', 'CharName', 'CharInfo',
        'PosInfo', 'Guide', 'Description', 'RaiseTime',
        'BlockStartTime', 'BlockEndTime', 'Punishtime', 'Status'
    ];
}

