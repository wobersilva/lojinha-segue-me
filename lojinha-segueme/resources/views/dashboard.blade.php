<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">Dashboard</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bem-vindo à Lojinha do Segue-me</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">

                {{-- Cards de Resumo --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
                    {{-- Card: Produtos --}}
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 rounded-xl p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-4xl font-bold mb-2">{{ $totalProdutos }}</h3>
                        <p class="text-blue-100 text-sm font-medium">Produtos Cadastrados</p>
                        <div class="mt-3">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs">{{ $totalProdutosAtivos }} ativos</span>
                        </div>
                    </div>

                    {{-- Card: Paróquias --}}
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 rounded-xl p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-4xl font-bold mb-2">{{ $totalParoquias }}</h3>
                        <p class="text-emerald-100 text-sm font-medium">Paróquias Cadastradas</p>
                        <div class="mt-3">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs">{{ $totalParoquiasAtivas }} ativas</span>
                        </div>
                    </div>

                    {{-- Card: Estoque --}}
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 rounded-xl p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-4xl font-bold mb-2">{{ number_format($totalEstoque, 0, ',', '.') }}</h3>
                        <p class="text-amber-100 text-sm font-medium">Unidades em Estoque</p>
                        <div class="mt-3">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs">inventário total</span>
                        </div>
                    </div>

                    {{-- Card: Vendas --}}
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white hover:shadow-2xl transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="bg-white/20 rounded-xl p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-4xl font-bold mb-2">R$ {{ number_format($totalVendido, 2, ',', '.') }}</h3>
                        <p class="text-purple-100 text-sm font-medium">Total Vendido</p>
                        <div class="mt-3">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs">{{ $encontrosAbertos }} encontros abertos</span>
                        </div>
                    </div>
                </div>

                {{-- Encontros em Aberto --}}
                <a href="{{ url('/encontros') }}" class="block">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow cursor-pointer group">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Encontros em Aberto</h3>
                                        <p class="text-indigo-100 text-sm">
                                            @if($encontrosEmAberto->count() > 0)
                                                {{ $encontrosEmAberto->count() }} {{ $encontrosEmAberto->count() === 1 ? 'encontro ativo' : 'encontros ativos' }}
                                            @else
                                                Nenhum encontro aberto no momento
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center text-white group-hover:translate-x-1 transition-transform">
                                    <span class="text-sm mr-2">Ver todos</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @php
                                $mesesAberto = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
                            @endphp
                            @forelse($encontrosEmAberto->take(3) as $encontro)
                                <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold flex-col">
                                            <span class="text-lg">{{ $encontro->data_inicio->format('d') }}</span>
                                            <span class="text-xs uppercase">{{ $mesesAberto[$encontro->data_inicio->format('n') - 1] }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->nome }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $encontro->paroquia->nome ?? 'Sem paróquia' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-200 rounded-lg text-xs font-semibold">
                                            Aberto
                                        </span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-16 h-16 text-indigo-300 dark:text-indigo-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum encontro aberto no momento</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Clique aqui para ver todos os encontros</p>
                                </div>
                            @endforelse
                            @if($encontrosEmAberto->count() > 3)
                                <div class="text-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        + {{ $encontrosEmAberto->count() - 3 }} {{ $encontrosEmAberto->count() - 3 === 1 ? 'outro encontro' : 'outros encontros' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>

                {{-- Produtos com Estoque Baixo --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-white">Alerta de Estoque Baixo</h3>
                                <p class="text-red-100 text-sm">Produtos com menos de 10 unidades</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse($produtosBaixoEstoque as $produto)
                            <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                        {{ $produto->quantidade }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $produto->descricao }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Tamanho: {{ $produto->tamanho }}</p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 rounded-lg text-sm font-semibold">
                                    Baixo
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Todos os produtos com estoque adequado!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Grid: Rankings --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Top Produtos --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Top 5 Produtos Mais Vendidos
                            </h3>
                        </div>
                        <div class="p-6">
                            @forelse($produtosMaisVendidos as $produto)
                                <div class="flex items-center gap-4 p-3 mb-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $produto->descricao }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $produto->tamanho }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 rounded-lg text-sm font-semibold">
                                        {{ $produto->quantidade }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">Nenhuma venda registrada</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Top Paróquias --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Top 5 Paróquias Compradoras
                            </h3>
                        </div>
                        <div class="p-6">
                            @forelse($paroquiasTopCompradoras as $paroquia)
                                <div class="flex items-center gap-4 p-3 mb-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $paroquia->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $paroquia->cidade }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 rounded-lg text-sm font-semibold">
                                        R$ {{ number_format($paroquia->total_compras, 2, ',', '.') }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">Nenhuma compra registrada</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Últimos Encontros --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Últimos Encontros
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
                        @endphp
                        @forelse($ultimosEncontros as $encontro)
                            <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white font-bold flex-col">
                                        <span class="text-lg leading-none">{{ $encontro->data_inicio->format('d') }}</span>
                                        <span class="text-xs uppercase leading-none">{{ $meses[$encontro->data_inicio->format('n') - 1] }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $encontro->nome }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $encontro->paroquia->nome ?? 'Sem paróquia' }}</p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 {{ $encontro->status === 'aberto' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200' }} rounded-lg text-sm font-semibold">
                                    {{ ucfirst($encontro->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">Nenhum encontro cadastrado</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
