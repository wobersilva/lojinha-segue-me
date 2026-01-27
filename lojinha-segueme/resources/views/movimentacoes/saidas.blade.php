<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ⬆️ Saída de Estoque
        </h2>
    </x-slot>

    <div class="py-6" x-data="saidaEstoque()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Formulário para adicionar produtos à lista --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Adicionar Produtos à Saída
                </h3>

                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                Produto
                            </label>
                            <select x-model="novoProduto.produto_id" @change="atualizarProduto()"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Selecione um produto</option>
                                @forelse($produtos as $produto)
                                    @php
                                        $estoqueAtual = $produto->estoque->quantidade ?? 0;
                                    @endphp
                                    <option value="{{ $produto->id }}" 
                                        data-descricao="{{ $produto->descricao }}" 
                                        data-tamanho="{{ $produto->tamanho }}" 
                                        data-estoque="{{ $estoqueAtual }}">
                                        {{ $produto->descricao }} - {{ $produto->tamanho }} ({{ $estoqueAtual }} unid.)
                                    </option>
                                @empty
                                    <option value="" disabled>Nenhum produto com estoque disponível</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                Quantidade
                            </label>
                            <input type="number" x-model="novoProduto.quantidade" min="1"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="10">
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
                        Produtos na Saída
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
                                    <th class="py-3 pr-4 text-right">Estoque Atual</th>
                                    <th class="py-3 pr-4 text-right">Quantidade Saída</th>
                                    <th class="py-3 pr-4 text-right">Estoque Restante</th>
                                    <th class="py-3 w-20 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in itens" :key="index">
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100" x-text="item.descricao"></td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300" x-text="item.tamanho"></td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300" x-text="item.estoque_atual"></td>
                                        <td class="py-3 pr-4 text-right font-semibold text-red-600 dark:text-red-400" x-text="item.quantidade"></td>
                                        <td class="py-3 pr-4 text-right font-semibold"
                                            :class="(item.estoque_atual - item.quantidade) < 10 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-gray-100'"
                                            x-text="item.estoque_atual - item.quantidade"></td>
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
                                    <td colspan="3" class="py-4 pr-4 text-right font-bold text-gray-900 dark:text-gray-100">
                                        Total de Itens Saindo:
                                    </td>
                                    <td class="py-4 pr-4 text-right font-bold text-lg text-red-600 dark:text-red-400" x-text="calcularTotalQuantidade()"></td>
                                    <td colspan="2"></td>
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

                    {{-- Motivo e botão de finalizar --}}
                    <div x-show="itens.length > 0" class="mt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Motivo da Saída <span class="text-red-500">*</span>
                            </label>
                            <select x-model="motivo"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Selecione o motivo</option>
                                <option value="venda">Venda</option>
                                <option value="transferencia">Transferência</option>
                                <option value="perda">Perda/Avaria</option>
                                <option value="doacao">Doação</option>
                                <option value="devolucao">Devolução</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observações <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="observacao" rows="2"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                    focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="Descreva o motivo da saída de estoque..."></textarea>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <button @click="limparTudo()" type="button"
                                class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
                                Limpar Tudo
                            </button>
                            <button @click="finalizarSaida()" type="button"
                                class="px-6 py-2.5 rounded-xl bg-red-600 text-white hover:bg-red-700 font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Finalizar Saída
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const urlSaidas = "{{ url('/movimentacoes/saidas') }}";
        const csrfTokenSaida = "{{ csrf_token() }}";
        
        function saidaEstoque() {
            return {
                itens: [],
                motivo: '',
                observacao: '',
                novoProduto: {
                    produto_id: '',
                    descricao: '',
                    tamanho: '',
                    estoque_atual: 0,
                    quantidade: 1
                },

                atualizarProduto() {
                    const select = document.querySelector('select[x-model="novoProduto.produto_id"]');
                    const option = select.options[select.selectedIndex];
                    
                    if (option && option.value) {
                        this.novoProduto.descricao = option.dataset.descricao || '';
                        this.novoProduto.tamanho = option.dataset.tamanho || '';
                        this.novoProduto.estoque_atual = parseInt(option.dataset.estoque) || 0;
                    }
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

                    if (this.novoProduto.quantidade > this.novoProduto.estoque_atual) {
                        alert(`Quantidade informada (${this.novoProduto.quantidade}) é maior que o estoque disponível (${this.novoProduto.estoque_atual})`);
                        return;
                    }

                    // Verificar se o produto já foi adicionado
                    const jaExiste = this.itens.findIndex(item => item.produto_id === this.novoProduto.produto_id);
                    
                    if (jaExiste !== -1) {
                        if (confirm('Este produto já está na lista. Deseja atualizar a quantidade?')) {
                            const novaQuantidade = parseInt(this.novoProduto.quantidade);
                            if (novaQuantidade > this.novoProduto.estoque_atual) {
                                alert(`Quantidade informada (${novaQuantidade}) é maior que o estoque disponível (${this.novoProduto.estoque_atual})`);
                                return;
                            }
                            this.itens[jaExiste].quantidade = novaQuantidade;
                        }
                    } else {
                        // Adicionar novo item
                        this.itens.push({
                            produto_id: this.novoProduto.produto_id,
                            descricao: this.novoProduto.descricao,
                            tamanho: this.novoProduto.tamanho,
                            estoque_atual: this.novoProduto.estoque_atual,
                            quantidade: parseInt(this.novoProduto.quantidade)
                        });
                    }

                    // Limpar formulário
                    this.novoProduto = {
                        produto_id: '',
                        descricao: '',
                        tamanho: '',
                        estoque_atual: 0,
                        quantidade: 1
                    };

                    // Reset do select
                    document.querySelector('select[x-model="novoProduto.produto_id"]').value = '';
                },

                removerItem(index) {
                    if (confirm('Deseja remover este item da lista?')) {
                        this.itens.splice(index, 1);
                    }
                },

                calcularTotalQuantidade() {
                    return this.itens.reduce((total, item) => total + item.quantidade, 0);
                },

                limparTudo() {
                    if (confirm('Deseja limpar toda a lista de produtos?')) {
                        this.itens = [];
                        this.motivo = '';
                        this.observacao = '';
                    }
                },

                finalizarSaida() {
                    if (this.itens.length === 0) {
                        alert('Adicione pelo menos um produto antes de finalizar');
                        return;
                    }

                    if (!this.motivo) {
                        alert('Por favor, selecione o motivo da saída');
                        return;
                    }

                    if (!this.observacao || this.observacao.trim() === '') {
                        alert('Por favor, informe uma observação sobre a saída');
                        return;
                    }

                    const mensagem = 'Confirmar saída de ' + this.itens.length + ' produto(s) com total de ' + this.calcularTotalQuantidade() + ' unidades?';
                    if (confirm(mensagem)) {
                        // Criar formulário e enviar
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = urlSaidas;

                        // Token CSRF
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfTokenSaida;
                        form.appendChild(csrfInput);

                        // Itens
                        const itensInput = document.createElement('input');
                        itensInput.type = 'hidden';
                        itensInput.name = 'itens';
                        itensInput.value = JSON.stringify(this.itens);
                        form.appendChild(itensInput);

                        // Motivo
                        const motivoInput = document.createElement('input');
                        motivoInput.type = 'hidden';
                        motivoInput.name = 'motivo';
                        motivoInput.value = this.motivo;
                        form.appendChild(motivoInput);

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
