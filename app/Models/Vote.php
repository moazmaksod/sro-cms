<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'title',
        'url',
        'ip',
        'reward',
        'timeout',
        'active',
    ];
}
