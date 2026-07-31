<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key'   => $key],
                ['value' => $value]
            );
        }
    }
}
