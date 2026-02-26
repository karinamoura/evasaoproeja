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
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('professor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('oferta_id')->constrained('ofertas')->onDelete('cascade');
            $table->string('periodo'); // Ex: 2025.1, 2025.2, etc
            $table->integer('carga_horaria_total'); // h/aula
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinas');
    }
};
