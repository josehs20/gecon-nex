@if (!$balanco || $balanco->status_id == config('config.status.aberto'))
    <div class="card card-body">
        <form id="form-movimentar-balanco" method="POST">
            @csrf
            <div class="row d-flex justify-content-between align-items-center">
                <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert"
                    style="width: auto;">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    <span style="color: black!important; font-size: 0.875rem;">
                        &nbsp; Ao repetir a operação para o mesmo item, ele será atualizado.</span>
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
                                <select id="tipo_movimentacao_select" class="form-control select2" disabled>
                                    <option value="{{ config('config.tipo_movimentacao_estoque.balanco') }}" selected>
                                        BALANÇO</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade">Quantidade no sistema: </label>
                                <input required type="text" id="quantidade_estoque_sistema"
                                    name="quantidade_estoque_sistema" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade">Quantidade no estoque real: </label>
                                <input required type="text" id="quantidade_estoque_real" value=""
                                    name="quantidade_estoque_real" class="form-control"
                                    oninput="calcularResultadoOperacional(event)">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantidade">Resultado operacional: </label>
                                <input required type="text" id="quantidade_operacional" name="quantidade_operacional"
                                    class="form-control" oninput="calcularResultadoOperacional(event)" readonly>
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
            {{-- <input type="hidden" name="balanco_id" value="{{ $balanco->id ?? '' }}"> --}}

        </form>
    </div>
@endif

<div class="card card-body">
    <div class="row">
        <div class="alert alert-info d-inline-flex align-items-center p-1 mb-3" role="alert" style="width: auto;">
            <i class="bi bi-info-circle-fill me-1"></i>
            <span style="color: black!important; font-size: 0.875rem;">
                &nbsp;O sistema sempre validará o resultado operacional com o estoque atual.
            </span>
        </div>

        <div class="col-md-10">
            <h5 style="color: black !important;">Produtos adicionados</h5>
        </div>
        <div class="col-md-2">
            <div class="px-3 text-center">
                <span class="{{ $balanco && $balanco->status ? $balanco->status->badge() : '' }}">
                    STATUS: {{ $balanco && $balanco->status ? $balanco->status->descricao() : '' }}
                </span>
            </div>
        </div>
    </div>
    <div class="row justify-content-start">
        <div class="col-auto">
            @if ($balanco)
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        • <strong>Movimentado por:</strong> {{ $balanco->usuario->master->name ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Loja:</strong> {{ $balanco->loja->nome ?? '-' }}
                    </li>
                    <li class="list-inline-item">
                        • <strong>Data início:</strong> {{ formatarData($balanco->created_at) ?? '-' }}
                    </li>
                    @if ($balanco && $balanco->status_id != config('config.status.aberto'))
                        <li class="list-inline-item">
                            • <strong>Data fim:</strong> {{ formatarData($balanco->updated_at) ?? '-' }}
                        </li>
                    @endif
                </ul>
            @endif

        </div>
    </div>
    <br>
    <table id="tabela-balanco-item" class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Produto</th>
                <th>Qtd no sistema</th>
                <th>Qtd real</th>
                <th>Resultado operacional</th>

                @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
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
                <th>Qtd no sistema</th>
                <th>Qtd real</th>
                <th>Resultado operacional</th>
                @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
                    <th>Ação</th>
                @endif
            </tr>
        </tfoot>
    </table>
    <div class="row">
        <div class="m-2 col-md-12">
            <label for="observacao" class="form-label">Observação:*</label>
            <textarea {{$balanco && $balanco->status_id != config('config.status.aberto') ? 'disabled' : ''}} required class="form-control" name="observacao" id="observacao" cols="30" rows="2">{{ $balanco && $balanco->observacao ? $balanco->observacao : '' }}</textarea>
        </div>
        <div class="mx-4 col-md-10">
            @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
                <input class="form-check-input" type="checkbox" id="confirmacaoBalanco" required
                    style="transform: scale(1.5);">
                <label class="form-check-label" for="confirmacaoBalanco">
                    Declaro, para os devidos fins, que estou ciente dos itens e suas quantidades no balanço.
                </label>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('estoque.balanco.index') }}" class="btn btn-danger">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        @if (!$balanco || $balanco->status_id == config('config.status.aberto'))
            <button id="btn_salvar_balanco" type="button" class="btn btn-info">
                <i class="bi bi-floppy"></i> Salvar balanço
            </button>
            <button id="btn_finalizar_balanco" type="button" class="btn btn-dark">
                <i class="bi bi-check"></i> Finalizar balanço
            </button>
            @if ($balanco)
                <form action="{{ route('estoque.balanco.delete', ['balanco_id' => $balanco->id]) }}"
                    id="cancelarBalanco" method="POST">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" name="cancelar_balanco_id"
                        value="{{ empty($balanco) ? $balanco->id : '' }}">
                </form>
            @endif

        @endif

    </div>
