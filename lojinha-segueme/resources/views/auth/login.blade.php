<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Manter conectado') }}</span>
            </label>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                Se não marcar, sua sessão expirará ao fechar o navegador
            </p>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Entrar') }}
            </x-primary-button>
        </div>

        <!-- Link para criar conta -->
        @if (Route::has('register'))
            <div class="mt-6 text-center border-t border-gray-200 dark:border-gray-700 pt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Não tem uma conta?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Criar cadastro
                    </a>
                </p>
            </div>
        @endif
    </form>

    <!-- Script para salvar preferência de manter conectado -->
    <script>
        document.getElementById('login-form').addEventListener('submit', function() {
            const manterConectado = document.getElementById('remember_me').checked;
            localStorage.setItem('manter_conectado', manterConectado ? 'true' : 'false');
            
            // Limpa flags de sessão anterior
            localStorage.removeItem('lojinha_close_time');
            sessionStorage.removeItem('lojinha_session_active');
        });
        
        // Ao carregar a página de login, limpa as flags (usuário fez logout ou sessão expirou)
        localStorage.removeItem('manter_conectado');
        localStorage.removeItem('lojinha_close_time');
        sessionStorage.removeItem('lojinha_session_active');
    </script>
</x-guest-layout>
