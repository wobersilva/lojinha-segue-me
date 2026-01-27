<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⬇️ Entrada de Estoque
        </h2>
    </x-slot>

    <div class="py-6" x-data="entradaEstoque()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Formulário para adicionar produtos à lista --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Adicionar Produtos à Entrada
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-5">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                Produto
                            </label>
                            <select x-model="novoProduto.produto_id" @change="atualizarProduto()"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Selecione um produto</option>
                                @forelse($produtos as $produto)
                                    <option value="{{ $produto->id }}" 
                                        data-descricao="{{ $produto->descricao }}" 
                                        data-tamanho="{{ $produto->tamanho }}" 
                                        data-preco="{{ $produto->preco_custo }}">
                                        {{ $produto->descricao }} - {{ $produto->tamanho }}
                                    </option>
                                @empty
                                    <option value="" disabled>Nenhum produto ativo encontrado</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                Quantidade
                            </label>
                            <input type="number" x-model="novoProduto.quantidade" min="1"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="10">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                Valor Custo (R$)
                            </label>
                            <input type="text" 
                                x-model="novoProduto.valor_custo"
                                @blur="formatarValorCusto()"
                                @input="validarNumero($event)"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="25,00">
                        </div>

                        <div class="md:col-span-3 flex items-end">
                            <button @click="adicionarProduto()" type="button"
                                class="w-full px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-medium">
                                ➕ Adicionar à Lista
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista de produtos adicionados --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Produtos na Entrada
                    </h3>
                    <span class="text-sm text-gray-600 dark:text-gray-300" x-show="itens.length > 0">
                        <span x-text="itens.length"></span> item(ns)
                    </span>
                </div>

                <div class="p-6">
                    {{-- Tabela de itens --}}
                    <div class="overflow-x-auto" x-show="itens.length > 0">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Produto</th>
                                    <th class="py-3 pr-4">Tamanho</th>
                                    <th class="py-3 pr-4 text-right">Quantidade</th>
                                    <th class="py-3 pr-4 text-right">Valor Unitário</th>
                                    <th class="py-3 pr-4 text-right">Subtotal</th>
                                    <th class="py-3 w-20 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in itens" :key="index">
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100" x-text="item.descricao"></td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300" x-text="item.tamanho"></td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300" x-text="item.quantidade"></td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300" x-text="formatarMoeda(item.valor_custo)"></td>
                                        <td class="py-3 pr-4 text-right font-semibold text-gray-900 dark:text-gray-100" x-text="formatarMoeda(item.subtotal)"></td>
                                        <td class="py-3 text-center">
                                            <button @click="removerItem(index)" type="button"
                                                class="px-2 py-1 rounded-lg bg-red-600 text-white hover:bg-red-700 text-xs">
                                                🗑️
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                    <td colspan="4" class="py-4 pr-4 text-right font-bold text-gray-900 dark:text-gray-100">
                                        Total da Entrada:
                                    </td>
                                    <td class="py-4 pr-4 text-right font-bold text-lg text-indigo-600 dark:text-indigo-400" x-text="formatarMoeda(calcularTotal())"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Mensagem quando não há itens --}}
                    <div x-show="itens.length === 0" class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            Nenhum produto adicionado ainda
                        </p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
                            Adicione produtos usando o formulário acima
                        </p>
                    </div>

                    {{-- Observações e botão de finalizar --}}
                    <div x-show="itens.length > 0" class="mt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observações (opcional)
                            </label>
                            <textarea x-model="observacao" rows="2"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="Informações adicionais sobre esta entrada de estoque..."></textarea>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <button @click="limparTudo()" type="button"
                                class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
                                Limpar Tudo
                            </button>
                            <button @click="finalizarEntrada()" type="button"
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Finalizar Entrada
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const urlEntradas = "{{ url('/movimentacoes/entradas') }}";
        const csrfToken = "{{ csrf_token() }}";
        
        function entradaEstoque() {
            return {
                itens: [],
                observacao: '',
                novoProduto: {
                    produto_id: '',
                    descricao: '',
                    tamanho: '',
                    quantidade: 1,
                    valor_custo: '0,00'
                },

                atualizarProduto() {
                    const select = document.querySelector('select[x-model="novoProduto.produto_id"]');
                    const option = select.options[select.selectedIndex];
                    
                    if (option && option.value) {
                        this.novoProduto.descricao = option.dataset.descricao || '';
                        this.novoProduto.tamanho = option.dataset.tamanho || '';
                        const preco = parseFloat(option.dataset.preco) || 0;
                        // Formatar com 2 casas decimais e vírgula
                        this.novoProduto.valor_custo = preco.toFixed(2).replace('.', ',');
                    }
                },

                formatarValorCusto() {
                    if (this.novoProduto.valor_custo) {
                        // Remove tudo que não é número, vírgula ou ponto
                        let valor = this.novoProduto.valor_custo.toString().replace(/[^\d,\.]/g, '');
                        // Substitui vírgula por ponto para cálculo
                        valor = valor.replace(',', '.');
                        // Converte para número
                        let numero = parseFloat(valor) || 0;
                        // Formata com 2 casas decimais e vírgula
                        this.novoProduto.valor_custo = numero.toFixed(2).replace('.', ',');
                    }
                },

                validarNumero(event) {
                    // Permite apenas números, vírgula, ponto e uma vírgula/ponto
                    const valor = event.target.value;
                    const regex = /^[0-9]*[,\.]?[0-9]*$/;
                    if (!regex.test(valor)) {
                        event.target.value = valor.slice(0, -1);
                        this.novoProduto.valor_custo = valor.slice(0, -1);
                    }
                },

                converterValorParaFloat(valorString) {
                    // Converte valor formatado (12,50) para float (12.5)
                    return parseFloat(valorString.toString().replace(',', '.')) || 0;
                },

                adicionarProduto() {
                    if (!this.novoProduto.produto_id) {
                        alert('Por favor, selecione um produto');
                        return;
                    }

                    if (!this.novoProduto.quantidade || this.novoProduto.quantidade <= 0) {
                        alert('Por favor, informe uma quantidade válida');
                        return;
                    }

                    const valorCusto = this.converterValorParaFloat(this.novoProduto.valor_custo);
                    
                    if (!valorCusto || valorCusto <= 0) {
                        alert('Por favor, informe um valor de custo válido');
                        return;
                    }

                    // Verificar se o produto já foi adicionado
                    const jaExiste = this.itens.findIndex(item => item.produto_id === this.novoProduto.produto_id);
                    
                    if (jaExiste !== -1) {
                        if (confirm('Este produto já está na lista. Deseja atualizar a quantidade?')) {
                            this.itens[jaExiste].quantidade = parseInt(this.novoProduto.quantidade);
                            this.itens[jaExiste].valor_custo = valorCusto;
                            this.itens[jaExiste].subtotal = this.itens[jaExiste].quantidade * valorCusto;
                        }
                    } else {
                        // Adicionar novo item
                        this.itens.push({
                            produto_id: this.novoProduto.produto_id,
                            descricao: this.novoProduto.descricao,
                            tamanho: this.novoProduto.tamanho,
                            quantidade: parseInt(this.novoProduto.quantidade),
                            valor_custo: valorCusto,
                            subtotal: parseInt(this.novoProduto.quantidade) * valorCusto
                        });
                    }

                    // Limpar formulário
                    this.novoProduto = {
                        produto_id: '',
                        descricao: '',
                        tamanho: '',
                        quantidade: 1,
                        valor_custo: '0,00'
                    };

                    // Reset do select
                    document.querySelector('select[x-model="novoProduto.produto_id"]').value = '';
                },

                removerItem(index) {
                    if (confirm('Deseja remover este item da lista?')) {
                        this.itens.splice(index, 1);
                    }
                },

                calcularTotal() {
                    return this.itens.reduce((total, item) => total + item.subtotal, 0);
                },

                formatarMoeda(valor) {
                    return 'R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
                },

                limparTudo() {
                    if (confirm('Deseja limpar toda a lista de produtos?')) {
                        this.itens = [];
                        this.observacao = '';
                    }
                },

                finalizarEntrada() {
                    if (this.itens.length === 0) {
                        alert('Adicione pelo menos um produto antes de finalizar');
                        return;
                    }

                    const mensagem = 'Confirmar entrada de ' + this.itens.length + ' produto(s) no valor total de ' + this.formatarMoeda(this.calcularTotal()) + '?';
                    if (confirm(mensagem)) {
                        // Criar formulário e enviar
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = urlEntradas;

                        // Token CSRF
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        // Itens
                        const itensInput = document.createElement('input');
                        itensInput.type = 'hidden';
                        itensInput.name = 'itens';
                        itensInput.value = JSON.stringify(this.itens);
                        form.appendChild(itensInput);

                        // Observação
                        const obsInput = document.createElement('input');
                        obsInput.type = 'hidden';
                        obsInput.name = 'observacao';
                        obsInput.value = this.observacao;
                        form.appendChild(obsInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>
