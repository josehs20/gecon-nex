<?php

use Illuminate\Support\Facades\Route;
use Modules\Mercado\Http\Controllers\PDV\CaixaPDVController;

//as rotas deven estar criadas aqui pois
//o middleware verifica se o usuario tem
// permissao de mexer nas tarefas do caixa
Route::middleware(['processo:' . config('config.processos.pdv.caixa.id')])->group(function () {
    Route::get('/caixa', [CaixaPDVController::class, 'index'])->name('caixa.autenticacao');
    Route::post('/abrir', [CaixaPDVController::class, 'abrir'])->name('caixa.abrir')->defaults('acao_id', config('config.acoes.abriu_caixa.id'));
    Route::get('/verifica/caixa', [CaixaPDVController::class, 'verifica_caixa'])->name('caixa.verificar.status');
    Route::post('/caixa/status', [CaixaPDVController::class, 'update_status'])->name('caixa.status.update')->defaults('acao_id', config('config.acoes.atualizou_status_caixa.id'));

    Route::middleware('caixa')->group(function () {
        Route::get('/venda', [CaixaPDVController::class, 'venda'])->name('caixa.venda');
        Route::get('/caixa/produto/get', [CaixaPDVController::class, 'get_produtos'])->name('caixa.produto.get');
        Route::post('/caixa/finalizar/venda', [CaixaPDVController::class, 'finalizar_venda'])->name('caixa.finalizar.venda')->defaults('acao_id', config('config.acoes.finalizou_venda.id'));
        Route::post('/caixa/salvar/venda', [CaixaPDVController::class, 'salvar_venda'])->name('caixa.salvar.venda')->defaults('acao_id', config('config.acoes.salvou_venda_caixa.id'));
        Route::get('/caixa/get/vendas', [CaixaPDVController::class, 'get_vendas'])->name('caixa.get.vendas');
        Route::get('/caixa/voltar/venda', [CaixaPDVController::class, 'get_venda_voltar'])->name('caixa.voltar.venda');
        Route::post('/caixa/cancelar/venda', [CaixaPDVController::class, 'cancelar_venda'])->name('caixa.cancelar.venda')->defaults('acao_id', config('config.acoes.cancelou_venda_salva.id'));
        Route::get('/caixa/clientes/get/{clienteVenda?}', [CaixaPDVController::class, 'get_clientes'])->name('caixa.clientes.get');
        Route::post('/caixa/cliente/cadastrar', [CaixaPDVController::class, 'cadastrar_cliente'])->name('caixa.clientes.cadastrar')->defaults('acao_id', config('config.acoes.cadastrou_cliente.id'));
        Route::get('/caixa/devolucao/vendas', [CaixaPDVController::class, 'get_vendas_devolucao'])->name('caixa.devolucao.venda.get');
        Route::post('/caixa/devolucao', [CaixaPDVController::class, 'devolucao'])->name('caixa.devolucao.venda')->defaults('acao_id', config('config.acoes.devolucao.id'));
        Route::get('/caixa/teste/venda', [CaixaPDVController::class, 'venda_teste'])->name('caixa.teste.venda');
        Route::get('/caixa/fechar/index/{caixa_id}', [CaixaPDVController::class, 'fechar_caixa_index'])->name('caixa.fechar.index');
        Route::get('/caixa/sangria/get', [CaixaPDVController::class, 'get_sangria'])->name('caixa.sangria.get');
        Route::get('/caixa/sangria/segunda/via', [CaixaPDVController::class, 'get_sangria_segunda_via'])->name('caixa.sangria.segunda_via');
        Route::post('/caixa/sangria/post', [CaixaPDVController::class, 'sangria'])->name('caixa.sangria.post')->defaults('acao_id', config('config.acoes.sangria.id'));
        Route::post('/caixa/fechar/post', [CaixaPDVController::class, 'fechar_caixa'])->name('caixa.fechar.post')->defaults('acao_id', config('config.acoes.fechou_caixa.id'));
        Route::get('/caixa/get/recebimentos', [CaixaPDVController::class, 'get_recebimentos'])->name('caixa.recebimento.venda.get');
        Route::get('/caixa/get/cliente/venda/recebimentos', [CaixaPDVController::class, 'get_venda_recebimentos'])->name('caixa.recebimento.cliente.venda.get');
        Route::post('/caixa/venda/recebimentos', [CaixaPDVController::class, 'receber_venda'])->name('caixa.recebimento.cliente.venda.post')->defaults('acao_id', config('config.acoes.recebeu_venda_caixa.id'));;
    });
});
Route::middleware(['processo:' . config('config.processos.pdv.fechamento_caixa.id')])->group(function () {
    Route::get('/caixa/fechamento/index', [CaixaPDVController::class, 'fechamento_caixa_index'])->name('caixa.fechamento.index');
    Route::get('/caixa/fechamento/show/{evidencia_id}', [CaixaPDVController::class, 'fechamento_show'])->name('caixa.fechamento.show');
    Route::get('/caixa/fechamento/get/venda/itens', [CaixaPDVController::class, 'fechamento_get_itens_venda'])->name('caixa.fechar.itens.venda.get');
    Route::get('/caixa/fechamento/get/venda/itens/devolucao', [CaixaPDVController::class, 'fechamento_get_itens_venda_devolucao'])->name('caixa.fechar.itens.venda.devolucao.get');
});
