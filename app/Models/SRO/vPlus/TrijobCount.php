<?php

namespace App\Models\SRO\vPlus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrijobCount extends Model
{
    protected $connection = 'filter';
    protected $table = '_panel_chart_trijobcount';
    public $timestamps = false;

    public static function getLatestCounts(): object
    {
        return Cache::remember('trijob_latest_counts', now()->addMinutes(5), function () {
            try {
                $result = DB::connection('filter')
                    ->table('_panel_chart_trijobcount')
                    ->select('TradersCount', 'ThievesCount', 'HuntersCount')
                    ->orderByDesc('Day')
                    ->first();

                return $result ?? (object)[
                    'TradersCount' => 0,
                    'ThievesCount' => 0,
                    'HuntersCount' => 0
                ];
            } catch (\Exception $e) {
                Log::error('Error fetching Trijob statistics: ' . $e->getMessage());
                return (object)[
                    'TradersCount' => 0,
                    'ThievesCount' => 0,
                    'HuntersCount' => 0
                ];
            }
        });
    }
}

