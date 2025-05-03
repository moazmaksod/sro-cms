<?php

namespace App\Models\SRO\Log;

use App\Models\SRO\Shard\Items;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LogChatMessage extends Model
{
    use HasFactory;

    /**
     * The Database connection name for the model.
     *
     * @var string
     */
    protected $connection = 'log';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo._LogChatMessage';

    public static function getGlobalsHistory($limit = 25, $CharName = null)
    {
        $globals_history =  Cache::remember("globals_history_{$limit}_{$CharName}", now()->addMinutes(config('global.general.cache.data.globals_history')), function () use ($CharName, $limit) {
            return self::select(['_Char.CharID', '_Char.RefObjID', 'CharName', 'EventTime', 'Comment'])
                ->leftJoin(DB::raw('SILKROAD_R_SHARD.._Char'), function ($join) {
                    $join->on(DB::raw('_Char.CharName16 COLLATE Latin1_General_CI_AS'), '=', DB::raw('_LogChatMessage.CharName COLLATE Latin1_General_CI_AS'));
                })
                ->where('TargetName', '[YELL]')
                ->when(!is_null($CharName), function ($query) use ($CharName) {
                    $query->where('CharName', $CharName);
                })
                ->orderByDesc('EventTime')
                ->limit($limit)
                ->get();
        });

        foreach ($globals_history as $value) {
            preg_match_all('/\d{19}/', $value->Comment, $matches);
            $serials = $matches[0] ?? [];

            if (!empty($serials)) {
                $items = Items::getItemNameBySerial($serials);

                foreach ($serials as $serial) {
                    if (isset($items[$serial])) {
                        //$itemName = "<img src='".asset("/images/sro/".$items[$serial]['IconPath'].".png")."' alt='' width='32' height='32'><u><span><</span>".$items[$serial]['ItemName']."<span>>[+".$items[$serial]['OptLevel']."]</span></u>";
                        $itemName = "<u><span><</span>".$items[$serial]['ItemName']."<span>>[+".$items[$serial]['OptLevel']."]</span></u>";
                        $value->Comment = str_replace($serial, $itemName, $value->Comment);
                    }else {
                        $value->Comment = str_replace($serial, '<u><span><</span>Unknown<span>></span></u>', $value->Comment);
                    }
                }
            }
        }

        return $globals_history;
    }
}
