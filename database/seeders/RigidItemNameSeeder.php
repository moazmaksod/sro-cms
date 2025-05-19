<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RigidItemNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('account')->unprepared(
            file_get_contents('database/seeders/_Rigid_ItemNameDesc.sql')
        );
    }
}
