<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// ENCONTROS
// -------------------------------
return new class extends Migration {
    public function up(): void {
    Schema::create('encontros', function (Blueprint $table) {
    $table->id();
    $table->foreignId('paroquia_id')->constrained('paroquias')->cascadeOnDelete();
    $table->string('nome');
    $table->date('data_inicio');
    $table->date('data_fim')->nullable();
    $table->enum('status', ['aberto','fechado'])->default('aberto');
    $table->timestamps();
    });
    }
    public function down(): void {
    Schema::dropIfExists('encontros');
    }
};
