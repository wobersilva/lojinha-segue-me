@php
    $isActive = fn ($path) => request()->is(trim($path, '/').'*');
    $linkClass = function ($active) {
        return $active
            ? 'bg-indigo-600 text-white'
            : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800';
    };
@endphp

<aside
    class="hidden lg:flex lg:flex-col border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 transition-all duration-200"
    :class="collapsed ? 'w-20' : 'w-72'"
>
    <div class="h-16 flex items-center px-4 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center gap-3 w-full">
            {{-- Logo --}}
            <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-white dark:bg-gray-800">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Logo Segue-me" 
                     class="w-full h-full object-contain"
                     width="40"
                     height="40"
                     loading="lazy"
                     x-show="!collapsed">
                <img src="{{ asset('images/logo-icon.png') }}" 
                     alt="Logo Segue-me" 
                     class="w-full h-full object-contain"
                     width="40"
                     height="40"
                     loading="lazy"
                     x-show="collapsed"
                     x-cloak>
            </div>
            <div class="min-w-0" x-show="!collapsed">
                <div class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">Segue-me</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Controle de Estoque</div>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ url('/dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('dashboard')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM3 21h8v-6H3v6zm10-10h8V3h-8v8z"/>
            </svg>
            <span x-show="!collapsed">Dashboard</span>
        </a>

        {{-- Produtos --}}
        <a href="{{ url('/produtos') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('produtos')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0l-8 6-8-6m16 0H4"/>
            </svg>
            <span x-show="!collapsed">Produtos</span>
        </a>

        {{-- Paróquias --}}
        <a href="{{ url('/paroquias') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('paroquias')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 6v14H5V8l7-6z"/>
            </svg>
            <span x-show="!collapsed">Paróquias</span>
        </a>

        {{-- Encontros --}}
        <a href="{{ url('/encontros') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('encontros')) }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed">Encontros</span>
        </a>

        {{-- Movimentações --}}
        <div x-data="{ open: {{ $isActive('movimentacoes') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('movimentacoes')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                <span x-show="!collapsed" class="flex-1 text-left">Movimentações</span>
                <svg x-show="!collapsed" class="w-4 h-4 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open && !collapsed" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="ml-8 mt-1 space-y-1">
                <a href="{{ url('/movimentacoes/entradas') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('movimentacoes/entradas')) }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4"/>
                    </svg>
                    <span>Entradas</span>
                </a>
                <a href="{{ url('/movimentacoes/saidas') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('movimentacoes/saidas')) }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                    <span>Saídas</span>
                </a>
                <a href="{{ url('/movimentacoes/historico') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('movimentacoes/historico')) }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Histórico</span>
                </a>
            </div>
        </div>

        <div class="pt-4">
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400" x-show="!collapsed">
                Relatórios
            </div>

            <a href="{{ url('/relatorios/inventario') }}"
               class="mt-2 flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('relatorios/inventario')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14H5V6a2 2 0 012-2z"/>
                </svg>
                <span x-show="!collapsed">Inventário</span>
            </a>

            <a href="{{ url('/relatorios/vendas-paroquia') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('relatorios/vendas-paroquia')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 12h12M8 17h12M4 7h.01M4 12h.01M4 17h.01"/>
                </svg>
                <span x-show="!collapsed">Vendas por Paróquia</span>
            </a>

            <a href="{{ url('/relatorios/vendas-periodo') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('relatorios/vendas-periodo')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M6 21h12a2 2 0 002-2V7H4v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="!collapsed">Vendas por Período</span>
            </a>
        </div>

        {{-- Administração (apenas para admins) --}}
        @if(auth()->user()->isAdmin())
        <div class="pt-4">
            <div class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400" x-show="!collapsed">
                Administração
            </div>

            <a href="{{ url('/admin/users') }}"
               class="mt-2 flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition {{ $linkClass($isActive('admin/users')) }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="!collapsed">Gerenciar Usuários</span>
                @php
                    $pendingCount = \App\Models\User::where('is_approved', false)->count();
                @endphp
                @if($pendingCount > 0)
                    <span x-show="!collapsed" class="ml-auto px-2 py-0.5 bg-amber-600 dark:bg-amber-500 text-white rounded-full text-xs font-bold shadow-sm">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
        </div>
        @endif
    </nav>

    <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-800">
        <div class="text-xs text-gray-500 dark:text-gray-400" x-show="!collapsed">
            Logado como
        </div>
        <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" x-show="!collapsed">
            {{ auth()->user()->name ?? '' }}
        </div>
    </div>
</aside>
