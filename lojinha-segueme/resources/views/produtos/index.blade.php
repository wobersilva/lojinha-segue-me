<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📦 Produtos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Gerencie os produtos do sistema.
                </div>

                <div style="padding: 10px">
                    <a href="{{ route('produtos.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        ➕ Novo Produto
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('produtos.index') }}"
                  class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-4 flex flex-col sm:flex-row gap-3 items-end">

                <div class="flex-1 w-full">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        Buscar (Descrição ou Tamanho)
                    </label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $busca ?? '' }}"
                        placeholder="Ex: Camiseta, P, M, G..."
                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                            focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                </div>

                <div class="w-full sm:w-56">
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                        Status
                    </label>
                    <select
                        name="status"
                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                            focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        @php($s = $status ?? 'todos')
                        <option value="todos" @selected($s === 'todos')>Todos</option>
                        <option value="ativo" @selected($s === 'ativo')>Ativos</option>
                        <option value="inativo" @selected($s === 'inativo')>Inativos</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 w-full sm:w-auto">
                        Filtrar
                    </button>

                    <a href="{{ route('produtos.index') }}"
                       class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 w-full sm:w-auto text-center">
                        Limpar
                    </a>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Descrição</th>
                                    <th class="py-3 pr-4">Tamanho</th>
                                    <th class="py-3 pr-4 text-center">Estoque</th>
                                    <th class="py-3 pr-4">Preço Custo</th>
                                    <th class="py-3 pr-4">Preço Venda Sugerido</th>
                                    <th class="py-3 pr-4">Status</th>
                                    <th class="py-3 w-40">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produtos as $produto)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $produto->descricao }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $produto->tamanho ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4 text-center">
                                            @if($produto->estoque && $produto->estoque->quantidade > 10)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                    {{ $produto->estoque->quantidade }} un
                                                </span>
                                            @elseif($produto->estoque && $produto->estoque->quantidade > 0)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    {{ $produto->estoque->quantidade }} un
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    0 un
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if($produto->status === 'ativo')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/30
                                                    dark:bg-emerald-900/50 dark:text-emerald-300 dark:ring-emerald-500/50">
                                                    Ativo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-gray-200 text-gray-700 ring-1 ring-gray-500/30
                                                    dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600">
                                                    Inativo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 flex items-center gap-2">
                                            <a href="{{ route('produtos.edit', $produto) }}"
                                               class="px-3 py-1.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('produtos.destroy', $produto) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Excluir este produto?')"
                                                        class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            Nenhum produto cadastrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $produtos->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
