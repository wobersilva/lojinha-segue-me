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
    {{-- PARÓQUIA --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Paróquia</label>

        @php($nomeSelecionado = old('nome', $paroquia->nome ?? ''))

        @php($selecionado = old('nome', ($paroquia->nome ?? '').'||'.($paroquia->cidade ?? '')))

        <select id="paroquia_nome" name="nome" class="{{ $inputClass }}" required>
            <option value="">Selecione uma paróquia...</option>

            @foreach(($paroquiasTxt ?? collect()) as $p)
                @php($valor = ($p['nome'] ?? '').'||'.($p['cidade'] ?? ''))

                <option value="{{ $valor }}" @selected($selecionado === $valor)>
                    {{ $p['nome'] ?? '' }} — {{ $p['cidade'] ?? '' }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- CIDADE (AUTOMÁTICA / DESABILITADA) --}}
    <div style="padding: 1%;">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Cidade
            <span class="text-xs text-gray-400">(preenchida automaticamente)</span>
        </label>

        @php($cidadeSelecionada = old('cidade', $paroquia->cidade ?? ''))

        {{-- Campo visível, travado --}}
        <input
            id="cidade_display"
            type="text"
            class="{{ $inputClass }} opacity-80 cursor-not-allowed"
            value="{{ $cidadeSelecionada }}"
            disabled>

        {{-- Campo real enviado no POST --}}
        <input
            type="hidden"
            name="cidade"
            id="cidade_hidden"
            value="{{ $cidadeSelecionada }}"
        >
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
    });

</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectParoquia = document.getElementById('paroquia_nome');
    const cidadeDisplay = document.getElementById('cidade_display');
    const cidadeHidden = document.getElementById('cidade_hidden');

    if (!selectParoquia || !cidadeDisplay || !cidadeHidden) return;

    function atualizarCidade() {
        const valor = (selectParoquia.value || '').trim();
        if (!valor.includes('||')) {
            cidadeDisplay.value = '';
            cidadeHidden.value = '';
            return;
        }

        const partes = valor.split('||');
        const cidade = (partes[1] || '').trim();

        cidadeDisplay.value = cidade;
        cidadeHidden.value = cidade;
    }

    selectParoquia.addEventListener('change', atualizarCidade);
    atualizarCidade();
});
</script>


