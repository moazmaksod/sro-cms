<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

$menu = config('ranking.menu');
$menu['ranking-level'] = [ //ranking-pvp
    'enabled' => false, //true or false
    'name' => 'Level Ranking', //Pvp Ranking
    'image' => 'fa fa-users',
    'route' => ['name' => 'ranking.custom', 'params' => ['type' => 'levelRanking']], //'type' => 'pvpRanking'
];
config(['ranking.menu' => $menu]);

if (!function_exists('levelRanking')) { //pvpRanking
    function levelRanking() { //pvpRanking()
        return Cache::remember('levelRanking', 600, function () { //'pvpRanking', 600 (600 sec = 10 min, and replace the sql query)
            $data = DB::connection('shard')->select(
                "SELECT TOP(25) Charname16 AS Name, CurLevel AS Level, ExpOffset AS Exp
                 FROM SILKROAD_R_SHARD.._Char
                 WHERE CharID > 0
                 ORDER BY CurLevel DESC, ExpOffset DESC"
            );
            return collect($data);
        });
    }
}
