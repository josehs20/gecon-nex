<?php

use Illuminate\Support\Facades\Route;
use Modules\Mercado\Http\Controllers\Gerenciamento\CaixaGerenciamentoController;
use Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('mercados', MercadoController::class)->names('mercado');
// });
Route::get('/', [Modules\Mercado\Http\Controllers\HomeController::class, 'index'])->name('home.index');
/**
 * METODOS GET SELECT2
 *
 */
Route::prefix('/select2')->group(function () {
    Route::get('/unidade_medida', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'select2'])->name('unidade_medida.select2');
    Route::get('/classificacao_produto', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'select2'])->name('classificacao_produto.select2');
    Route::get('/produto', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'select2'])->name('produto.select2');
    Route::get('/estoques/select2', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'estoqueSelect2'])->name('estoques.select2');
    Route::get('/fornecedores/select2', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'getFornecedores'])->name('fornecedores.select2');

});

/**
 * Movimentacao de estoque
 */
Route::prefix('/estoque/movimentacao')->middleware(['processo:' . config('config.processos.gerenciamento.movimentacao.id')])->group(function () {
    Route::get('/index', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'index'])->name('estoque.movimentacao.index');
    Route::get('/create', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'create'])->name('estoque.movimentacao.create')->defaults('acao_id', config('config.acoes.criou_movimentacao_estoque.id'));
    // Route::get('/detalhes/{movimentacaoId}', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'detalhesMovimentacao'])->name('estoque.movimentacao.detalhes');
    Route::get('/get/produtos', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'getProdutos'])->name('estoque.movimentacao.getProdutos');
    Route::get('/get/estoque', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'getEstoque'])->name('estoque.movimentacao.getEstoque');
    Route::post('/movimentar', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'movimentar'])->name('estoque.movimentacao.movimentar')->defaults('acao_id', config('config.acoes.adicionou_item_movimentacao_estoque.id'));
    Route::post('/finalizar/post', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'finalizar_movimentacao'])->name('estoque.movimentacao.finalizar')->defaults('acao_id', config('config.acoes.finalizou_movimentacao.id'));
    Route::delete('/delete/movimentacao/{movimentacao_id}', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'delete'])->name('estoque.movimentacao.delete')->defaults('acao_id', config('config.acoes.removeu_movimentacao_item.id'));
    Route::get('/yajra/movimentacoes/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getMovimentacoes'])->name('yajra.service.estoques.movimentacoes.get');
    Route::get('/yajra/movimentacoes/itens/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getMovimentacoesItens'])->name('yajra.service.estoque.movimentacao.itens.get');


    Route::get('/estoque/movimentacao/edit/{id}', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'edit'])->name('estoque.movimentacao.edit');
    // Route::post('/estoque/movimentacao', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'store'])->name('estoque.movimentacao.store');
    // Route::put('/estoque/movimentacao/update/{estoqueId}', [Modules\Mercado\Http\Controllers\Estoque\MovimentacaoController::class, 'update'])->name('estoque.movimentacao.update');
});

/**
 * Balanco
 */
Route::prefix('/estoque/balanco')->middleware(['processo:' . config('config.processos.gerenciamento.balanco.id')])->group(function () {

    Route::get('/index', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'index'])->name('estoque.balanco.index');
    Route::get('/getProdutos', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'getProdutos'])->name('estoque.balanco.getProdutos');
    Route::get('/getEstoque', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'getEstoque'])->name('estoque.balanco.getEstoque');
    Route::get('/create', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'create'])->name('estoque.balanco.create')->defaults('acao_id', config('config.acoes.criou_balanco.id'));
    Route::get('/edit/{id}', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'edit'])->name('estoque.balanco.edit');
    Route::post('/post', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'store'])->name('estoque.balanco.store')->defaults('acao_id', config('config.acoes.criou_balanco_item.id'));
    Route::post('/finalizar', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'finalizar'])->name('estoque.balanco.finalizar')->defaults('acao_id', config('config.acoes.finalizou_balanco.id'));
    Route::delete('/delete/{balanco_id}', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'delete'])->name('estoque.balanco.delete')->defaults('acao_id', config('config.acoes.cancelou_balanco.id'));
    Route::put('/update/{estoqueId}', [Modules\Mercado\Http\Controllers\Estoque\BalancoController::class, 'update'])->name('estoque.balanco.update')->defaults('acao_id', config('config.acoes.realizou_balanco_estoque.id'));
    Route::get('/yajra/service/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getBalancos'])->name('yajra.service.estoque.balanco.get');
    Route::get('/itens/yajra/service/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getBalancosItens'])->name('yajra.service.estoque.balanco.itens.get');
});


