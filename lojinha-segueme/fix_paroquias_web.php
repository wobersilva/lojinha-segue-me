<?php
/**
 * SCRIPT DE CORREÇÃO - PARÓQUIAS
 * 
 * Execute via navegador ou linha de comando:
 * php fix_paroquias_web.php
 */

// Verifica se está sendo executado via CLI
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    // Se for via navegador, adicione autenticação básica aqui
    // Para segurança, este script só deve ser acessível por administradores
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Fix Paróquias</title>";
    echo "<style>body{font-family:monospace;background:#1a1a1a;color:#00ff00;padding:20px;}";
    echo ".error{color:#ff0000;}.success{color:#00ff00;}.info{color:#ffff00;}</style></head><body>";
}

function log_message($message, $type = 'info') {
    global $isCli;
    
    $prefix = [
        'success' => '✓',
        'error' => '✗',
        'info' => '→'
    ][$type] ?? '→';
    
    if ($isCli) {
        echo "{$prefix} {$message}\n";
    } else {
        $class = $type;
        echo "<div class='{$class}'>{$prefix} {$message}</div>";
    }
}

// Carregar Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

log_message("=== CORREÇÃO DO SISTEMA DE PARÓQUIAS ===", 'info');
log_message("");

// 1. Verificar arquivo fonte
log_message("Passo 1: Procurando arquivo fonte...", 'info');

$sourcePaths = [
    storage_path('app/private/paroquias.txt'),
    storage_path('app/paroquias.txt'),
    base_path('paroquias.txt'),
];

$sourceFile = null;
foreach ($sourcePaths as $path) {
    if (file_exists($path) && filesize($path) > 0) {
        $sourceFile = $path;
        log_message("Arquivo fonte encontrado: {$path}", 'success');
        break;
    }
}

if (!$sourceFile) {
    log_message("ERRO: Arquivo paroquias.txt não encontrado!", 'error');
    log_message("Locais verificados:", 'error');
    foreach ($sourcePaths as $path) {
        log_message("  - {$path}", 'error');
    }
    exit(1);
}

// 2. Copiar para destino
log_message("");
log_message("Passo 2: Copiando arquivo...", 'info');

$destPath = storage_path('app/public/paroquias.txt');
$destDir = dirname($destPath);

if (!is_dir($destDir)) {
    mkdir($destDir, 0755, true);
    log_message("Diretório criado: {$destDir}", 'success');
}

copy($sourceFile, $destPath);
chmod($destPath, 0644);

$lines = count(file($destPath));
log_message("Arquivo copiado com sucesso!", 'success');
log_message("Total de linhas: {$lines}", 'success');

// 3. Limpar cache
log_message("");
log_message("Passo 3: Limpando cache...", 'info');

Illuminate\Support\Facades\Cache::forget('paroquias_txt_data');
Illuminate\Support\Facades\Artisan::call('cache:clear');
Illuminate\Support\Facades\Artisan::call('view:clear');

log_message("Cache limpo!", 'success');

// 4. Testar leitura
log_message("");
log_message("Passo 4: Testando leitura...", 'info');

$disk = Illuminate\Support\Facades\Storage::disk('public');
if ($disk->exists('paroquias.txt')) {
    $content = $disk->get('paroquias.txt');
    $paroquias = collect(explode("\n", $content))
        ->filter(fn($l) => !empty(trim($l)))
        ->count();
    
    log_message("Paróquias carregadas: {$paroquias}", 'success');
} else {
    log_message("ERRO: Arquivo não acessível via Storage!", 'error');
}

// 5. Mostrar primeiras paróquias
log_message("");
log_message("Primeiras 5 paróquias:", 'info');
$firstLines = array_slice(file($destPath), 0, 5);
foreach ($firstLines as $line) {
    log_message("  " . trim($line), 'info');
}

log_message("");
log_message("=== CORREÇÃO CONCLUÍDA ===", 'success');
log_message("");
log_message("Próximo passo: Acesse a página de Nova Paróquia no sistema", 'info');

if (!$isCli) {
    echo "</body></html>";
}
