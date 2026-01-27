<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✅ Dar Baixa no Encontro - {{ $encontro->nome }}
        </h2>
    </x-slot>

    <div class="py-6" 
         x-data="baixaEncontro()"
         data-url-baixa="{{ route('encontros.fechar', $encontro) }}"
         data-csrf-token="{{ csrf_token() }}"
         data-total-saidas="{{ $encontro->saidasProvisorias->count() }}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Conferência de Produtos
                </h3>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Informe a quantidade vendida e devolvida de cada produto. O que não foi vendido retornará ao estoque.
                </p>

                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-3 pr-4">Produto</th>
                            <th class="py-3 pr-4 text-center">Enviado</th>
                            <th class="py-3 pr-4 text-center">Vendido</th>
                            <th class="py-3 pr-4 text-center">Devolvido</th>
                            <th class="py-3 pr-4 text-right">Preço Custo</th>
                            <th class="py-3 pr-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($encontro->saidasProvisorias as $index => $saida)
                            <tr class="border-b border-gray-100 dark:border-gray-700/60 item-baixa"
                                x-data="{ 
                                    enviado: {{ $saida->quantidade }},
                                    vendido: 0,
                                    devolvido: {{ $saida->quantidade }},
                                    preco: {{ $saida->produto->preco_custo ?? 0 }},
                                    calcularDevolvido() { this.devolvido = this.enviado - this.vendido; },
                                    calcularTotal() { return this.vendido * this.preco; }
                                }">
                                <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $saida->produto->descricao }} - {{ $saida->produto->tamanho }}
                                    <input type="hidden" class="input-produto-id" value="{{ $saida->produto_id }}">
                                </td>
                                <td class="py-3 pr-4 text-center text-gray-600 dark:text-gray-300" x-text="enviado"></td>
                                <td class="py-3 pr-4 text-center">
                                    <input type="number" x-model.number="vendido" @input="calcularDevolvido()" min="0" :max="enviado"
                                        class="input-vendido w-20 px-2 py-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-center text-sm">
                                </td>
                                <td class="py-3 pr-4 text-center">
                                    <input type="number" x-model.number="devolvido" @input="vendido = enviado - devolvido" min="0" :max="enviado"
                                        class="input-devolvido w-20 px-2 py-1 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-center text-sm">
                                </td>
                                <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300">
                                    R$ <span x-text="preco.toFixed(2).replace('.', ',')"></span>
                                    <input type="hidden" class="input-preco" value="{{ number_format($saida->produto->preco_custo ?? 0, 2, '.', '') }}">
                                </td>
                                <td class="py-3 pr-4 text-right font-semibold text-gray-900 dark:text-gray-100">
                                    R$ <span x-text="calcularTotal().toFixed(2).replace('.', ',')"></span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex gap-3 justify-end mt-6">
                    <a href="{{ route('encontros.show', $encontro) }}"
                        class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
                        Cancelar
                    </a>
                    <button @click="finalizarBaixa()" type="button"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 font-medium">
                        ✅ Finalizar Baixa
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function baixaEncontro() {
            return {
                finalizarBaixa() {
                    const container = this.$el.closest('[data-url-baixa]');
                    const urlBaixa = container.dataset.urlBaixa;
                    const csrfToken = container.dataset.csrfToken;
                    
                    const baixas = [];
                    
                    // Buscar todos os itens usando classes CSS (funciona com escopos Alpine aninhados)
                    const itens = document.querySelectorAll('.item-baixa');
                    
                    itens.forEach(item => {
                        const produtoId = item.querySelector('.input-produto-id').value;
                        const vendido = item.querySelector('.input-vendido').value;
                        const devolvido = item.querySelector('.input-devolvido').value;
                        const preco = item.querySelector('.input-preco').value;
                        
                        const qtdVendida = parseInt(vendido) || 0;
                        const precoUnit = parseFloat(preco) || 0;
                        const valorTotal = qtdVendida * precoUnit;
                        
                        baixas.push({
                            produto_id: produtoId,
                            quantidade_vendida: qtdVendida,
                            quantidade_devolvida: parseInt(devolvido) || 0,
                            valor_total: Math.round(valorTotal * 100) / 100 // Arredondar para 2 casas decimais
                        });
                    });

                    if (confirm('Confirmar baixa do encontro? Esta ação não pode ser desfeita.')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = urlBaixa;

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        const baixasInput = document.createElement('input');
                        baixasInput.type = 'hidden';
                        baixasInput.name = 'baixas';
                        baixasInput.value = JSON.stringify(baixas);
                        form.appendChild(baixasInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>
