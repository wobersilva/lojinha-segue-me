<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📅 Detalhes do Encontro
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Informações do Encontro --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ $encontro->nome }}
                    </h3>
                    @if($encontro->status === 'aberto')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                            bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/30
                            dark:bg-emerald-900/50 dark:text-emerald-300 dark:ring-emerald-500/50">
                            Aberto
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold
                            bg-gray-200 text-gray-700 ring-1 ring-gray-500/30
                            dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600">
                            Fechado
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Paróquia</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->paroquia->nome }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Data Início</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->data_inicio->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Data Fim</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->data_fim ? $encontro->data_fim->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Lançado por</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->user ? $encontro->user->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('encontros.index') }}"
                        class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 text-sm">
                        ← Voltar
                    </a>
                    <a href="{{ route('encontros.relatorio-saida', $encontro) }}" target="_blank"
                        class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir Relatório de Saída
                    </a>
                    @if($encontro->status === 'aberto')
                        <a href="{{ route('encontros.edit', $encontro) }}"
                            class="px-4 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 text-sm">
                            Dar Baixa no Encontro
                        </a>
                    @endif
                </div>
            </div>

            {{-- Produtos Enviados --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Produtos Enviados (Saída Provisória)
                    </h3>
                </div>
                <div class="p-6">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                <th class="py-3 pr-4">Produto</th>
                                <th class="py-3 pr-4">Tamanho</th>
                                <th class="py-3 pr-4 text-right">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encontro->saidasProvisorias as $saida)
                                <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                    <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                        {{ $saida->produto->descricao }}
                                    </td>
                                    <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                        {{ $saida->produto->tamanho }}
                                    </td>
                                    <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300">
                                        {{ $saida->quantidade }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Baixas (se houver) --}}
            @if($encontro->baixas->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Baixa do Encontro
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Produto</th>
                                    <th class="py-3 pr-4 text-right">Vendido</th>
                                    <th class="py-3 pr-4 text-right">Devolvido</th>
                                    <th class="py-3 pr-4 text-right">Valor Unitário</th>
                                    <th class="py-3 pr-4 text-right">Valor Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalVendido = 0; @endphp
                                @foreach($encontro->baixas as $baixa)
                                    @php $totalVendido += $baixa->valor_total; @endphp
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $baixa->produto->descricao }} - {{ $baixa->produto->tamanho }}
                                        </td>
                                        <td class="py-3 pr-4 text-right text-green-600 dark:text-green-400">
                                            {{ $baixa->quantidade_vendida }}
                                        </td>
                                        <td class="py-3 pr-4 text-right text-yellow-600 dark:text-yellow-400">
                                            {{ $baixa->quantidade_devolvida }}
                                        </td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300">
                                            R$ {{ number_format($baixa->produto->preco_custo ?? 0, 2, ',', '.') }}
                                        </td>
                                        <td class="py-3 pr-4 text-right font-semibold text-gray-900 dark:text-gray-100">
                                            R$ {{ number_format($baixa->valor_total, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                    <td colspan="4" class="py-4 pr-4 text-right font-bold text-gray-900 dark:text-gray-100">
                                        Total Arrecadado:
                                    </td>
                                    <td class="py-4 pr-4 text-right font-bold text-lg text-emerald-600 dark:text-emerald-400">
                                        R$ {{ number_format($totalVendido, 2, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
