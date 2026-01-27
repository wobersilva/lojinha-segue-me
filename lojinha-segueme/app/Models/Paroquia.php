<?php
// =====================================================
// MODELS ELOQUENT + RELACIONAMENTOS
// LOJINHA DO SEGUE-ME
// =====================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // =============================================
    // SCOPES - Filtros reutilizáveis
    // =============================================

    /**
     * Filtra apenas paróquias ativas
     */
    public function scopeAtiva(Builder $query): Builder
    {
        return $query->where('status', 'ativa');
    }

    /**
     * Filtra apenas paróquias inativas
     */
    public function scopeInativa(Builder $query): Builder
    {
        return $query->where('status', 'inativa');
    }

    /**
     * Filtra por cidade
     */
    public function scopeDaCidade(Builder $query, string $cidade): Builder
    {
        return $query->where('cidade', $cidade);
    }

    // =============================================
    // RELACIONAMENTOS
    // =============================================

    public function encontros()
    {
        return $this->hasMany(Encontro::class);
    }
}
