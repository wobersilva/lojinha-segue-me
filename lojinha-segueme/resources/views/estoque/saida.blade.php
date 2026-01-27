<!-- ===================================================== -->
<!-- ESTOQUE - SAÍDA PROVISÓRIA -->
<!-- resources/views/estoque/saida.blade.php -->
<!-- ===================================================== -->
@extends('layouts.app')
@section('content')
<h3>Saída Provisória para Encontro</h3>
<form method="POST" action="/estoque/saida">
@csrf
<div class="mb-2">
<label>Encontro</label>
<select name="encontro_id" class="form-control">
@foreach($encontros as $encontro)
<option value="{{ $encontro->id }}">{{ $encontro->nome }}</option>
@endforeach
</select>
</div>
<div class="mb-2">
<label>Produto</label>
<select name="produto_id" class="form-control">
@foreach($produtos as $produto)
<option value="{{ $produto->id }}">{{ $produto->descricao }}</option>
@endforeach
</select>
</div>
<div class="mb-2">
<label>Quantidade</label>
<input type="number" name="quantidade" class="form-control">
</div>
<button class="btn btn-warning">Enviar para Encontro</button>
</form>
@endsection