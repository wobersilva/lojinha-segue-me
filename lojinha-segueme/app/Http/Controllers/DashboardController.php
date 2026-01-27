<?php

namespace App\Http\Controllers;

use App\Models\BaixaEstoque;
use App\Models\Encontro;
use App\Models\Estoque;
use App\Models\Paroquia;
use App\Models\Produto;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    /**
     * Tempo de cache em segundos (5 minutos)
     */
    private const CACHE_TTL = 300;

    public function index()
    {
        // =============================================
        // ESTATÍSTICAS COM CACHE (5 minutos)
        // =============================================

        // Cards resumo - consolidado em queries otimizadas com cache
        $estatisticas = Cache::remember('dashboard_estatisticas', self::CACHE_TTL, function () {
            return [
                'totalProdutos' => Produto::count(),
                'totalProdutosAtivos' => Produto::where('status', 'ativo')->count(),
                'totalParoquias' => Paroquia::count(),
                'totalParoquiasAtivas' => Paroquia::where('status', 'ativa')->count(),
                'totalEncontros' => Encontro::count(),
                'encontrosAbertos' => Encontro::where('status', 'aberto')->count(),
                // Query otimizada: sem JOIN desnecessário
                'totalEstoque' => Estoque::sum('quantidade'),
                'totalVendido' => BaixaEstoque::sum('valor_total'),
            ];
        });

        // Extrair estatísticas do cache
        extract($estatisticas);

        // Produtos com estoque baixo (cache separado - dados mais voláteis)
        $produtosBaixoEstoque = Cache::remember('dashboard_estoque_baixo', self::CACHE_TTL, function () {
            return Produto::join('estoque', 'produtos.id', '=', 'estoque.produto_id')
                ->where('estoque.quantidade', '<', 10)
                ->where('produtos.status', 'ativo')
                ->select('produtos.descricao', 'produtos.tamanho', 'estoque.quantidade')
                ->orderBy('estoque.quantidade')
                ->limit(5)
                ->get();
        });

        // Últimos encontros
        $ultimosEncontros = Cache::remember('dashboard_ultimos_encontros', self::CACHE_TTL, function () {
            return Encontro::with('paroquia')
                ->orderBy('data_inicio', 'asc')
                ->limit(5)
                ->get();
        });

        // Encontros em aberto (cache menor - pode mudar com frequência)
        $encontrosEmAberto = Cache::remember('dashboard_encontros_abertos', 60, function () {
            return Encontro::with('paroquia')
                ->where('status', 'aberto')
                ->orderBy('data_inicio', 'desc')
                ->limit(20) // Limitar para evitar carregar muitos registros
                ->get();
        });

        // Vendas por mês (últimos 6 meses) - query otimizada
        $vendasMes = Cache::remember('dashboard_vendas_mes', self::CACHE_TTL, function () {
            return BaixaEstoque::select(
                DB::raw('YEAR(data_baixa) as ano'),
                DB::raw('MONTH(data_baixa) as mes_num'),
                DB::raw("CONCAT(LPAD(MONTH(data_baixa), 2, '0'), '/', YEAR(data_baixa)) as mes"),
                DB::raw('SUM(valor_total) as total')
            )
                ->where('data_baixa', '>=', now()->subMonths(6))
                ->groupBy('ano', 'mes_num', 'mes')
                ->orderBy('ano')
                ->orderBy('mes_num')
                ->get();
        });

        // Produtos mais vendidos
        $produtosMaisVendidos = Cache::remember('dashboard_produtos_mais_vendidos', self::CACHE_TTL, function () {
            return BaixaEstoque::select(
                'produtos.descricao',
                'produtos.tamanho',
                DB::raw('SUM(baixas_estoque.quantidade_vendida) as quantidade')
            )
                ->join('produtos', 'produtos.id', '=', 'baixas_estoque.produto_id')
                ->groupBy('produtos.id', 'produtos.descricao', 'produtos.tamanho')
                ->orderByDesc('quantidade')
                ->limit(5)
                ->get();
        });

        // Paróquias que mais compraram
        $paroquiasTopCompradoras = Cache::remember('dashboard_paroquias_top', self::CACHE_TTL, function () {
            return BaixaEstoque::select(
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
        });

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

    /**
     * Limpa o cache do dashboard
     * Chamar este método após alterações em produtos, encontros, etc.
     */
    public static function limparCache(): void
    {
        Cache::forget('dashboard_estatisticas');
        Cache::forget('dashboard_estoque_baixo');
        Cache::forget('dashboard_ultimos_encontros');
        Cache::forget('dashboard_encontros_abertos');
        Cache::forget('dashboard_vendas_mes');
        Cache::forget('dashboard_produtos_mais_vendidos');
        Cache::forget('dashboard_paroquias_top');
    }
}