/**
 * Configurações do usuário
 *
 */
Route::prefix('/configuracoes')->group(function () {
    Route::get('/index', [Modules\Mercado\Http\Controllers\Configuracoes\ConfiguracaoController::class, 'index'])->name('configuracao.index');
    Route::get('/perfil', [Modules\Mercado\Http\Controllers\Configuracoes\ConfiguracaoController::class, 'perfil'])->name('configuracoes.perfil');
    Route::post('/perfil/store', [Modules\Mercado\Http\Controllers\Configuracoes\ConfiguracaoController::class, 'perfil_store'])->name('configuracoes.perfil.store');
    Route::post('/perfil/alterar_senha/store', [Modules\Mercado\Http\Controllers\Configuracoes\ConfiguracaoController::class, 'alterar_senha_store'])->name('configuracoes.perfil.alterar_senha.store');
});

/**
 * CRUDS
 */
Route::prefix('/cadastros')->group(function () {

    Route::middleware(['processo:' . config('config.processos.gerenciamento.forma_pagemento.id')])->group(function () {
        /**
         * forma de pagamento
         */
        Route::get('/forma_pagemento/index', [Modules\Mercado\Http\Controllers\Gerenciamento\FormaPagamentoController::class, 'index'])->name('cadastro.forma_pagemento.index');
        Route::get('/forma_pagemento/create', [Modules\Mercado\Http\Controllers\Gerenciamento\FormaPagamentoController::class, 'create'])->name('cadastro.forma_pagamento.create');
        Route::post('/forma_pagemento/store', [Modules\Mercado\Http\Controllers\Gerenciamento\FormaPagamentoController::class, 'store'])->name('cadastro.forma_pagemento.store');
        Route::get('/forma_pagemento/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\FormaPagamentoController::class, 'edit'])->name('cadastro.forma_pagemento.edit');
        Route::put('/forma_pagemento/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\FormaPagamentoController::class, 'update'])->name('cadastro.forma_pagemento.update');
        Route::get('/yajra/forma-pagamentos/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getFormasPagamento'])->name('yajra.service.gerenciamento.forma_pagamento.get');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.unidade_medida.id')])->group(function () {
        /**
         * unidade medida
         */
        Route::get('/unidade_medida/index', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'index'])->name('cadastro.unidade_medida.index');
        Route::get('/unidade_medida/create', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'create'])->name('cadastro.unidade_medida.create');
        Route::post('/unidade_medida', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'store'])->name('cadastro.unidade_medida.store')->defaults('acao_id', config('config.acoes.cadastrou_unidade_medida.id'));
        Route::get('/unidade_medida/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'edit'])->name('cadastro.unidade_medida.edit');
        Route::put('/unidade_medida/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\UnidadeMedidaController::class, 'update'])->name('cadastro.unidade_medida.update')->defaults('acao_id', config('config.acoes.alterou_unidade_medida.id'));
        Route::get('/yajra/un/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getUnidadeMedidas'])->name('yajra.service.unidade_medida.get');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.classificacao_produto.id')])->group(function () {
        /**
         * Classificacao produto
         */
        Route::get('/classificacao_produto/index', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'index'])->name('cadastro.classificacao_produto.index');
        Route::get('/classificacao_produto/create', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'create'])->name('cadastro.classificacao_produto.create');
        Route::post('/classificacao_produto', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'store'])->name('cadastro.classificacao_produto.store')->defaults('acao_id', config('config.acoes.cadastrou_classificacao_produto.id'));
        Route::get('/classificacao_produto/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'edit'])->name('cadastro.classificacao_produto.edit');
        Route::put('/classificacao_produto/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ClassificacaoProdutoController::class, 'update'])->name('cadastro.classificacao_produto.update')->defaults('acao_id', config('config.acoes.alterou_classificacao_produto.id'));
        Route::get('/yajra/get/classificacao_produto', [YajraMercadoController::class, 'getClassificacaoProduto'])->name('yajra.service.gerenciamento.classificao_produto.get');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.produto.id')])->group(function () {
        /**
         * Produtos
         */
        Route::get('/produtos/index', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'index'])->name('cadastro.produto.index');
        Route::get('/produtos/create', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'create'])->name('cadastro.produto.create');
        Route::post('/produtos', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'store'])->name('cadastro.produto.store')->defaults('acao_id', config('config.acoes.cadastrou_produto.id'));
        Route::get('/produtos/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'edit'])->name('cadastro.produto.edit');
        Route::put('/produtos/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'update'])->name('cadastro.produto.update')->defaults('acao_id', config('config.acoes.atualizou_produto.id'));
        Route::get('/produtos/get/yajra', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'get_produtos_yajra'])->name('cadastro.produto.get.yajra');
        Route::get('/nfe/get/ncms', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'get_ncms'])->name('cadastro.produto.get.ncms');
        Route::post('/nfe/post/ncms/{estoque_id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'post_ncms'])->name('cadastro.produto.post.ncms')->defaults('acao_id', config('config.acoes.atualizou_ncm.id'));
        Route::get('/nfe/get/gtin', [Modules\Mercado\Http\Controllers\Gerenciamento\ProdutoController::class, 'get_gtin'])->name('cadastro.produto.nfe.get.gtin');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.fornecedor.id')])->group(function () {
        /**
         * Fornecedores
         */
        Route::get('/fornecedor/index', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'index'])->name('cadastro.fornecedor.index');
        Route::get('/fornecedor/create', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'create'])->name('cadastro.fornecedor.create');
        Route::post('/fornecedor/store', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'store'])->name('cadastro.fornecedor.store')->defaults('acao_id', config('config.acoes.cadastrou_fornecedor.id'));
        Route::get('/fornecedor/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'edit'])->name('cadastro.fornecedor.edit');
        Route::post('/fornecedor/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'update'])->name('cadastro.fornecedor.update')->defaults('acao_id', config('config.acoes.alterou_fornecedor.id'));
        Route::get('listar/fornecedores/{fornecedoresInativos?}', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'listarFornecedores'])->name('listar.fornecedores');
        Route::get('fornecedor/info/get', [Modules\Mercado\Http\Controllers\Gerenciamento\FornecedorController::class, 'getFornecedor'])->name('cadastro.fornecedor.get');
        Route::get('yajra/fornecedores/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getFornecedores'])->name('yajra.service.gerenciamento.fornecedor.get');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.estoque.id')])->group(function () {
        /**
         * Estoque
         */

        Route::get('/estoque/index', [Modules\Mercado\Http\Controllers\Estoque\EstoqueController::class, 'index'])->name('cadastro.estoque.index');
        Route::get('/estoque/create', [Modules\Mercado\Http\Controllers\Estoque\EstoqueController::class, 'create'])->name('cadastro.estoque.create');
        Route::post('/estoque', [Modules\Mercado\Http\Controllers\Estoque\EstoqueController::class, 'store'])->name('cadastro.estoque.store');
        Route::get('/estoque/edit/{id}', [Modules\Mercado\Http\Controllers\Estoque\EstoqueController::class, 'edit'])->name('cadastro.estoque.edit');
        Route::put('/estoque/update/{id}', [Modules\Mercado\Http\Controllers\Estoque\EstoqueController::class, 'update'])->name('cadastro.estoque.update')->defaults('acao_id', config('config.acoes.alterou_informacao_estoque.id'));
        Route::get('/yajra/get/estoques', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getEstoques'])->name('yajra.service.estoques.get');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.estoque.id')])->prefix('caixa')->group(function () {
        /**
         * Caixas
         */
        Route::get('/index', [CaixaGerenciamentoController::class, 'index'])->name('cadastro.caixa.index');
        Route::get('/create', [CaixaGerenciamentoController::class, 'create'])->name('cadastro.caixa.create');
        Route::post('/store', [CaixaGerenciamentoController::class, 'store'])->name('cadastro.caixa.store')->defaults('acao_id', config('config.acoes.criou_caixa.id'));
        Route::post('/recursos/store/{id}', [CaixaGerenciamentoController::class, 'create_recursos_caixa'])->name('cadastro.caixa.store.recursos_caixa')->defaults('acao_id', config('config.acoes.atualizou_recursos_caixa.id'));
        Route::post('/permissoes/store/{id}', [CaixaGerenciamentoController::class, 'create_caixa_permissoes'])->name('cadastro.caixa.salvar_permissao')->defaults('acao_id', config('config.acoes.atualizou_permissao_caixa.id'));
        Route::post('/permissoes/delete', [CaixaGerenciamentoController::class, 'delete_caixa_permissoes'])->name('cadastro.caixa.delete.permissao')->defaults('acao_id', config('config.acoes.excluiu_permissao_caixa.id'));

        Route::get('/edit/{id}', [CaixaGerenciamentoController::class, 'edit'])->name('cadastro.caixa.edit');
        Route::put('/update/{id}', [CaixaGerenciamentoController::class, 'update'])->name('cadastro.caixa.update')->defaults('acao_id', config('config.acoes.atualizou_caixa.id'));
        Route::get('/yajra/get/caixas', [YajraMercadoController::class, 'getCaixas'])->name('yajra.service.gerenciamento.caixa.get');
        Route::get('/permissoes', [CaixaGerenciamentoController::class, 'get_usuarios_permissao_caixa'])->name('cadastro.caixa.get_usuarios_permissao_caixa');
        Route::get('/usuarios/permissao/get', [CaixaGerenciamentoController::class, 'get_usuarios'])->name('cadastro.caixa.get_usuarios');
    });

    Route::middleware(['processo:' . config('config.processos.gerenciamento.cliente.id')])->group(function () {
        /**
         * Cliente
         */
        Route::get('/cliente/index', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'index'])->name('cadastro.cliente.index');
        Route::get('/cliente/create', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'create'])->name('cadastro.cliente.create');
        Route::post('/cliente/store', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'store'])->name('cadastro.cliente.store')->defaults('acao_id', config('config.acoes.cadastrou_cliente.id'));
        Route::get('/cliente/edit/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'edit'])->name('cadastro.cliente.edit');
        Route::post('/cliente/update/{id}', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'update'])->name('cadastro.cliente.update')->defaults('acao_id', config('config.acoes.alterou_cliente.id'));
        Route::get('listar/clientes/{clientesInativos?}', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'listarClientes'])->name('listar.clientes');
        Route::get('cliente/get/json', [Modules\Mercado\Http\Controllers\Gerenciamento\ClienteController::class, 'getCliente'])->name('cadastro.cliente.get.cliente');
        Route::get('/yajra/get/clientes', [YajraMercadoController::class, 'getClientes'])->name('yajra.service.gerenciamento.clientes.get');
    });
});

