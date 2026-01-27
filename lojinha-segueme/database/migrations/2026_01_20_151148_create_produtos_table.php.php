<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// PRODUTOS
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('produtos', function (Blueprint $table) {
    $table->id();
    $table->string('descricao');
    $table->string('tamanho');
    $table->decimal('preco_custo', 10, 2);
    $table->decimal('preco_venda', 10, 2);
    $table->enum('status', ['ativo','inativo'])->default('ativo');
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('produtos');
    }
};