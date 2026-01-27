<?php

namespace App\Console\Commands;

use App\Models\EntradaEstoque;
use App\Models\MovimentacaoEstoque;
use Illuminate\Console\Command;

class MigrarEntradasParaHistorico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'movimentacoes:migrar-entradas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra entradas antigas para a tabela de histórico de movimentações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando migração de entradas para histórico...');

        $entradas = EntradaEstoque::all();
        $total = $entradas->count();

        if ($total === 0) {
            $this->warn('Nenhuma entrada encontrada para migrar.');
            return 0;
        }

        $this->info("Encontradas {$total} entradas para migrar.");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $migradas = 0;
        foreach ($entradas as $entrada) {
            // Verificar se já existe no histórico
            $existe = MovimentacaoEstoque::where('produto_id', $entrada->produto_id)
                ->where('tipo', 'entrada')
                ->where('quantidade', $entrada->quantidade)
                ->where('data_movimentacao', $entrada->data_entrada)
                ->exists();

            if (!$existe) {
                MovimentacaoEstoque::create([
                    'produto_id' => $entrada->produto_id,
                    'tipo' => 'entrada',
                    'quantidade' => $entrada->quantidade,
                    'motivo' => 'entrada_estoque',
                    'observacoes' => $entrada->observacoes,
                    'data_movimentacao' => $entrada->data_entrada,
                    'created_at' => $entrada->created_at,
                    'updated_at' => $entrada->updated_at,
                ]);
                $migradas++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Migração concluída! {$migradas} entradas foram migradas para o histórico.");

        return 0;
    }
}
