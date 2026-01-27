<?php

namespace App\Http\Controllers;

use App\Models\EntradaEstoque;
use App\Models\Estoque;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MovimentacaoController extends Controller
{
    /**
     * Limpa cache do dashboard após alterações
     */
    private function limparCacheDashboard(): void
    {
        DashboardController::limparCache();
    }

    /**
     * Exibe a tela de entradas
     */
    public function entradas()
    {
        $produtos = Produto::where('status', 'ativo')
            ->orderBy('descricao')
            ->orderBy('tamanho')
            ->get();

        return view('movimentacoes.entradas', compact('produtos'));
    }

    /**
     * Processa a entrada de estoque em lote
     */
    public function storeEntradas(Request $request)
    {
        // Validar dados recebidos
        $request->validate([
            'itens' => 'required|json',
            'observacao' => 'nullable|string|max:500'
        ]);

        $itens = json_decode($request->itens, true);

        if (empty($itens)) {
            return redirect()
                ->route('movimentacoes.entradas')
                ->with('error', 'Nenhum item foi adicionado à entrada');
        }

        try {
            DB::beginTransaction();

            $observacaoGeral = $request->observacao;
            $agora = now();

            // ===========================================
            // OTIMIZAÇÃO: Carregar todos os produtos e estoques de uma vez
            // ===========================================
            $produtoIds = collect($itens)->pluck('produto_id')->unique()->toArray();
            $produtos = Produto::whereIn('id', $produtoIds)->get()->keyBy('id');
            $estoques = Estoque::whereIn('produto_id', $produtoIds)->get()->keyBy('produto_id');

            // Preparar arrays para bulk insert
            $entradasEstoque = [];
            $movimentacoes = [];
            $estoquesParaAtualizar = [];
            $produtosParaAtualizar = [];
            $totalItens = 0;
            $totalValor = 0;

            foreach ($itens as $item) {
                // Validar dados do item
                $produtoId = $item['produto_id'] ?? null;
                $quantidade = (int) ($item['quantidade'] ?? 0);
                $valorCusto = (float) ($item['valor_custo'] ?? 0);

                if (!$produtoId || $quantidade <= 0 || $valorCusto < 0) {
                    throw new \Exception('Dados inválidos no item');
                }

                // Verificar se o produto existe
                $produto = $produtos->get($produtoId);
                if (!$produto) {
                    throw new \Exception("Produto ID {$produtoId} não encontrado");
                }

                // Preparar entrada para bulk insert
                $entradasEstoque[] = [
                    'produto_id' => $produtoId,
                    'data_entrada' => $agora,
                    'quantidade' => $quantidade,
                    'valor_custo' => $valorCusto,
                    'observacoes' => $observacaoGeral,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Preparar movimentação para bulk insert
                $movimentacoes[] = [
                    'produto_id' => $produtoId,
                    'tipo' => 'entrada',
                    'quantidade' => $quantidade,
                    'motivo' => 'entrada_estoque',
                    'observacoes' => $observacaoGeral,
                    'data_movimentacao' => $agora,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Atualizar ou preparar criação de estoque
                $estoque = $estoques->get($produtoId);
                if (!$estoque) {
                    $estoque = new Estoque(['produto_id' => $produtoId, 'quantidade' => 0]);
                }
                $estoque->quantidade += $quantidade;
                $estoquesParaAtualizar[$produtoId] = $estoque;

                // Atualizar preço de custo do produto se fornecido
                if ($valorCusto > 0 && $produto->preco_custo != $valorCusto) {
                    $produto->preco_custo = $valorCusto;
                    $produtosParaAtualizar[$produtoId] = $produto;
                }

                $totalItens += $quantidade;
                $totalValor += ($quantidade * $valorCusto);
            }

            // Bulk insert entradas
            EntradaEstoque::insert($entradasEstoque);

            // Bulk insert movimentações
            MovimentacaoEstoque::insert($movimentacoes);

            // Salvar estoques
            foreach ($estoquesParaAtualizar as $estoque) {
                $estoque->save();
            }

            // Salvar produtos com preço atualizado
            foreach ($produtosParaAtualizar as $produto) {
                $produto->save();
            }

            DB::commit();

            // Limpa cache do dashboard
            $this->limparCacheDashboard();

            return redirect()
                ->route('movimentacoes.entradas')
                ->with('success', "Entrada realizada com sucesso! {$totalItens} unidades de " . count($itens) . ' produto(s). Valor total: R$ ' . number_format($totalValor, 2, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar entrada de estoque: ' . $e->getMessage());

            return redirect()
                ->route('movimentacoes.entradas')
                ->with('error', 'Erro ao processar entrada: ' . $e->getMessage());
        }
    }

    /**
     * Exibe a tela de saídas
     */
    public function saidas()
    {
        $produtos = Produto::where('status', 'ativo')
            ->with('estoque')
            ->whereHas('estoque', function ($query) {
                $query->where('quantidade', '>', 0);
            })
            ->orderBy('descricao')
            ->orderBy('tamanho')
            ->get();

        return view('movimentacoes.saidas', compact('produtos'));
    }

    /**
     * Processa a saída de estoque em lote
     */
    public function storeSaidas(Request $request)
    {
        // Validar dados recebidos
        $request->validate([
            'itens' => 'required|json',
            'motivo' => 'required|string|in:venda,transferencia,perda,doacao,devolucao,outro',
            'observacao' => 'required|string|max:500'
        ]);

        $itens = json_decode($request->itens, true);

        if (empty($itens)) {
            return redirect()
                ->route('movimentacoes.saidas')
                ->with('error', 'Nenhum item foi adicionado à saída');
        }

        try {
            DB::beginTransaction();

            $motivo = $request->motivo;
            $observacao = $request->observacao;
            $agora = now();

            // ===========================================
            // OTIMIZAÇÃO: Carregar todos os produtos e estoques de uma vez
            // ===========================================
            $produtoIds = collect($itens)->pluck('produto_id')->unique()->toArray();
            $produtos = Produto::whereIn('id', $produtoIds)->get()->keyBy('id');
            $estoques = Estoque::whereIn('produto_id', $produtoIds)->get()->keyBy('produto_id');

            // Preparar arrays para bulk insert
            $movimentacoes = [];
            $estoquesParaAtualizar = [];
            $totalItens = 0;

            foreach ($itens as $item) {
                // Validar dados do item
                $produtoId = $item['produto_id'] ?? null;
                $quantidade = (int) ($item['quantidade'] ?? 0);

                if (!$produtoId || $quantidade <= 0) {
                    throw new \Exception('Dados inválidos no item');
                }

                // Verificar se o produto existe
                $produto = $produtos->get($produtoId);
                if (!$produto) {
                    throw new \Exception("Produto ID {$produtoId} não encontrado");
                }

                // Verificar estoque
                $estoque = $estoques->get($produtoId);
                if (!$estoque || $estoque->quantidade < $quantidade) {
                    throw new \Exception("Estoque insuficiente para o produto: {$produto->descricao} - {$produto->tamanho}");
                }

                // Preparar movimentação para bulk insert
                $movimentacoes[] = [
                    'produto_id' => $produtoId,
                    'tipo' => 'saida',
                    'quantidade' => $quantidade,
                    'motivo' => $motivo,
                    'observacoes' => $observacao,
                    'data_movimentacao' => $agora,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];

                // Atualizar estoque
                $estoque->quantidade -= $quantidade;
                $estoquesParaAtualizar[$produtoId] = $estoque;

                $totalItens += $quantidade;
            }

            // Bulk insert movimentações
            MovimentacaoEstoque::insert($movimentacoes);

            // Salvar estoques
            foreach ($estoquesParaAtualizar as $estoque) {
                $estoque->save();
            }

            DB::commit();

            // Limpa cache do dashboard
            $this->limparCacheDashboard();

            return redirect()
                ->route('movimentacoes.saidas')
                ->with('success', "Saída realizada com sucesso! {$totalItens} unidades de " . count($itens) . ' produto(s) removidas do estoque.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar saída de estoque: ' . $e->getMessage());

            return redirect()
                ->route('movimentacoes.saidas')
                ->with('error', 'Erro ao processar saída: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o histórico de movimentações
     */
    public function historico(Request $request)
    {
        $query = MovimentacaoEstoque::with('produto')
            ->orderBy('data_movimentacao', 'desc')
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('produto_id')) {
            $query->where('produto_id', $request->produto_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_movimentacao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_movimentacao', '<=', $request->data_fim);
        }

        $movimentacoes = $query->paginate(20);
        $produtos = Produto::where('status', 'ativo')
            ->orderBy('descricao')
            ->get();

        return view('movimentacoes.historico', compact('movimentacoes', 'produtos'));
    }
}
