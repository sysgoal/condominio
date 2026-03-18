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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['regimento', 'convencao', 'ata']);
            $table->string('titulo');
            $table->longText('conteudo_texto')->nullable(); // Utilizado para busca textual
            $table->string('arquivo_path')->nullable(); 
            $table->date('data_documento');
            $table->timestamps();
            
            // Índice para otimizar buscas textuais em bancos de dados suportados (MySQL/PostgreSQL)
            $table->fullText('conteudo_texto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_create');
    }
};
