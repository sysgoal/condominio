<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EnqueteController;
use App\Http\Controllers\InteracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\RoleAssignmentController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');

// O Breeze tenta jogar o usuário para /dashboard após o login. 
// Vamos ser espertos e redirecionar direto para o nosso painel de contas!

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ---------------------------------------------------
    // ROTAS DO BREEZE (Gestão de Perfil)
    // ---------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---------------------------------------------------
    // NOSSAS ROTAS PÚBLICAS DO CONDOMÍNIO (Para todos logados)
    // ---------------------------------------------------
    Route::get('/contas', [ContaController::class, 'index'])->name('contas.index');
    Route::get('/documentos', [DocumentoController::class, 'index'])->name('documentos.index');
    Route::get('/enquetes', [EnqueteController::class, 'index'])->name('enquetes.index');
    Route::post('/enquetes/{enquete}/votar', [EnqueteController::class, 'votar'])->name('enquetes.votar');
    
    Route::get('/interacoes', [InteracaoController::class, 'index'])->name('interacoes.index');
    Route::post('/interacoes', [InteracaoController::class, 'store'])->name('interacoes.store');
    Route::delete('/interacoes/{interacao}', [InteracaoController::class, 'destroy'])->name('interacoes.destroy');
    Route::post('/interacoes/{interacao}/curtir', [InteracaoController::class, 'curtir'])->name('interacoes.curtir');
    Route::post('/interacoes/{interacao}/comentar', [InteracaoController::class, 'comentar'])->name('interacoes.comentar');
    // ---------------------------------------------------
    // NOSSAS ROTAS RESTRITAS (Síndico e Admin)
    // ---------------------------------------------------
    Route::middleware(['role:admin|sindico'])->group(function () {
        
        // --- ADICIONE ESTAS 3 LINHAS AQUI ---
        Route::get('/moradores', [MoradorController::class, 'index'])->name('moradores.index');
        Route::get('/moradores/novo', [MoradorController::class, 'create'])->name('moradores.create');
        Route::post('/moradores', [MoradorController::class, 'store'])->name('moradores.store');
        Route::delete('/moradores/{user}', [MoradorController::class, 'destroy'])->name('moradores.destroy');
        Route::get('/moradores/{user}/editar', [MoradorController::class, 'edit'])->name('moradores.edit');
        Route::put('/moradores/{user}', [MoradorController::class, 'update'])->name('moradores.update');
        Route::patch('/moradores/{user}/status', [MoradorController::class, 'toggleStatus'])->name('moradores.status');
        Route::post('/moradores/{user}/reset-password', [MoradorController::class, 'resetPassword'])->name('moradores.reset');
        // ------------------------------------

        Route::get('/contas/nova', [ContaController::class, 'create'])->name('contas.create');
        Route::post('/contas', [ContaController::class, 'store'])->name('contas.store');
        Route::post('/enquetes', [EnqueteController::class, 'store'])->name('enquetes.store');
        Route::get('/documentos/novo', [DocumentoController::class, 'create'])->name('documentos.create');
        Route::post('/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
        Route::get('/financeiro/relatorio', [ContaController::class, 'relatorio'])->name('contas.relatorio');
        Route::middleware(['auth', 'role:sindico|admin'])->group(function () {
        Route::get('/configuracoes/permissoes', [RoleAssignmentController::class, 'index'])->name('roles.index');
        Route::patch('/configuracoes/permissoes/{user}', [RoleAssignmentController::class, 'update'])->name('roles.update');
        Route::get('/configuracoes/funcoes/nova', [RoleAssignmentController::class, 'createRole'])->name('roles.create');
        Route::post('/configuracoes/funcoes', [RoleAssignmentController::class, 'storeRole'])->name('roles.store');
        Route::delete('/documentos/{documento}', [DocumentoController::class, 'destroy'])
      ->name('documentos.destroy')
      ->middleware('role:sindico|admin'); // Camada extra de proteção
});
    });
});

require __DIR__.'/auth.php';