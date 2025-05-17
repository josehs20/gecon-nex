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
                                            onclick="mostrarCotacaoFornecedor({{ $cf->fornecedor_id }})"
                                            class="card-title mb-1 font-weight-bold text-primary d-flex align-items-center">
                                            {{ $cf->fornecedor->nome ?? 'Fornecedor' }}
                                            <button onclick="mostrarCotacaoFornecedor({{ $cf->fornecedor_id }})"
                                                type="button" class="btn btn-sm btn-success ms-2 mx-1"
                                                data-bs-toggle="tooltip" title="Nenhuma informação relevante">
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
<script>
    var routeRealizarCompra = @json(route('cadastro.compra.post'));
    var cotacao = @json($cotacao);
    var podeAlterar = @json($podeAlterar);
    var compra = @json(isset($compra) ? $compra : false);

    maskDinheiroByClass('preco-unitario');
    maskDinheiroByClass('total-item');
    maskDinheiroByClass('frete-fornecedor');
    maskDinheiroByClass('desconto-fornecedor');
    carregaMelhoresCondicoes();

    function carregaMelhoresCondicoes() {
        const fornecedores = cotacao.cot_fornecedores;
        let fornecedorClick = null;
        if (!fornecedores || fornecedores.length === 0) return;

        let fornecedorMaisBarato = fornecedores.reduce((menor, atual) => {
            const totalAtual = atual.total;
            const totalMenor = menor.total;
            return totalAtual < totalMenor ? atual : menor;
        });


        let id = fornecedorMaisBarato.fornecedor_id;

        if (compra) {
            fornecedorClick = compra.cot_fornecedor.fornecedor_id;
            $(`#titulo-fornecedor-${fornecedorClick}`).append(
                `<div class="text-success" style="text-decoration: underline;">
        <i class="bi bi-currency-dollar"></i> Comprado
    </div>`
            );

        } else {
            msgToastr('Melhor compra selecionada.', 'info');

            fornecedorClick = id;
        }

        const btn = $(`[data-fornecedor-id="${id}"] button[data-bs-toggle="tooltip"]`);
        $(`#titulo-fornecedor-${id}`).append(
            '<div class="text-warning" style="text-decoration: underline;"> ⭐ Melhor preço</div>'
        );

        if (btn.length === 0) return;

        const total = centavosParaReais(fornecedorMaisBarato.total);
        const entrega = fornecedorMaisBarato.previsao_entrega ?
            new Date(fornecedorMaisBarato.previsao_entrega).toLocaleDateString('pt-BR') :
            'Sem data';

        const texto = `⭐ Melhor preço <br> Entrega: ${entrega}<br> Total: ${total}`;

        btn.tooltip('dispose');
        btn.attr('title', texto).tooltip({
            trigger: 'hover',
            placement: 'top',
            html: true,
        }).tooltip('show');

        setTimeout(() => {
            $(`#titulo-fornecedor-${fornecedorClick}`).trigger('click')
        }, 300);

        $(`#seleciona-fornecedor-${fornecedorClick}`).prop('checked', true);

    }

    function cancelarCompra() {
        Swal.fire({
            title: 'Você tem certeza?',
            text: "Esta ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                cancelButton: 'btn btn-secondary mx-1',
                confirmButton: 'btn btn-dark',
            },
            buttonsStyling: false,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sim, cancelar!',
            html: '<input id="motivoCancelamento" class="swal2-input" placeholder="Digite o motivo do cancelamento" required>',
            preConfirm: () => {
                const motivo = document.getElementById('motivoCancelamento').value;
                if (!motivo) {
                    Swal.showValidationMessage('O motivo do cancelamento é obrigatório');
                    return false;
                }
                return motivo;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formMotivoCancelamento').val(result.value);
                $('#cancelarCompra').submit();
            }
        });
    }

    // Função para calcular o total de um item
    function calcularTotalItem($input) {
        const precoUnitario = parseFloat($input.val().replace(',', '.')) || 0;
        const quantidade = parseFloat($input.data('quantidade')) || 0;
        const total = precoUnitario * quantidade;
        const $totalItem = $input.closest('tr').find('.total-item');
        $totalItem.val(total.toFixed(2).replace('.', ','));

        // Atualiza o total e os pendentes do fornecedor
        const fornecedorId = $input.data('fornecedor-id');
        atualizarTotalFornecedor(fornecedorId);
        atualizarPendentes(fornecedorId);
    }

    // Função para atualizar o Subtotal e Total do fornecedor
    function atualizarTotalFornecedor(fornecedorId) {
        let totalFornecedor = 0;
        $(`#tabela-${fornecedorId} .total-item`).each(function() {
            const valor = parseFloat($(this).val().replace(',', '.')) || 0;
            totalFornecedor += valor;
        });

        let desconto = parseFloat($(`#desconto-${fornecedorId}`).val().replace(',', '.')) || 0;
        let frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;

        let subTotal = totalFornecedor;
        totalFornecedor = (subTotal - desconto) + frete;

        const $subTotalFornecedor = $(`.sub-total-fornecedor[data-fornecedor-id="${fornecedorId}"]`);
        const $totalFornecedor = $(`.total-fornecedor[data-fornecedor-id="${fornecedorId}"]`);
        $subTotalFornecedor.val(subTotal.toFixed(2).replace('.', ','));
        $totalFornecedor.val(totalFornecedor.toFixed(2).replace('.', ','));
    }

    // Função para atualizar a quantidade de itens pendentes
    function atualizarPendentes(fornecedorId) {
        let pendentes = 0;
        $(`#tabela-${fornecedorId} .preco-unitario`).each(function() {
            const valor = $(this).val().trim();
            if (!valor || parseFloat(valor.replace(',', '.')) === 0) {
                pendentes++;
            }
        });
        $(`#pendentes-${fornecedorId}`).text(pendentes);
    }

    // Função para validar a cotação (usada apenas ao finalizar)
    function validarCotacao() {
        for (const fornecedor of cotacao.cot_fornecedores) {
            const fornecedorId = fornecedor.fornecedor_id;
            const nomeFornecedor = fornecedor.fornecedor.nome || 'Fornecedor';

            // Verifica itens pendentes (preço unitário)
            let pendentes = 0;
            $(`#tabela-${fornecedorId} .preco-unitario`).each(function() {
                const valor = $(this).val().trim();
                if (!valor || parseFloat(valor.replace(',', '.')) === 0) {
                    pendentes++;
                }
            });
            if (pendentes > 0) {
                return {
                    erro: `Fornecedor ${nomeFornecedor}: ${pendentes} item(s) com preço unitário não preenchido(s).`,
                    fornecedorId
                };
            }

            // Verifica previsão de entrega
            const $previsaoEntrega = $(`#previsao-entrega-${fornecedorId}`);
            if (!$previsaoEntrega.val()) {
                return {
                    erro: `Fornecedor ${nomeFornecedor}: Previsão de entrega não preenchida.`,
                    fornecedorId
                };
            }

            // Verifica se o total é negativo
            let subTotal = 0;
            $(`#tabela-${fornecedorId} .total-item`).each(function() {
                const valor = parseFloat($(this).val().replace(',', '.')) || 0;
                subTotal += valor;
            });
            const desconto = parseFloat($(`#desconto-${fornecedorId}`).val().replace(',', '.')) || 0;
            const frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;
            const totalFornecedor = subTotal - desconto + frete;

            if (totalFornecedor < 0) {
                return {
                    erro: `Fornecedor ${nomeFornecedor}: O total não pode ser negativo.`,
                    fornecedorId
                };
            }
        }
        return {
            erro: null,
            fornecedorId: null
        };
    }

    // Função para montar o objeto da cotação
    function montarObjetoCotacao() {
        const cotacaoAtualizada = {
            ...cotacao
        };

        cotacaoAtualizada.cot_fornecedores.forEach(fornecedor => {
            const fornecedorId = fornecedor.fornecedor_id;

            const freteVal = $(`#frete-${fornecedorId}`).val();
            const frete = freteVal ? parseFloat(freteVal.replace(',', '.')) || null : null;

            const descontoVal = $(`#desconto-${fornecedorId}`).val();
            const desconto = descontoVal ? parseFloat(descontoVal.replace(',', '.')) || null : null;

            const previsaoEntrega = $(`#previsao-entrega-${fornecedorId}`).val() || null;
            const observacao = $(`#observacao-${fornecedorId}`).val()?.trim() || null;

            let totalItens = 0;
            $(`#tabela-${fornecedorId} .total-item`).each(function() {
                const valorVal = $(this).val();
                const valor = valorVal ? parseFloat(valorVal.replace(',', '.')) || 0 : 0;
                totalItens += valor;
            });

            fornecedor.frete = frete;
            fornecedor.desconto = desconto;
            fornecedor.previsao_entrega = previsaoEntrega;
            fornecedor.observacao = observacao;
            fornecedor.subTotal = totalItens > 0 ? totalItens : null;
            fornecedor.total = totalItens > 0 ? (totalItens + frete) - desconto : null;

            fornecedor.cot_for_itens.forEach(item => {
                const $precoUnitario = $(
                    `#tabela-${fornecedorId} .preco-unitario[data-item-id="${item.pedido_item_id}"]`
                );
                const precoUnitarioVal = $precoUnitario.val();
                const precoUnitario = precoUnitarioVal ? parseFloat(precoUnitarioVal.replace(',',
                    '.')) || null : null;
                item.preco_unitario = precoUnitario;
            });
        });

        return cotacaoAtualizada;
    }

    // Monitora mudanças no preço unitário
    $(document).on('input', '.preco-unitario', function() {
        calcularTotalItem($(this));
    });

    // Monitora mudanças no frete e desconto
    $(document).on('input', '.frete-fornecedor', function() {
        const fornecedorId = $(this).data('fornecedor-id');
        atualizarTotalFornecedor(fornecedorId);
    });

    $(document).on('input', '.desconto-fornecedor', function() {
        const $input = $(this);
        const fornecedorId = $input.data('fornecedor-id');
        const desconto = parseFloat($input.val().replace(',', '.')) || 0;
        let subTotal = 0;
        $(`#tabela-${fornecedorId} .total-item`).each(function() {
            const valor = parseFloat($(this).val().replace(',', '.')) || 0;
            subTotal += valor;
        });
        const frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;
        const totalFornecedor = subTotal - desconto + frete;

        if (totalFornecedor < 0) {
            msgToastr('O desconto não pode tornar o total negativo.', 'warning');
            $input.val('0,00'); // Redefine o desconto para 0
            atualizarTotalFornecedor(fornecedorId); // Atualiza com o desconto redefinido
        } else {
            atualizarTotalFornecedor(fornecedorId);
        }
    });

    $(document).on('change', '.fornecedor-selecionado-checkbox', function() {
        $('.fornecedor-selecionado-checkbox').not(this).prop('checked', false);
    });

    // Evento de clique no botão Finalizar Cotação
    $('#btn_finalizar_cotacao').on('click', function() {
        const checkboxSelecionado = $('.fornecedor-selecionado-checkbox:checked');

        if (checkboxSelecionado.length === 0) {
            msgToastr('Selecione um fornecedor primeiro', 'warning');
            return;
        }

        const fornecedorId = checkboxSelecionado.val();
        const nome = $(`#titulo-fornecedor-${fornecedorId}`).html();
        const subtotal = $(`#subtotal-${fornecedorId}`).val();
        const frete = $(`#frete-${fornecedorId}`).val();
        const desconto = $(`#desconto-${fornecedorId}`).val();
        const total = $(`#total-${fornecedorId}`).val();

        $('#modalFornecedorNome').html(nome);
        $('#modalSubtotal').text('R$ ' + subtotal);
        $('#modalFrete').text('R$ ' + frete);
        $('#modalDesconto').text('R$ ' + desconto);
        $('#modalTotal').text('R$ ' + total);
        $('#modalFinalizarCotacao').modal('show');
    });

    $('#btn_finalizar_compra').on('click', function() {
        const checkboxSelecionado = $('.fornecedor-selecionado-checkbox:checked');

        if (checkboxSelecionado.length === 0) {
            msgToastr('Selecione um fornecedor primeiro', 'warning');
            return;
        }

        const fornecedorId = checkboxSelecionado.val();
        const especie_pagamento_id = $('#forma_pagamento').val();
        const cot_fornecedor = cotacao.cot_fornecedores.find(cf => cf.fornecedor_id == fornecedorId);
        $('#cot_fornecedor_id').val(cot_fornecedor.id);
        $('#especie_pagamento_id').val(especie_pagamento_id);
        $('#formComprar').submit();
    });
    // Função para exibir o card do fornecedor selecionado
    function mostrarCotacaoFornecedor(fornecedorId) {
        bloquear();
        $('.cotacao-fornecedor').addClass('d-none');
        $('.fornecedor-card').removeClass('fornecedor-selecionado');
        $(`#cotacao-fornecedor-${fornecedorId}`).removeClass('d-none');
        $(`.fornecedor-card[data-fornecedor-id="${fornecedorId}"]`).addClass('fornecedor-selecionado');

        const $cardCotacao = $(`#cotacao-fornecedor-${fornecedorId}`);
        if ($cardCotacao.length) {
            const posicao = $cardCotacao.offset().top - 20;
            $('html, body').animate({
                scrollTop: posicao
            }, 500);
        }

        atualizarTotalFornecedor(fornecedorId);
        atualizarPendentes(fornecedorId);

        setTimeout(() => {
            desbloquear();
        }, 500);
    }

    // Inicializa as DataTables e pendentes
    cotacao.cot_fornecedores.forEach(function(element) {
        montaDatatable('tabela-' + element.fornecedor_id);
        atualizarPendentes(element.fornecedor_id);
        // Inicializa os totais dos itens
        $(`#tabela-${element.fornecedor_id} .preco-unitario`).each(function() {
            calcularTotalItem($(this));
        });
    });

    // Seleciona o primeiro fornecedor ao carregar a página
    if (cotacao.cot_fornecedores.length > 0) {
        var primeiroFornecedorId = cotacao.cot_fornecedores[0].fornecedor_id;
        mostrarCotacaoFornecedor(primeiroFornecedorId);
    }
</script>
