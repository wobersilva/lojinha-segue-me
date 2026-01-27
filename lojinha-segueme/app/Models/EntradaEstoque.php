<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// ENTRADA DE ESTOQUE
// -------------------------------
class EntradaEstoque extends Model
{
    use HasFactory;

    protected $table = 'entradas_estoque';

    protected $fillable = [
        'produto_id', 'data_entrada', 'quantidade', 'valor_custo', 'observacoes'
    ];

    protected $casts = [
        'data_entrada' => 'date'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
