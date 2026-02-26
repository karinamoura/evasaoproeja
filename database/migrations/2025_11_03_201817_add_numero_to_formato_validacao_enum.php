<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Atualizar enum na tabela perguntas
        DB::statement("ALTER TABLE perguntas MODIFY COLUMN formato_validacao ENUM('texto_comum', 'data', 'cpf', 'telefone', 'email', 'numero') DEFAULT 'texto_comum'");

        // Atualizar enum na tabela pergunta_oferta
        DB::statement("ALTER TABLE pergunta_oferta MODIFY COLUMN formato_validacao ENUM('texto_comum', 'data', 'cpf', 'telefone', 'email', 'numero') DEFAULT 'texto_comum'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover 'numero' do enum na tabela perguntas
        DB::statement("ALTER TABLE perguntas MODIFY COLUMN formato_validacao ENUM('texto_comum', 'data', 'cpf', 'telefone', 'email') DEFAULT 'texto_comum'");

        // Remover 'numero' do enum na tabela pergunta_oferta
        DB::statement("ALTER TABLE pergunta_oferta MODIFY COLUMN formato_validacao ENUM('texto_comum', 'data', 'cpf', 'telefone', 'email') DEFAULT 'texto_comum'");
    }
};
