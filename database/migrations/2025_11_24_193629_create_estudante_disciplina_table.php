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
        Schema::create('estudante_disciplina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained('estudantes')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['estudante_id', 'disciplina_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudante_disciplina');
    }
};
