import * as gerais from '@/gerais.js';
// Estado atual das seleções (mantido em memória)
var pedidosSelecionados = [];
// Lista de pedidos disponíveis (vinda do servidor)
var pedidos = $('#dataView').data('pedidos');
var routeGetFornecedores = $('#dataView').data('getFornecedores');

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
                            <td>${gerais.montaNomeProduto(item.produto)}</td>
                            <td><span class="${item.status.badge}">${item.status.descricao_formatada}</span></td>
                            <td>${item.quantidade_pedida}</td>
                        </tr>`;
    });

    html += `</tbody></table>`;
    return html;
}

$(document).ready(function () {
    gerais.constructSelect2('fornecedores', routeGetFornecedores);
    const table = gerais.montaDatatable('tabela-pedidos');
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
    $('#tabela-pedidos tbody').on('click', 'td.details-control', function () {
        const $tr = $(this).closest('tr');
        const row = table.row($tr);
        const id = $tr.data('id');

        if (row.child.isShown()) {
            $('div.slider', row.child()).slideUp(300, function () {
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
    $('#tabela-pedidos').on('click', '.btn-adicionar', function () {
        const pedidoId = $(this).data('id');
        if (!pedidosSelecionados.includes(pedidoId)) {
            pedidosSelecionados.push(pedidoId);
            atualizarBotao(pedidoId, true);
            gerais.msgToastr('Pedido adicionao com sucesso.', 'success');

        }
    });

    // Remove um pedido das seleções
    $('#tabela-pedidos').on('click', '.btn-remover', function () {
        const pedidoId = $(this).data('id');
        pedidosSelecionados = pedidosSelecionados.filter(id => id !== pedidoId);
        atualizarBotao(pedidoId, false);
        gerais.msgToastr('Pedido removido com sucesso.', 'success');

    });

    $('#btnIniciarCotacao').on('click', function () {
        let fornecedoresSelecionados = $('#fornecedores').val();
        if (fornecedoresSelecionados.length == 0) {
            gerais.msgToastr('Nenhum fornecedor selecionado.', 'info');
            $('#fornecedores').focus();
            return;
        }

        if (pedidosSelecionados.length == 0) {
            gerais.msgToastr('Nenhum pedido selecionado.', 'info');
            return;
        }
        $('#fornecedores_form').val(JSON.stringify(fornecedoresSelecionados));
        $('#pedidos_form').val(JSON.stringify(pedidosSelecionados));
        $('#form_cotacao_create').submit();

    })
    //ao abrir o modal criar cotação
    $('#modalItensCotacao').on('shown.bs.modal', function () {
        let tabelaItens = $('#tabela-itens-selecionados tbody');
        tabelaItens.empty();
        let html = '';
        pedidosSelecionados.forEach(pedidoId => {
            let pedido = pedidos.find(p => p.id == pedidoId).pedido_itens.forEach(
                item => {
                    html += `<tr>
                            <td>${item.pedido_id}</td>
                            <td>${gerais.montaNomeProduto(item.produto)}</td>
                            <td><span class="${item.status.badge}">${item.status.descricao_formatada}</span></td>
                            <td>${item.quantidade_pedida}</td>
                        </tr>`;
                });

        });

        tabelaItens.append(html);
    });

});
