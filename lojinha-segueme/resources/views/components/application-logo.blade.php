{{-- Logo para tela de login/autenticação --}}
<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <img src="{{ asset('images/logo.png') }}" 
         alt="Logo Segue-me" 
         class="w-full h-full object-contain"
         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-20 h-20 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-3xl font-bold\'>S</div>';">
</div>
