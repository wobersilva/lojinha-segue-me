<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// PRODUTO
// -------------------------------
class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao', 'tamanho', 'preco_custo', 'preco_venda', 'status'
    ];

    // =============================================
    // SCOPES - Filtros reutilizáveis
    // =============================================

    /**
     * Filtra apenas produtos ativos
     */
    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Filtra apenas produtos inativos
     */
    public function scopeInativo(Builder $query): Builder
    {
        return $query->where('status', 'inativo');
    }

    /**
     * Filtra produtos com estoque disponível
     */
    public function scopeComEstoque(Builder $query): Builder
    {
        return $query->whereHas('estoque', function ($q) {
            $q->where('quantidade', '>', 0);
        });
    }

    /**
     * Filtra produtos com estoque baixo (menos de X unidades)
     */
    public function scopeEstoqueBaixo(Builder $query, int $limite = 10): Builder
    {
        return $query->whereHas('estoque', function ($q) use ($limite) {
            $q->where('quantidade', '<', $limite);
        });
    }

    // =============================================
    // RELACIONAMENTOS
    // =============================================

    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoEstoque::class);
    }

    public function entradas()
    {
        return $this->hasMany(EntradaEstoque::class);
    }

    public function saidasProvisorias()
    {
        return $this->hasMany(SaidaProvisoria::class);
    }

    public function baixas()
    {
        return $this->hasMany(BaixaEstoque::class);
    }
}
