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
        $config = config('ranking.menu');
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.index', [
            'data' => $data,
            'config' => $config,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
            'type' => $request->input('type'),
        ]);
    }

    public function player()
    {
        $data = Char::getPlayerRanking();
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.player', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function guild()
    {
        $data = Guild::getGuildRanking();
        $topImage = config('ranking.top_image');

        return view('ranking.ranking.guild', [
            'data' => $data,
            'topImage' => $topImage,
        ]);
    }

    public function unique()
    {
        $data = LogInstanceWorldInfo::getUniqueRanking();
        $uniqueList = config('ranking.uniques');
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.unique', [
            'data' => $data,
            'uniqueList' => $uniqueList,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function unique_monthly()
    {
        $data = LogInstanceWorldInfo::getUniqueRanking(25, 1);
        $uniqueList = config('ranking.uniques');
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.unique-monthly', [
            'data' => $data,
            'uniqueList' => $uniqueList,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function fortress_player()
    {
        $data = GuildMember::getFortressPlayerRanking();
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.fortress-player', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function fortress_guild()
    {
        $data = Guild::getFortressGuildRanking();
        $topImage = config('ranking.top_image');

        return view('ranking.ranking.fortress-guild', [
            'data' => $data,
            'topImage' => $topImage,
        ]);
    }

    public function honor()
    {
        $data = TrainingCampHonorRank::getHonorRanking();
        $honorLevel = config('ranking.honor_level');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.honor', [
            'data' => $data,
            'honorLevel' => $honorLevel,
            'characterRace' => $characterRace,
        ]);
    }

    public function job()
    {
        if (config('global.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking();
        } else {
            $data = CharTradeConflictJob::getJobRanking();
        }
        $config = config('ranking.job_menu');
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');
        $jobType = config('ranking.job_type');

        return view('ranking.ranking.job', [
            'data' => $data,
            'config' => $config,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
            'jobType' => $jobType,
        ]);
    }

    public function job_all()
    {
        if (config('global.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking();
        } else {
            $data = CharTradeConflictJob::getJobRanking();
        }
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');
        $jobType = config('ranking.job_type');

        return view('ranking.ranking.job-all', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
            'jobType' => $jobType,
        ]);
    }

    public function job_hunter()
    {
        if (config('global.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 3);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 1);
        }
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.job-hunter', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function job_thieve()
    {
        if (config('global.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 2);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 2);
        }
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.job-thieve', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function job_trader()
    {
        if (config('global.server.version') === 'vSRO') {
            $data = CharTrijob::getJobRanking(25, 1);
        } else {
            $data = CharTradeConflictJob::getJobRanking(25, 3);
        }
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return view('ranking.ranking.job-trader', [
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function character_view($name, InventoryService $inventoryService)
    {
        $charID = Char::getCharIDByName($name);
        if ($charID > 0) {

            $data = Char::getPlayerRanking(1, $charID)->first();
            $build = CharSkillMastery::getCharBuildInfo($charID);

            $uniqueHistory = LogInstanceWorldInfo::getUniquesKill(5, $charID);
            $globalsHistory = LogChatMessage::getGlobalsHistory(5, $name);

            $inventorySet = $inventoryService->getInventorySet($charID);
            $inventoryAvatar = $inventoryService->getInventoryAvatar($charID);

            if (config('global.server.version') !== 'vSRO') {
                $inventoryJob = $inventoryService->getInventoryJob($charID);
            }

            $uniqueList = config('ranking.uniques');
            $characterImage = config('ranking.character_image');
            $skillMastery = config('ranking.skill_mastery');
            $jobType = config('ranking.job_type');
            $characterRace = config('ranking.character_race');
            $hwanLevel = config('ranking.hwan_level');

            if ($data) {
                return view('ranking.character.index', [
                    'data' => $data,
                    'build' => $build,
                    'uniqueHistory' => $uniqueHistory,
                    'globalsHistory' => $globalsHistory,
                    'inventorySet' => $inventorySet,
                    'inventoryAvatar' => $inventoryAvatar,
                    'inventoryJob' => $inventoryJob ?? null,
                    'uniqueList' => $uniqueList,
                    'characterImage' => $characterImage,
                    'skillMastery' => $skillMastery,
                    'jobType' => $jobType,
                    'characterRace' => $characterRace,
                    'hwanLevel' => $hwanLevel,
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

            $characterRace = config('ranking.character_race');
            $guildAuthority = config('ranking.guild_authority');

            if ($data) {
                return view('ranking.guild.index', [
                    'data' => $data,
                    'members' => $members,
                    'alliances' => $alliances,
                    'characterRace' => $characterRace,
                    'guildAuthority' => $guildAuthority,
                ]);
            }
        }

        return redirect()->back();
    }

    public function guild_crest($hex)
    {
        if (!preg_match('/^[a-fA-F0-9]+$/', $hex)) {
            abort(400, 'Invalid crest data.');
        }

        $img = CrestService::generateGuildCrest($hex);

        return response()->stream(function () use ($img) {
            header('Content-Type: image/png');
            imagepng($img);
            imagedestroy($img);
        });
    }
}
