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
        Schema::create('respostas_individual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_questionario_id')->constrained('respostas_questionario')->onDelete('cascade');
            $table->foreignId('pergunta_oferta_id')->constrained('pergunta_oferta')->onDelete('cascade');
            $table->text('resposta_texto')->nullable();
            $table->json('resposta_multipla')->nullable(); // Para checkbox
            $table->string('resposta_unica')->nullable(); // Para radio e select
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas_individual');
    }
};
