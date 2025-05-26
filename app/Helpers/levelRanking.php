<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

$menu = config('ranking.menu');
$menu['ranking-level'] = [
    'enabled' => true,
    'name' => 'Level Ranking',
    'image' => 'fa fa-users',
    'route' => ['name' => 'ranking.custom', 'params' => ['type' => 'level']],
];
config(['ranking.menu' => $menu]);

if (!function_exists('levelRanking')) {
    function levelRanking()
    {
        return Cache::remember('levelRanking', 600, function () {
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
