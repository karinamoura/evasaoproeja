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
            $table->string('cor_personalizada', 7)->nullable()->after('descricao_personalizada')->comment('Cor em formato hexadecimal (ex: #667eea)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionario_ofertas', function (Blueprint $table) {
            $table->dropColumn('cor_personalizada');
        });
    }
};
