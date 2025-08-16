<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('votes')->updateOrInsert(
            ['title' => 'XtremeTop100'],
            [
                'url' => 'https://www.xtremetop100.com/in.php?site=SERVER_ID&postback={JID}',
                'site' => 'xtremetop100',
                'image' => 'https://www.xtremetop100.com/votenew.jpg',
                'ip' => '54.37.41.89',
                'param' => 'custom',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'GTop100'],
            [
                'url' => 'https://gtop100.com/Silkroad-Online/SERVER_ID?vote=1&pingUsername={JID}',
                'site' => 'gtop100',
                'image' => 'https://gtop100.com/images/votebutton.jpg',
                'ip' => '198.148.82.98, 198.148.82.99',
                'param' => 'pingUsername',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'TopG'],
            [
                'url' => 'https://topg.org/silkroad-private-servers/in-SERVER_ID-{JID}',
                'site' => 'topg',
                'image' => 'https://topg.org/topg.gif',
                'ip' => 'monitor.topg.org',
                'param' => 'p_resp',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'Top100 Arena'],
            [
                'url' => 'https://www.top100arena.com/listing/SERVER_ID/vote?incentive={JID}',
                'site' => 'top100arena',
                'image' => 'https://www.top100arena.com/hit/101410/medium',
                'ip' => '3.86.48.116',
                'param' => 'postback',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'Arena Top100'],
            [
                'url' => 'https://www.arena-top100.com/index.php?a=in&u=SERVER_ID&id={JID}',
                'site' => 'arena-top100',
                'image' => 'https://www.arena-top100.com/images/vote/silkroad-private-servers.png',
                'ip' => '184.154.46.76',
                'param' => 'userid',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'Silkroad Servers'],
            [
                'url' => 'https://silkroad-servers.com/index.php?a=in&u=SERVER_ID&id={JID}',
                'site' => 'silkroad-servers',
                'image' => 'https://silkroad-servers.com/images/button.png',
                'ip' => '116.203.217.217',
                'param' => 'userid',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'Private Servers'],
            [
                'url' => 'https://private-server.ws/index.php?a=in&u=SERVER_ID&id={JID}',
                'site' => 'private-server',
                'image' => 'https://private-server.ws/images/vote_button.jpg',
                'ip' => '116.203.234.215',
                'param' => 'userid',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );
    }
}
