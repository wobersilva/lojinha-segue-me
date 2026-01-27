<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

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
