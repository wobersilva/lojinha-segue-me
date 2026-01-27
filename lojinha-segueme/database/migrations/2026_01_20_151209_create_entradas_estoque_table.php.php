<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// ENTRADAS DE ESTOQUE
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('entradas_estoque', function (Blueprint $table) {
    $table->id();
    $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
    $table->date('data_entrada');
    $table->integer('quantidade');
    $table->decimal('valor_custo', 10, 2);
    $table->text('observacoes')->nullable();
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('entradas_estoque');
    }
};
