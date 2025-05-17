@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.cotacao.index'), 'titulo' => 'Cotações'], ['titulo' => 'Seleção de pedidos']],
])

@section('content')
    <style>
        .expander {
            width: 30px;
            cursor: pointer;
        }

        td.details-control {
            cursor: pointer;
        }
    </style>

    <div class="cabecalho">
        <div class="page-header">
            <h3>Seleção de pedidos</h3>
            <p class="lead">
                Selecione os pedidos que estão aguardando cotação, realize as cotações e prossiga para a geração da compra.
            </p>
        </div>
        <div>
            <button type="button" class="btn btn-success mb-2" id="btnCriarCotacao" data-toggle="modal"
                data-target="#modalItensCotacao">
                Criar cotação
                <span class="badge badge-dark ml-1" id="qtdItensParaCotar">0</span>
            </button>
        </div>
    </div>

    <div class="card card-body">
        {{-- <div class="page-header">
            <h4>Pedidos aguardando cotação</h4>
        </div> --}}

        <table id="tabela-pedidos" class="table table-bordered">
            <thead>
                <tr>
                    <th class="expander"></th>
                    <th>Nº Pedido</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Data limite</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos_aguardando_cotacao as $p)
                    <tr class="pedido-row" data-id="{{ $p->id }}">
                        <td class="details-control">
                            <i class="bi bi-chevron-right expand-icon"></i>
                        </td>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->usuario->master->name }}</td>
                        <td><span class="{{ $p->status->badge() }}">{{ $p->status->descricao() }}</span></td>
                        <td>{{ aplicarMascaraDataNascimento($p->data_limite) }}</td>
                        <td>{{ $p->pedido_itens->count() }}</td>
                        <td>{{ $p->observacao }}</td>
                        <td>
                            <button class="btn btn-sm btn-dark btn-adicionar" data-id="{{ $p->id }}">
                                <i class="bi bi-cart-plus"></i> Adicionar
                            </button>
                            <button class="btn btn-sm btn-danger btn-remover" data-id="{{ $p->id }}"
                                style="display: none;">
                                <i class="bi bi-cart-dash"></i> Remover
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            <a href="{{ route('cadastro.cotacao.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    <!-- Modal para listagem de itens e seleção de fornecedores -->
    <div class="modal fade" id="modalItensCotacao" tabindex="-1" role="dialog" aria-labelledby="modalItensCotacaoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalItensCotacaoLabel">Definir participantes:*</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Select2 para fornecedores -->
                    <div class="form-group">
                        <label for="fornecedores">Selecione os fornecedores que participarão desta cotação</label>
                        <select style="width: 100%!important;" required id="fornecedores" name="fornecedores[]" multiple
                            class="form-control select2">

                        </select>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-10">
                            <h5>Produtos na cotação</h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Tabela de itens selecionados -->
                        <table class="table table-bordered" id="tabela-itens-selecionados">
                            <thead>
                                <tr>
                                    <th>Nº Pedido</th>
                                    <th>Produto</th>
                                    <th>Status</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Preenchido via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-dark" id="btnIniciarCotacao">Iniciar cotação</button>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('cadastro.cotacao.post') }}" id="form_cotacao_create" method="post">
        @csrf
        <input type="hidden" id="pedidos_form" name="pedidos">
        <input type="hidden" id="fornecedores_form" name="fornecedores">
    </form>
    <script>
        // Estado atual das seleções (mantido em memória)
        var pedidosSelecionados = [];
        // Lista de pedidos disponíveis (vinda do servidor)
        var pedidos = @json($pedidos_aguardando_cotacao);
        var routeGetFornecedores = @json(route('fornecedores.select2'));
        // Formata os detalhes de um pedido para exibição na tabela expansível
        function formatDetails(pedidoId) {
            const pedido = pedidos.find(p => p.id == pedidoId);
            let html = `<table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produto</th>
                                    <th>Status</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>`;

            pedido.pedido_itens.forEach(item => {
                html += `<tr>
                            <td>${item.produto_id}</td>
                            <td>${montaNomeProduto(item.produto)}</td>
                            <td><span class="${item.status.badge}">${item.status.descricao_formatada}</span></td>
                            <td>${item.quantidade_pedida}</td>
                        </tr>`;
            });

            html += `</tbody></table>`;
            return html;
        }

        $(document).ready(function() {
            select2('fornecedores', routeGetFornecedores);
            const table = montaDatatable('tabela-pedidos');
            // montaDatatable('tabela-itens-selecionados');

            // Atualiza a visibilidade dos botões Adicionar/Remover
            function atualizarBotao(pedidoId, isSelecionado) {
                const $btnAdicionar = $(`.btn-adicionar[data-id="${pedidoId}"]`);
                const $btnRemover = $(`.btn-remover[data-id="${pedidoId}"]`);
                $btnAdicionar.toggle(!isSelecionado);
                $btnRemover.toggle(isSelecionado);
                atualizarContador();
            }

            function atualizarContador() {
                let totalItens = 0;
                pedidosSelecionados.forEach(pedidoId => {
                    let pedido = pedidos.find(p => p.id == pedidoId);
                    totalItens += pedido.pedido_itens.length;
                });
                $('#qtdItensParaCotar').text(totalItens);
            }

            // Expande/contrai detalhes do pedido na tabela
            $('#tabela-pedidos tbody').on('click', 'td.details-control', function() {
                const $tr = $(this).closest('tr');
                const row = table.row($tr);
                const id = $tr.data('id');

                if (row.child.isShown()) {
                    $('div.slider', row.child()).slideUp(300, function() {
                        row.child.hide();
                        $tr.find('.expand-icon').removeClass('bi-chevron-down').addClass(
                            'bi-chevron-right');
                    });
                } else {
                    row.child(`<div class="slider">${formatDetails(id)}</div>`).show();
                    $('div.slider', row.child()).hide().slideDown(300);
                    $tr.find('.expand-icon').removeClass('bi-chevron-right').addClass('bi-chevron-down');
                }
            });

            // Adiciona um pedido às seleções
            $('#tabela-pedidos').on('click', '.btn-adicionar', function() {
                const pedidoId = $(this).data('id');
                if (!pedidosSelecionados.includes(pedidoId)) {
                    pedidosSelecionados.push(pedidoId);
                    atualizarBotao(pedidoId, true);
                    msgToastr('Pedido adicionao com sucesso.', 'success');

                }
            });

            // Remove um pedido das seleções
            $('#tabela-pedidos').on('click', '.btn-remover', function() {
                const pedidoId = $(this).data('id');
                pedidosSelecionados = pedidosSelecionados.filter(id => id !== pedidoId);
                atualizarBotao(pedidoId, false);
                msgToastr('Pedido removido com sucesso.', 'success');

            });

            $('#btnIniciarCotacao').on('click', function() {
                let fornecedoresSelecionados = $('#fornecedores').val();
                if (fornecedoresSelecionados.length == 0) {
                    msgToastr('Nenhum fornecedor selecionado.', 'info');
                    $('#fornecedores').focus();
                    return;
                }

                if (pedidosSelecionados.length == 0) {
                    msgToastr('Nenhum pedido selecionado.', 'info');
                    return;
                }
                $('#fornecedores_form').val(JSON.stringify(fornecedoresSelecionados));
                $('#pedidos_form').val(JSON.stringify(pedidosSelecionados));
                $('#form_cotacao_create').submit();

            })
            //ao abrir o modal criar cotação
            $('#modalItensCotacao').on('shown.bs.modal', function() {
                let tabelaItens = $('#tabela-itens-selecionados tbody');
                tabelaItens.empty();
                let html = '';
                pedidosSelecionados.forEach(pedidoId => {
                    let pedido = pedidos.find(p => p.id == pedidoId).pedido_itens.forEach(
                        item => {
                            html += `<tr>
                            <td>${item.pedido_id}</td>
                            <td>${montaNomeProduto(item.produto)}</td>
                            <td><span class="${item.status.badge}">${item.status.descricao_formatada}</span></td>
                            <td>${item.quantidade_pedida}</td>
                        </tr>`;
                        });

                });

                tabelaItens.append(html);
            });

        });
    </script>
@endsection
