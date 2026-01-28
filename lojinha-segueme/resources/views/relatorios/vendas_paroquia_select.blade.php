<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 Relatório de Vendas por Paróquia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                            <span class="text-2xl">📊</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Selecione a Paróquia
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Escolha a paróquia para visualizar o relatório de vendas
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('relatorios.vendas-paroquia') }}" id="formRelatorio">
                        <div class="space-y-4">
                            <div>
                                <label for="paroquia_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Paróquia *
                                </label>
                                <select
                                    name="paroquia_id"
                                    id="paroquia_id"
                                    required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                        focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                >
                                    <option value="">Selecione uma paróquia...</option>
                                    @foreach($paroquias as $paroquia)
                                        <option value="{{ $paroquia->id }}">
                                            {{ $paroquia->nome }}
                                            @if($paroquia->cidade)
                                                - {{ $paroquia->cidade }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($paroquias->isEmpty())
                                <div class="rounded-lg bg-yellow-50 dark:bg-yellow-900/20 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <span class="text-yellow-400 text-xl">⚠️</span>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                Nenhuma paróquia cadastrada
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                <p>Você precisa cadastrar paróquias antes de gerar relatórios.</p>
                                            </div>
                                            <div class="mt-4">
                                                <a href="{{ route('paroquias.create') }}"
                                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:bg-yellow-800 dark:text-yellow-100 dark:hover:bg-yellow-700">
                                                    Cadastrar Paróquia
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                                   text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                ← Voltar
                            </a>

                            <button
                                type="submit"
                                @if($paroquias->isEmpty()) disabled @endif
                                class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-indigo-600 text-white
                                    hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span>📊</span>
                                Gerar Relatório
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informações adicionais -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-blue-500 text-xl">ℹ️</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            Sobre este relatório
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>
                                Este relatório mostra todas as vendas realizadas em encontros da paróquia selecionada,
                                detalhando produtos vendidos, quantidades e valores totais por encontro.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
