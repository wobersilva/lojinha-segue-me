<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📅 Encontros
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Cabeçalho com botão --}}
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Gerencie os encontros e seus produtos
                </div>
                <a href="{{ route('encontros.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Novo Encontro
                </a>
            </div>

            {{-- Filtros e Pesquisa --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-4">
                <form method="GET" action="{{ route('encontros.index') }}">
                    <div class="flex flex-wrap items-end gap-3">
                        {{-- Campo de busca --}}
                        <div class="flex-1 min-w-[200px] relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" name="busca" value="{{ request('busca') }}"
                                placeholder="Buscar encontro ou paróquia..."
                                class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                onkeydown="if(event.key==='Enter'){this.form.submit()}">
                        </div>

                        {{-- Filtro por Status --}}
                        <div class="w-32">
                            <select name="status" onchange="this.form.submit()"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Todos</option>
                                <option value="aberto" {{ request('status') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="fechado" {{ request('status') == 'fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                        </div>

                        {{-- Filtro por Paróquia --}}
                        <div class="w-48">
                            <select name="paroquia_id" onchange="this.form.submit()"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Todas</option>
                                @foreach($paroquias as $paroquia)
                                    <option value="{{ $paroquia->id }}" {{ request('paroquia_id') == $paroquia->id ? 'selected' : '' }}>
                                        {{ $paroquia->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por período --}}
                        <div class="flex items-center gap-2">
                            <input type="date" name="data_inicio" value="{{ request('data_inicio', date('Y-m-01')) }}"
                                title="Data início (de)"
                                onchange="this.form.submit()"
                                class="w-36 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <span class="text-gray-400 text-sm">até</span>
                            <input type="date" name="data_fim" value="{{ request('data_fim', date('Y-m-t')) }}"
                                title="Data início (até)"
                                onchange="this.form.submit()"
                                class="w-36 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        {{-- Botão Limpar --}}
                        @if(request()->hasAny(['busca', 'status', 'paroquia_id', 'data_inicio', 'data_fim']))
                            <a href="{{ route('encontros.index') }}"
                                class="px-3 py-2 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 text-sm"
                                title="Limpar filtros">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabela de Encontros --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Nome</th>
                                    <th class="py-3 pr-4">Paróquia</th>
                                    <th class="py-3 pr-4">Data Início</th>
                                    <th class="py-3 pr-4">Data Fim</th>
                                    <th class="py-3 pr-4">Status</th>
                                    <th class="py-3 w-48">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($encontros as $encontro)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $encontro->nome }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $encontro->paroquia->nome ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $encontro->data_inicio->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $encontro->data_fim ? $encontro->data_fim->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if($encontro->status === 'aberto')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/30
                                                    dark:bg-emerald-900/50 dark:text-emerald-300 dark:ring-emerald-500/50">
                                                    Aberto
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-gray-200 text-gray-700 ring-1 ring-gray-500/30
                                                    dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600">
                                                    Fechado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 flex items-center gap-2">
                                            <a href="{{ route('encontros.show', $encontro) }}"
                                               class="px-3 py-1.5 rounded-lg bg-blue-500 text-white hover:bg-blue-600 text-xs">
                                                Ver Detalhes
                                            </a>

                                            @if($encontro->status === 'aberto')
                                                <a href="{{ route('encontros.edit', $encontro) }}"
                                                   class="px-3 py-1.5 rounded-lg bg-green-500 text-white hover:bg-green-600 text-xs">
                                                    Dar Baixa
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">
                                            Nenhum encontro cadastrado
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $encontros->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
