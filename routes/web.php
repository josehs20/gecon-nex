<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\IA\ChatBotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Usuario\PermissaoUsuarioController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Yajra\EmpresaController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Route::get('/login/sair', function () {
    Auth::logout(); // Desloga o usuário autenticado
    return redirect()->route('home.welcome');
})->name('sairDoSistema');

// Route::get('recuperar-senha', [UsuarioController::class, 'recupera_senha'])->name('recuperar.senha');
// Route::get('email-recuperar-senha', [UsuarioController::class, 'email_recupera_senha'])->name('email.recuperar.senha');
// Route::get('verificar-nova-senha/{token}', [UsuarioController::class, 'atualizar_senha'])->name('atualizar.senha');
// Route::post('nova-senha', [UsuarioController::class, 'atualizar_senha_post'])->name('atualizar.senha.post');

Route::get('/welcome', function () {
    return view('welcome');
})->name('home.welcome');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/verification', [App\Http\Controllers\HomeController::class, 'index'])->name('verification');

    /** USUÁRIOS */
    Route::prefix('/usuarios')->group(function () {
        Route::get('/index', [UsuarioController::class, 'index'])->name('cadastro.gecon.usuarios.index');
        Route::get('/create', [UsuarioController::class, 'create'])->name('gecon.usuarios.create');
        Route::post('/store', [UsuarioController::class, 'store'])->name('gecon.usuarios.store');
        Route::get('/edit/{usuario_master_cod}', [UsuarioController::class, 'edit'])->name('gecon.usuarios.edit');
        Route::post('/update/{usuario_master_cod}', [UsuarioController::class, 'update'])->name('gecon.usuarios.update');
        Route::get('/obter', [UsuarioController::class, 'obter_usuarios'])->name('gecon.usuarios.obter');
        Route::get('/obter-lojas-por-empresa/{empresa_id}', [EmpresaController::class, 'obterLojasPorEmpresaParaSelect'])->name('gecon.usuarios.obter_lojas_por_empresa');
    });

    /** PERMISSÃO USUÁRIOS */
    Route::prefix('/usuarios/permissao')->group(function () {
        Route::get('/index', [PermissaoUsuarioController::class, 'index'])->name('cadastro.gecon.usuarios.permissao.index');
        Route::get('/buscar_permissoes_por_tipo_usuario/{tipo_usuario_id}', [PermissaoUsuarioController::class, 'buscar_permissoes_por_tipo_usuario_id'])->name('gecon.usuarios.permissao.buscar_por_tipo_usuario');
        Route::get('/buscar_permissoes/{tipo_usuario_id}', [PermissaoUsuarioController::class, 'buscar_permissoes'])->name('gecon.usuarios.permissao.buscar');
        Route::post('/adicionar/{processo_id}/{tipo_usuario_id}', [PermissaoUsuarioController::class, 'adicionar'])->name('gecon.usuarios.permissao.adicionar');
        Route::post('/remover/{processo_id}/{tipo_usuario_id}', [PermissaoUsuarioController::class, 'remover'])->name('gecon.usuarios.permissao.remover');
    });

    /** DASHBOARD */
    Route::prefix('/dashboard')->group(function () {
        Route::get('/index', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/renderizar/{view}', [DashboardController::class, 'renderizar'])->name('dashboard.renderizar');
    });
    /**
     * ROTAS ADMIN GECON
     */
    Route::prefix('/admin')->group(function () {
        /**
         * Empresas
         */
        Route::resource('/empresa', App\Http\Controllers\Dashboard\Admin\HomeController::class)->names('admin.empresa')->middleware(config('config.middlewares.admin'));
        Route::get('/admin/empresa/api/brasil/get', [App\Http\Controllers\Dashboard\Admin\HomeController::class, 'getEmpresaBrasilApi'])->name('admin.empresa.api.brasil.get')->middleware(config('config.middlewares.admin'));

        Route::get('/loja/{empresa_id}', [App\Http\Controllers\Dashboard\Admin\LojaController::class, 'create'])->name('admin.loja.create')->middleware(config('config.middlewares.admin'));
        Route::get('/loja/edit/{loja_id}', [App\Http\Controllers\Dashboard\Admin\LojaController::class, 'edit'])->name('admin.loja.edit')->middleware(config('config.middlewares.admin'));
        Route::post('/loja/{empresa_id}', [App\Http\Controllers\Dashboard\Admin\LojaController::class, 'store'])->name('admin.loja.store')->middleware(config('config.middlewares.admin'));
        Route::put('/loja/{empresa_id}/{loja_id}', [App\Http\Controllers\Dashboard\Admin\LojaController::class, 'update'])->name('admin.loja.update')->middleware(config('config.middlewares.admin'));

        /**
         * NFC
         */
        // Route::get('/nfe/empresas', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'index'])->name('nfe.cadastro.empresas')->middleware(config('config.middlewares.admin'));
        // Route::get('/nfe/empresas/create', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'create'])->name('nfe.cadastro.empresas.create')->middleware(config('config.middlewares.admin'));
        Route::post('/nfe/empresas/store', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'store'])->name('nfe.cadastro.empresas.store')->middleware(config('config.middlewares.admin'));
        Route::post('/nfe/empresas/store-certificado/{loja_id}', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'storeCertificado'])->name('nfe.cadastro.empresas.store.certificado')->middleware(config('config.middlewares.admin'));
        Route::get('/nfe/empresas/download-certificado/{loja_id}', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'downloadCertificado'])->name('nfe.cadastro.empresas.download.certificado')->middleware(config('config.middlewares.admin'));
        Route::post('/nfe/empresas/store-incricao-estadual/{loja_id}', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'storeInscricaoEstadual'])->name('nfe.cadastro.empresas.store.inscricao')->middleware(config('config.middlewares.admin'));
        Route::post('/nfe/empresas/update-incricao-estadual/{inscricao_id}', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'updateInscricaoEstadual'])->name('nfe.cadastro.empresas.update.inscricao')->middleware(config('config.middlewares.admin'));
        Route::post('/nfe/empresas/delete-incricao-estadual/{inscricao_id}', [App\Http\Controllers\Dashboard\Admin\EmpresaNFEController::class, 'deleteInscricaoEstadual'])->name('nfe.cadastro.empresas.delete.inscricao')->middleware(config('config.middlewares.admin'));


        /**
         * Gtin
         */
        Route::get('/admin/gtin/index', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'index'])->name('admin.gtin.index')->middleware(config('config.middlewares.admin'));
        Route::get('/admin/gtin/yajra', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'get_yajra'])->name('admin.gtin.yajra')->middleware(config('config.middlewares.admin'));
        Route::get('/admin/gtin/show', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'show'])->name('admin.gtin.show')->middleware(config('config.middlewares.admin'));
        Route::post('/admin/gtin/update', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'update'])->name('admin.gtin.update')->middleware(config('config.middlewares.admin'));
        Route::post('/admin/gtin/delete', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'delete'])->name('admin.gtin.delete')->middleware(config('config.middlewares.admin'));
        Route::post('/admin/gtin/post', [App\Http\Controllers\Dashboard\Admin\GtinController::class, 'post'])->name('admin.gtin.post')->middleware(config('config.middlewares.admin'));
    });


    /**
     * Yajra Services
     */
    Route::prefix('/yajra')->group(function () {

        Route::get('/empresas/get', [EmpresaController::class, 'getEmpresas'])
            ->name('yajra.service.empresas.get')
            ->middleware(['processo:' . config('config.processos.empresas.empresa.id')]);

        Route::get('/empresas/lojas/get/{empresa_id}', [EmpresaController::class, 'getLojas'])
            ->name('yajra.service.empresa.get.lojas')
            ->middleware(['processo:' . config('config.processos.empresas.empresa.id')]);
    });

    /**
     * teste IA boot
     */
    Route::prefix('/chat-bot')->group(function () {

        Route::get('/teste/ia', [ChatBotController::class, 'teste'])
            ->name('chat.bot.teste.ia');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
