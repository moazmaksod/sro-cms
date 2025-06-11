<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('referrals')->updateOrInsert(
            ['code' => 'GOOGLE'],
            ['name' => 'Google',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'YOUTUBE'],
            ['name' => 'Youtube',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'FACEBOOK'],
            ['name' => 'Facebook',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'DISCORD'],
            ['name' => 'Discord',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'ELITEPVPERS'],
            ['name' => 'Elitepvpers',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'SILKROAD4ARAB'],
            ['name' => 'Silkroad4Arab',]
        );

        DB::table('referrals')->updateOrInsert(
            ['code' => 'SROCAVE'],
            ['name' => 'SroCave',]
        );

    }
}
