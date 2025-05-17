<div class="cards-container position-relative">
    <!-- Formulário para adicionar itens -->
    @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
        <div class="card card-body position-relative">
            <form id="adicionar-item-pedido" method="POST">
                @csrf
                <div class="row d-flex justify-content-between align-items-center">
                    <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                        style="width: auto;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <span style="color: black!important; font-size: 0.875rem;">
                            Ao repetir um item, ele será atualizado na lista.
                        </span>
                    </div>
                    <div class="col-md-10">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="estoque_id">Selecione o produto: *</label>
                                    <select required id="estoque_id" name="estoque_id"
                                        class="form-control select2"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantidade">Quantidade: *</label>
                                    <input required type="text" id="quantidade" name="quantidade"
                                        class="form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark" style="float: right">
                            <i class="bi bi-check"></i> Adicionar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <!-- Lista de itens e finalização -->
    <div class="card card-body position-relative mt-5">
        <div class="col-auto">
            @if ($pedido)
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Solicitado por:</strong> {{ $pedido->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $pedido->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data início:</strong> {{ formatarData($pedido->created_at) ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data limite:</strong> {{ formatarData($pedido->data_limite) ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong> <span class="{{ $pedido && $pedido->status ? $pedido->status->badge() : '' }}">
                                STATUS: {{ $pedido && $pedido->status ? $pedido->status->descricao() : '' }}
                            </span></strong>
                    </li>
                </ul>
            @endif
        </div>

        <div class="row mt-4">
            <div class="col-md-10">
                <h5 style="color: black !important;">Produtos selecionados</h5>
            </div>
        </div>
        <table id="tabela-item-pedido" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Status</th>
                    @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                        <th>Ação</th>
                    @endif
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <form id="finalizar_pedido_post" action="{{ route('cadastro.pedido.post') }}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="pedido_id" value="{{ $pedido->id ?? '' }}">
                @php
                    $dataLimite =
                        $pedido && $pedido->data_limite
                            ? \Carbon\Carbon::parse($pedido->data_limite)->format('Y-m-d')
                            : '';
                    $minDate = \Carbon\Carbon::now()->format('Y-m-d');
                    $disabled = $pedido && $pedido->status_id != config('config.status.aberto') ? 'readonly' : '';
                @endphp

                <div class="col-md-12 mt-3">
                    <label for="data_limite" class="form-label">Data limite *</label>
                    <input required type="date" name="data_limite" id="data_limite" class="form-control"
                        value="{{ $dataLimite }}" min="{{ $minDate }}" {{ $disabled }}>
                </div>



                <div class="col-md-12 mt-3">
                    <label for="observacao">Observações *</label>

                    <textarea {{ $pedido && $pedido->status_id != config('config.status.aberto') ? 'readonly' : '' }} required
                        class="form-control" name="observacao" id="observacao" rows="3"
                        placeholder="Informações adicionais sobre o pedido">{{ $pedido->observacao ?? '' }}</textarea>
                </div>
                @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                    <div class="col-md-12 mt-3 mx-4">
                        <input class="form-check-input" type="checkbox" id="confirmacaoPedido" required
                            style="transform: scale(1.5);">
                        <label class="form-check-label" for="confirmacaoPedido">
                            Declaro que estou ciente dos itens e quantidades neste pedido.
                        </label>
                    </div>
                @endif

            </div>

            <input type="hidden" name="itens" id="itens">
            <input type="hidden" name="finalizar" id="finalizar" value="false">

            <div class="card-footer mt-4">
                <a href="{{ route('cadastro.pedido.index') }}" class="btn btn-danger">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
                @if ($pedido && $pedido->status_id == config('config.status.aguardando_cotacao'))
                <button id="btn_alterar_pedido" type="button" class="btn btn-info">
                    <i class="bi bi-pencil"></i> Alterar pedido
                </button>
                @endif
                @if (!$pedido || $pedido->status_id == config('config.status.aberto'))
                    <button id="btn_salvar_pedido" type="button" class="btn btn-info">
                        <i class="bi bi-floppy"></i> Salvar pedido
                    </button>
                    <button id="btn_finalizar_pedido" type="button" class="btn btn-dark">
                        <i class="bi bi-check"></i> Finalizar pedido
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    var urlGetProdutos = @json(route('estoques.select2')); // Reutilizando a rota de produtos do estoque
    var itensPedido = [];
    @if (session()->has('itens_na_movimentacao'))
        let itens = @json(session()->get('itens_no_pedido'));
        formataPedidoItens(itens, true);
    @elseif ($pedido && $pedido->pedido_itens->count() > 0)
        formataPedidoItens(@json($pedido->pedido_itens));
    @endif
    function formataPedidoItens(itens, session = false) {
        let itensFormatados = [];
        if (session == true) {

            itensFormatados = itens;
        } else {
            itens.forEach(item => {
                let nomeProduto = montaNomeProduto(item.estoque.produto);
                let estoqueId = item.estoque_id;
                let quantidade = item.quantidade_pedida;
                let status = item.status.descricao
                itensFormatados.push({
                    estoqueId,
                    nomeProduto,
                    quantidade,
                    status
                });
            });
        }

        itensPedido = itensFormatados;

        atualizarTabelaPedido();
    }
    $(document).ready(function() {
        montaDatatable('tabela-item-pedido');
        select2('estoque_id', urlGetProdutos);
        maskQtd('quantidade');


        // Adicionar item ao pedido
        $('#adicionar-item-pedido').submit(function(e) {
            e.preventDefault();

            const estoqueId = $('#estoque_id').val();
            const nomeProduto = $('#estoque_id option:selected').text();
            const quantidade = converteParaFloat($('#quantidade').val());
            const status = 'ABERTO';
            if (!estoqueId || !quantidade) {
                msgToastr('Verifique os campos obrigatórios.', 'info');
                return;
            }

            const indexExistente = itensPedido.findIndex(item => item.estoqueId == estoqueId);
            if (indexExistente !== -1) {
                msgToastr('Item atualizado.', 'success');
                itensPedido[indexExistente] = {
                    estoqueId,
                    nomeProduto,
                    quantidade,
                    status
                };
            } else {
                msgToastr('Item adicionado.', 'success');
                itensPedido.push({
                    estoqueId,
                    nomeProduto,
                    quantidade,
                    status
                });
            }

            atualizarTabelaPedido();
            $('#adicionar-item-pedido')[0].reset();
            $('#estoque_id').empty();
            $('#estoque_id').focus();
        });

        // Botões de salvar e finalizar
        $('#btn_salvar_pedido, #btn_finalizar_pedido, #btn_alterar_pedido').on('click', function() {
            var observacao = $('#observacao')[0];
            var confirmacaoPedido = $('#confirmacaoPedido')[0];
            var isFinalizar = $(this).attr('id') === 'btn_finalizar_pedido';
            var isAlterar = $(this).attr('id') === 'btn_alterar_pedido';
            console.log(isAlterar);

            var data = $('#data_limite').val();
            if (data == '') {
                msgToastr('Selecione a data limite..', 'info');
                $('#data_limite').focus();
                return
            }

            if (!itensPedido.length) {
                msgToastr('Nenhum item adicionado.', 'info');
                return;
            }
            if (!observacao.checkValidity()) {
                observacao.reportValidity();
                msgToastr('Campo observação obrigatório', 'info');
            } else if (!isAlterar && !confirmacaoPedido.checked) {
                confirmacaoPedido.reportValidity();
                msgToastr('Você precisa confirmar o pedido', 'info');
            } else {
                $('#itens').val(JSON.stringify(itensPedido));
                $('#finalizar').val(isFinalizar ? 'true' : 'false');
                $('#finalizar_pedido_post').submit();
            }
        });
    });

    function atualizarTabelaPedido() {
        const tbody = $('#tabela-item-pedido tbody');
        tbody.empty();

        itensPedido.forEach((item, index) => {

            let botao = @json(!$pedido || $pedido->status_id == config('config.status.aberto'));

            if (botao) {
                botao =
                    `<td><button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${item.estoqueId}')"><i class="bi bi-trash"></i></button></td>`
            }
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nomeProduto}</td>
                    <td>${item.quantidade}</td>
                    <td>${item.status}</td>
                ${botao}
                </tr>
            `;
            tbody.append(row);
        });
    }

    function confirmDelete(estoqueId) {
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
            confirmButtonText: 'Sim, excluir!',
        }).then((result) => {
            if (result.isConfirmed) {
                itensPedido = itensPedido.filter(item => item.estoqueId != estoqueId);
                atualizarTabelaPedido();
            }
        });
    }

    function cancelarPedido() {

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
                // Adiciona o motivo ao input do formulário
                $('#formMotivoCancelamento').val(result
                    .value); // Certifique-se que o input no formulário tem id="formMotivoCancelamento"
                $('#cancelarPedido').submit();
            }
        });

    }
</script>
