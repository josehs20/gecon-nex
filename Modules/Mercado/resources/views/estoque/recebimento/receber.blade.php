@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.recebimento.index'), 'titulo' => 'Recebimentos'], ['titulo' => 'Receber']]])

@section('content')
    <style>
        .nav-link.active {
            background-color: #007bff !important;
            /* Cor primária do Bootstrap */
            color: #fff !important;
            /* Texto branco */
        }
    </style>
    <div class="cabecalho">
        <div class="page-header">
            <h3>Receber Pedido</h3>
            <p class="lead">
                Nesta tela, você pode gerenciar o recebimento de pedidos realizados de forma rápida e eficiente.
            </p>

        </div>
    </div>

    <div class="card card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Informações do pedido para recebimento</h5>
                <br>
            </div>
        </div>

        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="px-3 text-center">
                    <strong>Nº Pedido:</strong> {{ $pedido->id }}
                </div>
                <div class="px-3 text-center">
                    <strong>Realizado por:</strong> {{ $pedido->usuario->master->name }}
                </div>
                <div class="px-3 text-center">
                    <strong>Fornecedor:</strong> {{ $pedido->fornecedor->nome }}
                </div>
                <div class="px-3 text-center">
                    <strong>Loja:</strong> {{ $pedido->loja->nome }}
                </div>
                <div class="px-3 text-center">
                    <strong>Data solicitado:</strong>
                    {{ formatarData($pedido->data_pedido) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Previsão de entrega:</strong>
                    {{ aplicarMascaraDataNascimento($pedido->previsao_entrega) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Valor total:</strong>
                    {{ converterParaReais($pedido->pedido_itens->sum('total')) }}
                </div>
                <div class="px-3 text-center">
                    <strong>Status:</strong><span
                        class="badge
                        @if ($pedido->status_id === config('config.status.aberto')) badge-info @endif
                        @if ($pedido->status_id === config('config.status.concluido')) badge-success @endif">
                        {{ $pedido->status->descricao }}
                    </span>
                </div>
            </div>
        </div>

        <br>
        <h5 class="mt-5">Listagem de item</h5>
        <br>

        <table id="tabela-item-receber" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 30%;">Item</th>
                    <th>Qtd Solicitada</th>
                    <th>Qtd Recebida</th>
                    <th>Qtd Receber</th>
                    <th>Preço</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @if ($pedido->pedido_itens && $pedido->pedido_itens->count())
                    @foreach ($pedido->pedido_itens as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->produto->getNomeCompleto() }}</td>
                            <td>{{ number_format($item->quantidade_pedida, 3, ',', '.') }}</td>
                            <td>{{ $item->recebimento_item ? number_format($item->recebimento_item->quantidade_recebida, 3, ',', '.') : 0 }}
                            </td>
                            <td>
                                @if ($item->recebimento_item && $item->recebimento_item->status_id == config('config.status.concluido'))
                                    <input type="text" disabled value="0" class="form-control" required>
                                @else
                                    <input type="text" data-pedido-id="{{ $item->id }}"
                                        class="form-control qtd_receber_pedidos"
                                        data-qtd-max-receber="{{ number_format($item->quantidade_pedida - ($item->recebimento_item->quantidade_recebida ?? 0), 3, ',', '.') }}"
                                        value="{{ number_format($item->quantidade_pedida - ($item->recebimento_item->quantidade_recebida ?? 0), 3, ',', '.') }}"
                                        required>
                                @endif
                                <!-- Input para a quantidade a ser recebida -->

                            </td>
                            <td>R$ {{ converterParaReais($item->preco_unitario) }}</td>
                            <td>R$ {{ converterParaReais($item->total) }}</td>
                            <td>
                                <button class="btn btn-dark btn-sm" data-nome="{{ $item->produto->nome }}"
                                    data-validade="{{ $item->recebimento_item->validade ?? '' }}"
                                    data-lote="{{ $item->recebimento_item->lote ?? '' }}"
                                    onclick="abrirModalLoteValidade({{ $item->id }}, this)">
                                    <i class="bi bi-pencil-square"></i> Lote/Validade
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <form id="receberPedido" action="{{ route('estoque.recebimento.receber') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{ $pedido->id }}" name="pedido">
            <input type="hidden" id="dadosStorage" name="dados_storage">

            <div class="row d-flex">

                <div class="col-md-4 mt-4">
                    <div class="form-check mb-3">
                        <input {{ $pedido->status_id != config('config.status.concluido') ? '' : 'disabled checked' }}
                            class="form-check-input" type="checkbox" id="confirmacaoCaixa" required
                            style="transform: scale(1.5);">
                        <label class="form-check-label" for="confirmacaoCaixa">
                            Declaro, para os devidos fins, que estou ciente de que os itens recebidos estão em conformidade
                            com o esperado.
                        </label>
                    </div>
                </div>

                <!-- Campo de Data -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dataRecebimento">Data de Recebimento <span class="text-danger">*</span></label>
                        @if ($pedido->status_id != config('config.status.concluido'))
                            <input type="date" id="dataRecebimento" name="dataRecebimento" class="form-control" required
                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        @else
                            <input
                                value="{{ \Carbon\Carbon::parse($pedido->recebimento->data_recebimento)->format('Y-m-d') }}"
                                disabled type="date" id="dataRecebimento" name="dataRecebimento" class="form-control"
                                required>
                        @endif

                    </div>
                </div>

                <!-- Campo de Anexar Nota Fiscal -->
                <div class="col-md-4">

                    @if ($pedido->status_id != config('config.status.concluido'))
                        <div class="form-group">
                            <label for="notaFiscal">Anexar Nota Fiscal (Opcional)</label>
                            <input type="file" id="notaFiscal" name="notaFiscal" class="form-control"
                                accept=".pdf,.jpg,.jpeg,.png">
                            {{-- <small class="form-text text-muted">Formatos permitidos: PDF, JPG, JPEG, PNG.</small> --}}
                        </div>
                    @else
                        @if ($pedido->recebimento && $pedido->recebimento->arquivo_id)
                            <div class="form-group">
                                <label for="notaFiscal">Nota Fiscal Anexada:</label><br>
                                <a href="{{ route('download.nf', ['arquivo_id' => $pedido->recebimento->arquivo_id]) }}"
                                    class="btn btn-success btn-sm" target="_blank">
                                    <i class="bi bi-download"></i> Baixar Nota Fiscal
                                </a>
                            </div>
                        @else
                            <div class="form-group">
                                <p class="text-muted">Nenhuma nota fiscal anexada.</p>
                            </div>
                        @endif
                    @endif

                </div>
            </div>


            <!-- Campo de Observações -->
            <div class="row d-flex">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea {{ $pedido->status_id == config('config.status.concluido') ? 'disabled' : '' }} id="observacoes"
                            name="observacoes" class="form-control" rows="4" placeholder="Digite suas observações aqui...">{{ $pedido->recebimento ? $pedido->recebimento->observacoes : '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a type="button" href="{{ route('cadastro.recebimento.index') }}" class="btn btn-outline-danger"> <i
                        class="bi bi-arrow-left"></i> Voltar</a>
                <button type="submit"
                    class="btn btn-dark {{ $pedido->status_id == config('config.status.concluido') ? 'd-none' : '' }}"> <i
                        class="bi bi-floppy"></i> Salvar</button>
            </div>
        </form>



    </div>
    <!-- Modal Único para Lote e Validade -->
    <div class="modal fade" id="modalLoteValidade" tabindex="-1" aria-labelledby="modalLoteValidadeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLoteValidadeLabel">Lote e Validade</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 id="nomeMaterial">Material nome</h5>
                    <br>
                    <form id="formLoteValidade" method="POST">
                        <input type="hidden" id="pedidoItemId" name="pedido_item_id">
                        <div class="form-group">
                            <label for="lote">Lote</label>
                            <input type="text" class="form-control" id="lote" name="lote">
                        </div>
                        <div class="form-group">
                            <label for="validade">Validade</label>
                            <input type="date" class="form-control" id="validade" name="validade"
                                min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <button onclick="fecharModal()" type="button" class="btn btn-outline-danger"
                                data-dismiss="modal"><i class="bi bi-arrow-left"></i> Sair</button>
                            @if ($pedido->status_id != config('config.status.concluido'))
                                <button type="submit" class="btn btn-dark"><i class="bi bi-floppy"></i> Salvar</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function abrirModalLoteValidade(itemId, button) {
            // Preencher os campos do modal com as informações do item
            $('#pedidoItemId').val(itemId);
            var nomeMaterial = $(button).data('nome');
            var validade = $(button).data('validade');
            var lote = $(button).data('lote');
            $('#nomeMaterial').text(nomeMaterial);

            // Recuperar os itens do localStorage
            var itens = JSON.parse(localStorage.getItem('itensRecebimentoPedido')) || [];


            // Verificar se o itemId existe no localStorage
            var item = itens.length ? itens.find(function(it) {
                return it.pedido_item_id == itemId;
            }) : null;

            // console.log(item);

            // Se o item for encontrado, preencher os campos de lote e validade
            if (item) {
                $('#lote').val(item.lote || '');
                $('#validade').val(item.validade || '');
            } else {
                $('#lote').val(lote);
                $('#validade').val(validade);
            }

            // Abrir o modal
            $('#modalLoteValidade').modal('show');
        }

        function fecharModal() {
            $('#modalLoteValidade').modal('hide');

        }

        $(document).ready(function() {
            montaDatatable('tabela-item-receber');
            maskQtdByClass('qtd_receber_pedidos');

            $('#receberPedido').on('submit', function(event) {
                // Prevenir o envio padrão do formulário
                event.preventDefault();

                // Obter dados da localStorage
                var itens = JSON.parse(localStorage.getItem('itensRecebimentoPedido')) || [];

                // Loop através dos inputs de quantidade recebida
                $('.qtd_receber_pedidos').each(function() {
                    var $input = $(this);
                    var pedidoItemId = $input.data('pedido-id'); // ID do pedido do item
                    var quantidadeRecebida = converteParaFloat($input
                        .val()); // Obtém a quantidade inserida no input

                    // Verifica se o pedido_item_id já existe no array 'itens'
                    var itemExistente = itens.find(item => item.pedido_item_id == pedidoItemId);

                    if (itemExistente) {
                        // Se o item já existe, apenas atualiza a quantidade recebida
                        itemExistente.quantidade_recebida = quantidadeRecebida;
                    } else {
                        // Se o item não existe, cria um novo objeto e adiciona ao array
                        itens.push({
                            pedido_item_id: pedidoItemId,
                            quantidade_recebida: quantidadeRecebida
                        });
                    }
                });

                // Preencher o campo hidden com os dados em formato JSON
                $('#dadosStorage').val(JSON.stringify(itens));
                localStorage.removeItem('itensRecebimentoPedido');
                // Agora o formulário pode ser enviado
                this.submit();

            });
            // Quando o foco sai do input (evento blur)
            $('.qtd_receber_pedidos').on('blur', function() {
                var $input = $(this);
                var quantidadeReceber = converteParaFloat($input.val()); // Obtém o valor do input
                var qtdMax = converteParaFloat($input.data('qtd-max-receber')); // Quantidade máxima permitida

                // Valida se a quantidade inserida é um número válido, maior que 0 e não ultrapassa a quantidade máxima
                if (isNaN(quantidadeReceber) || quantidadeReceber < 0) {
                    msgToastr('A quantidade deve ser maior que 0!', 'info')
                    $input.val($input.val());
                    return
                } else if (quantidadeReceber > qtdMax) {
                    msgToastr(
                        'A quantidade recebida não pode ser maior que a quantidade restante a ser recebida.',
                        'info')

                    $input.val($input.val()); // Ajusta o valor para a quantidade máxima
                }
            });

            $('#modalLoteValidade').on('hidden.bs.modal', function() {
                // Limpar os campos de input
                $('#pedidoItemId').val('');
                $('#nomeMaterial').text('');
                $('#lote').val('');
                $('#validade').val('');
            });


            $('#formLoteValidade').on('submit', function(e) {

                e.preventDefault(); // Previne o envio do formulário

                // Coleta as informações
                var pedidoItemId = $('#pedidoItemId').val();
                var lote = $('#lote').val();
                var validade = $('#validade').val();

                // Verifica se os campos lote e validade não estão vazios

                // Cria um objeto com as informações
                var itemData = {
                    pedido_item_id: pedidoItemId,
                    lote: lote,
                    validade: validade
                };

                // Recupera o array de itens do localStorage, ou cria um novo se não existir
                var itens = JSON.parse(localStorage.getItem('itensRecebimentoPedido')) || [];

                // Adiciona o novo item ao array
                itens.push(itemData);

                // Salva o array atualizado de volta no localStorage
                localStorage.setItem('itensRecebimentoPedido', JSON.stringify(itens));

                // Exibe um alerta ou mensagem de sucesso
                msgToastr('Informações salvas com sucesso', 'success')

                // Fecha o modal após salvar
                $('#modalLoteValidade').modal('hide');

                // Opcional: Limpa os campos do formulário após salvar
                this.reset();
            });
        });
    </script>
@endsection
