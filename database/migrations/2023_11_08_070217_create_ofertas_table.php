<?php

use App\Models\Institution;
use App\Models\Oferta;
use App\Models\School;
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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->integer('collection_id');
            $table->foreignIdFor(Institution::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(School::class)->nullable();
            $table->string('codigo_sistema_academico')->nullable();
            $table->string('turma')->nullable();
            $table->string('nome_curso')->nullable();
            $table->string('ano_letivo')->nullable();
            $table->string('periodo_letivo')->nullable();
            $table->timestamps();
        });

        Schema::create('oferta_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Oferta::class)->constrained()->onDelete('cascade');
            $table->string('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
        Schema::dropIfExists('oferta_images');
    }
};
