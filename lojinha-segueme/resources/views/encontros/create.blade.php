<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ➕ Novo Encontro
        </h2>
    </x-slot>

    <div class="py-6" x-data="novoEncontro()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Dados do Encontro --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Informações do Encontro
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nome do Encontro <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="encontro.nome" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Ex: Encontro de Jovens 2026"
                            oninput="this.value = this.value.toUpperCase()"
                            style="text-transform: uppercase">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Paróquia <span class="text-red-500">*</span>
                        </label>
                        <select x-model="encontro.paroquia_id" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Selecione uma paróquia...</option>
                            @foreach($paroquias as $paroquia)
                                <option value="{{ $paroquia->id }}">
                                    {{ $paroquia->nome }} — {{ $paroquia->cidade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Data Início <span class="text-red-500">*</span>
                        </label>
                        <input type="date" x-model="encontro.data_inicio" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Data Fim
                        </label>
                        <input type="date" x-model="encontro.data_fim"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
            </div>

            {{-- Adicionar Produtos --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Adicionar Produtos ao Encontro
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
                                @foreach($produtos as $produto)
                                    @php
                                        $estoqueAtual = $produto->estoque->quantidade ?? 0;
                                    @endphp
                                    <option value="{{ $produto->id }}" 
                                        data-descricao="{{ $produto->descricao }}" 
                                        data-tamanho="{{ $produto->tamanho }}"
                                        data-preco="{{ $produto->preco_custo }}"
                                        data-estoque="{{ $estoqueAtual }}">
                                        {{ $produto->descricao }} - {{ $produto->tamanho }} ({{ $estoqueAtual }} disp.)
                                    </option>
                                @endforeach
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
                                ➕ Adicionar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista de Produtos --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Produtos no Encontro
                    </h3>
                    <span class="text-sm text-gray-600 dark:text-gray-300" x-show="itens.length > 0">
                        <span x-text="itens.length"></span> item(ns)
                    </span>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto" x-show="itens.length > 0">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">Produto</th>
                                    <th class="py-3 pr-4">Tamanho</th>
                                    <th class="py-3 pr-4 text-right">Quantidade</th>
                                    <th class="py-3 pr-4 text-right">Preço Custo</th>
                                    <th class="py-3 pr-4 text-right">Total Previsto</th>
                                    <th class="py-3 w-20 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in itens" :key="index">
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100" x-text="item.descricao"></td>
                                        <td class="py-3 pr-4 text-gray-600 dark:text-gray-300" x-text="item.tamanho"></td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300" x-text="item.quantidade"></td>
                                        <td class="py-3 pr-4 text-right text-gray-600 dark:text-gray-300" x-text="formatarMoeda(item.preco_custo)"></td>
                                        <td class="py-3 pr-4 text-right font-semibold text-gray-900 dark:text-gray-100" x-text="formatarMoeda(item.total)"></td>
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
                                        Total Previsto:
                                    </td>
                                    <td class="py-4 pr-4 text-right font-bold text-lg text-indigo-600 dark:text-indigo-400" x-text="formatarMoeda(calcularTotal())"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div x-show="itens.length === 0" class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            Nenhum produto adicionado
                        </p>
                    </div>

                    <div x-show="itens.length > 0" class="flex gap-3 justify-end mt-6">
                        <a href="{{ route('encontros.index') }}"
                            class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
                            Cancelar
                        </a>
                        <button @click="criarEncontro()" type="button"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Criar Encontro
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const urlEncontros = "{{ route('encontros.store') }}";
        const csrfToken = "{{ csrf_token() }}";
        
        function novoEncontro() {
            return {
                encontro: {
                    nome: '',
                    paroquia_id: '',
                    data_inicio: new Date().toISOString().split('T')[0],
                    data_fim: new Date().toISOString().split('T')[0]
                },
                itens: [],
                novoProduto: {
                    produto_id: '',
                    descricao: '',
                    tamanho: '',
                    quantidade: 1,
                    preco_custo: 0,
                    estoque_atual: 0
                },

                atualizarProduto() {
                    const select = document.querySelector('select[x-model="novoProduto.produto_id"]');
                    const option = select.options[select.selectedIndex];
                    
                    if (option && option.value) {
                        this.novoProduto.descricao = option.dataset.descricao || '';
                        this.novoProduto.tamanho = option.dataset.tamanho || '';
                        this.novoProduto.preco_custo = parseFloat(option.dataset.preco) || 0;
                        this.novoProduto.estoque_atual = parseInt(option.dataset.estoque) || 0;
                    }
                },

                adicionarProduto() {
                    if (!this.novoProduto.produto_id) {
                        alert('Selecione um produto');
                        return;
                    }

                    if (!this.novoProduto.quantidade || this.novoProduto.quantidade <= 0) {
                        alert('Informe uma quantidade válida');
                        return;
                    }

                    if (this.novoProduto.quantidade > this.novoProduto.estoque_atual) {
                        alert(`Estoque insuficiente. Disponível: ${this.novoProduto.estoque_atual}`);
                        return;
                    }

                    const jaExiste = this.itens.findIndex(item => item.produto_id === this.novoProduto.produto_id);
                    
                    if (jaExiste !== -1) {
                        const quantidadeAtual = this.itens[jaExiste].quantidade;
                        const novaQuantidade = parseInt(this.novoProduto.quantidade);
                        const quantidadeTotal = quantidadeAtual + novaQuantidade;
                        
                        // Verifica se a quantidade total não ultrapassa o estoque
                        if (quantidadeTotal > this.novoProduto.estoque_atual) {
                            alert(`Estoque insuficiente! Já foram adicionadas ${quantidadeAtual} unidades. Disponível no estoque: ${this.novoProduto.estoque_atual}`);
                            return;
                        }
                        
                        if (confirm(`Produto já adicionado com ${quantidadeAtual} unidade(s). Deseja somar mais ${novaQuantidade} unidade(s)? (Total: ${quantidadeTotal})`)) {
                            this.itens[jaExiste].quantidade = quantidadeTotal;
                            this.itens[jaExiste].total = this.itens[jaExiste].quantidade * this.itens[jaExiste].preco_custo;
                        }
                    } else {
                        this.itens.push({
                            produto_id: this.novoProduto.produto_id,
                            descricao: this.novoProduto.descricao,
                            tamanho: this.novoProduto.tamanho,
                            quantidade: parseInt(this.novoProduto.quantidade),
                            preco_custo: this.novoProduto.preco_custo,
                            total: parseInt(this.novoProduto.quantidade) * this.novoProduto.preco_custo
                        });
                    }

                    this.novoProduto = {
                        produto_id: '',
                        descricao: '',
                        tamanho: '',
                        quantidade: 1,
                        preco_custo: 0,
                        estoque_atual: 0
                    };

                    document.querySelector('select[x-model="novoProduto.produto_id"]').value = '';
                },

                removerItem(index) {
                    if (confirm('Remover este produto?')) {
                        this.itens.splice(index, 1);
                    }
                },

                calcularTotal() {
                    return this.itens.reduce((total, item) => total + item.total, 0);
                },

                formatarMoeda(valor) {
                    return 'R$ ' + parseFloat(valor).toFixed(2).replace('.', ',');
                },

                async criarEncontro() {
                    if (!this.encontro.nome) {
                        alert('Informe o nome do encontro');
                        return;
                    }

                    if (!this.encontro.paroquia_id) {
                        alert('Selecione uma paróquia');
                        return;
                    }

                    if (!this.encontro.data_inicio) {
                        alert('Informe a data de início');
                        return;
                    }

                    if (this.itens.length === 0) {
                        alert('Adicione pelo menos um produto');
                        return;
                    }

                    const mensagem = 'Criar encontro com ' + this.itens.length + ' produto(s)?\n\nApós criar, o relatório de saída será aberto para impressão.';
                    if (!confirm(mensagem)) {
                        return;
                    }

                    try {
                        const formData = new FormData();
                        formData.append('_token', csrfToken);
                        formData.append('nome', this.encontro.nome);
                        formData.append('paroquia_id', this.encontro.paroquia_id);
                        formData.append('data_inicio', this.encontro.data_inicio);
                        formData.append('data_fim', this.encontro.data_fim || '');
                        formData.append('itens', JSON.stringify(this.itens));

                        const response = await fetch(urlEncontros, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Abre o relatório em nova aba
                            window.open(data.relatorio_url, '_blank');
                            
                            // Redireciona para a página do encontro
                            alert(data.message);
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Erro: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        alert('Erro ao criar encontro. Tente novamente.');
                    }
                }
            }
        }
    </script>
</x-app-layout>
