<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// -------------------------------
// PAROQUIA
// -------------------------------
class Paroquia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'cidade', 'responsavel', 'contato', 'status'
    ];

    public function encontros()
    {
        return $this->hasMany(Encontro::class);
    }
}
