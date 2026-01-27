<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// PAROQUIAS
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('paroquias', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->string('cidade')->nullable();
    $table->string('responsavel')->nullable();
    $table->string('contato')->nullable();
    $table->enum('status', ['ativa','inativa'])->default('ativa');
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('paroquias');
    }
};
