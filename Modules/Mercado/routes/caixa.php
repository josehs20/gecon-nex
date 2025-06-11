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
        //rotas com os recursos principais do caixa
        Route::get('/venda', [CaixaPDVController::class, 'venda'])->name('caixa.venda');
        Route::post('/caixa/finalizar/venda', [CaixaPDVController::class, 'finalizar_venda'])->name('caixa.finalizar.venda')->defaults('acao_id', config('config.acoes.finalizou_venda.id'));
        Route::post('/caixa/orcamento', [CaixaPDVController::class, 'orcamento'])->name('caixa.orcamento.venda')->defaults('acao_id', config('config.acoes.orcamento.id'));
        Route::post('/caixa/devolucao', [CaixaPDVController::class, 'devolucao'])->name('caixa.devolucao.venda')->defaults('acao_id', config('config.acoes.devolucao.id'));
        Route::post('/caixa/suprir', [CaixaPDVController::class, 'suprir_caixa'])->name('caixa.suprir')->defaults('acao_id', config('config.acoes.supriu_caixa.id'));
        Route::post('/caixa/sangria/post', [CaixaPDVController::class, 'sangria'])->name('caixa.sangria.post')->defaults('acao_id', config('config.acoes.sangria.id'));
        Route::post('/caixa/receber/conta/post', [CaixaPDVController::class, 'receber_conta'])->name('caixa.receber.conta.post')->defaults('acao_id', config('config.acoes.recebeu_venda_caixa.id'));
        Route::post('/caixa/fechar/post', [CaixaPDVController::class, 'fechar_caixa'])->name('caixa.fechar.post')->defaults('acao_id', config('config.acoes.fechou_caixa.id'));
        Route::post('/caixa/adcionar/item', [CaixaPDVController::class, 'adicionar_item'])->name('caixa.adicionar.item')->defaults('acao_id', config('config.acoes.adicionou_item_caixa.id'));
        Route::post('/caixa/remover/item', [CaixaPDVController::class, 'remover_item'])->name('caixa.remover.item')->defaults('acao_id', config('config.acoes.removeu_item_caixa.id'));
        Route::post('/caixa/supervisor/validar', [CaixaPDVController::class, 'validar_supervisor'])->name('caixa.supervisor.validar');
        Route::post('/caixa/colocar/orcamento/venda', [CaixaPDVController::class, 'colocar_orcamento_em_venda'])->name('caixa.colcoar.orcamento.orcamento.em.venda')->defaults('acao_id', config('config.acoes.colocou_orcamento_a_venda.id'));
        Route::post('/caixa/excluiu/orcamento', [CaixaPDVController::class, 'excluir_orcamento'])->name('caixa.orcamento.excluir')->defaults('acao_id', config('config.acoes.excluiu_orcamento.id'));

        //get
        Route::get('/caixa/orcamentos/get', [CaixaPDVController::class, 'get_orcamentos'])->name('caixa.orcamento.get');
        Route::get('/caixa/get/orcamento', [CaixaPDVController::class, 'get_orcamento'])->name('caixa.orcamento.get.itens');
        Route::get('/caixa/especies/get', [CaixaPDVController::class, 'get_especies'])->name('caixa.get.especies');
        Route::get('/caixa/caixa/get', [CaixaPDVController::class, 'get_caixa'])->name('caixa.get.caixa');
        Route::get('/caixa/fechamento/get', [CaixaPDVController::class, 'get_caixa_fechamento'])->name('caixa.fechamento.caixa.get');


        Route::get('/caixa/produto/get', [CaixaPDVController::class, 'get_produtos'])->name('caixa.produto.get');
        Route::get('/caixa/supervisores', [CaixaPDVController::class, 'get_supervisores'])->name('caixa.supervisores.get');
        Route::get('/caixa/clientes/get', [CaixaPDVController::class, 'get_clientes'])->name('caixa.clientes.get');
        Route::get('/caixa/formas-pagamento/get', [CaixaPDVController::class, 'get_formas_pagamento'])->name('caixa.formas_pagamento.get');
        Route::get('/caixa/get-vendas-devolucao', [CaixaPDVController::class, 'get_vendas_devolucao'])->name('caixa.devolucao.venda.get');
        Route::get('/caixa/get-venda-devolver', [CaixaPDVController::class, 'get_venda_devolver'])->name('caixa.devolver.venda.get');
        Route::get('/caixa/get-clientes-parcelas-receber', [CaixaPDVController::class, 'get_clientes_parcela_receber'])->name('caixa.cliente.recebimento.parcelas.get');
        Route::get('/caixa/get-cliente-parcelas', [CaixaPDVController::class, 'get_clientes_parcela'])->name('caixa.cliente.parcelas.get');

        //outras para manipulação do caixa
        Route::get('/caixa/get/vendas', [CaixaPDVController::class, 'get_vendas'])->name('caixa.get.vendas');
        Route::get('/caixa/voltar/venda', [CaixaPDVController::class, 'get_venda_voltar'])->name('caixa.voltar.venda');
        Route::post('/caixa/cancelar/venda', [CaixaPDVController::class, 'cancelar_venda'])->name('caixa.cancelar.venda')->defaults('acao_id', config('config.acoes.cancelou_venda_salva.id'));
        Route::post('/caixa/cliente/cadastrar', [CaixaPDVController::class, 'cadastrar_cliente'])->name('caixa.clientes.cadastrar')->defaults('acao_id', config('config.acoes.cadastrou_cliente.id'));
        Route::get('/caixa/teste/venda', [CaixaPDVController::class, 'venda_teste'])->name('caixa.teste.venda');
        Route::get('/caixa/fechar/index/{caixa_id}', [CaixaPDVController::class, 'fechar_caixa_index'])->name('caixa.fechar.index');
        Route::get('/caixa/sangria/get', [CaixaPDVController::class, 'get_sangria'])->name('caixa.sangria.get');
        Route::get('/caixa/sangria/segunda/via', [CaixaPDVController::class, 'get_sangria_segunda_via'])->name('caixa.sangria.segunda_via');
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
