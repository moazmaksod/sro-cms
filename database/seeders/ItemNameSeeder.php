<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = [
            'database/seeders/_ItemNameDesc.sql',
            'database/seeders/_ItemNameDesc_data.sql',
        ];

        foreach ($files as $file) {
            DB::connection('account')->unprepared(file_get_contents($file));
        }
    }
}
