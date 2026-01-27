<?php

namespace App\Http\Controllers;

use App\Models\BaixaEstoque;
use App\Models\Encontro;
use App\Models\Paroquia;
use App\Models\Produto;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index()
    {
        // Cards resumo
        $totalProdutos = Produto::count();
        $totalProdutosAtivos = Produto::where('status', 'ativo')->count();
        $totalParoquias = Paroquia::count();
        $totalParoquiasAtivas = Paroquia::where('status', 'ativa')->count();
        $totalEncontros = Encontro::count();
        $encontrosAbertos = Encontro::where('status', 'aberto')->count();

        $totalEstoque = Produto::join('estoque', 'produtos.id', '=', 'estoque.produto_id')
            ->sum('estoque.quantidade');

        $totalVendido = BaixaEstoque::sum('valor_total');

        // Produtos com estoque baixo (menos de 10 unidades)
        $produtosBaixoEstoque = Produto::join('estoque', 'produtos.id', '=', 'estoque.produto_id')
            ->where('estoque.quantidade', '<', 10)
            ->where('produtos.status', 'ativo')
            ->select('produtos.descricao', 'produtos.tamanho', 'estoque.quantidade')
            ->orderBy('estoque.quantidade')
            ->limit(5)
            ->get();

        // Últimos encontros
        $ultimosEncontros = Encontro::with('paroquia')
            ->orderBy('data_inicio', 'asc')
            ->limit(5)
            ->get();

        // Encontros em aberto
        $encontrosEmAberto = Encontro::with('paroquia')
            ->where('status', 'aberto')
            ->orderBy('data_inicio', 'desc')
            ->get();

        // Vendas por mês (últimos 6 meses)
        $vendasMes = BaixaEstoque::select(
            DB::raw("DATE_FORMAT(data_baixa,'%m/%Y') as mes"),
            DB::raw('SUM(valor_total) as total')
        )
            ->where('data_baixa', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy(DB::raw('MIN(data_baixa)'))
            ->get();

        // Produtos mais vendidos
        $produtosMaisVendidos = BaixaEstoque::select(
            'produtos.descricao',
            'produtos.tamanho',
            DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade')
        )
            ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
            ->groupBy('produtos.id', 'produtos.descricao', 'produtos.tamanho')
            ->orderByDesc('quantidade')
            ->limit(5)
            ->get();

        // Paróquias que mais compraram
        $paroquiasTopCompradoras = BaixaEstoque::select(
            'paroquias.nome',
            'paroquias.cidade',
            DB::raw('SUM(baixas_estoque.valor_total) as total_compras')
        )
            ->join('encontros', 'encontros.id', '=', 'baixas_estoque.encontro_id')
            ->join('paroquias', 'paroquias.id', '=', 'encontros.paroquia_id')
            ->groupBy('paroquias.id', 'paroquias.nome', 'paroquias.cidade')
            ->orderByDesc('total_compras')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProdutos',
            'totalProdutosAtivos',
            'totalParoquias',
            'totalParoquiasAtivas',
            'totalEncontros',
            'encontrosAbertos',
            'totalEstoque',
            'totalVendido',
            'produtosBaixoEstoque',
            'ultimosEncontros',
            'encontrosEmAberto',
            'vendasMes',
            'produtosMaisVendidos',
            'paroquiasTopCompradoras'
        ));
    }
}
