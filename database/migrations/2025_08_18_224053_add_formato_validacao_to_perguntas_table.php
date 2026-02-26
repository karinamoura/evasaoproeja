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
        Schema::table('perguntas', function (Blueprint $table) {
            $table->enum('formato_validacao', ['texto_comum', 'data', 'cpf', 'telefone', 'email'])->default('texto_comum')->after('obrigatoria')->comment('Formato de validação para campos de texto simples');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perguntas', function (Blueprint $table) {
            $table->dropColumn('formato_validacao');
        });
    }
};
