<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProdutoController extends BaseController
{
    /**
     * Limpa cache do dashboard após alterações
     */
    private function limparCacheDashboard(): void
    {
        DashboardController::limparCache();
    }

    public function index(Request $request)
    {
        $busca = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', 'todos');  // 'todos' | 'ativo' | 'inativo'

        $query = Produto::query();

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q
                    ->where('descricao', 'like', "%{$busca}%")
                    ->orWhere('tamanho', 'like', "%{$busca}%");
            });
        }

        if (in_array($status, ['ativo', 'inativo'], true)) {
            $query->where('status', $status);
        }

        $produtos = $query
            ->with('estoque')
            ->orderBy('descricao')
            ->orderBy('tamanho')
            ->orderBy('preco_custo')
            ->paginate(50)
            ->appends($request->only(['q', 'status']));

        return view('produtos.index', compact('produtos', 'busca', 'status'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'tamanho' => ['nullable', 'string', 'max:50'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:ativo,inativo'],
        ]);

        // Converte campos de texto para uppercase
        $data['descricao'] = strtoupper($data['descricao']);
        if (!empty($data['tamanho'])) {
            $data['tamanho'] = strtoupper($data['tamanho']);
        }

        // Verifica se já existe produto com mesma descrição, tamanho e preço de custo
        $produtoExistente = Produto::where('descricao', $data['descricao'])
            ->where('tamanho', $data['tamanho'] ?? '')
            ->where('preco_custo', $data['preco_custo'])
            ->first();

        if ($produtoExistente) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Já existe um produto cadastrado com a mesma descrição, tamanho e preço de custo!');
        }

        $produto = Produto::create($data);

        // Garante estoque inicial
        $produto->estoque()->firstOrCreate([], ['quantidade' => 0]);

        // Limpa cache do dashboard
        $this->limparCacheDashboard();

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $data = $request->validate([
            'descricao' => ['required', 'string', 'max:255'],
            'tamanho' => ['nullable', 'string', 'max:50'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'preco_venda' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:ativo,inativo'],
        ]);

        // Converte campos de texto para uppercase
        $data['descricao'] = strtoupper($data['descricao']);
        if (!empty($data['tamanho'])) {
            $data['tamanho'] = strtoupper($data['tamanho']);
        }

        // Verifica se já existe outro produto com mesma descrição, tamanho e preço de custo
        $produtoExistente = Produto::where('descricao', $data['descricao'])
            ->where('tamanho', $data['tamanho'] ?? '')
            ->where('preco_custo', $data['preco_custo'])
            ->where('id', '!=', $produto->id)
            ->first();

        if ($produtoExistente) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Já existe outro produto cadastrado com a mesma descrição, tamanho e preço de custo!');
        }

        $produto->update($data);

        // Limpa cache do dashboard
        $this->limparCacheDashboard();

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        // Limpa cache do dashboard
        $this->limparCacheDashboard();

        return redirect()
            ->route('produtos.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}