Route::middleware(['processo:' . config('config.processos.gerenciamento.recebimento.id')])->group(function () {
    /**
     * Recebimeto de mercadorias
     */
    Route::get('/recebimento/index', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'index'])->name('cadastro.recebimento.index');
    Route::get('/estoque/recebimento/create', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'create'])->name('estoque.recebimento.create');
    Route::get('/estoque/produtos/get', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'get_produtos'])->name('estoque.recebimento.produtos.get');
    Route::get('/estoque/recebimento/pedido/{pedido_id}', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'receber_pedido'])->name('estoque.recebimento.iniciar');
    Route::post('/estoque/recebimento/pedido/receber', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'receber'])->name('estoque.recebimento.receber')->defaults('acao_id', config('config.acoes.realizou_recebimento.id'));
    Route::get('/download/nf/{arquivo_id}', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'download_nf'])->name('download.nf');
    Route::post('/estoque/recebimento/nf/receber', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'receber_nf'])->name('estoque.recebimento.receber.nf')->defaults('acao_id', config('config.acoes.realizou_recebimento.id'));
    Route::get('/estoque/recebimento/nf', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'receber_nf_create'])->name('estoque.recebimento.nf.create');
    Route::get('/estoque/recebimento/qr-code', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'gerar_qr_code'])->name('gerar.qr.code.recebimento');
});

