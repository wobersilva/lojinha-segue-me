<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// ESTOQUE
// -------------------------------
class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque';

    protected $fillable = [
        'produto_id', 'quantidade'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
