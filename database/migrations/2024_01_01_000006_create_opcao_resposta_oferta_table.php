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
        Schema::create('opcao_resposta_oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pergunta_oferta_id')->constrained('pergunta_oferta')->onDelete('cascade');
            $table->string('opcao');
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opcao_resposta_oferta');
    }
};
