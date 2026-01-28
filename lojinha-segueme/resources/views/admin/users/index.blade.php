<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">Gerenciamento de Usuários</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Aprove ou rejeite solicitações de acesso</p>
            </div>
            @if($pendingCount > 0)
                <span class="px-4 py-2 bg-amber-600 dark:bg-amber-500 text-white rounded-lg font-semibold text-sm flex items-center gap-2 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ $pendingCount }} {{ $pendingCount === 1 ? 'pendente' : 'pendentes' }}
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                
                <!-- Usuários Pendentes -->
                @php
                    $pendingUsers = $users->where('is_approved', false);
                    $approvedUsers = $users->where('is_approved', true);
                @endphp

                @if($pendingUsers->count() > 0)
                <div class="bg-amber-50 dark:bg-amber-950/50 border-b border-amber-200 dark:border-amber-700/50 px-6 py-4">
                    <h3 class="text-lg font-bold text-amber-900 dark:text-amber-300 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Aguardando Aprovação ({{ $pendingUsers->count() }})
                    </h3>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($pendingUsers as $user)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-700 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">
                                        Solicitado em {{ $user->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Aprovar
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja rejeitar este usuário? Ele será removido do sistema.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Rejeitar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Usuários Aprovados -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Usuários Aprovados ({{ $approvedUsers->count() }})
                    </h3>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($approvedUsers as $user)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $user->is_admin ? 'from-purple-600 to-purple-800' : 'from-emerald-500 to-emerald-700' }} rounded-full flex items-center justify-center text-white font-bold text-xl relative shadow-md">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @if($user->is_admin)
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-500 dark:bg-yellow-400 rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="w-3 h-3 text-white dark:text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        @if($user->is_admin)
                                        <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-500 text-purple-800 dark:text-white rounded text-xs font-semibold border border-purple-200 dark:border-purple-400">
                                            Administrador
                                        </span>
                                        @endif
                                        @if($user->id === auth()->id())
                                        <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-500 text-blue-800 dark:text-white rounded text-xs font-semibold border border-blue-200 dark:border-blue-400">
                                            Você
                                        </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-200">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1">
                                        Aprovado em {{ $user->approved_at ? $user->approved_at->format('d/m/Y H:i') : 'N/A' }}
                                        @if($user->approver)
                                            por {{ $user->approver->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($user->id !== auth()->id())
                            <div class="flex items-center gap-2">
                                <!-- Botão Resetar Senha -->
                                <button 
                                    @click="$dispatch('open-reset-modal', { userId: {{ $user->id }}, userName: '{{ $user->name }}' })"
                                    class="px-4 py-2 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                    Resetar Senha
                                </button>

                                <!-- Botão Toggle Admin -->
                                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 {{ $user->is_admin ? 'bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600' : 'bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600' }} text-white rounded-lg font-medium text-sm transition-colors flex items-center gap-2 shadow-sm">
                                        @if($user->is_admin)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6z"/>
                                        </svg>
                                        Remover Admin
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        Tornar Admin
                                        @endif
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Nenhum usuário aprovado ainda</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Reset de Senha -->
    <div 
        x-data="{ 
            open: false, 
            userId: null, 
            userName: '',
            password: '',
            password_confirmation: '',
            showPassword: false,
            showPasswordConfirmation: false
        }"
        @open-reset-modal.window="open = true; userId = $event.detail.userId; userName = $event.detail.userName; password = ''; password_confirmation = ''"
        @keydown.escape.window="open = false"
        x-show="open"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Overlay -->
        <div 
            class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
            @click="open = false"
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md"
                @click.away="open = false"
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Resetar Senha</h3>
                                <p class="text-sm text-orange-100" x-text="userName"></p>
                            </div>
                        </div>
                        <button @click="open = false" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <form :action="`{{ url('/admin/users') }}/${userId}/reset-password`" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div class="text-sm text-amber-800 dark:text-amber-200">
                                <p class="font-semibold mb-1">Atenção!</p>
                                <p>Você está prestes a alterar a senha deste usuário. Certifique-se de informá-lo sobre a nova senha.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Nova Senha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nova Senha
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                x-model="password"
                                required
                                minlength="8"
                                class="w-full px-4 py-2.5 pr-12 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-transparent text-gray-900 dark:text-white"
                                placeholder="Mínimo 8 caracteres"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirmar Nova Senha
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                name="password_confirmation"
                                x-model="password_confirmation"
                                required
                                minlength="8"
                                class="w-full px-4 py-2.5 pr-12 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-transparent text-gray-900 dark:text-white"
                                placeholder="Repita a senha"
                            >
                            <button 
                                type="button"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                            >
                                <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Indicador de força da senha -->
                    <div x-show="password.length > 0">
                        <div class="flex gap-1 mb-2">
                            <div class="h-1 flex-1 rounded-full transition-colors" :class="password.length >= 8 ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"></div>
                            <div class="h-1 flex-1 rounded-full transition-colors" :class="password.length >= 10 ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"></div>
                            <div class="h-1 flex-1 rounded-full transition-colors" :class="password.length >= 12 ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600'"></div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            <span x-show="password.length < 8">Senha fraca - mínimo 8 caracteres</span>
                            <span x-show="password.length >= 8 && password.length < 10">Senha razoável</span>
                            <span x-show="password.length >= 10 && password.length < 12">Senha boa</span>
                            <span x-show="password.length >= 12">Senha forte</span>
                        </p>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-3 pt-4">
                        <button
                            type="button"
                            @click="open = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white rounded-lg font-medium transition-colors shadow-sm flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Resetar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
