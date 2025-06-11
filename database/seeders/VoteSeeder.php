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
            ['title' => 'silkroad-servers.com'],
            [
                'url' => 'https://silkroad-servers.com/index.php?a=in&u=SERVER_ID&id={JID}',
                'ip' => '116.203.217.217',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );

        DB::table('votes')->updateOrInsert(
            ['title' => 'xtremetop100.com'],
            [
                'url' => 'https://www.xtremetop100.com/in.php?site=SERVER_ID&postback={JID}',
                'ip' => '199.59.161.214',
                'reward' => 5,
                'timeout' => 12,
                'active' => 0,
            ]
        );
    }
}
