<?php

namespace App\Http\Controllers;

use App\Models\BaixaEstoque;
use App\Models\Paroquia;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class RelatorioController extends BaseController
{
    // -------------------------------
    // INVENTÁRIO
    // -------------------------------
    public function inventario()
    {
        $dados = Produto::with('estoque')->get();

        return view('relatorios.inventario', compact('dados'));
    }

    // -------------------------------
    // VENDAS POR PARÓQUIA
    // -------------------------------
    public function vendasPorParoquia(Request $request)
    {
        // Se não tiver paroquia_id, mostra a tela de seleção
        if (!$request->has('paroquia_id')) {
            $paroquias = Paroquia::orderBy('nome')->get();
            return view('relatorios.vendas_paroquia_select', compact('paroquias'));
        }

        $paroquiaId = $request->paroquia_id;

        $dados = BaixaEstoque::select(
            'paroquias.nome as paroquia',
            'encontros.nome as encontro',
            'produtos.descricao',
            DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade'),
            DB::raw('SUM(baixas_estoque.valor_total) as total')
        )
            ->join('encontros', 'encontros.id', '=', 'baixas_estoque.encontro_id')
            ->join('paroquias', 'paroquias.id', '=', 'encontros.paroquia_id')
            ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
            ->where('paroquias.id', $paroquiaId)
            ->groupBy('paroquias.nome', 'encontros.nome', 'produtos.descricao')
            ->get();

        $paroquia = Paroquia::findOrFail($paroquiaId);

        return view('relatorios.vendas_paroquia', compact('dados', 'paroquia'));
    }

    // -------------------------------
    // PRODUTOS VENDIDOS POR PERÍODO
    // -------------------------------
    public function vendasPorPeriodo(Request $request)
    {
        $dados = BaixaEstoque::select(
            'produtos.descricao',
            DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade'),
            DB::raw('SUM(baixas_estoque.valor_total) as total')
        )
            ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
            ->whereBetween('data_baixa', [$request->data_inicio, $request->data_fim])
            ->groupBy('produtos.descricao')
            ->get();

        return view('relatorios.vendas_periodo', compact('dados'));
    }
}
