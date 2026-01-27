<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// -------------------------------
// ÍNDICES DE PERFORMANCE
// Adiciona índices em colunas frequentemente
// usadas em WHERE, JOIN e ORDER BY
// -------------------------------
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Índices para tabela PRODUTOS
        Schema::table('produtos', function (Blueprint $table) {
            $table->index('status', 'idx_produtos_status');
        });

        // Índices para tabela PAROQUIAS
        Schema::table('paroquias', function (Blueprint $table) {
            $table->index('status', 'idx_paroquias_status');
        });

        // Índices para tabela ENCONTROS
        Schema::table('encontros', function (Blueprint $table) {
            $table->index('status', 'idx_encontros_status');
            $table->index('data_inicio', 'idx_encontros_data_inicio');
            // Índice composto para queries que filtram por paróquia e status
            $table->index(['paroquia_id', 'status'], 'idx_encontros_paroquia_status');
        });

        // Índices para tabela BAIXAS_ESTOQUE
        Schema::table('baixas_estoque', function (Blueprint $table) {
            $table->index('data_baixa', 'idx_baixas_data_baixa');
            // Índice composto para queries de relatórios
            $table->index(['encontro_id', 'produto_id'], 'idx_baixas_encontro_produto');
        });

        // Índices para tabela MOVIMENTACOES_ESTOQUE
        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            $table->index('tipo', 'idx_movimentacoes_tipo');
            $table->index('data_movimentacao', 'idx_movimentacoes_data');
            // Índice composto para filtros por produto e tipo
            $table->index(['produto_id', 'tipo'], 'idx_movimentacoes_produto_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropIndex('idx_produtos_status');
        });

        Schema::table('paroquias', function (Blueprint $table) {
            $table->dropIndex('idx_paroquias_status');
        });

        Schema::table('encontros', function (Blueprint $table) {
            $table->dropIndex('idx_encontros_status');
            $table->dropIndex('idx_encontros_data_inicio');
            $table->dropIndex('idx_encontros_paroquia_status');
        });

        Schema::table('baixas_estoque', function (Blueprint $table) {
            $table->dropIndex('idx_baixas_data_baixa');
            $table->dropIndex('idx_baixas_encontro_produto');
        });

        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            $table->dropIndex('idx_movimentacoes_tipo');
            $table->dropIndex('idx_movimentacoes_data');
            $table->dropIndex('idx_movimentacoes_produto_tipo');
        });
    }
};
