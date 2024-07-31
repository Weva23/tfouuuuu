<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesseursTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('professeurs')) {
            Schema::create('professeurs', function (Blueprint $table) {
                $table->id();
                $table->string('image')->nullable();
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
                $table->foreignId('type_id')->constrained('typeymntprofs');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('professeurs');
    }
}