</div>

<form action="{{ route('estoque.balanco.finalizar') }}" id="finalizar_balanco_post" method="POST">
    @method('POST')
    @csrf
    <input type="hidden" name="balanco_id" value="{{ $balanco ? $balanco->id : '' }}">
    <input type="hidden" name="itens" id="itens">
    <input type="hidden" name="observacao" id="observacaoFinalizar" value="">
    <input type="hidden" name="finalizar" value="{{ false }}">
    <input type="hidden" id="tipo_movimentacao" name="tipo_movimentacao"
        value="{{ config('config.tipo_movimentacao_estoque.balanco') }}">
</form>

<br>
<script>
    var urlGetProdutos = @json(route('estoque.balanco.getProdutos'));
    var urlGetEstoque = @json(route('estoque.balanco.getEstoque'));
    var podeAlterarAlgo = @json(!$balanco || $balanco->status_id == config('config.status.aberto'));

    // Inicializa o array global sempre
    var itensBalanco = [];

    @if ($balanco && $balanco->balanco_itens->count() > 0)
        formataBalancoItens(@json($balanco->balanco_itens));
    @endif

    $(document).ready(function() {
        montaDatatable('tabela-balanco-item');
        select2('estoque_id', urlGetProdutos);
        maskQtd('quantidade_estoque_sistema');
        maskQtd('quantidade_disponivel');
        $('#btn_finalizar_balanco, #btn_salvar_balanco').on('click', function() {
            var observacao = $('#observacao')[0];
            var confirmacaoBalanco = $('#confirmacaoBalanco')[0];
            var isFinalizar = $(this).attr('id') === 'btn_finalizar_balanco';

            if (!itensBalanco.length) {
                msgToastr('Nenhum item adicionado.', 'info')
                return;
            }
            if (!observacao.checkValidity()) {
                observacao.reportValidity();
                msgToastr('Campo observação obrigatório', 'info');
            } else if (!confirmacaoBalanco.checked) {
                confirmacaoBalanco.reportValidity();
                msgToastr('Você precisa confirmar o balanço', 'info');
            } else {
                $('#observacaoFinalizar').val(observacao.value);
                $('#itens').val(JSON.stringify(itensBalanco));
                $('input[name="finalizar"]').val(isFinalizar ? 'true' : 'false');
                $('#finalizar_balanco_post').submit();
            }
        });

        $('#estoque_id').change(function() {
            var selectedValue = $(this).val();

            $.ajax({
                url: urlGetEstoque, // URL da sua rota para consulta
                type: 'GET',
                data: {
                    id: selectedValue
                },
                success: function(response) {
                    var valor = response.estoque.quantidade_disponivel;
                    valor = valor.replace(/[^0-9,\.]/g,
                        ''); // Remover qualquer caractere não numérico

                    $('#quantidade_estoque_sistema').val(valor).trigger('input');
                    $('#quantidade_estoque_real').val(0).trigger('input');
                    $('#quantidade_operacional').val(0).trigger('input');
                    // Forçar o evento de input para aplicar a máscara
                    // $('#quantidade_estoque_sistema').trigger('input');
                    // $('#quantidade_estoque_real').trigger('input');
                    // $('#quantidade_operacional').trigger('input');
                },
                error: function(error) {
                    msgToastr(error, 'error');

                }
            });
        });

        $('#form-movimentar-balanco').submit(function(e) {
            e.preventDefault();

            const estoqueId = $('#estoque_id').val();
            const nomeProduto = $('#estoque_id option:selected').text();
            const quantidadeSistema = converteParaFloat($('#quantidade_estoque_sistema').val());
            const quantidadeReal = converteParaFloat($('#quantidade_estoque_real').val());
            const resultado = converteParaFloat($('#quantidade_operacional').val());


            if (!estoqueId || !quantidadeReal) {
                msgToastr('Verifique a quantidade ou material escolhido.', 'info');
                return
            };

            // Verifica se já existe e atualiza
            const indexExistente = itensBalanco.findIndex(item => item.estoqueId == estoqueId);
            if (indexExistente !== -1) {
                msgToastr('Item atualizado.', 'success');
                itensBalanco[indexExistente] = {
                    estoqueId,
                    nomeProduto,
                    quantidadeSistema,
                    quantidadeReal,
                    resultado
                };
            } else {
                msgToastr('Item adicionado.', 'success');

                itensBalanco.push({
                    estoqueId,
                    nomeProduto,
                    quantidadeSistema,
                    quantidadeReal,
                    resultado
                });
            }

            atualizarTabelaBalanco();
            $('#estoque_id').empty();
            $('#form-movimentar-balanco')[0].reset();
        });

    });

    function formataBalancoItens(itens) {
        let itensFormatados = []

        itens.forEach(item => {
            let nome = montaNomeProduto(item.estoque.produto)
            let estoqueId = item.estoque_id;
            let nomeProduto = nome;
            let quantidadeSistema = item.quantidade_estoque_sistema;
            let quantidadeReal = item.quantidade_estoque_real;
            let resultado = item.quantidade_resultado_operacional;
            itensFormatados.push({
                estoqueId,
                nomeProduto,
                quantidadeSistema,
                quantidadeReal,
                resultado
            });
        });

        itensBalanco = itensFormatados;

        atualizarTabelaBalanco();
    }

    function atualizarTabelaBalanco() {
        const tbody = $('#tabela-balanco-item tbody');
        tbody.empty(); // Limpa o conteúdo atual da tabela

        itensBalanco.forEach((item, index) => {
            let resultado = item.quantidadeReal - item.quantidadeSistema;
            let colunaAcao = podeAlterarAlgo ? `<td><button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('${item.estoqueId}')">
                        <i class="bi bi-trash"></i>
                    </button></td>` : '';
            const row = `
        <tr>
            <td>${index + 1}</td>
            <td>${item.nomeProduto}</td>
            <td>${item.quantidadeSistema}</td>
            <td>${item.quantidadeReal}</td>
            <td>${resultado}</td>
        ${colunaAcao}
        </tr>
        `;
            tbody.append(row);
        });
    }


    function confirmDelete(estoque_id) {
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
                removerItemBalanco(estoque_id)
            }
        });
    }

    function cancelarBalanco() {
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
                $('#cancelarBalanco').submit();
            }
        });
    }

    function removerItemBalanco(estoqueId) {
        itensBalanco = itensBalanco.filter(item => item.estoqueId != estoqueId);

        atualizarTabelaBalanco();
    }

    /*
     *   Balanço = Estoque real - Estoque teórico
     */
    function calcularResultadoOperacional(e) {
        validarInput(e);

        // Obter os valores dos campos de entrada
        let quantidade_estoque_sistema = $('#quantidade_estoque_sistema').val();
        let quantidade_estoque_real = $('#quantidade_estoque_real').val();

        // Garantir que os valores sejam números válidos
        quantidade_estoque_sistema = converteParaFloat(quantidade_estoque_sistema);
        quantidade_estoque_real = converteParaFloat(quantidade_estoque_real);

        // Verificar se os valores são números válidos antes de calcular
        if (isNaN(quantidade_estoque_sistema) || isNaN(quantidade_estoque_real)) {
            console.error("Por favor, insira valores válidos para quantidade de estoque.");
            return; // Evitar o cálculo com valores inválidos
        }

        // Calcular o resultado operacional
        let resultado = quantidade_estoque_real - quantidade_estoque_sistema;

        // Atualizar o campo de quantidade operacional com o resultado formatado
        let quantidade_operacional = $('#quantidade_operacional');
        quantidade_operacional.val(resultado.toFixed(3).replace('.', ','));

        // Verificar se o campo está vazio (se necessário)
        verificarSeVazio();
    }


    function verificarSeVazio() {
        let quantidade_estoque_real = $('#quantidade_estoque_real').val();
        let quantidade_operacional = $('#quantidade_operacional');
        if (quantidade_estoque_real == '') {
            quantidade_operacional.val(0);
        }
    }
</script>
