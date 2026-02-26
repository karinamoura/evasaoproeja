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
        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained('estudantes')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->date('data_aula');
            $table->integer('hora_aula'); // Quantidade de horas/aula registradas
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['estudante_id', 'disciplina_id', 'data_aula']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frequencias');
    }
};
