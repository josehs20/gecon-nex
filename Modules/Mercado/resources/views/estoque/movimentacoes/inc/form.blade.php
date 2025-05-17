@if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
    <div class="card card-body">
        <form id="form-movimentar-movimentacao" method="POST">
            @csrf
            <div class="row d-flex justify-content-between align-items-center">
                <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                    style="width: auto;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    <span style="color: black!important; font-size: 0.875rem;">
                        Ao repetir a operação para o mesmo item, ele será atualizado.
                    </span>
                </div>
                <div class="col-md-10">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="estoque_id">Selecione o produto: *</label>
                                <select required id="estoque_id" name="estoque_id" class="form-control select2">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tipo_movimentacao">Tipo de movimentação: *</label>
                                <select required id="tipo_movimentacao" name="tipo_movimentacao"
                                    class="form-control select2">
                                    <option value="{{ config('config.tipo_movimentacao_estoque.entrada') }}">ENTRADA
                                    </option>
                                    <option value="{{ config('config.tipo_movimentacao_estoque.saida') }}">SAÍDA
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantidade_disponivel">Quantidade disponível: </label>
                                <input required type="text" id="quantidade_disponivel" name="quantidade_disponivel"
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantidade">Quantidade a movimentar: *</label>
                                <input required type="text" id="quantidade" name="quantidade" value=""
                                    class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark" style="float: right">
                            <i class="bi bi-check"></i> Adicionar
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="movimentacao_id" value="{{ $movimentacao->id ?? '' }}">
        </form>
    </div>
@endif

