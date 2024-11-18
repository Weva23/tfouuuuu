<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('nni', 10)->unique();
            $table->string('nomprenom');
            $table->string('diplome')->nullable();
            $table->string('genre');
            $table->string('lieunaissance')->nullable();
            $table->string('adress')->nullable();
            $table->date('datenaissance')->nullable();
            $table->date('dateninscrip');
            $table->string('email', 191)->unique()->nullable();
            $table->integer('phone')->unique();
            $table->integer('wtsp')->unique()->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etudiants');
    }
};
