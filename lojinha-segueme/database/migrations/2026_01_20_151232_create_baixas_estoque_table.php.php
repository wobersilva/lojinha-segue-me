<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// BAIXAS DE ESTOQUE
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('baixas_estoque', function (Blueprint $table) {
    $table->id();
    $table->foreignId('encontro_id')->constrained('encontros');
    $table->foreignId('produto_id')->constrained('produtos');
    $table->integer('quantidade_vendida');
    $table->integer('quantidade_devolvida');
    $table->decimal('valor_total', 10, 2);
    $table->date('data_baixa');
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('baixas_estoque');
    }
};