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

    // =============================================
    // SCOPES - Filtros reutilizáveis
    // =============================================

    /**
     * Filtra apenas encontros abertos
     */
    public function scopeAberto(Builder $query): Builder
    {
        return $query->where('status', 'aberto');
    }

    /**
     * Filtra apenas encontros fechados
     */
    public function scopeFechado(Builder $query): Builder
    {
        return $query->where('status', 'fechado');
    }

    /**
     * Filtra encontros de uma paróquia específica
     */
    public function scopeDaParoquia(Builder $query, int $paroquiaId): Builder
    {
        return $query->where('paroquia_id', $paroquiaId);
    }

    /**
     * Filtra encontros a partir de uma data
     */
    public function scopeAPartirDe(Builder $query, $data): Builder
    {
        return $query->whereDate('data_inicio', '>=', $data);
    }

    /**
     * Filtra encontros até uma data
     */
    public function scopeAte(Builder $query, $data): Builder
    {
        return $query->whereDate('data_inicio', '<=', $data);
    }

    /**
     * Ordena por status (abertos primeiro) e data
     */
    public function scopeOrdenadoPorPrioridade(Builder $query): Builder
    {
        return $query
            ->orderByRaw("CASE WHEN status = 'aberto' THEN 0 ELSE 1 END")
            ->orderBy('data_inicio', 'desc');
    }

    // =============================================
    // RELACIONAMENTOS
    // =============================================

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
