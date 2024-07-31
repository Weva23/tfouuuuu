<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('contenus_formations')) {
            Schema::create('contenus_formations', function (Blueprint $table) {
                $table->id();
                $table->string('nomchap');
                $table->string('nomunite');
                $table->text('description')->nullable();
                $table->integer('nombreheures');
                $table->foreignId('formation_id')->constrained('formations');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contenus_formations');
    }
};
