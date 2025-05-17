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
                            <li class="list-inline-item">
                                • <strong>
                                    <span id="statusCotacao"
                                        class="{{ $cotacao && $cotacao->status ? $cotacao->status->badge() : '' }}">
                                        STATUS: {{ $cotacao && $cotacao->status ? $cotacao->status->descricao() : '' }}
                                    </span>
                                </strong>
                            </li>
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
                                        <h5 class="card-title mb-1 font-weight-bold text-primary">
                                            {{ $cf->fornecedor->nome ?? 'Fornecedor' }}
                                        </h5>
                                        <p class="mb-0 text-muted small">
                                            {{ $cf->fornecedor->email ?? 'Sem e-mail cadastrado' }}
                                        </p>
                                    </div>
                                    <div class="card-footer d-flex align-items-center">
                                        <button onclick="mostrarCotacaoFornecedor({{ $cf->fornecedor_id }})"
                                            type="button" class="btn btn-info d-flex align-items-center btn-sm">
                                            Pendentes
                                            <span class="badge badge-warning ml-2"
                                                id="pendentes-{{ $cf->fornecedor_id }}">{{ $cf->cot_for_itens->count() }}</span>
                                        </button>
                                        <div class="ml-sm-1">
                                            <smal style="font-size: 0.9rem" for="subtotal-{{ $cf->fornecedor_id }}"
                                                class="form-label custom-label">Subtotal:</smal>
                                            <input type="text" readonly
                                                class="form-control form-control-sm sub-total-fornecedor"
                                                id="subtotal-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00">
                                        </div>
                                        <div class="ml-sm-1">
                                            <smal style="font-size: 0.9rem" for="total-{{ $cf->fornecedor_id }}"
                                                class="form-label custom-label">Total:</smal>
                                            <input type="text" readonly
                                                class="form-control form-control-sm total-fornecedor"
                                                id="total-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00">
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
                                <div class="card-header bg-dark text-white" style="padding: 1rem 0.8rem !important;">
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
                                            @php
                                                $permitirAlterarInputs = !(
                                                    $cotacao->status_id == config('config.status.cotado')
                                                );
                                            @endphp
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
                                                            value="{{ $item->preco_unitario() }}"
                                                            {{ $permitirAlterarInputs ? '' : 'disabled' }}>
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
                                        <div class="col-md-4">
                                            <label for="frete-{{ $cf->fornecedor_id }}">Frete</label>
                                            <input type="text" class="form-control frete-fornecedor"
                                                id="frete-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" step="0.01"
                                                min="0" placeholder="0,00" value="{{ $cf->frete() }}"
                                                {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="previsao-entrega-{{ $cf->fornecedor_id }}">Previsão de
                                                Entrega</label>
                                            <input type="date" class="form-control"
                                                id="previsao-entrega-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}"
                                                value="{{ $cf->previsao_entrega }}" min="{{ date('Y-m-d') }}"
                                                {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="desconto-{{ $cf->fornecedor_id }}">Desconto</label>
                                            <input type="text" class="form-control desconto-fornecedor"
                                                id="desconto-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" placeholder="0,00"
                                                value="{{ $cf->desconto() }}"
                                                {{ $permitirAlterarInputs ? '' : 'disabled' }}>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="observacao-{{ $cf->fornecedor_id }}">Observação</label>
                                            <textarea class="form-control" id="observacao-{{ $cf->fornecedor_id }}"
                                                data-fornecedor-id="{{ $cf->fornecedor_id }}" rows="3" {{ $permitirAlterarInputs ? '' : 'disabled' }}>{{ $cf->observacao }}</textarea>
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
        <a href="{{ route('cadastro.cotacao.selecionar_pedidos') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if ($cotacao && $cotacao->status_id == config('config.status.cotado'))
            <button id="btn_alterar_cotacao" type="button" class="btn btn-info">
                <i class="bi bi-pencil"></i> Alterar cotação
            </button>
        @elseif($cotacao && $cotacao->status_id != config('config.status.cancelado'))
            <button id="btn_salvar_cotacao" type="button" class="btn btn-info">
                <i class="bi bi-floppy"></i> Salvar cotação
            </button>
            <button id="btn_finalizar_cotacao" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar cotação
            </button>
        @endif

    </div>
</div>

<script>
    var routeSalvarCotacao = @json(route('cadastro.cotacao.update'));
    var cotacao = @json($cotacao);

    maskDinheiroByClass('preco-unitario');
    maskDinheiroByClass('total-item');
    maskDinheiroByClass('frete-fornecedor');
    maskDinheiroByClass('desconto-fornecedor');

    function cancelarCotacao() {
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
                $('#cancelarCotacao').submit();
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

    // Evento de clique no botão Salvar Cotação
    $('#btn_salvar_cotacao, #btn_alterar_cotacao').on('click', function() {
        const cotacaoAtualizada = montarObjetoCotacao();
        if (!cotacaoAtualizada) {
            msgToastr('Erro ao montar cotação', 'error');
            return;
        }
        const isBotaoAlterar = $(this).is('#btn_alterar_cotacao');
        cotacaoAtualizada.finalizar = false;
        bloquear();
        $.ajax({
            url: routeSalvarCotacao,
            method: 'POST',
            data: JSON.stringify(cotacaoAtualizada),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Add CSRF token to headers
            },
            success: function(response) {
                if (response.success == true) {
                    msgToastr(response.msg, 'success');

                    if (isBotaoAlterar) {
                        setTimeout(function() {
                            location.reload();
                        }, 500); // Aguarda 1 segundo antes de recarregar
                    } else {
                        $('#statusCotacao')
                            .removeClass() // remove todas as classes
                            .addClass(response.cotacao.status_badge) // adiciona a nova
                            .text(response.cotacao.status_descricao); // atualiza o texto
                    }

                } else {
                    msgToastr(response.msg, 'warning');

                }
            },
            error: function(xhr) {
                msgToastr('Erro ao salvar cotação: ' + (xhr.responseText || 'Tente novamente.'),
                    'error');
            },
            complete: function() {
                desbloquear();
            }
        });
    });

    // Evento de clique no botão Finalizar Cotação
    $('#btn_finalizar_cotacao').on('click', function() {
        const {
            erro,
            fornecedorId
        } = validarCotacao();

        if (erro) {
            msgToastr(erro, 'warning');
            if (fornecedorId) {
                mostrarCotacaoFornecedor(fornecedorId);
            }
        } else {
            const cotacaoAtualizada = montarObjetoCotacao();
            cotacaoAtualizada.finalizar = true;
            bloquear();
            $.ajax({
                url: routeSalvarCotacao, // Substitua pelo endpoint correto
                method: 'POST',
                data: JSON.stringify(cotacaoAtualizada),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Add CSRF token to headers
                },
                success: function(response) {
                    if (response.success == true) {
                        msgToastr(response.msg, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 500); // Aguarda 1 segundo antes de recarregar
                    } else {
                        msgToastr(response.msg, 'warning');
                    }
                },
                error: function(xhr) {
                    msgToastr('Erro ao finalizar cotação: ' + (xhr.responseText ||
                        'Tente novamente.'), 'error');
                },
                complete: function() {
                    desbloquear();
                }
            });
        }
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
