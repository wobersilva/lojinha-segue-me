@php
    $inputClass = "w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900
                   focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900 dark:text-gray-100
                   placeholder-gray-400";
@endphp

@if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
        <div class="font-medium mb-1">Corrija os campos abaixo:</div>
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4" style="padding: 1%;">
    {{-- NOME DA PARÓQUIA --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Paróquia</label>

        @if(isset($paroquia) && $paroquia->id)
            {{-- Modo EDIÇÃO: campo editável --}}
            <input
                name="nome"
                type="text"
                class="{{ $inputClass }}"
                value="{{ old('nome', $paroquia->nome ?? '') }}"
                style="text-transform: uppercase"
                oninput="this.value = this.value.toUpperCase()"
                required
            >
        @else
            {{-- Modo CRIAÇÃO: select com paróquias do BANCO --}}
            <select id="paroquia_select" name="nome" class="{{ $inputClass }}" required>
                <option value="">Selecione uma paróquia...</option>
                
                @foreach(($paroquiasBanco ?? collect()) as $p)
                    <option 
                        value="{{ $p->nome ?? $p['nome'] ?? '' }}"
                        data-cidade="{{ $p->cidade ?? $p['cidade'] ?? '' }}"
                        @selected(old('nome') === ($p->nome ?? $p['nome'] ?? ''))>
                        {{ $p->nome ?? $p['nome'] ?? '' }} — {{ $p->cidade ?? $p['cidade'] ?? '' }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>

    {{-- CIDADE --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Cidade
            @if(!isset($paroquia) || !$paroquia->id)
                <span class="text-xs text-gray-400">(preenchida automaticamente)</span>
            @endif
        </label>

        @if(isset($paroquia) && $paroquia->id)
            {{-- Modo EDIÇÃO: campo editável --}}
            <input
                name="cidade"
                type="text"
                class="{{ $inputClass }}"
                value="{{ old('cidade', $paroquia->cidade ?? '') }}"
                style="text-transform: uppercase"
                oninput="this.value = this.value.toUpperCase()"
                required
            >
        @else
            {{-- Modo CRIAÇÃO: campo automático --}}
            <input
                id="cidade_display"
                type="text"
                class="{{ $inputClass }} opacity-80 cursor-not-allowed"
                value="{{ old('cidade', '') }}"
                disabled>
            
            <input
                type="hidden"
                name="cidade"
                id="cidade_hidden"
                value="{{ old('cidade', '') }}"
            >
        @endif
    </div>

    {{-- TELEFONE --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
        <input
            name="contato"
            type="tel"
            class="{{ $inputClass }}"
            value="{{ old('contato', $paroquia->contato ?? '') }}"
            placeholder="(00) 00000-0000"
            maxlength="15"
            oninput="this.value = formatarTelefone(this.value)"
            onkeypress="return apenasNumeros(event)"
            required
        >
    </div>

    {{-- RESPONSÁVEL --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Responsável</label>
        <input
            name="responsavel"
            type="text"
            class="{{ $inputClass }}"
            value="{{ old('responsavel', $paroquia->responsavel ?? '') }}"
            style="text-transform: uppercase"
            oninput="this.value = this.value.toUpperCase()"
            required
        >
    </div>

    {{-- STATUS --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
        <select name="status" class="{{ $inputClass }}" required>
            @php($status = old('status', $paroquia->status ?? 'ativa'))
            <option value="ativa" @selected($status === 'ativa')>Ativa</option>
            <option value="inativa" @selected($status === 'inativa')>Inativa</option>
        </select>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    // ====== TELEFONE ======
    function apenasNumeros(event) {
        const char = String.fromCharCode(event.which);
        if (!/[0-9]/.test(char)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function formatarTelefone(valor) {
        const numeros = valor.replace(/\D/g, '');
        const numerosLimitados = numeros.substring(0, 11);

        if (numerosLimitados.length <= 2) {
            return numerosLimitados;
        } else if (numerosLimitados.length <= 7) {
            return `(${numerosLimitados.substring(0, 2)}) ${numerosLimitados.substring(2)}`;
        } else {
            return `(${numerosLimitados.substring(0, 2)}) ${numerosLimitados.substring(2, 7)}-${numerosLimitados.substring(7)}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const telefoneInput = document.querySelector('input[name="contato"]');
        if (telefoneInput && telefoneInput.value) {
            telefoneInput.value = formatarTelefone(telefoneInput.value);
        }

        // ====== AUTO-PREENCHER CIDADE (apenas no modo criação) ======
        const selectParoquia = document.getElementById('paroquia_select');
        const cidadeDisplay = document.getElementById('cidade_display');
        const cidadeHidden = document.getElementById('cidade_hidden');

        if (selectParoquia && cidadeDisplay && cidadeHidden) {
            function atualizarCidade() {
                const option = selectParoquia.options[selectParoquia.selectedIndex];
                const cidade = option.dataset.cidade || '';
                
                cidadeDisplay.value = cidade;
                cidadeHidden.value = cidade;
            }

            selectParoquia.addEventListener('change', atualizarCidade);
            
            // Preenche ao carregar se já houver valor selecionado
            if (selectParoquia.value) {
                atualizarCidade();
            }
        }
    });
</script>


