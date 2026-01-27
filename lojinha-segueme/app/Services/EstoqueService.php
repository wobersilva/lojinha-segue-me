<?php
// =====================================================
// SERVICE DE ESTOQUE
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Services;

use App\Models\Produto;
use App\Models\Estoque;
use App\Models\EntradaEstoque;
use App\Models\SaidaProvisoria;
use App\Models\BaixaEstoque;
use Illuminate\Support\Facades\DB;
use Exception;

class EstoqueService
{
    /**
     * Registrar entrada de produtos (COMPRA)
     */
    public function entrada(array $dados): EntradaEstoque
    {
        return DB::transaction(function () use ($dados) {

            $entrada = EntradaEstoque::create($dados);

            $estoque = Estoque::firstOrCreate(
                ['produto_id' => $dados['produto_id']],
                ['quantidade' => 0]
            );

            $estoque->increment('quantidade', $dados['quantidade']);

            return $entrada;
        });
    }

    /**
     * Registrar saída provisória para encontro
     */
    public function saidaProvisoria(array $dados): SaidaProvisoria
    {
        return DB::transaction(function () use ($dados) {

            $estoque = Estoque::where('produto_id', $dados['produto_id'])->firstOrFail();

            if ($estoque->quantidade < $dados['quantidade']) {
                throw new Exception('Estoque insuficiente para saída provisória');
            }

            $estoque->decrement('quantidade', $dados['quantidade']);

            return SaidaProvisoria::create($dados);
        });
    }

    /**
     * Baixa definitiva após encontro
     */
    public function baixaDefinitiva(array $dados): BaixaEstoque
    {
        return DB::transaction(function () use ($dados) {

            $totalMovimentado = $dados['quantidade_vendida'] + $dados['quantidade_devolvida'];

            $saida = SaidaProvisoria::where('encontro_id', $dados['encontro_id'])
                ->where('produto_id', $dados['produto_id'])
                ->sum('quantidade');

            if ($totalMovimentado > $saida) {
                throw new Exception('Quantidade maior que a enviada para o encontro');
            }

            if ($dados['quantidade_devolvida'] > 0) {
                $estoque = Estoque::where('produto_id', $dados['produto_id'])->firstOrFail();
                $estoque->increment('quantidade', $dados['quantidade_devolvida']);
            }

            $produto = Produto::findOrFail($dados['produto_id']);
            $dados['valor_total'] = $dados['quantidade_vendida'] * $produto->preco_venda;

            return BaixaEstoque::create($dados);
        });
    }

    /**
     * Consulta estoque atual de um produto
     */
    public function estoqueAtual(int $produtoId): int
    {
        return Estoque::where('produto_id', $produtoId)->value('quantidade') ?? 0;
    }
}
