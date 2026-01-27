<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// SAIDA PROVISORIA
// -------------------------------
class SaidaProvisoria extends Model
{
    use HasFactory;

    protected $table = 'saidas_provisorias';

    protected $fillable = [
        'encontro_id', 'produto_id', 'quantidade', 'data_saida'
    ];

    protected $casts = [
        'data_saida' => 'date'
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