Route::middleware(['processo:' . config('config.processos.gerenciamento.pedidos.id')])->group(function () {
    /**
     * Pedido
     */
    Route::get('/pedidos/index', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'index'])->name('cadastro.pedido.index');
    Route::get('/pedidos/create', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'create'])->name('cadastro.pedido.create');
    Route::post('/pedidos/post', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'store'])->name('cadastro.pedido.post')->defaults('acao_id', config('config.acoes.realizou_pedido.id'));
    Route::get('/pedidos/edit/{id}', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'edit'])->name('cadastro.pedido.edit');
    Route::delete('/pedidos/delete/{pedido_id}', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'delete'])->name('pedido.cadastro.delete')->defaults('acao_id', config('config.acoes.cancelou_pedido.id'));
    Route::get('/yajra/pedidos/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getPedidos'])->name('yajra.service.pedidos.get');
});

Route::middleware(['processo:' . config('config.processos.gerenciamento.cotacao.id')])->group(function () {
    /**
     * Cotação
     */
    Route::get('/cotacao/index', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'index'])->name('cadastro.cotacao.index');
    Route::get('/cotacao/create', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'create'])->name('cadastro.cotacao.create');
    Route::get('/cotacao/edit/{cotacao_id}', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'edit'])->name('cadastro.cotacao.edit');
    Route::delete('/cotacao/delete/{cotacao_id}', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'delete'])->name('cadastro.cotacao.delete')->defaults('acao_id', config('config.acoes.cancelou_cotacao.id'));
    Route::post('/cotacao/post', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'store'])->name('cadastro.cotacao.post')->defaults('acao_id', config('config.acoes.iniciou_cotacao.id'));
    Route::post('/cotacao/update', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'update'])->name('cadastro.cotacao.update')->defaults('acao_id', config('config.acoes.atualizou_cotacao.id'));
    Route::get('/cotacao/selecionar-pedidos', [Modules\Mercado\Http\Controllers\Pedido\CotacaoController::class, 'selecionarPedidos'])->name('cadastro.cotacao.selecionar_pedidos');
    Route::get('/yajra/cotacao/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getCotacoes'])->name('yajra.service.cotacao.get');
});

