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
        Schema::table('questionario_ofertas', function (Blueprint $table) {
            // Remover a foreign key incorreta
            $table->dropForeign(['pergunta_identificadora_id']);

            // Recriar a foreign key apontando para a tabela correta
            $table->foreign('pergunta_identificadora_id')->references('id')->on('pergunta_oferta')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionario_ofertas', function (Blueprint $table) {
            // Remover a foreign key corrigida
            $table->dropForeign(['pergunta_identificadora_id']);

            // Recriar a foreign key original (incorreta)
            $table->foreign('pergunta_identificadora_id')->references('id')->on('perguntas')->onDelete('set null');
        });
    }
};
