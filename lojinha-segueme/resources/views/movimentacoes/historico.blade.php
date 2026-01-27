<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📜 Histórico de Movimentações
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Filtros --}}
            <form method="GET" action="{{ route('movimentacoes.historico') }}"
                  class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-4">
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                            Tipo
                        </label>
                        <select name="tipo"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos</option>
                            <option value="entrada" {{ request('tipo') === 'entrada' ? 'selected' : '' }}>Entradas</option>
                            <option value="saida" {{ request('tipo') === 'saida' ? 'selected' : '' }}>Saídas</option>
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                            Produto
                        </label>
                        <select name="produto_id"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Todos os produtos</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" {{ request('produto_id') == $produto->id ? 'selected' : '' }}>
                                    {{ $produto->descricao }} - {{ $produto->tamanho }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                            Data Início
                        </label>
                        <input type="date" name="data_inicio" value="{{ request('data_inicio', date('Y-m-01')) }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                            Data Fim
                        </label>
                        <input type="date" name="data_fim" value="{{ request('data_fim', date('Y-m-t')) }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="md:col-span-2 flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm">
                            Filtrar
                        </button>
                        <a href="{{ route('movimentacoes.historico') }}"
                            class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm">
                            Limpar
                        </a>
                    </div>
                </div>
            </form>

            {{-- Tabela de Histórico --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Data</th>
                                    <th class="py-3 pr-4">Tipo</th>
                                    <th class="py-3 pr-4">Produto</th>
                                    <th class="py-3 pr-4">Tamanho</th>
                                    <th class="py-3 pr-4 text-right">Quantidade</th>
                                    <th class="py-3 pr-4">Motivo</th>
                                    <th class="py-3">Observações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movimentacoes as $mov)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $mov->data_movimentacao->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if($mov->tipo === 'entrada')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/30
                                                    dark:bg-emerald-900/50 dark:text-emerald-300 dark:ring-emerald-500/50">
                                                    ⬇️ Entrada
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-red-100 text-red-700 ring-1 ring-red-500/30
                                                    dark:bg-red-900/50 dark:text-red-300 dark:ring-red-500/50">
                                                    ⬆️ Saída
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $mov->produto->descricao }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $mov->produto->tamanho }}
                                        </td>
                                        <td class="py-3 pr-4 text-right font-semibold"
                                            :class="$mov->tipo === 'entrada' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'">
                                            {{ $mov->tipo === 'entrada' ? '+' : '-' }}{{ $mov->quantidade }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $mov->motivo ? ucfirst($mov->motivo) : '-' }}
                                        </td>
                                        <td class="py-3 text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                            {{ $mov->observacoes ?: '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            Nenhuma movimentação encontrada
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $movimentacoes->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
