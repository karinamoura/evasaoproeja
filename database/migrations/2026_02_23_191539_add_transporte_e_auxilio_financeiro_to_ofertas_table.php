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
        Schema::table('ofertas', function (Blueprint $table) {
            $table->string('responsavel_transporte_estudante')->nullable()->after('periodo_letivo');
            $table->string('oferta_auxilio_financeiro')->nullable()->after('responsavel_transporte_estudante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ofertas', function (Blueprint $table) {
            $table->dropColumn(['responsavel_transporte_estudante', 'oferta_auxilio_financeiro']);
        });
    }
};
