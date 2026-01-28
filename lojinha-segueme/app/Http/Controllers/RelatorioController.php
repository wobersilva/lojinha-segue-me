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
        $dataInicio = $request->data_inicio;
        $dataFim = $request->data_fim;

        $query = BaixaEstoque::select(
            'paroquias.nome as paroquia',
            'encontros.nome as encontro',
            'encontros.data_inicio as data_encontro',
            'produtos.descricao',
            DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade'),
            DB::raw('SUM(baixas_estoque.valor_total) as total')
        )
            ->join('encontros', 'encontros.id', '=', 'baixas_estoque.encontro_id')
            ->join('paroquias', 'paroquias.id', '=', 'encontros.paroquia_id')
            ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
            ->where('paroquias.id', $paroquiaId);

        // Aplicar filtros de data se fornecidos
        if ($dataInicio) {
            $query->where('encontros.data_inicio', '>=', $dataInicio);
        }
        if ($dataFim) {
            $query->where('encontros.data_inicio', '<=', $dataFim);
        }

        $dados = $query
            ->groupBy('paroquias.nome', 'encontros.nome', 'encontros.data_inicio', 'produtos.descricao')
            ->orderBy('encontros.data_inicio', 'desc')
            ->get();

        $paroquia = Paroquia::findOrFail($paroquiaId);

        return view('relatorios.vendas_paroquia', compact('dados', 'paroquia', 'dataInicio', 'dataFim'));
    }

    // -------------------------------
    // PRODUTOS VENDIDOS POR PERÍODO
    // -------------------------------
    public function vendasPorPeriodo(Request $request)
    {
        // Se não tiver data_inicio ou data_fim, mostra a tela de seleção
        if (!$request->has('data_inicio') || !$request->has('data_fim')) {
            return view('relatorios.vendas_periodo_select');
        }

        $dataInicio = $request->data_inicio;
        $dataFim = $request->data_fim;

        $dados = BaixaEstoque::select(
            'produtos.descricao',
            DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade'),
            DB::raw('SUM(baixas_estoque.valor_total) as total')
        )
            ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
            ->whereBetween('baixas_estoque.data_baixa', [$dataInicio, $dataFim])
            ->groupBy('produtos.descricao')
            ->orderBy('produtos.descricao')
            ->get();

        return view('relatorios.vendas_periodo', compact('dados', 'dataInicio', 'dataFim'));
    }
}
