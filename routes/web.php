<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CestaController;
use App\Http\Controllers\FamiliaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ParceiroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RelatorioPdfController;
use App\Http\Controllers\SolicitacaoController;
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
   Route::get('/familias/create', [FamiliaController::class, 'create'])->name('familias.create');
   Route::get('/familias/{familia}/show', [FamiliaController::class, 'show'])->name('familias.show');
   Route::get('/familias/{familia}/edit', [FamiliaController::class, 'edit'])->name('familias.edit');
   Route::put('/familias/{familia}', [FamiliaController::class, 'update'])->name('familias.update');
   Route::delete('/familias/{familia}/destroy', [FamiliaController::class, 'destroy'])->name('familias.destroy');
   Route::get('/familias/{familia}/cestas', [FamiliaController::class, 'getCestas'])->name('familias.getCestas');
   Route::post('/familias/check-cpf', [FamiliaController::class, 'checkCpf'])->name('familias.checkCpf');
   Route::post('/familias/importar', [FamiliaController::class, 'importStore'])->name('familias.import.store');
   Route::get('/familias/{familia}/importacao_cpf', [FamiliaController::class, 'importacaoCpf'])->name('familias.importacao_cpf');

   //CESTAS
   Route::get('/cestas', [CestaController::class, 'index'])->name('cestas.index');
   Route::post('/cestas', [CestaController::class, 'store'])->name('cestas.store');
   Route::put('/cestas/{cesta}', [CestaController::class, 'update'])->name('cestas.update');
   Route::post('/cestas/entregaPropria', [CestaController::class, 'entregaCestaPropria'])->name('cestas.entregaCestaPropria');
   Route::get('/cestas/entrega_familia/{cesta}', [CestaController::class, 'entregaFamilia'])->name('cestas.entrega_familia');
   Route::put('/cestas/entrega_familia_store/{cesta}', [CestaController::class, 'entregaFamiliaStore'])->name('cestas.entrega_familia_store');
   Route::put('/cestas/entrega_ifes/{cesta}', [CestaController::class, 'entregaFamiliaIfes'])->name('cestas.entrega_ifes');

   //ITENS
   Route::get('/itens', [ItemController::class, 'index'])->name('itens.index');

   //REGISTRAR_ENTREGA
   Route::get('/solicitacoes', [SolicitacaoController::class, 'index'])->name('solicitacoes.index');
   Route::post('/solicitacoes', [SolicitacaoController::class, 'store'])->name('solicitacoes.store');
   Route::get('/solicitacoes/gerenciar_solicitacoes', [SolicitacaoController::class, 'gerenciarSolicitacoes'])->name('solicitacoes.gerenciar_solicitacoes');
   Route::put('/solicitacoes/gerenciar_solicitacoes/{solicitacao}', [SolicitacaoController::class, 'atualizarStatusSolicitacao'])->name('solicitacoes.alterar_status_solicitacao');
   Route::get('/solicitacoes/gerenciar_itens', [SolicitacaoController::class, 'gerenciarItens'])->name('solicitacoes.gerenciar_itens');

   //RELATORIOS
   Route::get('/relatorios/relatorio_visual', [RelatorioController::class, 'RelatorioVisual'])->name('relatorios.relatorio_visual');
   Route::get('/relatorios/relatorio_pdf', [RelatorioController::class, 'RelatorioPdf'])->name('relatorios.relatorio_pdf');
   Route::get('/relatorios/relatorio_planilha', [RelatorioController::class, 'RelatorioPlanilha'])->name('relatorios.relatorio_planilha');
   //RELATORIOS_VISUAIS
   Route::get('/relatorios/relatorio_visuais_telas/relatorio_saida_de_cesta', [RelatorioController::class, 'RelatorioSaidaDeCesta'])->name('relatorios.relatorio_saida_de_cesta');
   Route::get('/relatorios/relatorio_visuais_telas/relatorio_parceiro', [RelatorioController::class, 'RelatorioParceiro'])->name('relatorios.relatorio_parceiro');
   //RELATORIOS_PDF
   Route::get('/relatorios/relatorios_pdf/relatorio_saida_de_cesta_pdf', [RelatorioPdfController::class, 'relatorio_saida_de_cesta'])->name('relatorios_pdf.relatorio_saida_de_cesta_pdf');
   Route::get('/relatorios/relatorios_pdf/relatorio_parceiro_pdf', [RelatorioPdfController::class, 'relatorio_parceiro'])->name('relatorios_pdf.relatorio_parceiro_pdf');
});

Route::get('/', function () {
   return view('auth.login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(['auth', 'verified']);

require __DIR__ . '/auth.php';
