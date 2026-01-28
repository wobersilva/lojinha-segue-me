<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SyncParoquiasFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paroquias:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza o arquivo paroquias.txt para o diretório correto e limpa o cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sincronizando arquivo de paróquias...');

        // Caminhos possíveis do arquivo
        $paths = [
            storage_path('app/private/paroquias.txt'),
            storage_path('app/paroquias.txt'),
            base_path('paroquias.txt'),
        ];

        $sourceFile = null;

        // Procura o arquivo fonte
        foreach ($paths as $path) {
            if (File::exists($path) && File::size($path) > 0) {
                $sourceFile = $path;
                break;
            }
        }

        if (!$sourceFile) {
            $this->error('❌ Arquivo paroquias.txt não encontrado ou está vazio!');
            $this->info('Procurado em:');
            foreach ($paths as $path) {
                $this->line('  - ' . $path);
            }
            return Command::FAILURE;
        }

        $this->info("✓ Arquivo fonte encontrado: {$sourceFile}");

        // Destino
        $destinationPath = storage_path('app/public/paroquias.txt');

        // Copia o arquivo
        try {
            File::copy($sourceFile, $destinationPath);
            $this->info("✓ Arquivo copiado para: {$destinationPath}");

            // Verifica o conteúdo
            $lines = count(file($destinationPath));
            $this->info("✓ Total de linhas: {$lines}");

            // Limpa o cache
            Cache::forget('paroquias_txt_data');
            $this->info('✓ Cache limpo');

            $this->newLine();
            $this->info('✅ Sincronização concluída com sucesso!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Erro ao copiar arquivo: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
