<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etud_session', function (Blueprint $table) {
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->foreignId('session_id')->constrained('sessions');
            $table->date('date_paiement');
            $table->timestamps();

            $table->primary(['etudiant_id', 'session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('etud_session');
    }
};
