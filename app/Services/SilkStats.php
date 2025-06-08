<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SRO\Portal\AphChangedSilk;

class SilkStats
{
    public static function getStats(): array
    {
        $serverVersion = Config::get('global.server.version', 'vSRO');
        $totalSilk = 0;
        $currentAvailableSilk = 0;

        if ($serverVersion === 'vSRO') {
            try {
                $silkResult = DB::connection('account')->selectOne("
                    SELECT 
                        (SELECT SUM(silk_own) FROM dbo.SK_Silk) + 
                        (SELECT SUM(silk_own) FROM dbo.SK_PackageItemSaleLog) AS total_silk
                ");
                $totalSilk = $silkResult->total_silk ?? 0;
            } catch (\Exception $e) {
                Log::error("Error fetching total silk: " . $e->getMessage());
            }

            try {
                $currentSilkResult = DB::connection('account')->selectOne("
                    SELECT SUM(silk_own) AS current_silk FROM dbo.SK_Silk
                ");
                $currentAvailableSilk = $currentSilkResult->current_silk ?? 0;
            } catch (\Exception $e) {
                Log::error("Error fetching current silk: " . $e->getMessage());
            }
        } else {
            try {
                $totalSilk = AphChangedSilk::getSilkSum();
            } catch (\Exception $e) {
                Log::error("Error fetching total silk for non-vSRO: " . $e->getMessage());
            }
        }

        return [
            'totalSilk' => $totalSilk,
            'currentAvailableSilk' => $currentAvailableSilk,
        ];
    }
}

