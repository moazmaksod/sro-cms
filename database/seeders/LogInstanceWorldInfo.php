<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogInstanceWorldInfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = [
            'database/seeders/_LogInstanceWorldInfo.sql',
            'database/seeders/_AddLogInstanceWorldInfo.sql',
        ];

        foreach ($files as $file) {
            DB::connection('log')->unprepared(file_get_contents($file));
        }
    }
}
