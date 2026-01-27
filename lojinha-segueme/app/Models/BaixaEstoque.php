<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// BAIXA DE ESTOQUE
// -------------------------------
class BaixaEstoque extends Model
{
    use HasFactory;

    protected $table = 'baixas_estoque';

    protected $fillable = [
        'encontro_id', 'produto_id', 'quantidade_vendida',
        'quantidade_devolvida', 'valor_total', 'data_baixa'
    ];

    protected $casts = [
        'data_baixa' => 'date'
    ];

    public function encontro()
    {
        return $this->belongsTo(Encontro::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
