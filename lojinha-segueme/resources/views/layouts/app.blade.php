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

        <!-- Session Expiration Script -->
        <script>
            // Marca quando o usuário fecha a última aba/janela do site
            window.addEventListener('beforeunload', function() {
                // Salva timestamp quando a janela é fechada
                sessionStorage.setItem('lastClosed', Date.now());
            });

            // Verifica ao carregar se deve fazer logout
            window.addEventListener('load', function() {
                const lastClosed = sessionStorage.getItem('lastClosed');
                
                // Se não houver registro, é a primeira vez
                if (!lastClosed) {
                    sessionStorage.setItem('isActive', 'true');
                    return;
                }
                
                // Se passou mais de 3 segundos desde o fechamento, considera que fechou o navegador
                const timeSinceClose = Date.now() - parseInt(lastClosed);
                
                // Remove o item após verificar
                sessionStorage.removeItem('lastClosed');
                sessionStorage.setItem('isActive', 'true');
            });
        </script>
    </head>
    <body class="font-sans antialiased">
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
    
</body>

</html>
