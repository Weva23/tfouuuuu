<?php

namespace Database\Seeders;

use App\Models\Etudiant;
use App\Models\Formations;
use App\Models\Professeur;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@supnum.mr',
            'password' => ('secret')
        ]);
        $this->call([
            Etudiant::class,
            Professeur::class,
            Formations::class

        ]);
    }
}
