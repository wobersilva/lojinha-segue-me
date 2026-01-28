<?php
// =====================================================
// ROTAS WEB - LOJINHA DO SEGUE-ME
// Arquivo: routes/web.php
// =====================================================

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EncontroController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\ParoquiaController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('paroquias', ParoquiaController::class);
    Route::resource('produtos', ProdutoController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/estoque/entrada', [EstoqueController::class, 'entrada'])
        ->name('estoque.entrada.form');
    Route::post('/estoque/entrada', [EstoqueController::class, 'entrada'])
        ->name('estoque.entrada');
    Route::get('/estoque/saida', [EstoqueController::class, 'saidaProvisoria'])
        ->name('estoque.saida.form');
    Route::post('/estoque/saida', [EstoqueController::class, 'saidaProvisoria'])
        ->name('estoque.saida');
    Route::get('/relatorios/inventario', [RelatorioController::class, 'inventario'])
        ->name('relatorios.inventario');
    Route::get('/relatorios/vendas-paroquia', [RelatorioController::class, 'vendasPorParoquia'])
        ->name('relatorios.vendas-paroquia');
    Route::get('/relatorios/vendas-periodo', [RelatorioController::class, 'vendasPorPeriodo'])
        ->name('relatorios.vendas-periodo');
});

Route::get('/toast-test', function () {
    return redirect('/paroquias')->with('success', 'Toast funcionando! ✅');
});
// -------------------------------
// DASHBOARD
// -------------------------------
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/', function () {
    return redirect('/dashboard');
});

// -------------------------------
// PARÓQUIAS
// -------------------------------
Route::resource('paroquias', ParoquiaController::class);

// -------------------------------
// PRODUTOS
// -------------------------------
Route::resource('produtos', ProdutoController::class);

// -------------------------------
// MOVIMENTAÇÕES
// -------------------------------
Route::prefix('movimentacoes')->group(function () {
    Route::get('/entradas', [MovimentacaoController::class, 'entradas'])
        ->name('movimentacoes.entradas');

    Route::post('/entradas', [MovimentacaoController::class, 'storeEntradas'])
        ->name('movimentacoes.entradas.store');

    Route::get('/saidas', [MovimentacaoController::class, 'saidas'])
        ->name('movimentacoes.saidas');

    Route::post('/saidas', [MovimentacaoController::class, 'storeSaidas'])
        ->name('movimentacoes.saidas.store');

    Route::get('/historico', [MovimentacaoController::class, 'historico'])
        ->name('movimentacoes.historico');
});

// -------------------------------
// ENCONTROS
// -------------------------------
Route::resource('encontros', EncontroController::class)->except(['destroy']);
Route::post('encontros/{encontro}/fechar', [EncontroController::class, 'fechar'])
    ->name('encontros.fechar');
Route::get('encontros/{encontro}/relatorio-saida', [EncontroController::class, 'relatorioSaida'])
    ->name('encontros.relatorio-saida');

// -------------------------------
// ESTOQUE
// -------------------------------
Route::prefix('estoque')->group(function () {
    Route::get('/', function () {
        return view('estoque.index');
    });

    Route::get('/entrada', function () {
        $produtos = \App\Models\Produto::all();
        return view('estoque.entrada', compact('produtos'));
    })->name('estoque.entrada.form');

    Route::post('/entrada', [EstoqueController::class, 'entrada'])
        ->name('estoque.entrada');

    Route::get('/saida', function () {
        $produtos = \App\Models\Produto::all();
        $encontros = \App\Models\Encontro::where('status', 'aberto')->get();
        return view('estoque.saida', compact('produtos', 'encontros'));
    })->name('estoque.saida.form');

    Route::post('/saida', [EstoqueController::class, 'saidaProvisoria'])
        ->name('estoque.saida');

    Route::post('/baixa', [EstoqueController::class, 'baixa'])
        ->name('estoque.baixa');
});

Route::get('/relatorios/inventario', [RelatorioController::class, 'inventario'])
    ->name('relatorios.inventario');
Route::get('/relatorios/vendas-paroquia', [RelatorioController::class, 'vendasPorParoquia'])
    ->name('relatorios.vendas-paroquia');
Route::get('/relatorios/vendas-periodo', [RelatorioController::class, 'vendasPorPeriodo'])
    ->name('relatorios.vendas-periodo');

// -------------------------------
// ADMIN - GERENCIAMENTO DE USUÁRIOS
// -------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    Route::delete('/users/{user}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
    Route::post('/users/{user}/toggle-admin', [UserManagementController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
});

require __DIR__ . '/auth.php';
// =====================================================
// FIM DAS ROTAS
// =====================================================
