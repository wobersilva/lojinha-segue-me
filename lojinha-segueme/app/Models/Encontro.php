<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// ENCONTRO
// -------------------------------
class Encontro extends Model
{
    use HasFactory;

    protected $fillable = [
        'paroquia_id', 'user_id', 'nome', 'data_inicio', 'data_fim', 'status'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date'
    ];

    public function paroquia()
    {
        return $this->belongsTo(Paroquia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saidasProvisorias()
    {
        return $this->hasMany(SaidaProvisoria::class);
    }

    public function baixas()
    {
        return $this->hasMany(BaixaEstoque::class);
    }
}
