<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pergunta_oferta', function (Blueprint $table) {
            $table->foreignId('secao_oferta_id')->nullable()->after('questionario_oferta_id')->constrained('secoes_oferta')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pergunta_oferta', function (Blueprint $table) {
            $table->dropForeign(['secao_oferta_id']);
            $table->dropColumn('secao_oferta_id');
        });
    }
};


