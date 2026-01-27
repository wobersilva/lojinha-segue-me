<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ Editar Paróquia
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-2xl">
                <div class="p-6">
                    <form method="POST" action="{{ route('paroquias.update', $paroquia) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @include('paroquias.form', [
                            'paroquia' => $paroquia,
                            'paroquiasTxt' => $paroquiasTxt ?? collect(),
                            'cidadesTxt' => $cidadesTxt ?? collect()
                        ])

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('paroquias.index') }}"
                               class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600">
                                Cancelar
                            </a>
                            <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
