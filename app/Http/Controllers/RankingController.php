<?php

namespace App\Http\Controllers;

use App\Models\SRO\Log\LogChatMessage;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Models\SRO\Shard\Char;
use App\Models\SRO\Shard\CharSkillMastery;
use App\Models\SRO\Shard\CharTradeConflictJob;
use App\Models\SRO\Shard\CharTrijob;
use App\Models\SRO\Shard\Guild;
use App\Models\SRO\Shard\GuildMember;
use App\Models\SRO\Shard\TrainingCampHonorRank;
use App\Services\CrestService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('type') == 'player' && $request->filled('search')) {
            $data = Char::getPlayerRanking(25, 0, $request->search);
        }elseif ($request->input('type') == 'guild' && $request->filled('search')) {
            $data = Guild::getGuildRanking(25, 0, $request->search);
        }else {
            $data = Char::getPlayerRanking();
        }
        $config = config('global.ranking.menu');

        return view('ranking.index', [
            'data' => $data,
            'config' => $config,
            'type' => $request->input('type')
        ]);
    }

    public function player()
    {
        $data = Char::getPlayerRanking();
        return view('ranking.ranking.player', compact('data'));
    }

    public function guild()
    {
        $data = Guild::getGuildRanking();
        return view('ranking.ranking.guild', compact('data'));
    }

    public function unique()
    {
        $data = LogInstanceWorldInfo::getUniqueRanking();
        $uniqueList = config('global.ranking.uniques');

        return view('ranking.ranking.unique', [
            'data' => $data,
            'uniqueList' => $uniqueList,
        ]);
    }

    public function unique_monthly()
    {
        $data = LogInstanceWorldInfo::getUniqueRanking(25, 1);
        $uniqueList = config('global.ranking.uniques');

        return view('ranking.ranking.unique-monthly', [
            'data' => $data,
            'uniqueList' => $uniqueList,
        ]);
    }

    public function fortress_player()
    {
        $data = GuildMember::getFortressPlayerRanking();
        return view('ranking.ranking.fortress-player', compact('data'));
    }

    public function fortress_guild()
    {
        $data = Guild::getFortressGuildRanking();
        return view('ranking.ranking.fortress-guild', compact('data'));
    }

    public function honor()
    {
        $data = TrainingCampHonorRank::getHonorRanking();
        return view('ranking.ranking.honor', compact('data'));
    }

    public function job()
    {
        if (config('global.general.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking();
        } else {
            $data = CharTradeConflictJob::getJobRanking();
        }
        $config = config('global.ranking.job_menu');

        return view('ranking.ranking.job', [
            'data' => $data,
            'config' => $config
        ]);
    }

    public function job_all()
    {
        if (config('global.general.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking();
        } else {
            $data = CharTradeConflictJob::getJobRanking();
        }

        return view('ranking.ranking.job-all', compact('data'));
    }

    public function job_hunter()
    {
        if (config('global.general.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 3);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 1);
        }

        return view('ranking.ranking.job-hunter', compact('data'));
    }

    public function job_thieve()
    {
        if (config('global.general.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 2);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 2);
        }

        return view('ranking.ranking.job-thieve', compact('data'));
    }

    public function job_trader()
    {
        if (config('global.general.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 1);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 3);
        }

        return view('ranking.ranking.job-trader', compact('data'));
    }

    public function character_view($name, InventoryService $inventoryService)
    {
        $charID = Char::getCharIDByName($name);
        if ($charID > 0) {

            $data = Char::getPlayerRanking(1, $charID)->first();
            $build = CharSkillMastery::getCharBuildInfo($charID);

            $uniqueHistory = LogInstanceWorldInfo::getUniquesKill(5, $charID);
            $globalsHistory = LogChatMessage::getGlobalsHistory(5, $name);

            $inventorySet = $inventoryService->getInventorySet($charID, 13, 0);
            $inventoryAvatar = $inventoryService->getInventoryAvatar($charID);

            if (config('global.general.server.version') !== 'vSRO') {
                $inventoryJob = $inventoryService->getInventoryJob($charID);
            }

            if ($data) {
                return view('ranking.character.index', [
                    'data' => $data,
                    'build' => $build,
                    'uniqueHistory' => $uniqueHistory,
                    'globalsHistory' => $globalsHistory,
                    'inventorySet' => $inventorySet,
                    'inventoryAvatar' => $inventoryAvatar,
                    'inventoryJob' => $inventoryJob ?? null
                ]);
            }
        }
        return redirect()->back();
    }

    public function guild_view($name)
    {
        $guildID = Guild::getGuildIDByName($name);
        if ($guildID > 0) {

            $data = Guild::getGuildRanking(1, $guildID)->first();
            $members = GuildMember::getGuildInfoMembers($guildID);
            $alliances = Guild::getGuildInfoAlliance($guildID);

            if ($data) {
                return view('ranking.guild.index', [
                    'data' => $data,
                    'members' => $members,
                    'alliances' => $alliances,
                ]);
            }
        }

        return redirect()->back();
    }

    public function guild_crest($hex, CrestService $guildService)
    {
        if ($hex) {
            return $guildService->drawGuildIconToPNG($hex);
        }

        abort(404);
    }
}
