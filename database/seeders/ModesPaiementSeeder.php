<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModesPaiementSeeder extends Seeder
{
    public function run()
    {
        $modes = [
            ['nom' => 'Cash'],
            ['nom' => 'Bankily'],
            ['nom' => 'Sedad'],
            ['nom' => 'Bimbank'],
        ];

        DB::table('modes_paiement')->insert($modes);
    }
}