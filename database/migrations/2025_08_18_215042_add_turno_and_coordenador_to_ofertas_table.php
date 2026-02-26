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
            $table->enum('turno', ['Matutino', 'Vespertino', 'Noturno'])->after('school_id');
            $table->foreignId('coordenador_id')->nullable()->after('turno')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ofertas', function (Blueprint $table) {
            $table->dropForeign(['coordenador_id']);
            $table->dropColumn(['turno', 'coordenador_id']);
        });
    }
};
