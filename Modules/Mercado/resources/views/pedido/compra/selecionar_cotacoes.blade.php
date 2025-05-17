@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.compra.index'), 'titulo' => 'Compras'], ['titulo' => 'Selecionar cotações']],
])

@section('content')
    <style>
        .expander {
            width: 30px;
            cursor: pointer;
        }

        td.details-control,
        td.details-control-sub {
            cursor: pointer;
        }

        .sub-slider {
            padding: 10px;
        }

        table.table-sm tr.shown+tr td {
            padding: 0;
        }
    </style>

    <div class="cabecalho">
        <div class="row align-items-center justify-content-between mb-3">
            <div class="col">
                <h3>Seleção de cotações</h3>
                <p class="lead mb-0">
                    Selecione a cotação com os pedidos relacionados e avance para escolher as melhores condições de compra.
                </p>
            </div>
            {{-- <div class="col-auto">
                <button type="button" class="btn btn-success" id="btnCriarCompra" data-toggle="modal"
                    data-target="#modalCotacoes">
                    Cotações selecionadas
                    <span class="badge badge-dark ml-1" id="qtdCotacoesComprar">0</span>
                </button>
            </div> --}}
        </div>
    </div>

    <div class="card card-body">
        <table id="tabela-cotacoes" class="table table-bordered">
            <thead>
                <tr>
                    <th class="expander"></th>
                    <th>Nº Pedidos</th>
                    <th>Usuário</th>
                    <th>Data de abertura</th>
                    <th>Status</th>
                    <th>Qtd Itens</th>
                    <th>Observação</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotacoes_aguardando_compra as $c)
                    <tr class="cotacao-row" data-id="{{ $c->id }}">
                        <td class="details-control">
                            <i class="bi bi-chevron-right expand-icon"></i>
                        </td>
                        <td>{{ $c->cot_for_itens->pluck('pedido_id')->unique()->join('/') }}</td>
                        <td>{{ $c->usuario->master->name }}</td>
                        <td>{{ aplicarMascaraDataNascimento($c->data_abertura) }}</td>
                        <td><span class="{{ $c->status->badge() }}">{{ $c->status->descricao() }}</span></td>
                        <td>{{ $c->cot_for_itens->pluck('estoque_id')->unique()->count() }}</td>
                        <td>{{ $c->descricao }}</td>
                        <td>
                            <a href="{{route('cadastro.compra.create', ['cotacao_id' => $c->id])}}" class="btn btn-warning">
                                <i class="bi-arrow-right-circle"></i>
                            </a>
                            {{-- <button class="btn btn-sm btn-dark btn-adicionar" data-id="{{ $c->id }}">
                                <i class="bi bi-cart-plus"></i> Adicionar
                            </button>
                            <button class="btn btn-sm btn-danger btn-remover" data-id="{{ $c->id }}"
                                style="display: none;">
                                <i class="bi bi-cart-dash"></i> Remover
                            </button> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="card-footer">
            <a href="{{ route('cadastro.compra.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>


    <script>
        // Estado atual das seleções
        var cotacoesSelecionados = [];
        // Lista de cotações disponíveis (vinda do servidor)
        var cotacoes = @json($cotacoes_aguardando_compra);

        // Função para formatar detalhes de uma cotação
        function formatDetails(cotacaoId) {
            if (!cotacaoId || isNaN(cotacaoId)) {
                console.error('ID de cotação inválido:', cotacaoId);
                return '<div class="text-danger">Erro: ID de cotação inválido.</div>';
            }

            const cotacao = cotacoes.find(p => p.id === Number(cotacaoId));
            if (!cotacao) {
                console.error(`Cotação com ID ${cotacaoId} não encontrada.`);
                return '<div class="text-danger">Erro: Cotação não encontrada.</div>';
            }

            if (!cotacao.cot_fornecedores || !Array.isArray(cotacao.cot_fornecedores)) {
                return '<div class="text-danger">Nenhum fornecedor disponível.</div>';
            }

            let html = `<table class="table table-sm table-bordered" data-cotacao-id="${cotacaoId}">
                            <thead>
                                <tr>
                                    <th class="expander"></th>
                                    <th>Fornecedor</th>
                                    <th>SubTotal</th>
                                    <th>Frete</th>
                                    <th>Desconto</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>`;

            cotacao.cot_fornecedores.forEach(cf => {
                html += `<tr class="fornecedor-row" data-id="${cf.id || ''}">
                            <td class="details-control-sub">
                                <i class="bi bi-chevron-right expand-icon-sub"></i>
                            </td>
                            <td>${cf.fornecedor?.nome || 'N/A'}</td>
                            <td>R$ ${centavosParaReais(cf.sub_total || 0)}</td>
                            <td>R$ ${centavosParaReais(cf.frete || 0)}</td>
                            <td>R$ ${centavosParaReais(cf.desconto || 0)}</td>
                            <td>R$ ${centavosParaReais(cf.total || 0)}</td>
                        </tr>`;
            });

            html += `</tbody></table>`;
            return html;
        }

        // Função para formatar os itens de um fornecedor
        function formatFornecedorItens(fornecedorId, cotacaoId) {
            const cotacao = cotacoes.find(p => p.id === Number(cotacaoId));
            const fornecedor = cotacao?.cot_fornecedores.find(f => f.id === Number(fornecedorId));

            if (!fornecedor) {
                return '<div class="text-danger">Fornecedor não encontrado.</div>';
            }

            let html = `<table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Nº Pedidos</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>`;

            fornecedor.cot_for_itens.forEach(item => {
                html += `<tr>
                            <td>${item.pedidos_agrupados || 'N/A'}</td>
                            <td>${item.produto?.nome || 'N/A'}</td>
                            <td>${item.quantidade || 'N/A'}</td>
                            <td>R$ ${centavosParaReais(item.preco_unitario || 0)}</td>
                            <td>R$ ${centavosParaReais((item.preco_unitario || 0) * (item.quantidade || 0))}</td>
                        </tr>`;
            });

            html += `</tbody></table>`;
            return html;
        }

        $(document).ready(function() {
            const table = montaDatatable('tabela-cotacoes');

            // Atualiza a visibilidade dos botões Adicionar/Remover
            function atualizarBotao(cotacaoId, isSelecionado) {
                const $btnAdicionar = $(`.btn-adicionar[data-id="${cotacaoId}"]`);
                const $btnRemover = $(`.btn-remover[data-id="${cotacaoId}"]`);
                $btnAdicionar.toggle(!isSelecionado);
                $btnRemover.toggle(isSelecionado);
                atualizarContador();
            }

            // Atualiza o contador de cotações selecionadas
            function atualizarContador() {
                $('#qtdCotacoesComprar').text(cotacoesSelecionados.length);
            }

            // Expande/contrai detalhes da cotação (tabela principal)
            $('#tabela-cotacoes tbody').on('click', 'td.details-control', function() {
                const $tr = $(this).closest('tr');
                const row = table.row($tr);
                const id = Number($tr.data('id'));

                if (row.child.isShown()) {
                    $('div.slider', row.child()).slideUp(300, function() {
                        row.child.hide();
                        $tr.find('.expand-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    });
                } else {
                    row.child(`<div class="slider">${formatDetails(id)}</div>`).show();
                    $('div.slider', row.child()).hide().slideDown(300);
                    $tr.find('.expand-icon').removeClass('bi-chevron-right').addClass('bi-chevron-down');
                }
            });

            // Expande/contrai detalhes dos itens do fornecedor (tabela principal e modal)
            $(document).on('click', 'td.details-control-sub', function() {
                const $tr = $(this).closest('tr');
                const $table = $tr.closest('table[data-cotacao-id]');
                const fornecedorId = Number($tr.data('id'));
                const cotacaoId = Number($table.data('cotacao-id'));

                if (!cotacaoId || isNaN(cotacaoId) || !fornecedorId || isNaN(fornecedorId)) {
                    console.error('IDs inválidos ao expandir fornecedor:', { cotacaoId, fornecedorId });
                    msgToastr('Erro: IDs inválidos.', 'error');
                    return;
                }

                if ($tr.hasClass('shown')) {
                    $('div.sub-slider', $tr.next()).slideUp(300, function() {
                        $tr.next().remove();
                        $tr.removeClass('shown');
                        $tr.find('.expand-icon-sub').removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    });
                } else {
                    const rowHtml = `<tr><td colspan="6"><div class="sub-slider">${formatFornecedorItens(fornecedorId, cotacaoId)}</div></td></tr>`;
                    $tr.after(rowHtml);
                    $('div.sub-slider', $tr.next()).hide().slideDown(300);
                    $tr.addClass('shown');
                    $tr.find('.expand-icon-sub').removeClass('bi-chevron-right').addClass('bi-chevron-down');
                }
            });

            // Adiciona uma cotação às seleções
            $('#tabela-cotacoes').on('click', '.btn-adicionar', function() {
                const cotacaoId = Number($(this).data('id'));
                if (!cotacaoId || isNaN(cotacaoId)) {
                    console.error('ID inválido ao adicionar cotação:', cotacaoId);
                    msgToastr('Erro: ID de cotação inválido.', 'error');
                    return;
                }
                if (!cotacoesSelecionados.includes(cotacaoId)) {
                    cotacoesSelecionados.push(cotacaoId);
                    atualizarBotao(cotacaoId, true);
                    msgToastr('Cotação adicionada com sucesso.', 'success');
                }
            });

            // Remove uma cotação das seleções (tabela principal e modal)
            $(document).on('click', '.btn-remover', function() {
                const cotacaoId = Number($(this).data('id'));
                cotacoesSelecionados = cotacoesSelecionados.filter(id => id !== cotacaoId);
                atualizarBotao(cotacaoId, false);
                msgToastr('Cotação removida com sucesso.', 'success');

                // Atualiza a tabela do modal se estiver aberta
                if ($('#modalCotacoes').hasClass('show')) {
                    atualizarTabelaModal();
                }
            });

            // Função para atualizar a tabela do modal
            function atualizarTabelaModal() {
                const $tbody = $('#tabela-itens-selecionados tbody');
                $tbody.empty();

                cotacoesSelecionados.forEach(id => {
                    const c = cotacoes.find(c => c.id === id);
                    if (!c) return;

                    const row = `<tr class="cotacao-row" data-id="${c.id}">
                        <td class="details-control">
                            <i class="bi bi-chevron-right expand-icon"></i>
                        </td>
                        <td>${[...new Set(c.cot_for_itens.map(i => i.pedido_id))].join('/')}</td>
                        <td>${c.usuario?.master?.name || 'N/A'}</td>
                        <td>${aplicarMascaraDataNascimento(c.data_abertura)}</td>
                        <td><span class="${getBadgeStatus(c.status_id)}">${getDescricaoStatus(c.status_id)}</span></td>
                        <td>${[...new Set(c.cot_for_itens.map(i => i.estoque_id))].length}</td>
                        <td>${c.descricao || 'N/A'}</td>

                    </tr>`;
                    $tbody.append(row);
                });

                // Destroi o DataTable anterior, se existir, e reinicializa
                const $table = $('#tabela-itens-selecionados');
                if ($.fn.DataTable.isDataTable($table)) {
                    $table.DataTable().destroy();
                }
                montaDatatable('tabela-itens-selecionados');
            }

            // Expande/contrai detalhes da cotação no modal
            $('#tabela-itens-selecionados tbody').on('click', 'td.details-control', function() {
                const $tr = $(this).closest('tr');
                const tableModal = $('#tabela-itens-selecionados').DataTable();
                const row = tableModal.row($tr);
                const id = Number($tr.data('id'));

                if (row.child.isShown()) {
                    $('div.slider', row.child()).slideUp(300, function() {
                        row.child.hide();
                        $tr.find('.expand-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    });
                } else {
                    row.child(`<div class="slider">${formatDetails(id)}</div>`).show();
                    $('div.slider', row.child()).hide().slideDown(300);
                    $tr.find('.expand-icon').removeClass('bi-chevron-right').addClass('bi-chevron-down');
                }
            });


        });
    </script>
@endsection
