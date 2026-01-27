<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// SAIDAS PROVISORIAS
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('saidas_provisorias', function (Blueprint $table) {
    $table->id();
    $table->foreignId('encontro_id')->constrained('encontros');
    $table->foreignId('produto_id')->constrained('produtos');
    $table->integer('quantidade');
    $table->date('data_saida');
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('saidas_provisorias');
    }
};