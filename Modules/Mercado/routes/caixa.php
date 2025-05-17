<?php

use Illuminate\Support\Facades\Route;
use Modules\Mercado\Http\Controllers\Caixa\CaixaController;

//as rotas deven estar criadas aqui pois
//o middleware verifica se o usuario tem
// permissao de mexer nas tarefas do caixa
Route::middleware(['processo:' . config('config.processos.pdv.caixa.id')])->group(function () {
    Route::get('/caixa', [CaixaController::class, 'index'])->name('caixa.autenticacao');
    Route::post('/abrir', [CaixaController::class, 'abrir'])->name('caixa.abrir')->defaults('acao_id', config('config.acoes.abriu_caixa.id'));
    Route::get('/verifica/caixa', [CaixaController::class, 'verifica_caixa'])->name('caixa.verificar.status');
    Route::post('/caixa/status', [CaixaController::class, 'update_status'])->name('caixa.status.update')->defaults('acao_id', config('config.acoes.atualizou_status_caixa.id'));

    Route::middleware('caixa')->group(function () {
        Route::get('/venda', [CaixaController::class, 'venda'])->name('caixa.venda');
        Route::get('/caixa/produto/get', [CaixaController::class, 'get_produtos'])->name('caixa.produto.get');
        Route::post('/caixa/finalizar/venda', [CaixaController::class, 'finalizar_venda'])->name('caixa.finalizar.venda')->defaults('acao_id', config('config.acoes.finalizou_venda.id'));
        Route::post('/caixa/salvar/venda', [CaixaController::class, 'salvar_venda'])->name('caixa.salvar.venda')->defaults('acao_id', config('config.acoes.salvou_venda_caixa.id'));
        Route::get('/caixa/get/vendas', [CaixaController::class, 'get_vendas'])->name('caixa.get.vendas');
        Route::get('/caixa/voltar/venda', [CaixaController::class, 'get_venda_voltar'])->name('caixa.voltar.venda');
        Route::post('/caixa/cancelar/venda', [CaixaController::class, 'cancelar_venda'])->name('caixa.cancelar.venda')->defaults('acao_id', config('config.acoes.cancelou_venda_salva.id'));
        Route::get('/caixa/clientes/get/{clienteVenda?}', [CaixaController::class, 'get_clientes'])->name('caixa.clientes.get');
        Route::post('/caixa/cliente/cadastrar', [CaixaController::class, 'cadastrar_cliente'])->name('caixa.clientes.cadastrar')->defaults('acao_id', config('config.acoes.cadastrou_cliente.id'));
        Route::get('/caixa/devolucao/vendas', [CaixaController::class, 'get_vendas_devolucao'])->name('caixa.devolucao.venda.get');
        Route::post('/caixa/devolucao', [CaixaController::class, 'devolucao'])->name('caixa.devolucao.venda')->defaults('acao_id', config('config.acoes.devolucao.id'));
        Route::get('/caixa/teste/venda', [CaixaController::class, 'venda_teste'])->name('caixa.teste.venda');
        Route::get('/caixa/fechar/index/{caixa_id}', [CaixaController::class, 'fechar_caixa_index'])->name('caixa.fechar.index');
        Route::get('/caixa/sangria/get', [CaixaController::class, 'get_sangria'])->name('caixa.sangria.get');
        Route::get('/caixa/sangria/segunda/via', [CaixaController::class, 'get_sangria_segunda_via'])->name('caixa.sangria.segunda_via');
        Route::post('/caixa/sangria/post', [CaixaController::class, 'sangria'])->name('caixa.sangria.post')->defaults('acao_id', config('config.acoes.sangria.id'));
        Route::post('/caixa/fechar/post', [CaixaController::class, 'fechar_caixa'])->name('caixa.fechar.post')->defaults('acao_id', config('config.acoes.fechou_caixa.id'));
        Route::get('/caixa/get/recebimentos', [CaixaController::class, 'get_recebimentos'])->name('caixa.recebimento.venda.get');
        Route::get('/caixa/get/cliente/venda/recebimentos', [CaixaController::class, 'get_venda_recebimentos'])->name('caixa.recebimento.cliente.venda.get');
        Route::post('/caixa/venda/recebimentos', [CaixaController::class, 'receber_venda'])->name('caixa.recebimento.cliente.venda.post')->defaults('acao_id', config('config.acoes.recebeu_venda_caixa.id'));;

    });
});
Route::middleware(['processo:' . config('config.processos.pdv.fechamento_caixa.id')])->group(function () {
    Route::get('/caixa/fechamento/index', [CaixaController::class, 'fechamento_caixa_index'])->name('caixa.fechamento.index');
    Route::get('/caixa/fechamento/show/{evidencia_id}', [CaixaController::class, 'fechamento_show'])->name('caixa.fechamento.show');
    Route::get('/caixa/fechamento/get/venda/itens', [CaixaController::class, 'fechamento_get_itens_venda'])->name('caixa.fechar.itens.venda.get');
    Route::get('/caixa/fechamento/get/venda/itens/devolucao', [CaixaController::class, 'fechamento_get_itens_venda_devolucao'])->name('caixa.fechar.itens.venda.devolucao.get');
});
