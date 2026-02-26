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
            $table->foreignId('termo_condicao_id')->nullable()->after('descricao_personalizada')->constrained('termos_condicoes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionario_ofertas', function (Blueprint $table) {
            $table->dropForeign(['termo_condicao_id']);
            $table->dropColumn('termo_condicao_id');
        });
    }
};
