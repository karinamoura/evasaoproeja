<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perguntas', function (Blueprint $table) {
            $table->foreignId('secao_id')->nullable()->after('questionario_id')->constrained('secoes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('perguntas', function (Blueprint $table) {
            $table->dropForeign(['secao_id']);
            $table->dropColumn('secao_id');
        });
    }
};


