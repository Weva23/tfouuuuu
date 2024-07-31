<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('paiements')) {
            Schema::create('paiements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
                $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
                $table->foreignId('mode_paiement_id')->constrained('modes_paiement');
                $table->integer('prix_reel');
                $table->integer('montant_paye');
                $table->integer('note_test');  
                $table->date('date_paiement');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
