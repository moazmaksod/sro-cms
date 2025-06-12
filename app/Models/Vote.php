<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'title',
        'url',
        'site',
        'image',
        'ip',
        'param',
        'reward',
        'timeout',
        'active',
    ];
}