Route::middleware(['processo:' . config('config.processos.gerenciamento.compras.id')])->group(function () {
    /**
     * Compras
     */
    Route::get('/compras/index', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'index'])->name('cadastro.compra.index');
    Route::get('/compras/selecionar_cotacoes', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'selecionar_cotacoes'])->name('cadastro.compra.selecionar_cotacoes');
    Route::get('/compras/create/{cotacao_id}', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'create'])->name('cadastro.compra.create');
    Route::post('/compras/store', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'store'])->name('cadastro.compra.post')->defaults('acao_id', config('config.acoes.criou_compra.id'));
    Route::get('/compras/edit/{compra_id}', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'edit'])->name('cadastro.compra.edit');
    Route::delete('/compras/delete/{compra_id}', [Modules\Mercado\Http\Controllers\Pedido\CompraController::class, 'delete'])->name('cadastro.compra.delete')->defaults('acao_id', config('config.acoes.cancelou_compra.id'));

    Route::get('/yajra/compras/get', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getCompras'])->name('yajra.service.compra.get');

});

Route::middleware(['processo:' . config('config.processos.nfe.empresa.id')])->group(function () {
    /**
     * NFE
     */
    Route::get('/nfe/empresa/index', [Modules\Mercado\Http\Controllers\NFE\EmpresaNFEController::class, 'index'])->name('nfe.empresa.index');
});
Route::middleware(['processo:' . config('config.processos.nfe.certificado.id')])->group(function () {
    /**
     * Certificado
     */
    Route::get('/nfe/certificado/index', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'index'])->name('nfe.certificado.index');
});
Route::middleware(['processo:' . config('config.processos.nfe.inscricao_estadual.id')])->group(function () {
    /**
     * Inscrição estadual
     */
    Route::get('/nfe/inscricao_estadual/index', [Modules\Mercado\Http\Controllers\Pedido\PedidoController::class, 'index'])->name('nfe.inscricao_estadual.index');
});
Route::post('/confirmarComSenha', [Modules\Mercado\Http\Controllers\ControllerBaseMercado::class, 'confirmar_com_senha'])->name('confirmar_com_senha');
Route::post('/scanear/nf', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'scanear_nf'])->name('scanear.nf');
Route::get('consulta/nf/services', [Modules\Mercado\Http\Controllers\Estoque\RecebimentoController::class, 'consulta_nf'])->name('consulta.nf')->middleware('auth');

/**
 * FABRICANTES
 */
Route::prefix('/fabricantes')->group(function () {
    Route::middleware(['processo:' . config('config.processos.gerenciamento.fabricantes.id')])->group(function () {
        Route::get('/yajra-get-fabricante', [Modules\Mercado\Http\Controllers\Yajra\YajraMercadoController::class, 'getFabricantes'])->name('yajra.service.fabricante.get');
        Route::get('/index', [Modules\Mercado\Http\Controllers\Gerenciamento\FabricanteController::class, 'index'])->name('cadastro.fabricante.index');
        Route::get('/create', [Modules\Mercado\Http\Controllers\Gerenciamento\FabricanteController::class, 'create'])->name('cadastro.fabricante.create');
        Route::get('/edit/{fabricante_id}', [Modules\Mercado\Http\Controllers\Gerenciamento\FabricanteController::class, 'edit'])->name('cadastro.fabricante.edit');
        Route::post('/update/{fabricante_id}/{endereco_id?}', [Modules\Mercado\Http\Controllers\Gerenciamento\FabricanteController::class, 'update'])->name('cadastro.fabricante.update')->defaults('acao_id', config('config.acoes.atualizou_fabricante.id'));
        Route::post('/store', [Modules\Mercado\Http\Controllers\Gerenciamento\FabricanteController::class, 'store'])->name('cadastro.fabricante.store')->defaults('acao_id', config('config.acoes.cadastrou_fabricante.id'));
    });
});

