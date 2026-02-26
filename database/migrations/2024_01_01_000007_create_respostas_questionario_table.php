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
        Schema::create('respostas_questionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_oferta_id')->constrained('questionario_ofertas')->onDelete('cascade');
            $table->string('identificador_respondente')->nullable(); // Email, CPF, etc.
            $table->timestamp('data_resposta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respostas_questionario');
    }
};
