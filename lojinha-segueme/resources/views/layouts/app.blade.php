<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lojinha - Segue-me') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-icon.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Dark Mode Script (must run before page renders) -->
        <script>
            // Apply dark mode immediately to prevent flash
            (function() {
                const darkMode = localStorage.getItem('darkMode') === 'true' || 
                    (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                if (darkMode) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Estilos do Loader (inline para carregar primeiro) -->
        <style>
            /* Loader principal */
            #global-loader {
                position: fixed;
                inset: 0;
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                opacity: 1;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
            
            #global-loader.fade-out {
                opacity: 0;
                pointer-events: none;
            }
            
            /* Previne scroll e flash enquanto carrega */
            body.page-loading {
                overflow: hidden;
            }
            
            body.page-loading #main-content {
                visibility: hidden;
            }
            
            /* Animações modernas */
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes pulse-ring {
                0% {
                    transform: scale(0.8);
                    opacity: 1;
                }
                100% {
                    transform: scale(1.4);
                    opacity: 0;
                }
            }
            
            @keyframes rotate-smooth {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            @keyframes dots {
                0%, 20% { content: '.'; }
                40% { content: '..'; }
                60%, 100% { content: '...'; }
            }
            
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            
            .animate-pulse-ring {
                animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            .animate-rotate-smooth {
                animation: rotate-smooth 2s linear infinite;
            }
            
            .loading-dots::after {
                content: '.';
                animation: dots 1.5s steps(1) infinite;
            }
        </style>
        
        <!-- Script do Loader (executa antes do body) -->
        <script>
            // Adiciona classe ao body para prevenir flash
            document.documentElement.classList.add('page-loading');
            if (document.body) {
                document.body.classList.add('page-loading');
            }
        </script>

        <!-- Session Expiration Script - Logout ao fechar aba/navegador -->
        <script>
            (function() {
                const SESSION_KEY = 'lojinha_tab_id';
                const TABS_KEY = 'lojinha_open_tabs';
                
                // Verifica se o usuário marcou "manter conectado"
                const manterConectado = localStorage.getItem('manter_conectado') === 'true';
                
                // Se marcou manter conectado, não faz logout ao fechar
                if (manterConectado) {
                    sessionStorage.setItem(SESSION_KEY, 'active');
                    return;
                }
                
                // Gera ID único para esta aba
                const tabId = 'tab_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                
                // Verifica se é uma nova sessão (navegador foi fechado e reaberto)
                const existingTabId = sessionStorage.getItem(SESSION_KEY);
                const openTabs = JSON.parse(localStorage.getItem(TABS_KEY) || '{}');
                
                // Se não tem ID de aba no sessionStorage E existem abas registradas no localStorage
                // significa que o navegador/aba foi fechado e reaberto
                if (!existingTabId && Object.keys(openTabs).length > 0) {
                    // Limpa as abas antigas
                    localStorage.setItem(TABS_KEY, '{}');
                    
                    // Faz logout
                    const logoutForm = document.createElement('form');
                    logoutForm.method = 'POST';
                    logoutForm.action = '{{ route("logout") }}';
                    logoutForm.style.display = 'none';
                    logoutForm.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                    document.body.appendChild(logoutForm);
                    logoutForm.submit();
                    return;
                }
                
                // Registra esta aba
                sessionStorage.setItem(SESSION_KEY, tabId);
                openTabs[tabId] = Date.now();
                localStorage.setItem(TABS_KEY, JSON.stringify(openTabs));
                
                // Ao fechar a aba, remove do registro
                window.addEventListener('beforeunload', function() {
                    const tabs = JSON.parse(localStorage.getItem(TABS_KEY) || '{}');
                    delete tabs[tabId];
                    localStorage.setItem(TABS_KEY, JSON.stringify(tabs));
                });
                
                // Heartbeat para manter registro atualizado (a cada 30s)
                setInterval(function() {
                    const tabs = JSON.parse(localStorage.getItem(TABS_KEY) || '{}');
                    tabs[tabId] = Date.now();
                    localStorage.setItem(TABS_KEY, JSON.stringify(tabs));
                    
                    // Remove abas inativas (mais de 60 segundos sem heartbeat)
                    const now = Date.now();
                    for (const id in tabs) {
                        if (now - tabs[id] > 60000) {
                            delete tabs[id];
                        }
                    }
                    localStorage.setItem(TABS_KEY, JSON.stringify(tabs));
                }, 30000);
            })();
        </script>
    </head>
    <body class="font-sans antialiased page-loading">
    
    {{-- Tela de Carregamento Global - Design Moderno --}}
    <div id="global-loader">
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 w-full h-full flex items-center justify-center relative overflow-hidden">
            
            {{-- Efeito de fundo animado --}}
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl animate-pulse-ring"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse-ring" style="animation-delay: 1s;"></div>
            </div>
            
            <div class="text-center relative z-10">
                {{-- Logo Minimalista com Efeito Float --}}
                <div class="mb-8 animate-float">
                    <div class="relative inline-block">
                        {{-- Anel pulsante externo --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl opacity-20 blur-xl scale-110"></div>
                        
                        {{-- Container do ícone --}}
                        <div class="relative w-20 h-20 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <svg class="w-11 h-11 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                {{-- Spinner Minimalista --}}
                <div class="mb-8 flex justify-center">
                    <div class="relative w-16 h-16">
                        {{-- Círculo base --}}
                        <div class="absolute inset-0 border-4 border-gray-200 dark:border-gray-700 rounded-full opacity-25"></div>
                        
                        {{-- Círculo animado gradiente --}}
                        <div class="absolute inset-0 border-4 border-transparent border-t-indigo-500 border-r-purple-500 rounded-full animate-rotate-smooth"></div>
                        
                        {{-- Ponto central --}}
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-2 h-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Texto Elegante --}}
                <div class="space-y-3">
                    <h3 class="text-xl font-light text-gray-800 dark:text-gray-100 tracking-wide">
                        <span class="loading-text">Carregando</span><span class="loading-dots"></span>
                    </h3>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Aguarde um momento
                    </p>
                </div>
                
                {{-- Barra de Progresso Minimalista --}}
                <div class="mt-8 w-64 mx-auto">
                    <div class="h-0.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" 
                             style="animation: progress 2s ease-in-out infinite;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes progress {
            0% {
                width: 0%;
                opacity: 0.8;
            }
            50% {
                width: 70%;
                opacity: 1;
            }
            100% {
                width: 100%;
                opacity: 0.8;
            }
        }
    </style>
    
    <div
        x-data="{
            sidebarOpen: false,
            collapsed: (localStorage.getItem('sidebar-collapsed') === '1'),
            darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggleCollapsed() {
                this.collapsed = !this.collapsed;
                localStorage.setItem('sidebar-collapsed', this.collapsed ? '1' : '0');
            },
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode ? 'true' : 'false');
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        }"
        id="main-content"
        class="min-h-screen bg-gray-100 dark:bg-gray-900"
    >
        <div class="flex min-h-screen">

            {{-- Sidebar (desktop) --}}
            @include('layouts.sidebar-premium')

            {{-- Mobile overlay --}}
            <div
                x-show="sidebarOpen"
                x-transition.opacity
                class="fixed inset-0 z-40 bg-black/50 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            {{-- Mobile sidebar --}}
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 lg:hidden"
            >
                @include('layouts.sidebar-premium-mobile')
            </div>

            {{-- Main --}}
            <div class="flex-1 min-w-0">

                {{-- Topbar --}}
                <header class="sticky top-0 z-30 bg-white/70 dark:bg-gray-900/70 backdrop-blur border-b border-gray-200 dark:border-gray-800">
                    <div class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">

                        <div class="flex items-center gap-3">
                            {{-- Mobile menu button --}}
                            <button
                                class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="sidebarOpen = true"
                                aria-label="Abrir menu"
                            >
                                {{-- hamburger icon --}}
                                <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <div class="font-semibold text-gray-900 dark:text-gray-100">
                                Lojinha do Segue-me
                            </div>

                            {{-- Desktop collapse button --}}
                            <button
                                class="hidden lg:inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                                @click="toggleCollapsed()"
                                :title="collapsed ? 'Expandir menu' : 'Recolher menu'"
                            >
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path x-show="!collapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    <path x-show="collapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden sm:block text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()->name ?? '' }}
                            </div>

                            {{-- Dark Mode Toggle --}}
                            <button
                                @click="toggleDarkMode()"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                :title="darkMode ? 'Modo Claro' : 'Modo Escuro'"
                                :aria-label="darkMode ? 'Ativar modo claro' : 'Ativar modo escuro'"
                            >
                                {{-- Sun icon (shown in dark mode) --}}
                                <svg x-show="darkMode" x-cloak class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{-- Moon icon (shown in light mode) --}}
                                <svg x-show="!darkMode" x-cloak class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </button>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="px-3 py-2 rounded-lg text-sm bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-700">
                                    Sair
                                </button>
                            </form>
                        </div>

                    </div>
                </header>

                {{-- Header slot (Breeze) --}}
                @isset($header)
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @endisset

                {{-- Page content --}}
                <main class="pb-10 text-gray-900 dark:text-gray-100">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </div>
 {{-- Toast Pop-up Global --}}
    <div id="toast-root" class="fixed top-4 right-4 z-[9999] space-y-3 pointer-events-none"></div>

    @php
        $flashMessages = [
            'success' => session('success'),
            'error' => session('error'),
            'warning' => session('warning'),
            'info' => session('info'),
        ];
        // Remove valores null do array
        $flashMessages = array_filter($flashMessages, function($value) {
            return $value !== null && $value !== '';
        });
    @endphp
    
    @if(!empty($flashMessages))
    <div id="flash-messages" data-messages='{!! json_encode($flashMessages, JSON_HEX_APOS | JSON_HEX_QUOT) !!}' style="display: none;"></div>
    @endif

    <script>
    (function () {
        function toast(message, type = 'success', duration = 3500) {
            if (!message) return;

            const root = document.getElementById('toast-root');
            if (!root) return;

            const styles = {
                success: 'bg-emerald-500/15 text-emerald-200 ring-1 ring-emerald-500/30',
                error:   'bg-red-500/15 text-red-200 ring-1 ring-red-500/30',
                warning: 'bg-amber-500/15 text-amber-200 ring-1 ring-amber-500/30',
                info:    'bg-indigo-500/15 text-indigo-200 ring-1 ring-indigo-500/30',
            };

            const icons = {
                success: '<path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>',
                error:   '<path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>',
                warning: '<path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
                info:    '<path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8h.01"/>',
            };

            const el = document.createElement('div');
            el.className = `pointer-events-auto w-full max-w-sm rounded-2xl px-4 py-3 shadow-lg backdrop-blur transform transition
                            ${styles[type] || styles.info}`;
            el.style.opacity = '0';
            el.style.transform = 'translateY(8px)';

            el.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            ${icons[type] || icons.info}
                        </svg>
                    </div>
                    <div class="flex-1 text-sm leading-relaxed">${escapeHtml(message)}</div>
                    <button class="opacity-70 hover:opacity-100" aria-label="Fechar">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            const closeBtn = el.querySelector('button');
            closeBtn.addEventListener('click', () => remove());

            root.appendChild(el);

            // animate in
            requestAnimationFrame(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            });

            const timer = setTimeout(remove, duration);

            function remove() {
                clearTimeout(timer);
                el.style.opacity = '0';
                el.style.transform = 'translateY(8px)';
                setTimeout(() => el.remove(), 180);
            }
        }

        function escapeHtml(str) {
            return String(str).replace(/[&<>"']/g, (m) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;',
                '"': '&quot;', "'": '&#039;'
            }[m]));
        }

        // Expor função global
        window.toast = toast;

        // Dispara automaticamente se houver flash message do Laravel
        document.addEventListener('DOMContentLoaded', function() {
            const flashEl = document.getElementById('flash-messages');
            if (flashEl) {
                try {
                    const flashMessages = JSON.parse(flashEl.getAttribute('data-messages'));
                    if (flashMessages.success) toast(flashMessages.success, 'success');
                    if (flashMessages.error) toast(flashMessages.error, 'error');
                    if (flashMessages.warning) toast(flashMessages.warning, 'warning');
                    if (flashMessages.info) toast(flashMessages.info, 'info');
                } catch (e) {
                    console.error('Erro ao processar mensagens flash:', e);
                }
            }
        });
    })();
    </script>
    
    {{-- Script de Controle do Loader Global --}}
    <script>
    (function() {
        const loader = document.getElementById('global-loader');
        const body = document.body;
        const html = document.documentElement;
        
        // Função para esconder o loader
        function hideLoader() {
            if (loader) {
                loader.classList.add('fade-out');
                body.classList.remove('page-loading');
                html.classList.remove('page-loading');
                
                // Remove o elemento após a animação
                setTimeout(() => {
                    if (loader && loader.parentNode) {
                        loader.remove();
                    }
                }, 400);
            }
        }
        
        // Função para mostrar o loader
        function showLoader(text = 'Carregando...') {
            let currentLoader = document.getElementById('global-loader');
            
            if (!currentLoader) {
                // Cria um novo loader
                const loaderHTML = `
                    <div id="global-loader">
                        <div class="bg-white dark:bg-gray-900 w-full h-full flex items-center justify-center">
                            <div class="text-center">
                                <div class="mb-6 animate-bounce">
                                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                        <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="relative w-24 h-24 mx-auto mb-6">
                                    <div class="absolute inset-0 border-4 border-gray-200 dark:border-gray-700 rounded-full"></div>
                                    <div class="absolute inset-0 border-4 border-transparent border-t-indigo-600 border-r-purple-600 rounded-full animate-spin"></div>
                                    <div class="absolute inset-3 bg-gradient-to-br from-indigo-500/20 to-purple-600/20 rounded-full animate-pulse"></div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">${text}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 animate-pulse">Aguarde um momento</p>
                                </div>
                                <div class="mt-6 w-48 h-1 mx-auto bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 animate-loading-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.insertAdjacentHTML('afterbegin', loaderHTML);
                body.classList.add('page-loading');
                html.classList.add('page-loading');
            } else {
                currentLoader.classList.remove('fade-out');
                body.classList.add('page-loading');
                html.classList.add('page-loading');
            }
        }
        
        // Esconde quando a página estiver completamente carregada
        if (document.readyState === 'complete') {
            setTimeout(hideLoader, 100);
        } else {
            window.addEventListener('load', function() {
                setTimeout(hideLoader, 100);
            });
        }
        
        // Backup de segurança: esconde após 5 segundos
        setTimeout(hideLoader, 5000);
        
        // Mostra loader ao submeter formulários
        document.addEventListener('submit', function(e) {
            const form = e.target;
            
            // Ignora formulários específicos
            if (form.action.includes('logout') || 
                form.classList.contains('no-loader') ||
                form.method.toUpperCase() === 'GET') {
                return;
            }
            
            showLoader('Processando...');
        });
        
        // Mostra loader em cliques de links internos
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            
            if (link && 
                link.href && 
                !link.target && 
                !link.download && 
                !link.classList.contains('no-loader') &&
                link.href.startsWith(window.location.origin) &&
                !link.href.includes('#') &&
                !link.href.includes('javascript:')) {
                
                showLoader('Carregando...');
            }
        }, true);
        
        // Expõe funções globalmente
        window.showLoader = showLoader;
        window.hideLoader = hideLoader;
        
    })();
    </script>


    
</body>

</html>
