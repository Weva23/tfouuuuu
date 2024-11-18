<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('programmes')) { // Vérifie si la table n'existe pas
            Schema::create('programmes', function (Blueprint $table) {
                $table->id();
                $table->string('code', 191)->unique(); // Limiter la longueur à 191 caractères pour l'index
                $table->string('nom');
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('programmes')) { // Vérifie si la table existe avant de la supprimer
            Schema::dropIfExists('programmes');
        }
    }
}

