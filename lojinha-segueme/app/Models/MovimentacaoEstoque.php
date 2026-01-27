<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_id',
        'tipo',
        'quantidade',
        'motivo',
        'observacoes',
        'data_movimentacao'
    ];

    protected $casts = [
        'data_movimentacao' => 'date'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
