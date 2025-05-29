@vite('Modules/Mercado/resources/assets/js/views/pedido/compra/inc/form.js', 'build/.vite')

<style>
    .fornecedor-card {
        position: relative;
        /* Necessário para posicionar a barra */
        overflow: hidden;
        /* Evita que a barra ultrapasse os limites do card */
    }

    .selection-bar {
        position: absolute;
        top: 0;
        right: 0;
        width: 5px;
        /* Largura da barra */
        height: 100%;
        /* Ocupa toda a altura do card */
        background-color: transparent;
        /* Invisível por padrão */
        transition: background-color 0.3s ease;
        /* Transição suave */
    }

    .fornecedor-selecionado .selection-bar {
        background-color: #007bff;
        /* Cor da barra (azul) quando selecionado */
    }

    .fornecedor-selecionado {
        background-color: #f8f9fa;
        /* Fundo claro para reforçar o destaque */
    }

    .tooltip.show {
        opacity: 1 !important;
    }

    .tooltip-inner {
        background-color: green !important;
        color: #000000 !important;
        border: 1px solid #ccc;
        padding: 5px 10px;
        font-size: 0.875rem;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .bs-tooltip-auto[x-placement^="top"] .tooltip-arrow::before,
    .bs-tooltip-top .tooltip-arrow::before {
        border-top-color: #ffffff !important;
    }

    .bs-tooltip-auto[x-placement^="bottom"] .tooltip-arrow::before,
    .bs-tooltip-bottom .tooltip-arrow::before {
        border-bottom-color: #ffffff !important;
    }

    .bs-tooltip-auto[x-placement^="left"] .tooltip-arrow::before,
    .bs-tooltip-left .tooltip-arrow::before {
        border-left-color: #ffffff !important;
    }

    .bs-tooltip-auto[x-placement^="right"] .tooltip-arrow::before,
    .bs-tooltip-right .tooltip-arrow::before {
        border-right-color: #ffffff !important;
    }

    /* Ajusta o z-index do tooltip para garantir que ele não fique sobre o modal */
    .tooltip {
        z-index: 1050 !important;
        /* Defina um valor menor que o z-index do modal (geralmente 1050) */
    }
</style>
<div class="">
    <div class="row justify-content-start">
        <div class="col-md-12">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    @if ($cotacao)
                        <ul class="list-inline m-0">
                            <li class="list-inline-item">• <strong>Criada por:</strong>
                                {{ $cotacao->usuario->master->name ?? '-' }}</li>
                            <li class="list-inline-item">• <strong>Loja:</strong> {{ $cotacao->loja->nome ?? '-' }}</li>
                            <li class="list-inline-item">• <strong>Data abertura:</strong>
                                {{ aplicarMascaraDataNascimento($cotacao->data_abertura ?? now()) }}</li>
                            <li class="list-inline-item">• <strong>Data encerramento:</strong>
                                {{ aplicarMascaraDataNascimento($cotacao->data_encerramento) ?? '-' }}</li>
                            @if (isset($compra))
                                <li class="list-inline-item">• <strong>Pagamento:</strong>
                                    {{ $compra->especie_pagamento->nome }}</li>
                                <li class="list-inline-item">
                                    • <strong>
                                        <span id="statusCompra"
                                            class="{{ $compra->status ? $compra->status->badge() : '' }}">
                                            STATUS:
                                            {{ $compra->status ? $compra->status->descricao() : '' }}
                                        </span>
                                    </strong>
                                </li>
                            @else
                                <li class="list-inline-item">
                                    • <strong>
                                        <span id="statusCotacao"
                                            class="{{ $cotacao && $cotacao->status ? $cotacao->status->badge() : '' }}">
                                            STATUS:
                                            {{ $cotacao && $cotacao->status ? $cotacao->status->descricao() : '' }}
                                        </span>
                                    </strong>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="mt-1">
        @if ($cotacao && $cotacao->cot_fornecedores->count() > 0)
            <div class="row">
                <!-- Metade esquerda: lista de fornecedores -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white" style="padding: 1rem 0.8rem !important;">
                            Fornecedores na cotação
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($cotacao->cot_fornecedores as $cf)
                                <div class="card mb-3 shadow-sm border-0 rounded-lg fornecedor-card"
                                    data-fornecedor-id="{{ $cf->fornecedor_id }}">
                                    <div class="selection-bar"></div>
                                    <div class="card-body pb-2">
                                        <h5 style="cursor: pointer;"
                                            class="card-title mb-1 font-weight-bold text-primary d-flex align-items-center mostrar-cotacao"
                                            data-fornecedor-id="{{ $cf->fornecedor_id }}">
                                            {{ $cf->fornecedor->nome ?? 'Fornecedor' }}

                                            <button type="button"
                                                class="btn btn-sm btn-success ms-2 mx-1 mostrar-cotacao"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" data-bs-toggle="tooltip"
                                                title="Nenhuma informação relevante">
                                                <i class="bi bi-info-circle"></i>
                                            </button>
                                        </h5>

                                        <p class="mb-3 text-muted small">
                                            {{ $cf->fornecedor->email ?? 'Sem e-mail cadastrado' }}
                                        </p>
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="subtotal-{{ $cf->fornecedor_id }}"
                                                        class="form-label custom-label"
                                                        style="font-size: 0.9rem">Subtotal:</label>
                                                    <input type="text" readonly
                                                        class="form-control form-control-sm sub-total-fornecedor"
                                                        id="subtotal-{{ $cf->fornecedor_id }}"
                                                        data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                        placeholder="0,00">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="frete-{{ $cf->fornecedor_id }}"
                                                        class="form-label custom-label"
                                                        style="font-size: 0.9rem">Frete:</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm frete-fornecedor"
                                                        id="frete-{{ $cf->fornecedor_id }}"
                                                        data-fornecedor-id="{{ $cf->fornecedor_id }}" step="0.01"
                                                        min="0" placeholder="0,00" value="{{ $cf->frete() }}"
                                                        disabled>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="desconto-{{ $cf->fornecedor_id }}"
                                                        class="form-label custom-label"
                                                        style="font-size: 0.9rem">Desconto:</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm desconto-fornecedor"
                                                        id="desconto-{{ $cf->fornecedor_id }}"
                                                        data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                        placeholder="0,00" value="{{ $cf->desconto() }}" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="previsao-entrega-{{ $cf->fornecedor_id }}"
                                                        class="form-label custom-label"
                                                        style="font-size: 0.9rem">Previsão de Entrega:</label>
                                                    <input type="date"
                                                        class="form-control form-control-sm previsao-entrega-fornecedor"
                                                        id="previsao-entrega-{{ $cf->fornecedor_id }}"
                                                        data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                        value="{{ $cf->previsao_entrega }}" min="{{ date('Y-m-d') }}"
                                                        disabled>
                                                </div>
                                                <div class="col-md-4">

                                                    <label for="total-{{ $cf->fornecedor_id }}"
                                                        class="form-label custom-label" style="font-size: 0.9rem">
                                                        Total:
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="text" readonly
                                                            class="form-control form-control-sm total-fornecedor"
                                                            id="total-{{ $cf->fornecedor_id }}"
                                                            data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                            placeholder="0,00">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-check mt-4">
                                                        <input
                                                            onclick="mostrarCotacaoFornecedor({{ $cf->fornecedor_id }})"
                                                            class="form-check-input fornecedor-selecionado-checkbox"
                                                            type="checkbox" name="fornecedor_selecionado"
                                                            value="{{ $cf->fornecedor_id }}"
                                                            id="seleciona-fornecedor-{{ $cf->fornecedor_id }}"
                                                            style="transform: scale(2.0); margin-right: 10px;"
                                                            {{ !$podeAlterar ? 'disabled' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Metade direita: conteúdo dinâmico do fornecedor -->
                <div class="col-md-8" id="conteudoFornecedorEmCotacao">
                    @if ($cotacao->cot_fornecedores->count() > 0)
                        @foreach ($cotacao->cot_fornecedores as $cf)
                            <div class="card shadow-sm cotacao-fornecedor d-none"
                                id="cotacao-fornecedor-{{ $cf->fornecedor_id }}">
                                <div id="titulo-fornecedor-{{ $cf->fornecedor_id }}"
                                    class="card-header bg-dark text-white" style="padding: 1rem 0.8rem !important;">
                                    Cotação do fornecedor: {{ $cf->fornecedor->nome ?? 'Fornecedor' }}
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered" id="tabela-{{ $cf->fornecedor->id }}"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nº Pedido(s)</th>
                                                <th>Produto</th>
                                                <th>Qtd solicitada</th>
                                                <th>Preço unitário</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cf->cot_for_itens as $item)
                                                <tr>
                                                    <td>{{ $item->pedidos_agrupados }}</td>
                                                    <td>{{ $item->produto->getNomeCompleto() }}</td>
                                                    <td>{{ number_format($item->quantidade, 3, ',', '.') }}</td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm preco-unitario"
                                                            data-item-id="{{ $item->pedido_item_id }}"
                                                            data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                            data-quantidade="{{ $item->quantidade }}" step="0.01"
                                                            min="0" placeholder="0,00"
                                                            value="{{ $item->preco_unitario() }}" disabled>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm total-item"
                                                            data-item-id="{{ $item->pedido_item_id }}" readonly
                                                            placeholder="0,00">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="observacao-{{ $cf->fornecedor_id }}">Observação</label>
                                            <textarea class="form-control" id="observacao-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" rows="3" disabled>{{ $cf->observacao }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="card-footer mt-4">
        <a href="{{ route('cadastro.compra.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (!isset($compra))
            <button id="btn_finalizar_cotacao" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar compra
            </button>
        @endif

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalFinalizarCotacao" tabindex="-1" role="dialog"
    aria-labelledby="modalFinalizarCotacaoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalFinalizarCotacaoLabel">Confirmar Compra</h5>
                <button onclick="fecharModal('modalFinalizarCotacao')" type="button" class="close text-white"
                    data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p style="color: #000000 !important;"><strong>Fornecedor:</strong> <span
                        id="modalFornecedorNome"></span></p>
                <p style="color: #000000 !important;"><strong>Subtotal:</strong> <span id="modalSubtotal"></span></p>
                <p style="color: #000000 !important;"><strong>Frete:</strong> <span id="modalFrete"></span></p>
                <p style="color: #000000 !important;"><strong>Desconto:</strong> <span id="modalDesconto"></span></p>
                <p style="color: #000000 !important;"><strong>Total:</strong> <span id="modalTotal"></span></p>

                <div class="form-group mt-3">
                    <label for="forma_pagamento">Forma de pagamento</label>
                    <select class="form-control select2" id="forma_pagamento" style="width: 100%;">
                        @if (isset($forma_pagamentos))
                            @foreach ($forma_pagamentos as $fp)
                                <option value="{{ $fp['id'] }}">{{ $fp['nome'] }}</option>
                            @endforeach
                        @endif


                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="fecharModal('modalFinalizarCotacao')"
                    data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_finalizar_compra" class="btn btn-dark">Confirmar Compra</button>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('cadastro.compra.post') }}" id="formComprar" method="post">
    @csrf
    <input type="hidden" id="cot_fornecedor_id" name="cot_fornecedor_id">
    <input type="hidden" id="especie_pagamento_id" name="especie_pagamento_id">
</form>
<div id="dataView" data-route-realizar-compra="{{ route('cadastro.compra.post') }}"
    data-cotacao="{{ $cotacao }}" data-pode-alterar="{{ $podeAlterar }}"
    data-compra="{{ isset($compra) ? $compra : false }}"></div>
