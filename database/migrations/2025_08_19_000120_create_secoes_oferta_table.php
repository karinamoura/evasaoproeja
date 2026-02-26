<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secoes_oferta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_oferta_id')->constrained('questionario_ofertas')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secoes_oferta');
    }
};


