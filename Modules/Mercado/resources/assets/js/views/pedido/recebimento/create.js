import * as gerais from '@/gerais.js';
import jQuery from 'jquery';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

gerais.montaDatatable('tabela-compras');


// const tabela = $('#tabela-compras').DataTable({
//     ordering: true,
//     paging: true,
//     info: false,
//     autoWidth: false,
//     columnDefs: [{
//         orderable: false,
//         targets: -1
//     }]
// });

$('#tabela-compras').on('click', '.btn-expandir', function () {
    const btn = $(this);
    const tr = btn.closest('tr');

    // Se já estiver expandido, fecha
    if (tr.hasClass('aberto')) {
        fecharTodasLinhas();
        return;
    }

    fecharTodasLinhas();

    const itens = tr.data('itens');

    const novaLinha = itens.length ? montarLinhaDetalhes(itens) : montarLinhaSemItens();
    tr.after(novaLinha);

    tr.addClass('aberto');
    btn.find('i').removeClass('bi-arrow-down').addClass('bi-arrow-up');
});

function fecharTodasLinhas() {
    $('.linha-detalhes').remove();
    $('.linha-compra').removeClass('aberto');
    $('.btn-expandir i').removeClass('bi-arrow-up').addClass('bi-arrow-down');
}

function montarLinhaSemItens() {
    return `
                <tr class="linha-detalhes">
                    <td colspan="7" class="text-center text-muted">Sem itens</td>
                </tr>
            `;
}

function montarLinhaDetalhes(itens) {
    const linhas = itens.map(item => montarLinhaItem(item)).join('');

    return `
                <tr class="linha-detalhes">
                    <td colspan="7">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Nome</th>
                                    <th>Quantidade</th>
                                    <th>Unidade</th>
                                    <th>Preço unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${linhas}
                            </tbody>
                        </table>
                    </td>
                </tr>
            `;
}

function montarLinhaItem(item) {    
    const produto = item?.cotacao_fornecedor_item?.produto ?? {};
    const quantidade = item?.cotacao_fornecedor_item?.quantidade ?? 0;
    const preco = item?.cotacao_fornecedor_item?.preco_unitario ?? 0;
    const unidade = produto?.unidade_medida?.sigla ?? '-';
    const total = (preco * parseFloat(quantidade));
    
    return `
                <tr>
                    <td>${produto.id ?? '-'}</td>
                    <td>${produto.nome ?? '-'}</td>
                    <td>${quantidade}</td>
                    <td>${unidade}</td>
                    <td>R$ ${gerais.centavosParaReais(preco)}</td>
                    <td>R$ ${gerais.centavosParaReais(total)}</td>
                </tr>
            `;
}
