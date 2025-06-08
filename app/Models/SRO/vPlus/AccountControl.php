<?php

namespace App\Models\SRO\vPlus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountControl
{
    /**
     * Triggert die Prozedur _Account_Disconnect mit JID und optionalem Grund.
     *
     * @param int $jid
     * @param string $reason
     * @return bool
     */
    public static function disconnectUser(int $jid, string $reason = ''): bool
    {
        try {
            DB::connection('filter')->statement('EXEC _Account_Disconnect ?, ?', [
                $jid,
                $reason,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Account disconnect failed for JID {$jid}: " . $e->getMessage());
            return false;
        }
    }
}

