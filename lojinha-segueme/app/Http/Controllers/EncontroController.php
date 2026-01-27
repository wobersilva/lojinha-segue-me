<?php

namespace App\Http\Controllers;

use App\Models\BaixaEstoque;
use App\Models\Encontro;
use App\Models\Estoque;
use App\Models\MovimentacaoEstoque;
use App\Models\Paroquia;
use App\Models\Produto;
use App\Models\SaidaProvisoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncontroController extends Controller
{
    /**
     * Limpa cache do dashboard após alterações
     */
    private function limparCacheDashboard(): void
    {
        DashboardController::limparCache();
    }

    /**
     * Lista todos os encontros
     */
    public function index()
    {
        $query = Encontro::with('paroquia');

        // Filtro por pesquisa (nome do encontro ou paróquia)
        if (request()->filled('busca')) {
            $busca = request('busca');
            $query->where(function ($q) use ($busca) {
                $q
                    ->where('nome', 'like', "%{$busca}%")
                    ->orWhereHas('paroquia', function ($q2) use ($busca) {
                        $q2->where('nome', 'like', "%{$busca}%");
                    });
            });
        }

        // Filtro por status
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filtro por paróquia
        if (request()->filled('paroquia_id')) {
            $query->where('paroquia_id', request('paroquia_id'));
        }

        // Filtro por período
        if (request()->filled('data_inicio')) {
            $query->whereDate('data_inicio', '>=', request('data_inicio'));
        }
        if (request()->filled('data_fim')) {
            $query->whereDate('data_inicio', '<=', request('data_fim'));
        }

        // Ordenação: primeiro os abertos, depois por data mais recente
        $encontros = $query
            ->orderByRaw("CASE WHEN status = 'aberto' THEN 0 ELSE 1 END")
            ->orderBy('data_inicio', 'desc')
            ->paginate(15)
            ->appends(request()->query());

        // Buscar paróquias para o filtro
        $paroquias = Paroquia::orderBy('nome')->get();

        return view('encontros.index', compact('encontros', 'paroquias'));
    }

    /**
     * Exibe formulário de criação
     */
    public function create()
    {
        $paroquias = Paroquia::where('status', 'ativa')
            ->orderBy('nome')
            ->get();

        $produtos = Produto::where('status', 'ativo')
            ->with('estoque')
            ->whereHas('estoque', function ($query) {
                $query->where('quantidade', '>', 0);
            })
            ->orderBy('descricao')
            ->get();

        return view('encontros.create', compact('paroquias', 'produtos'));
    }

    /**
     * Salva novo encontro com saídas provisórias
     */
    public function store(Request $request)
    {
        $request->validate([
            'paroquia_id' => 'required|exists:paroquias,id',
            'nome' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'itens' => 'required|json'
        ]);

        $itens = json_decode($request->itens, true);

        if (empty($itens)) {
            return redirect()
                ->back()
                ->with('error', 'Adicione pelo menos um produto ao encontro');
        }

        try {
            DB::beginTransaction();

            // Criar encontro
            $encontro = Encontro::create([
                'paroquia_id' => $request->paroquia_id,
                'user_id' => auth()->id(),
                'nome' => $request->nome,
                'data_inicio' => $request->data_inicio,
                'data_fim' => $request->data_fim,
                'status' => 'aberto'
            ]);

            // ===========================================
            // OTIMIZAÇÃO: Carregar todos os estoques de uma vez
            // ===========================================
            $produtoIds = collect($itens)->pluck('produto_id')->unique()->toArray();
            $estoques = Estoque::whereIn('produto_id', $produtoIds)
                ->get()
                ->keyBy('produto_id');

            // Preparar arrays para bulk insert
            $saidasProvisorias = [];
            $movimentacoes = [];
            $estoquesParaAtualizar = [];
            $totalItens = 0;
            $agora = now();

            // Validar e preparar dados
            foreach ($itens as $item) {
                $produtoId = $item['produto_id'];
                $quantidade = (int) $item['quantidade'];

                // Verificar estoque
                $estoque = $estoques->get($produtoId);
                if (!$estoque || $estoque->quantidade < $quantidade) {
                    throw new \Exception("Estoque insuficiente para o produto ID {$produtoId}");
                }

                // Preparar saída provisória para bulk insert
                $saidasProvisorias[] = [
                    'encontro_id' => $encontro->id,
                    'produto_id' => $produtoId,
                    'quantidade' => $quantidade,
                    'data_saida' => $agora,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Preparar movimentação para bulk insert
                $movimentacoes[] = [
                    'produto_id' => $produtoId,
                    'tipo' => 'saida',
                    'quantidade' => $quantidade,
                    'motivo' => 'Saída Temporária para Encontro',
                    'observacoes' => "Encontro: {$encontro->nome} (ID: {$encontro->id})",
                    'data_movimentacao' => $agora,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Atualizar quantidade no objeto (será salvo depois)
                $estoque->quantidade -= $quantidade;
                $estoquesParaAtualizar[$produtoId] = $estoque;

                $totalItens += $quantidade;
            }

            // Bulk insert saídas provisórias
            SaidaProvisoria::insert($saidasProvisorias);

            // Bulk insert movimentações
            MovimentacaoEstoque::insert($movimentacoes);

            // Atualizar estoques (ainda precisa ser individual devido a valores diferentes)
            foreach ($estoquesParaAtualizar as $estoque) {
                $estoque->save();
            }

            DB::commit();

            // Limpa cache do dashboard
            $this->limparCacheDashboard();

            // Se for requisição AJAX, retorna JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Encontro criado com sucesso! {$totalItens} unidades de " . count($itens) . ' produto(s) enviadas.',
                    'encontro_id' => $encontro->id,
                    'redirect_url' => route('encontros.show', $encontro),
                    'relatorio_url' => route('encontros.relatorio-saida', $encontro)
                ]);
            }

            return redirect()
                ->route('encontros.show', $encontro)
                ->with('success', "Encontro criado com sucesso! {$totalItens} unidades de " . count($itens) . ' produto(s) enviadas.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar encontro: ' . $e->getMessage());

            // Se for requisição AJAX, retorna JSON com erro
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar encontro: ' . $e->getMessage()
                ], 422);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar encontro: ' . $e->getMessage());
        }
    }

    /**
     * Exibe detalhes do encontro
     */
    public function show(Encontro $encontro)
    {
        $encontro->load([
            'paroquia',
            'user',
            'saidasProvisorias.produto',
            'baixas.produto'
        ]);

        return view('encontros.show', compact('encontro'));
    }

    /**
     * Exibe formulário para dar baixa no encontro
     */
    public function edit(Encontro $encontro)
    {
        if ($encontro->status === 'fechado') {
            return redirect()
                ->route('encontros.show', $encontro)
                ->with('warning', 'Este encontro já foi fechado');
        }

        $encontro->load([
            'paroquia',
            'saidasProvisorias.produto.estoque'
        ]);

        return view('encontros.baixa', compact('encontro'));
    }

    /**
     * Gera relatório de saída de produtos do encontro
     */
    public function relatorioSaida(Encontro $encontro)
    {
        $encontro->load([
            'paroquia',
            'saidasProvisorias.produto'
        ]);

        return view('encontros.relatorio-saida', compact('encontro'));
    }

    /**
     * Processa baixa do encontro (vendas e devoluções)
     */
    public function fechar(Request $request, Encontro $encontro)
    {
        if ($encontro->status === 'fechado') {
            return redirect()
                ->route('encontros.show', $encontro)
                ->with('warning', 'Este encontro já foi fechado');
        }

        $request->validate([
            'baixas' => 'required|json'
        ]);

        $baixas = json_decode($request->baixas, true);

        try {
            DB::beginTransaction();

            // ===========================================
            // OTIMIZAÇÃO: Carregar todos os estoques de uma vez
            // ===========================================
            $produtoIds = collect($baixas)->pluck('produto_id')->unique()->toArray();
            $estoques = Estoque::whereIn('produto_id', $produtoIds)
                ->get()
                ->keyBy('produto_id');

            // Preparar arrays para bulk insert
            $baixasParaInserir = [];
            $movimentacoes = [];
            $estoquesParaAtualizar = [];
            $totalVendido = 0;
            $totalDevolvido = 0;
            $agora = now();

            foreach ($baixas as $baixa) {
                $produtoId = $baixa['produto_id'];
                $quantidadeVendida = (int) ($baixa['quantidade_vendida'] ?? 0);
                $quantidadeDevolvida = (int) ($baixa['quantidade_devolvida'] ?? 0);
                $valorTotal = (float) ($baixa['valor_total'] ?? 0);

                // Preparar baixa para bulk insert
                $baixasParaInserir[] = [
                    'encontro_id' => $encontro->id,
                    'produto_id' => $produtoId,
                    'quantidade_vendida' => $quantidadeVendida,
                    'quantidade_devolvida' => $quantidadeDevolvida,
                    'valor_total' => $valorTotal,
                    'data_baixa' => $agora,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Devolver ao estoque o que não foi vendido
                if ($quantidadeDevolvida > 0) {
                    $estoque = $estoques->get($produtoId);
                    if ($estoque) {
                        $estoque->quantidade += $quantidadeDevolvida;
                        $estoquesParaAtualizar[$produtoId] = $estoque;

                        // Preparar movimentação para bulk insert
                        $movimentacoes[] = [
                            'produto_id' => $produtoId,
                            'tipo' => 'entrada',
                            'quantidade' => $quantidadeDevolvida,
                            'motivo' => 'Devolução de Encontro',
                            'observacoes' => "Encontro: {$encontro->nome} (ID: {$encontro->id})",
                            'data_movimentacao' => $agora,
                            'created_at' => $agora,
                            'updated_at' => $agora,
                        ];
                    }
                }

                $totalVendido += $quantidadeVendida;
                $totalDevolvido += $quantidadeDevolvida;
            }

            // Bulk insert baixas
            BaixaEstoque::insert($baixasParaInserir);

            // Bulk insert movimentações (se houver devoluções)
            if (!empty($movimentacoes)) {
                MovimentacaoEstoque::insert($movimentacoes);
            }

            // Atualizar estoques
            foreach ($estoquesParaAtualizar as $estoque) {
                $estoque->save();
            }

            // Fechar encontro
            $encontro->update(['status' => 'fechado']);

            DB::commit();

            // Limpa cache do dashboard
            $this->limparCacheDashboard();

            return redirect()
                ->route('encontros.show', $encontro)
                ->with('success', "Encontro fechado com sucesso! Vendidos: {$totalVendido}, Devolvidos: {$totalDevolvido}");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao fechar encontro: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Erro ao processar baixa: ' . $e->getMessage());
        }
    }
}
