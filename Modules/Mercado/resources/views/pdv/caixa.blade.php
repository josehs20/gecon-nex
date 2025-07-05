@extends('mercado::layouts.pdv')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>

    <img src="{{ asset('img/logo_mercado.jpg') }}" class="imagem-canto-inferior">

    @php
        $corElemento = auth()->user()->empresa->caixa_cor ?? '#0b00aa';
        $corFundo    = auth()->user()->empresa->caixa_cor_fundo ?? 'rgb(241, 239, 250)';
        $corTexto    = auth()->user()->empresa->caixa_cor_letras ?? '#fff';
    @endphp

    {{-- Para o card ocupar a altura total, o pai imediato do card (geralmente body ou main)
         precisa ter altura definida. Se o layout principal (layouts.pdv) já tiver isso,
         então adicionar 'h-100' ou 'min-vh-100' ao 'card' pode ajudar.
         Vamos adicionar 'h-100' ao card e 'flex-grow-1' se o contêiner pai for um flexbox. --}}
    {{-- <div class="card h-100" style="background-color: rgb(241, 239, 250)"> Adicionado 'h-100' aqui --}}
    <div class="card h-100" style="background-color: {{$corFundo}}">
        <div class="card-header d-flex justify-content-between align-items-center px-4" style="background-color: {{$corElemento}}; color: {{$corTexto}}">
            <div>
                <div id="qrcode"></div>
                <button id="imprimir">testes imprimir</button>


                <h4 class="mb-0 fw-bold fs-5"><i class="bi bi-cash-register me-2"></i>CAIXA: <span
                        class="cashier-number">1</span></h4>
            </div>
            <div class="text-center">
                <h4 class="mb-0 fw-bold fs-5"><u id="statusCaixa">LIVRE</u></h4>
            </div>
            <div class="text-end">
                <div class="small-text">Versão: 1.0.0</div>
                <div class="small-text">Operador: João Silva</div>
            </div>
        </div>

        <div class="card-body d-flex flex-column"> {{-- Adicionado d-flex flex-column para fazer o row ocupar o restante do espaço --}}
            <div class="row flex-grow-1"> {{-- Adicionado flex-grow-1 para a row ocupar o restante do espaço do card-body --}}
                <div class="col-md-5 d-flex flex-column" style="border-right: 1px solid #dee2e6;">
                    <div class="flex-grow-1 d-flex flex-column">

                        <div class="form-group position-relative" style="z-index: 999;">
                        <div class="form-group" style="background-color: #dee2e6">
                            <label for="produto-select" class="text_padrao">Código de barras / Produto *</label>

                            <input type="text" id="busca-produto" class="form-control"
                                placeholder="Digite o nome ou código do produto" autocomplete="off">
                        </div>

                        {{-- Resultado fixo visível --}}
                        <div id="resultado-produto" class="list-group resultado-estatica">
                            <!-- Resultados vão aqui -->
                        </div>

                        <div class="mt-auto">
                            <div class="form-group mt-2">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <label for="quantidade" class="text_padrao">Quantidade * </label>
                                        <input type="text" class="form-control maskQtdByClass" id="quantidade"
                                            name="quantidade" required>
                                    </div>
                                    <div class="mr-2">
                                        <label for="valor-unitario" class="text_padrao">Valor Unitário</label>
                                        <input type="text" class="form-control maskDinheiroByClass" id="valor-unitario"
                                            name="valor_unitario" readonly>
                                    </div>
                                    <div class="mr-2">
                                        <label for="total-item" class="text_padrao">Total</label>
                                        <input type="text" class="form-control maskDinheiroByClass" id="total-item"
                                            name="total_item" readonly>
                                    </div>
                                    {{-- <div class="align-self-end">
                                    <button type="button" id="adicionarItemButton"
                                        class="btn btn-azul-forte">ADICIONAR</button>
                                </div> --}}
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+1</span>

                                        <button id="finalizaVendaBtnSideBar"
                                            class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#finalizarVendaSidebar">
                                            FINALIZAR
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+2</span>

                                        <button id="devolucaoVenda"
                                            class="btn btn-azul-forte w-100 sidebar-toggle-btn bg_padrao"
                                            data-sidebar-target="#devolucaoSidebar">
                                            DEVOLUÇÃO
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+3</span>

                                        <button id="orcamentoVenda" class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#orcamentoSiedbar">
                                            ORÇAMENTO
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+4</span>
                                        <button id="suprirCaixa" class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#suprirSidebar">
                                            SUPRIR
                                        </button>
                                    </div>

                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+5</span>
                                        <button id="sangriaCaixa" class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#sangriaSidebar">
                                            SANGRIA
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+6</span>
                                        <button id="receberConta" class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#recebimentoSidebar">
                                            RECEBER
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+7</span>
                                        <button id="fecharCaixa" class="btn btn-azul-forte w-100 sidebar-toggle-btn"
                                            data-sidebar-target="#fechamentoSidebar">
                                            FECHAR
                                        </button>
                                    </div>
                                    <div class="col-md-3 mt-1 text-center">
                                        <span class="atalho-label mx-4">⇧+8</span>
                                        <button id="cancelar" class="btn btn-azul-forte w-100">
                                            CANCELAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 d-flex flex-column">
                    <div class="flex-grow-1">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-striped table-hover table-sm">
                                <thead class="sticky-top table-header-custom" style="background-color: {{$corElemento}}; color: {{$corTexto}}">
                                    <tr>
                                        <th class="">Código</th>
                                        <th class="">Nome</th>
                                        <th class="text-center " style="width: 10%;">Quatidade</th>
                                        <th class="text-right " style="width: 10%;">Preço</th>
                                        <th class="text-right " style="width: 10%;">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="itensVendaTableBody" class="table-body-custom"> {{-- Linhas de itens serão injetadas aqui pelo JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-info text-center mt-3" id="noItemsMessage" style="display: none;">
                            Nenhum item adicionado ainda.
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-start">
                            <div class="mr-2 mt-4 flex-grow-1 col-md-3">
                                <label for="desconto" class="text_padrao">desconto (%) </label>
                                {{-- Adicionei um span para exibir o desconto em reais ao lado do label --}}
                                {{-- <span class="badge badge-primary mx-2" id="desconto-reais">0,00</span> --}}
                                {{-- Mantido como type="text" para a máscara de porcentagem/dinheiro --}}
                                <input type="text" class="form-control maskPorcentagem" id="desconto"
                                    name="desconto" placeholder="0,00" required>
                            </div>
                            <div class="mr-2 mt-4 flex-grow-1 col-md-3">
                                <label for="desconto" class="text_padrao">desconto (R$) </label>
                                <input type="text" class="form-control" id="desconto-reais" name="desconto-reais"
                                    placeholder="0,00" readonly>
                            </div>
                            <div class="mr-2 flex-grow-1 col-md-4">
                                <label for="total-venda" class="text_padrao"><u>Total da Venda</u></label>
                                <input style="height: 65px; font-size: 32px;" type="text"
                                    class="form-control form-control-lg maskDinheiroByClass" id="total-venda"
                                    name="total_venda" value="0.00" readonly>

                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->empresa && auth()->user()->empresa->foto)
                        <div class="text-end">
                            <img src="{{auth()->user()->empresa && auth()->user()->empresa->foto ? asset('storage/' . auth()->user()->empresa->foto) : ''}}" alt="Foto da empresa" width="300">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="dataView" data-rota-finalizar-venda="{{ route('caixa.finalizar.venda') }}"
        data-rota-devolucao-venda="{{ route('caixa.devolucao.venda') }}"
        data-especie-credito-loja="{{ config('config.especie_pagamento.credito_loja.id') }}"
        data-rota-orcamento-venda="{{ route('caixa.orcamento.venda') }}"
        data-rota-suprir-caixa="{{ route('caixa.suprir') }}" data-rota-sangria-caixa="{{ route('caixa.sangria.post') }}"
        data-rota-receber-conta="{{ route('caixa.receber.conta.post') }}"
        data-caixa-produto-get="{{ route('caixa.produto.get') }}" data-is-master-caixa="{{ $isMasterCaixa ?? null }}"
        data-rota-adicionar-item="{{ route('caixa.adicionar.item') }}"
        data-rota-remover-item="{{ route('caixa.remover.item') }}" data-caixa-itens-temp="{{ $itensTemp }}"
        data-caixa-itens-temp-total="{{ $itensTemp->count() == 0 ? 0 : $itensTemp->sum('total') }}"
        data-rota-supervisores="{{ route('caixa.supervisores.get') }}"
        data-validar-superior="{{ route('caixa.supervisor.validar') }}"
        data-clientes-get="{{ route('caixa.clientes.get') }}"
        data-formas-pagamento-get="{{ route('caixa.formas_pagamento.get') }}"
        data-rota-vendas-devolucao-get="{{ route('caixa.devolucao.venda.get') }}"
        data-rota-venda-devolver-get="{{ route('caixa.devolver.venda.get') }}"
        data-rota-orcamento="{{ route('caixa.orcamento.get') }}"
        data-rota-orcamento-get="{{ route('caixa.orcamento.get.itens') }}"
        data-excluir-orcamento="{{ route('caixa.orcamento.excluir') }}"
        data-rota-get-cliente-recebimento-parcelas="{{ route('caixa.cliente.recebimento.parcelas.get') }}"
        data-rota-get-cliente-parcelas="{{ route('caixa.cliente.parcelas.get') }}"
        data-rota-get-caixa-fechamento="{{ route('caixa.fechamento.caixa.get') }}"
        data-rota-teste-impressao="{{ route('caixa.teste.impressao') }}"
        data-rota-get-especies="{{ route('caixa.get.especies') }}" data-rota-get-caixa="{{ route('caixa.get.caixa') }}"
        data-rota-colocar-orcamento-em-venda="{{ route('caixa.colcoar.orcamento.orcamento.em.venda') }}"
        data-rota-fechar-caixa="{{ route('caixa.fechar.post') }}"></div>

    <!-- Modal para validação de senha -->
    <div class="modal fade" id="modalSenhaSuperior" tabindex="-1" role="dialog"
        aria-labelledby="modalSenhaSuperiorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formSenhaSuperior">
                    <div class="modal-header bg_padrao">
                        <h5 class="modal-title" id="modalSenhaSuperiorLabel">Validação de Superior</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="supervisor-select">Supervisor *</label>
                            <select required class="form-control select2" id="supervisor-select" name="supervisor"
                                style="width: 100%;">
                                <option disabled value="">Selecione o supervisor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="senhaSuperior">Senha do superior</label>
                            <input type="password" class="form-control" id="senhaSuperior" name="senhaSuperior"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-dark">Validar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- siedbar finalizzar venda --}}
    <div id="finalizarVendaSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Finalizar Venda</h3>
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#finalizarVendaSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formFinalizarVenda">
                <div class="form-group">
                    <label for="cliente-select">Cliente *</label>
                    <select required class="form-control select2" id="cliente-select" name="cliente_id"
                        style="width: 100%;">
                    </select>
                </div>

                <div class="form-group">
                    <label for="forma-pagamento-select">Formas de pagamento *</label>
                    <select required class="form-control select2" id="forma-pagamento-select" multiple
                        style="width: 100%;">
                    </select>
                    <div id="formasPagamentoValores" class="mt-3">
                    </div>
                </div>

                <div class="form-group mt-4"> <label class="d-block mb-2">Tipo de Finalização *</label>
                    <div class="d-flex justify-content-between finalizacao-options-group">
                        <input type="radio" id="finalizar-nfce" name="tipo_finalizacao" value="nfce"
                            class="d-none">
                        <label for="finalizar-nfce" class="finalizacao-option">
                            Emitir NFC-e
                        </label>

                        <input type="radio" id="finalizar-cupom" name="tipo_finalizacao" value="cupom"
                            class="d-none" checked>
                        <label for="finalizar-cupom" class="finalizacao-option">
                            Emitir Cupom Fiscal
                        </label>

                        <input type="radio" id="finalizar-somente" name="tipo_finalizacao" value="somente"
                            class="d-none">

                        <label for="finalizar-somente" class="finalizacao-option">
                            Somente Finalizar
                        </label>

                        <input type="radio" id="finalizar-como-orcamento" name="tipo_finalizacao" value="orcamento"
                            class="d-none">

                        <label for="finalizar-como-orcamento" class="finalizacao-option">
                            Orçamento
                        </label>
                    </div>
                </div>
                <button id="buttonSubmitFormFinalizarVenda" type="submit" class="btn btn-success w-100 mt-3">Confirmar
                    Finalização</button>
            </form>
        </div>
    </div>

    {{-- siedbar orçamento venda --}}
    <div id="orcamentoSiedbar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Orçamento</h3>
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#orcamentoSiedbar">&times;</button>
        </div>
        <div class="sidebar-body">
            <div class="form-group">
                <label for="orcamento-select">Buscar orçamento:</label>
                <div class="d-flex align-items-end gap-2">
                    <select required class="form-control select2 flex-grow-1 mr-2 col-md-6" id="orcamento-select"
                        name="orcaemento-select" style="min-width: 400px;">
                    </select>
                    <button id="buttonExcluirOrcamento" type="button" class="btn btn-danger mx-1 mt-1">
                        Excluir
                    </button>
                </div>
            </div>
            <div class="form-group mt-3">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div class="w-50 me-2 mx-1"> <label for="valor-total-orcamento">Total da Venda</label>
                        <input type="text" class="form-control" id="valor-total-orcamento" value="R$ 0,00" readonly>
                    </div>
                    <div class="w-50 ms-2"> <label for="valor-total-desconto-orcamento">Desconto</label>
                        <span class="badge badge-primary mx-2" id="desconto-orcamento-reais">R$ 0,00</span>

                        <input type="text" class="form-control" id="valor-total-desconto-orcamento" value="R$ 0,00"
                            readonly>
                    </div>
                </div>
            </div>
            <button id="colocarOrcamentoEmVenda" type="button" class="btn btn-success w-100 mt-3">
                Adicionar itens a venda
            </button>
        </div>
    </div>
    {{-- Sidebar Itens de orçamento (Filha - 50% à direita) --}}
    <div id="orcamentoItensSidebar" class="sidebar-filha">
        <div class="sidebar-header py-2 bg_padrao">
            <h3 id="ocamentoText"></h3> {{-- Para exibir o número da venda --}}
            {{-- Adicione o botão de fechar para a sidebar filha --}}

        </div>
        <div class="sidebar-body">
            <div class="alert alert-warning mt-2 mb-0 p-2" role="alert" style="font-size: 0.875rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Ao clicar em "Adicioanr itens a venda", esses itens serão colocar a uma nova venda.
            </div>
            <br>
            {{-- Estrutura para os itens da devolução --}}
            <div id="orcamentoItensContainer" class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Código</th>
                            <th>Nome</th>
                            <th class="">quantidade</th>
                            <th class="" style="width: 15%;">Preço</th>
                            <th class="" style="width: 15%;">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="orcamentoItensTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar de Devolução (Principal - 50% à esquerda) --}}
    <div id="devolucaoSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Devolução</h3>
            {{-- Adicione o botão de fechar para a sidebar principal --}}
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#devolucaoSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formDevolucao">
                <div class="form-group">
                    <label for="venda-devolucao-select">Nº Venda *</label>
                    <select required class="form-control select2" id="venda-devolucao-select" style="width: 100%;">
                        <option value="">Selecione uma venda</option>
                    </select>

                </div>
                <div class="form-group mt-3">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div class="w-50 me-2 mx-1"> <label for="valor-total-venda-devolucao">Total da Venda</label>
                            <input type="text" class="form-control" id="valor-total-venda-devolucao" value="R$ 0,00"
                                readonly>
                        </div>
                        <div class="w-50 ms-2"> <label for="valor-total-desconto-devolucao">Desconto</label>
                            <span class="badge badge-primary mx-2" id="desconto-devolucao-reais">R$ 0,00</span>

                            <input type="text" class="form-control" id="valor-total-desconto-devolucao"
                                value="R$ 0,00" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="valor-total-devolucao">Valor total a devolver</label>
                    <input type="text" class="form-control" id="valor-total-devolucao" value="R$ 0,00" readonly>
                </div>
                <div class="form-group">
                    <label for="forma-pagamento-devolucao-select">Espécie de pagamento *

                    </label>
                    <select required class="form-control select2" id="forma-pagamento-devolucao-select"
                        style="width: 100%;">
                        @foreach ($especiesPagamento as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="form-group">
                    <label for="motivo-devolucao-textarea">Motivo da Devolução *</label>
                    <textarea class="form-control" id="motivo-devolucao-textarea" name="motivo_devolucao" rows="3" required></textarea>
                </div>

                <button id="buttonSubmitFormDevolucao" type="submit" class="btn btn-danger w-100 mt-3">Confirmar
                    Devolução</button>
            </form>
        </div>
    </div>

    {{-- Sidebar Itens de Devolução (Filha - 50% à direita) --}}
    <div id="devolucaoItensSidebar" class="sidebar-filha">
        <div class="sidebar-header py-2 bg_padrao">
            <h3 id="vendaNumeroDevolucao"></h3> {{-- Para exibir o número da venda --}}
            {{-- Adicione o botão de fechar para a sidebar filha --}}
            {{-- <button type="button" class="close-btn close-sidebar-btn" data-sidebar-target="#devolucaoItensSidebar">&times;</button> --}}
        </div>
        <div class="sidebar-body">
            <div class="alert alert-warning mt-2 mb-0 p-2" role="alert" style="font-size: 0.875rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Itens com <strong>quantidade a devolver igual a 0</strong> serão <strong>desconsiderados</strong> na
                devolução.
            </div>
            <span class="badge badge-primary mt-2" id="aviso-especie-devolucao"></span>

            <br>
            {{-- Estrutura para os itens da devolução --}}
            <div id="orcamentoItensContainer" class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Código</th>
                            <th>Nome</th>
                            <th class="">Qtd</th>
                            <th class="" style="width: 15%;">Preço</th>
                            <th class="" style="width: 15%;">Total</th>
                            <th class="" style="width: 10%;">já devolvida</th>
                            <th class="" style="width: 20%;">Qtd. Devolver</th>
                        </tr>
                    </thead>
                    <tbody id="devolucaoItensTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar de suprir o caixa (Principal - 50% à esquerda) --}}
    <div id="suprirSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Suprimentos</h3>
            {{-- Adicione o botão de fechar para a sidebar principal --}}
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#suprirSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formSuprir">
                <div class="form-group mt-3">
                    <label for="valor-suprir-caixa">Valor*</label>
                    <input type="text" required class="form-control" id="valor-suprir-caixa" placeholder="0,00">
                </div>
                <div class="form-group">
                    <label for="suprir-especie-caixa">Espécie *</label>
                    <select required class="form-control select2" id="suprir-especie-caixa" style="width: 100%;">
                    </select>

                </div>
                <div class="form-group">
                    <label for="observacao-suprir">Observação *</label>
                    <textarea class="form-control" id="observacao-suprir" name="motivo_suprir" rows="3" required></textarea>
                </div>

                <button id="buttonSubmitFormSuprir" type="submit" class="btn btn-success w-100 mt-3">Confirmar
                    suprimento</button>
            </form>
        </div>
    </div>

    {{-- Sidebar de sangria o caixa (Principal - 50% à esquerda) --}}
    <div id="sangriaSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Sangria</h3>
            {{-- Adicione o botão de fechar para a sidebar principal --}}
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#sangriaSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formSangria">
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-6"> <label for="valor-total-caixa-sangria">Valor total em caixa:</label>
                            <input type="text" class="form-control" id="valor-total-caixa-sangria" value="R$ 0,00"
                                readonly>
                        </div>
                        <div class="col-md-6"> <label for="valor-total-em-dinheiro-sangria">Total em dinheiro *</label>
                            <input type="text" class="form-control" id="valor-total-em-dinheiro-sangria" readonly
                                value="R$ 0,00">
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="valor-sangria-caixa">Valor sangria*</label>
                    <input type="text" class="form-control" id="valor-sangria-caixa" placeholder="R$ 0,00">
                </div>
                <div class="form-group">
                    <label for="especie-sangria">Espécie *</label>
                    <select required class="form-control select2" id="especie-sangria" style="width: 100%;">
                    </select>

                </div>
                <div class="form-group">
                    <label for="motivo-sangria">Motivo *</label>
                    <textarea class="form-control" id="motivo-sangria" name="motivo_sangria" rows="3" required></textarea>
                </div>

                <button id="buttonSubmitFormSangria" type="submit" class="btn btn-danger w-100 mt-3">Confirmar
                    sangria</button>
            </form>
        </div>
    </div>

    {{-- Sidebar de recebiemento (Principal - 50% à esquerda) --}}
    <div id="recebimentoSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Recebimento</h3>
            {{-- Adicione o botão de fechar para a sidebar principal --}}
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#recebimentoSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formRecebimento">
                <div class="form-group">
                    <label for="cliente-recebimento-select">Cliente *</label>
                    <select required class="form-control select2" id="cliente-recebimento-select" style="width: 100%;">
                        {{-- <option value="">Selecione um cliente</option> --}}
                    </select>

                </div>
                <div class="form-group mt-3">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div class="w-50 me-2 mx-1"> <label for="valor-total-venda-recebimento">Total receber</label>
                            <input type="text" class="form-control" id="valor-total-venda-recebimento"
                                value="R$ 0,00" readonly>
                        </div>
                        <div class="w-50 me-2 mx-1"> <label for="cliente-credito-ave">Crédito (avê)</label>
                            <input type="text" class="form-control" id="cliente-credito-ave" value="R$ 0,00"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="forma-pagamento-recebimento-select">Forma de pagamento *</label>
                    <select required class="form-control select2" id="forma-pagamento-recebimento-select"
                        style="width: 100%;">
                    </select>
                </div>
                <div class="form-group">
                    <label for="observacao-recebimento-textarea">Observacao *</label>
                    <textarea class="form-control" id="observacao-recebimento-textarea" name="observacao_recebimento" rows="3"></textarea>
                </div>

                <button id="buttonSubmitFormRecebimento" type="submit" class="btn btn-success w-100 mt-3">Confirmar
                    recebimento</button>
            </form>
        </div>
    </div>

    {{-- Sidebar parcelas filhas (Filha - 50% à direita) --}}
    <div id="recebimentoParcelasSidebar" class="sidebar-filha">
        <div class="sidebar-header py-2 bg_padrao">
            <h3 id="clienteRecebimento"></h3> {{-- Para exibir o número da venda --}}
            {{-- Adicione o botão de fechar para a sidebar filha --}}
            {{-- <button type="button" class="close-btn close-sidebar-btn" data-sidebar-target="#devolucaoItensSidebar">&times;</button> --}}
        </div>
        <div class="sidebar-body">
            {{-- <div class="alert alert-warning mt-2 mb-0 p-2" role="alert" style="font-size: 0.875rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Itens com <strong>quantidade a devolver igual a 0</strong> serão <strong>desconsiderados</strong> na
                devolução.
            </div>
                <span
                            class="badge badge-primary mt-2" id="aviso-especie-devolucao"></span> --}}

            <br>
            {{-- Estrutura para os itens do recebimento de parcelas --}}
            <div id="recebimentoParcelasContainer" class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th>Restante</th>
                            <th>Devolvido</th>
                            <th>Vencimento</th>
                            <th>Receber</th>
                        </tr>
                    </thead>
                    <tbody id="recebimentoParcelasTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar de fechamento de caixa (Principal - 50% à esquerda) --}}
    <div id="fechamentoSidebar" class="sidebar">
        <div class="sidebar-header py-2 bg_padrao">
            <h3>Fechamento de caixa</h3>
            {{-- Adicione o botão de fechar para a sidebar principal --}}
            <button type="button" class="close-btn close-sidebar-btn"
                data-sidebar-target="#fechamentoSidebar">&times;</button>
        </div>
        <div class="sidebar-body">
            <form id="formFechamento">
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div class="w-50 me-2 mx-1"> <label for="valor-total-fechar-caixa">Total</label>
                            <input type="text" class="form-control" id="valor-total-fechar-caixa" value=""
                                readonly>
                        </div>
                        <div class="w-50 me-2 mx-1"> <label for="valor-total-credito-fechar-caixa">Cŕedito
                                loja</label>
                            <input type="text" class="form-control" id="valor-total-credito-fechar-caixa"
                                value="" readonly>
                        </div>
                        <div class="w-50 me-2 mx-1"> <label for="valor-total-dinheiro-fechar-caixa">Dinheiro</label>
                            <input type="text" class="form-control" id="valor-total-dinheiro-fechar-caixa"
                                value="" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div class="w-50 me-2 mx-1"> <label for="valor-dinheiro-fechar-caixa">Valor em caixa *
                                (Dinheiro)</label>
                            <input type="text" class="form-control" id="valor-dinheiro-fechar-caixa" value="">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="observacao-fechamento-textarea">Observacao *</label>
                    <textarea class="form-control" id="observacao-fechamento-textarea" name="observacao_recebimento" rows="3"></textarea>
                </div>

                <button id="buttonSubmitFormFechamento" type="submit" class="btn btn-success w-100 mt-3">Confirmar
                    fechamento</button>
            </form>
        </div>
    </div>

    {{-- Sidebar parcelas filhas (Filha - 50% à direita) --}}
    <div id="fechamentoSidebarFilha" class="sidebar-filha">
        <div class="sidebar-header py-2 bg_padrao">
            <h3 id="tituloSidebarFilhaFechamento"></h3> {{-- Para exibir o número da venda --}}
            {{-- Adicione o botão de fechar para a sidebar filha --}}
            {{-- <button type="button" class="close-btn close-sidebar-btn" data-sidebar-target="#devolucaoItensSidebar">&times;</button> --}}
        </div>
        <div class="sidebar-body">
            {{-- <div class="alert alert-warning mt-2 mb-0 p-2" role="alert" style="font-size: 0.875rem;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                Itens com <strong>quantidade a devolver igual a 0</strong> serão <strong>desconsiderados</strong> na
                devolução.
            </div>
                <span
                            class="badge badge-primary mt-2" id="aviso-especie-devolucao"></span> --}}

            <br>
            {{-- Estrutura para os itens do recebimento de parcelas --}}
            <div id="fechamentoDetalheContainer" class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th>Tipo</th>
                            <th>Espécie</th>
                            <th>Data</th>
                            <th>Valor movimentado</th>
                        </tr>
                    </thead>
                    <tbody id="fechamentoDetalheTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
