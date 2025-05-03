<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogChatMessage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $files = [
            'database/seeders/_LogChatMessage.sql',
            //'database/seeders/_InsertLogChatMessage.sql',
        ];

        foreach ($files as $file) {
            DB::connection('log')->unprepared(file_get_contents($file));
        }
    }
}
