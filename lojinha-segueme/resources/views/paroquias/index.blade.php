<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⛪ Paróquias
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Gerencie as paróquias do sistema.
                </div>

                <div style="padding: 10px">
                    <a href="{{ route('paroquias.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        ➕ Nova Paróquia
                    </a>
                </div>
            </div>

                    <form method="GET" action="{{ route('paroquias.index') }}"
            class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-4 flex flex-col sm:flex-row gap-3 items-end">

            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                    Buscar (Nome ou Cidade)
                </label>
                <input
                    type="text"
                    name="q"
                    value="{{ $busca ?? '' }}"
                    placeholder="Ex: São José, Catedral, Natal..."
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
                    @php($s = $status ?? 'todas')
                    <option value="todas"  @selected($s === 'todas')>Todas</option>
                    <option value="ativa"  @selected($s === 'ativa')>Ativas</option>
                    <option value="inativa"@selected($s === 'inativa')>Inativas</option>
                </select>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 w-full sm:w-auto">
                    Filtrar
                </button>

                <a href="{{ route('paroquias.index') }}"
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
                                    <th class="py-3 pr-4">Nome</th>
                                    <th class="py-3 pr-4">Cidade</th>
                                    <th class="py-3 pr-4">Responsável</th>
                                    <th class="py-3 pr-4">Contato</th>
                                    <th class="py-3 pr-4">Status</th>
                                    <th class="py-3 w-40">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paroquias as $p)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $p->nome }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $p->cidade ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $p->responsavel ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">
                                            {{ $p->contato ?? '-' }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if($p->status === 'ativa')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-emerald-100 text-emerald-700 ring-1 ring-emerald-500/30
                                                    dark:bg-emerald-900/50 dark:text-emerald-300 dark:ring-emerald-500/50">
                                                    Ativa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                                    bg-gray-200 text-gray-700 ring-1 ring-gray-500/30
                                                    dark:bg-gray-700 dark:text-gray-300 dark:ring-gray-600">
                                                    Inativa
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 flex items-center gap-2">
                                            <a href="{{ route('paroquias.edit', $p) }}"
                                               class="px-3 py-1.5 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('paroquias.destroy', $p) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Excluir esta paróquia?')"
                                                        class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 text-center text-gray-500">
                                            Nenhuma paróquia cadastrada
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $paroquias->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
