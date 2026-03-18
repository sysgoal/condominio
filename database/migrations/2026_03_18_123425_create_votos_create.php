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
        Schema::create('votos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enquete_opcao_id')->constrained('enquete_opcaos')->onDelete('cascade');
            $table->timestamps();
            
            // Impede que o mesmo usuário vote duas vezes na mesma opção
            $table->unique(['user_id', 'enquete_opcao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votos_create');
    }
};
