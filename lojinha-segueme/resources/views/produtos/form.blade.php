<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($produto) ? 'Editar Produto' : 'Novo Produto' }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                            <div class="font-medium mb-1">Corrija os campos abaixo:</div>
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ isset($produto) ? route('produtos.update', $produto) : route('produtos.store') }}">
                        @csrf
                        @isset($produto)
                            @method('PUT')
                        @endisset

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descrição
                            </label>
                            <input type="text" name="descricao" id="descricao"
                                   value="{{ old('descricao', $produto->descricao ?? '') }}"
                                   style="text-transform: uppercase"
                                   oninput="this.value = this.value.toUpperCase()"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tamanho
                            </label>
                            <input type="text" name="tamanho"
                                   value="{{ old('tamanho', $produto->tamanho ?? '') }}"
                                   style="text-transform: uppercase"
                                   oninput="this.value = this.value.toUpperCase()"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Preço Custo
                            </label>
                            <input type="number" step="0.01" name="preco_custo" id="preco_custo"
                                   value="{{ old('preco_custo', $produto->preco_custo ?? '') }}"
                                   placeholder="0,00"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Preço Venda Sugestivo
                            </label>
                            <input type="number" step="0.01" name="preco_venda" id="preco_venda"
                                   value="{{ old('preco_venda', $produto->preco_venda ?? '0.00') }}"
                                   placeholder="0,00"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                          focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                    >
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select name="status"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @php($statusAtual = old('status', $produto->status ?? 'ativo'))
                                <option value="ativo" @selected($statusAtual === 'ativo')>Ativo</option>
                                <option value="inativo" @selected($statusAtual === 'inativo')>Inativo</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('produtos.index') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                                Salvar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Autofocus no campo descrição
            const campoDescricao = document.getElementById('descricao');
            if (campoDescricao) {
                campoDescricao.focus();
            }

            const precoCusto = document.getElementById('preco_custo');
            const precoVenda = document.getElementById('preco_venda');

            function formatarPreco(input) {
                if (!input) return;
                
                // Formata o valor ao sair do campo
                input.addEventListener('blur', function() {
                    if (this.value !== '') {
                        const valor = parseFloat(this.value);
                        if (!isNaN(valor)) {
                            this.value = valor.toFixed(2);
                        }
                    }
                });

                // Ao carregar a página, formata se já tiver valor
                if (input.value !== '') {
                    const valor = parseFloat(input.value);
                    if (!isNaN(valor)) {
                        input.value = valor.toFixed(2);
                    }
                }
            }

            formatarPreco(precoCusto);
            formatarPreco(precoVenda);
        });
    </script>
</x-app-layout>
