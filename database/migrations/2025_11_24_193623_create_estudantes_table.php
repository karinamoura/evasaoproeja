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
        Schema::create('estudantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf', 14)->unique();
            $table->date('data_nascimento')->nullable();
            $table->string('matricula')->nullable();
            $table->string('nome_mae')->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('oferta_id')->constrained('ofertas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudantes');
    }
};
