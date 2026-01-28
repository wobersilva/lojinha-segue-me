<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📅 Produtos Vendidos por Período
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Selecione o Período
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Escolha o período para visualizar os produtos vendidos
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form method="GET" action="{{ route('relatorios.vendas-periodo') }}" id="formRelatorio">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Data Inicial *
                                    </label>
                                    <input
                                        type="date"
                                        name="data_inicio"
                                        id="data_inicio"
                                        required
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                            focus:border-green-500 focus:ring-green-500 text-sm"
                                    >
                                </div>

                                <div>
                                    <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Data Final *
                                    </label>
                                    <input
                                        type="date"
                                        name="data_fim"
                                        id="data_fim"
                                        required
                                        class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                            focus:border-green-500 focus:ring-green-500 text-sm"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                                   text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                ← Voltar
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2 rounded-xl bg-green-600 text-white
                                    hover:bg-green-700 transition"
                            >
                                <span>📅</span>
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
                                Este relatório mostra todos os produtos vendidos no período selecionado,
                                consolidando as quantidades e valores totais por produto.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
