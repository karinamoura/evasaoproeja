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
        Schema::create('questionario_ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_id')->constrained('questionarios')->onDelete('cascade');
            $table->foreignId('oferta_id')->constrained('ofertas')->onDelete('cascade');
            $table->string('titulo_personalizado')->nullable();
            $table->text('descricao_personalizada')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('url_publica')->unique();
            $table->timestamps();

            $table->unique(['questionario_id', 'oferta_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionario_ofertas');
    }
};
