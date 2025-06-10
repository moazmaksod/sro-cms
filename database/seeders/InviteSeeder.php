<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invites')->updateOrInsert(
            ['code' => 'GOOGLE'],
            ['name' => 'Google',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'YOUTUBE'],
            ['name' => 'Youtube',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'FACEBOOK'],
            ['name' => 'Facebook',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'DISCORD'],
            ['name' => 'Discord',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'ELITEPVPERS'],
            ['name' => 'Elitepvpers',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'SILKROAD4ARAB'],
            ['name' => 'Silkroad4Arab',]
        );

        DB::table('invites')->updateOrInsert(
            ['code' => 'SROCAVE'],
            ['name' => 'SroCave',]
        );

    }
}
