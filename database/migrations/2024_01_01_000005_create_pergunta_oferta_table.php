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
        Schema::create('pergunta_oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_oferta_id')->constrained('questionario_ofertas')->onDelete('cascade');
            $table->string('pergunta');
            $table->enum('tipo', ['texto_simples', 'texto_longo', 'radio', 'checkbox', 'select']);
            $table->boolean('obrigatoria')->default(false);
            $table->integer('ordem')->default(0);
            $table->boolean('personalizada')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pergunta_oferta');
    }
};
