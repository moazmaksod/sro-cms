<?php

namespace App\Models\SRO\vPlus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class GameSecurityLog
{
    /**
     * Returns the number of unique characters logged in today.
     *
     * @return int
     */
    public static function getTodayActiveCharCount(): int
    {
        return Cache::remember('vplus_active_chars_today', now()->addMinutes(5), function () {
            try {
                $result = DB::connection('filter')
                    ->selectOne("
                        SELECT COUNT(DISTINCT CharName16) AS UniqueCharCount
                        FROM _GameSecurityLogs
                        WHERE CAST(LoginTime AS DATE) = CAST(GETDATE() AS DATE)
                    ");

                return $result->UniqueCharCount ?? 0;
            } catch (\Exception $e) {
                \Log::error("vPlus GameSecurityLog Error: " . $e->getMessage());
                return 0;
            }
        });
    }

    /**
     * Returns the latest login time for a given UserJID.
     *
     * @param int $portalJID
     * @return string|null
     */
    public static function getLastLogin(int $userJID): ?string
    {
        try {
            return DB::connection('filter')
                ->table('_GameSecurityLogs')
                ->where('UserJID', $userJID)
                ->orderByDesc('LoginTime')
                ->limit(1)
                ->value('LoginTime');
        } catch (\Exception $e) {
            \Log::error("Error fetching last login for JID $userJID: " . $e->getMessage());
            return null;
        }
    }
}

