<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CestaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RelatorioPdfController;
use App\Http\Middleware\CheckIfIsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')
   ->group(function () {
      // Route::resource('/users', UserController::class);
      Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
      Route::get('/users/gerenciar_usuarios', [UserController::class, 'gerenciarUsuarios'])->name('users.gerenciar_usuarios');
      Route::get('/users/gerenciar_siglas', [UserController::class, 'gerenciarSiglas'])->name('users.gerenciar_siglas');
      Route::post('/users', [UserController::class, 'store'])->name('users.store');
      Route::get('/users', [UserController::class, 'index'])->name('users.index');
      Route::post('/parceiros/storeCoordenador', [UserController::class, 'storeCoordenador'])->name('parceiros.storeCoordenador');
      Route::delete('/users/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy')->middleware(CheckIfIsAdmin::class);
      Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
      Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
      Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
      Route::get('/users/{user}/configuracao', [UserController::class, 'configuracao'])->name('users.configuracao');
   });

Route::middleware('auth')->group(function () {
   Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   //PARCEIROS
   Route::get('/parceiros', [ParceiroController::class, 'index'])->name('parceiros.index');
   Route::post('/parceiros', [ParceiroController::class, 'store'])->name('parceiros.store');
   Route::get('/parceiros/create', [ParceiroController::class, 'create'])->name('parceiros.create');
   Route::post('/parceiros/{parceiro}/toggleStatus', [ParceiroController::class, 'toggleStatus'])->name('parceiros.toggleStatus');
   Route::get('/parceiros/{parceiro}/show', [ParceiroController::class, 'show'])->name('parceiros.show');
   Route::get('/parceiros/{parceiro}/edit', [ParceiroController::class, 'edit'])->name('parceiros.edit');
   Route::put('/parceiros/{parceiro}', [ParceiroController::class, 'update'])->name('parceiros.update');
   Route::delete('/parceiros/{parceiro}/destroy', [ParceiroController::class, 'destroy'])->name('parceiros.destroy');
   Route::post('/parceiros/{parceiro}/sigla', [ParceiroController::class, 'alterarSigla'])->name('parceiros.alterarSigla');

   //FAMILIAS
   Route::get('/familias', [FamiliaController::class, 'index'])->name('familias.index');
   Route::post('/familias', [FamiliaController::class, 'store'])->name('familias.store');

   //CESTAS
   Route::get('/cestas', [CestaController::class, 'index'])->name('cestas.index');
   Route::post('/cestas', [CestaController::class, 'store'])->name('cestas.store');

   //ITENS
   Route::get('/itens', [ItemController::class, 'index'])->name('itens.index');

   //REGISTRAR_ENTREGA
   Route::get('/entregas', [EntregaController::class, 'index'])->name('entregas.index');
   Route::get('/entregas/gerenciar_cestas', [EntregaController::class, 'gerenciarCestas'])->name('entregas.gerenciar_cestas');
   Route::get('/entregas/gerenciar_cestas/{cesta}', [EntregaController::class, 'atualizarStatusCesta'])->name('entregas.alterar_status_cesta');
   Route::get('/entregas/gerenciar_itens', [EntregaController::class, 'gerenciarItens'])->name('entregas.gerenciar_itens');

   //RELATORIOS
   Route::get('/relatorios/relatorio_visual', [RelatorioController::class, 'RelatorioVisual'])->name('relatorios.relatorio_visual');
   Route::get('/relatorios/relatorio_pdf', [RelatorioController::class, 'RelatorioPdf'])->name('relatorios.relatorio_pdf');
   Route::get('/relatorios/relatorio_planilha', [RelatorioController::class, 'RelatorioPlanilha'])->name('relatorios.relatorio_planilha');
   //RELATORIOS_VISUAIS
   Route::get('/relatorios/relatorio_visuais_telas/relatorio_saida_de_cesta', [RelatorioController::class, 'RelatorioSaidaDeCesta'])->name('relatorios.relatorio_saida_de_cesta');
   //RELATORIOS_PDF
   Route::get('/relatorios/relatorios_pdf/relatorio_saida_de_cesta_pdf', [RelatorioPdfController::class, 'relatorio_saida_de_cesta'])->name('relatorios_pdf.relatorio_saida_de_cesta_pdf');
   Route::get('/relatorios/relatorios_pdf/relatorio_parceiro_pdf', [RelatorioPdfController::class, 'relatorio_parceiro'])->name('relatorios_pdf.relatorio_parceiro_pdf');
});

Route::get('/', function () {
   return view('auth.login');
})->name('dashboard');

Route::get('/dashboard', function () {
   return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
