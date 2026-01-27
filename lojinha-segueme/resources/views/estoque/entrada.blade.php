<!-- ===================================================== -->
<!-- ESTOQUE - ENTRADA -->
<!-- resources/views/estoque/entrada.blade.php -->
<!-- ===================================================== -->
@extends('layouts.app')
@section('content')
<h3>Entrada de Produtos</h3>
<form method="POST" action="/estoque/entrada">
@csrf
<div class="mb-2">
<label>Produto</label>
<select name="produto_id" class="form-control">
@foreach($produtos as $produto)
<option value="{{ $produto->id }}">{{ $produto->descricao }} - {{ $produto->tamanho }}</option>
@endforeach
</select>
</div>
<div class="mb-2">
<label>Quantidade</label>
<input type="number" name="quantidade" class="form-control">
</div>
<div class="mb-2">
<label>Valor de Custo</label>
<input type="number" step="0.01" name="valor_custo" class="form-control">
</div>
<button class="btn btn-success">Registrar Entrada</button>
</form>
@endsection