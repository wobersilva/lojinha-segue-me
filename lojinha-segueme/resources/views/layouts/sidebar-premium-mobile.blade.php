<div class="h-16 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-800">
    <div class="flex items-center gap-3">
        {{-- Logo --}}
        <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-white dark:bg-gray-800">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo Segue-me" 
                 class="w-full h-full object-contain"
                 width="40"
                 height="40"
                 loading="lazy">
        </div>
        <div class="font-semibold text-gray-900 dark:text-gray-100">Lojinha do Segue-me</div>
    </div>
    <button class="w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
            @click="sidebarOpen = false" aria-label="Fechar menu">
        <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<nav class="px-3 py-4 space-y-1">
    <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
        <span>📊</span><span>Dashboard</span>
    </a>
    <a href="{{ url('/produtos') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
        <span>📦</span><span>Produtos</span>
    </a>
    <a href="{{ url('/paroquias') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
        <span>⛪</span><span>Paróquias</span>
    </a>
    <a href="{{ url('/encontros') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
        <span>📅</span><span>Encontros</span>
    </a>

    {{-- Movimentações --}}
    <div x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            <span>💱</span>
            <span class="flex-1 text-left">Movimentações</span>
            <svg class="w-4 h-4 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="ml-8 mt-1 space-y-1">
            <a href="{{ url('/movimentacoes/entradas') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                <span>⬇️</span><span>Entradas</span>
            </a>
            <a href="{{ url('/movimentacoes/saidas') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                <span>⬆️</span><span>Saídas</span>
            </a>
            <a href="{{ url('/movimentacoes/historico') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
                <span>📜</span><span>Histórico</span>
            </a>
        </div>
    </div>

    <div class="pt-4">
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Relatórios
        </div>
        <a href="{{ url('/relatorios/inventario') }}" class="mt-2 flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            <span>🧾</span><span>Inventário</span>
        </a>
        <a href="{{ url('/relatorios/vendas-paroquia') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            <span>🏷️</span><span>Vendas por Paróquia</span>
        </a>
        <a href="{{ url('/relatorios/vendas-periodo') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            <span>📅</span><span>Vendas por Período</span>
        </a>
    </div>

    {{-- Administração (apenas para admins) --}}
    @if(auth()->user()->isAdmin())
    <div class="pt-4">
        <div class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            Administração
        </div>
        <a href="{{ url('/admin/users') }}" class="mt-2 flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
            <span>👥</span>
            <span>Gerenciar Usuários</span>
            @php
                $pendingCount = \App\Models\User::where('is_approved', false)->count();
            @endphp
            @if($pendingCount > 0)
                <span class="ml-auto px-2 py-0.5 bg-amber-600 dark:bg-amber-500 text-white rounded-full text-xs font-bold shadow-sm">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
    </div>
    @endif
</nav>
