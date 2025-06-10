<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SRO\Log\LogInstanceWorldInfo;
use App\Models\SRO\Shard\Char;
use App\Models\SRO\Shard\Guild;
use App\Models\SRO\Shard\GuildMember;

class RankingController extends Controller
{
    public function player()
    {
        $data = Char::getPlayerRanking();
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function guild()
    {
        $data = Guild::getGuildRanking();
        $topImage = config('ranking.top_image');

        return response()->json([
            'status' => 'success',
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

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'uniqueList' => $uniqueList,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function level()
    {
        $data = Char::getLevelRanking();
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function fortress_player()
    {
        $data = GuildMember::getFortressPlayerRanking();
        $topImage = config('ranking.top_image');
        $characterRace = config('ranking.character_race');

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'topImage' => $topImage,
            'characterRace' => $characterRace,
        ]);
    }

    public function fortress_guild()
    {
        $data = Guild::getFortressGuildRanking();
        $topImage = config('ranking.top_image');

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'topImage' => $topImage,
        ]);
    }
}
