<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('programme_id')->constrained('programmes');
            $table->string('code');
            $table->string('nom');
            $table->integer('duree')->unsigned();
            $table->integer('prix');
            $table->foreignId('programme_id')->constrained('programmes')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