<div class="card card-body">
    <div class="row">
        <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert" style="width: auto;">
            <i class="bi bi-info-circle-fill me-1"></i>
            <span style="color: black!important; font-size: 0.875rem;">
                O sistema sempre manterá sincronizado as quantidades com o estoque atual.
            </span>
        </div>
        <div class="col-md-10">
            <h5 style="color: black !important;">Produtos movimentados</h5>
        </div>
        <div class="col-md-2">
            <div class="px-3 text-center">
                <span class="{{ $movimentacao && $movimentacao->status ? $movimentacao->status->badge() : '' }}">
                    STATUS: {{ $movimentacao && $movimentacao->status ? $movimentacao->status->descricao() : '' }}
                </span>
            </div>
        </div>
    </div>
    <div class="row justify-content-start">
        <div class="col-auto">
            @if ($movimentacao)
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Movimentado por:</strong> {{ $movimentacao->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $movimentacao->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data início:</strong> {{ formatarData($movimentacao->created_at) ?? '-' }}
                    </li>
                    @if ($movimentacao && $movimentacao->status_id != config('config.status.aberto'))
                        <li class="list-inline-item">
                            • <strong>Data fim:</strong> {{ formatarData($movimentacao->updated_at) ?? '-' }}
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    <br>
    <table id="tabela-movimentacao-item" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd disponível</th>
                <th>Qtd movimentada</th>
                <th>Tipo</th>
                @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd disponível</th>
                <th>Qtd movimentada</th>
                <th>Tipo</th>
                @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </tfoot>
    </table>
    <div class="row">
        <div class="m-2 col-md-12">
            <label for="observacao" class="form-label">Observação:*</label>
            <textarea {{$movimentacao && $movimentacao->status_id != config('config.status.aberto') ? 'disabled' : ''}} required class="form-control" name="observacao" id="observacao" cols="30" rows="2">{{ $movimentacao && $movimentacao->observacao ? $movimentacao->observacao : '' }}</textarea>
        </div>
        <div class="mx-4 col-md-10">
            @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
                <input class="form-check-input" type="checkbox" id="confirmacaoMovimentacao" required
                    style="transform: scale(1.5);">
                <label class="form-check-label" for="confirmacaoMovimentacao">
                    Declaro, para os devidos fins, que estou ciente dos itens e suas quantidades na movimentação.
                </label>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('estoque.movimentacao.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (!$movimentacao || $movimentacao->status_id == config('config.status.aberto'))
            <button id="btn_salvar_movimentacao" type="button" class="btn btn-info">
                <i class="bi bi-floppy"></i> Salvar movimentação
            </button>
            <button id="btn_finalizar_movimentacao" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar movimentação
            </button>
        @endif
    </div>
</div>

<form action="{{ route('estoque.movimentacao.finalizar') }}" id="finalizar_movimentacao_post" method="POST">
    @method('POST')
    @csrf
    <input type="hidden" name="movimentacao_id" value="{{ $movimentacao ? $movimentacao->id : '' }}">
    <input type="hidden" name="itens" id="itens">
    <input type="hidden" name="observacao" id="observacaoFinalizar" value="">
    <input type="hidden" name="finalizar" value="{{ false }}">
</form>

{{-- @dd(session()->get('itens_na_movimentacao')) --}}
<br>
<script>
    var urlGetProdutos = @json(route('estoque.movimentacao.getProdutos'));
    var urlGetEstoque = @json(route('estoque.movimentacao.getEstoque'));
    var podeAlterarAlgo = @json(!$movimentacao || $movimentacao->status_id == config('config.status.aberto'));

    // Inicializa o array global sempre
    var itensMovimentacao = [];
    @if (session()->has('itens_na_movimentacao'))
        let itens = @json(session()->get('itens_na_movimentacao'));
        formataMovimentacaoItens(itens, true);
    @elseif ($movimentacao && $movimentacao->movimentacao_estoque_itens->count() > 0)
        formataMovimentacaoItens(@json($movimentacao->movimentacao_estoque_itens));
    @endif

    $(document).ready(function() {
        montaDatatable('tabela-movimentacao-item');
        select2('estoque_id', urlGetProdutos);
        maskQtd('quantidade_disponivel');
        maskQtd('quantidade');

        $('#btn_finalizar_movimentacao, #btn_salvar_movimentacao').on('click', function() {
            var observacao = $('#observacao')[0];
            var confirmacaoMovimentacao = $('#confirmacaoMovimentacao')[0];
            var isFinalizar = $(this).attr('id') === 'btn_finalizar_movimentacao';

            if (!itensMovimentacao.length) {
                msgToastr('Nenhum item adicionado.', 'info');
                return;
            }
            if (!observacao.checkValidity()) {
                observacao.reportValidity();
                msgToastr('Campo observação obrigatório', 'info');
            } else if (!confirmacaoMovimentacao.checked) {
                confirmacaoMovimentacao.reportValidity();
                msgToastr('Você precisa confirmar a movimentação', 'info');
            } else {
                $('#observacaoFinalizar').val(observacao.value);
                $('#itens').val(JSON.stringify(itensMovimentacao));
                $('input[name="finalizar"]').val(isFinalizar ? 'true' : 'false');
                $('#finalizar_movimentacao_post').submit();
            }
        });

        $('#estoque_id').change(function() {
            var selectedValue = $(this).val();

            $.ajax({
                url: urlGetEstoque,
                type: 'GET',
                data: {
                    id: selectedValue
                },
                success: function(response) {
                    var valor = response.estoque.quantidade_disponivel;
                    valor = valor.replace(/[^0-9,\.]/g, '');
                    $('#quantidade_disponivel').val(valor).trigger('input');
                    $('#quantidade').val('').trigger('input');
                    $('#quantidade').focus();

                },
                error: function(error) {
                    msgToastr(error, 'error');
                }
            });
        });

        $('#form-movimentar-movimentacao').submit(function(e) {
            e.preventDefault();

            const estoqueId = $('#estoque_id').val();
            const nomeProduto = $('#estoque_id option:selected').text();
            const quantidadeDisponivel = converteParaFloat($('#quantidade_disponivel').val());
            const quantidadeMovimentar = converteParaFloat($('#quantidade').val());
            const tipoMovimentacao = $('#tipo_movimentacao').val();
            const tipoDescricao = $('#tipo_movimentacao option:selected').text();

            if (!estoqueId || !quantidadeMovimentar || !tipoMovimentacao) {
                msgToastr('Verifique os campos obrigatórios.', 'info');
                return;
            }

            if (quantidadeMovimentar > quantidadeDisponivel) {
                msgToastr('Quantidade saída maior que disponível.', 'info');
                return;
            }
            const indexExistente = itensMovimentacao.findIndex(item => item.estoqueId == estoqueId);
            if (indexExistente !== -1) {
                msgToastr('Item atualizado.', 'success');
                itensMovimentacao[indexExistente] = {
                    estoqueId,
                    nomeProduto,
                    quantidadeDisponivel,
                    quantidadeMovimentar,
                    tipoMovimentacao,
                    tipoDescricao
                };
            } else {
                msgToastr('Item adicionado.', 'success');
                itensMovimentacao.push({
                    estoqueId,
                    nomeProduto,
                    quantidadeDisponivel,
                    quantidadeMovimentar,
                    tipoMovimentacao,
                    tipoDescricao
                });
            }

            atualizarTabelaMovimentacao();
            $('#estoque_id').empty();
            $('#form-movimentar-movimentacao')[0].reset();
            $('#estoque_id').focus();

        });
    });

    function formataMovimentacaoItens(itens, session = false) {
        let itensFormatados = [];
        if (session == true) {

            itensFormatados = itens;
        } else {

            itens.forEach(item => {
                let tipoMovimentacaoDescricao = @json(config('config.tipo_movimentacao_estoque.entrada'));
                let nome = montaNomeProduto(item.estoque.produto);
                itensFormatados.push({
                    estoqueId: item.estoque_id,
                    nomeProduto: nome,
                    quantidadeDisponivel: item.estoque.quantidade_disponivel,
                    quantidadeMovimentar: item.quantidade_movimentada,
                    tipoMovimentacao: item.tipo_movimentacao_estoque_id,
                    tipoDescricao: item.tipo_movimentacao_id == tipoMovimentacaoDescricao ? 'ENTRADA' :
                        'SAÍDA'
                });
            });
        }

        itensMovimentacao = itensFormatados;

        atualizarTabelaMovimentacao();
    }

    function atualizarTabelaMovimentacao() {
        const tbody = $('#tabela-movimentacao-item tbody');
        tbody.empty();

        itensMovimentacao.forEach((item, index) => {
            let colunaAcao = podeAlterarAlgo ?
                `<td><button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${item.estoqueId}')"><i class="bi bi-trash"></i></button></td>` :
                '';
            const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${item.nomeProduto}</td>
                <td>${item.quantidadeDisponivel}</td>
                <td>${item.quantidadeMovimentar}</td>
                <td>${item.tipoDescricao}</td>
                ${colunaAcao}
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
                removerItemMovimentacao(estoqueId);
            }
        });
    }

    function cancelarMovimentacao() {
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
        }).then((result) => {
            if (result.isConfirmed) {
                $('#cancelarMovimentacao').submit();
            }
        });
    }

    function removerItemMovimentacao(estoqueId) {
        itensMovimentacao = itensMovimentacao.filter(item => item.estoqueId != estoqueId);
        atualizarTabelaMovimentacao();
    }
</script>
