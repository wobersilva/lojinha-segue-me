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
                $produto = Produto::find($produtoId);
                if (!$produto) {
                    throw new \Exception("Produto ID {$produtoId} não encontrado");
                }

                // Registrar entrada no histórico
                EntradaEstoque::create([
                    'produto_id' => $produtoId,
                    'data_entrada' => now(),
                    'quantidade' => $quantidade,
                    'valor_custo' => $valorCusto,
                    'observacoes' => $observacaoGeral
                ]);

                // Registrar na tabela de movimentações para histórico unificado
                MovimentacaoEstoque::create([
                    'produto_id' => $produtoId,
                    'tipo' => 'entrada',
                    'quantidade' => $quantidade,
                    'motivo' => 'entrada_estoque',
                    'observacoes' => $observacaoGeral,
                    'data_movimentacao' => now()
                ]);

                // Atualizar ou criar estoque
                $estoque = Estoque::firstOrCreate(
                    ['produto_id' => $produtoId],
                    ['quantidade' => 0]
                );

                $estoque->quantidade += $quantidade;
                $estoque->save();

                // Atualizar preço de custo do produto se fornecido
                if ($valorCusto > 0 && $produto->preco_custo != $valorCusto) {
                    $produto->preco_custo = $valorCusto;
                    $produto->save();
                }

                $totalItens += $quantidade;
                $totalValor += ($quantidade * $valorCusto);
            }

            DB::commit();

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
            $totalItens = 0;

            foreach ($itens as $item) {
                // Validar dados do item
                $produtoId = $item['produto_id'] ?? null;
                $quantidade = (int) ($item['quantidade'] ?? 0);

                if (!$produtoId || $quantidade <= 0) {
                    throw new \Exception('Dados inválidos no item');
                }

                // Verificar se o produto existe
                $produto = Produto::find($produtoId);
                if (!$produto) {
                    throw new \Exception("Produto ID {$produtoId} não encontrado");
                }

                // Verificar estoque
                $estoque = Estoque::where('produto_id', $produtoId)->first();
                if (!$estoque || $estoque->quantidade < $quantidade) {
                    throw new \Exception("Estoque insuficiente para o produto: {$produto->descricao} - {$produto->tamanho}");
                }

                // Criar registro de saída na tabela movimentacoes_estoque
                MovimentacaoEstoque::create([
                    'produto_id' => $produtoId,
                    'tipo' => 'saida',
                    'quantidade' => $quantidade,
                    'motivo' => $motivo,
                    'observacoes' => $observacao,
                    'data_movimentacao' => now()
                ]);

                // Atualizar estoque
                $estoque->quantidade -= $quantidade;
                $estoque->save();

                $totalItens += $quantidade;
            }

            DB::commit();

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
