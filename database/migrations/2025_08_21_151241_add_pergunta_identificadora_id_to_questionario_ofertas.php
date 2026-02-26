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
            $table->unsignedBigInteger('pergunta_identificadora_id')->nullable()->after('url_publica');
            $table->foreign('pergunta_identificadora_id')->references('id')->on('perguntas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionario_ofertas', function (Blueprint $table) {
            $table->dropForeign(['pergunta_identificadora_id']);
            $table->dropColumn('pergunta_identificadora_id');
        });
    }
};
