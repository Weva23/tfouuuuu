<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table n'existe pas avant de la créer
        if (!Schema::hasTable('plages')) {
            Schema::create('plages', function (Blueprint $table) {
                $table->id();
                $table->string('lundi')->nullable();
                $table->string('mardi')->nullable();
                $table->string('mercredi')->nullable();
                $table->string('jeudi')->nullable();
                $table->string('vendredi')->nullable();
                $table->string('samedi')->nullable();
                $table->string('dimanche')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plages');
    }
};
