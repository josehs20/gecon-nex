import * as gerais from '@/gerais.js';

var urlGetProdutos = $('#dataView').data('urlGetProdutos');
var urlGetEstoque = $('#dataView').data('urlGetEstoque');
var podeAlterarAlgo = $('#dataView').data('podeAlterarAlgo');
var balanco = $('#dataView').data('balanco');
// Inicializa o array global sempre
var itensBalanco = [];

if (balanco && balanco.balanco_itens.length > 0) {
    formataBalancoItens(balanco.balanco_itens);

}

gerais.montaDatatable('tabela-balanco-item');
gerais.constructSelect2('estoque_id', urlGetProdutos);
gerais.maskQtd('quantidade_estoque_sistema');
gerais.maskQtd('quantidade_disponivel');
$('#btn_finalizar_balanco, #btn_salvar_balanco').on('click', function () {
    var observacao = $('#observacao')[0];
    var confirmacaoBalanco = $('#confirmacaoBalanco')[0];
    var isFinalizar = $(this).attr('id') === 'btn_finalizar_balanco';

    if (!itensBalanco.length) {
        gerais.msgToastr('Nenhum item adicionado.', 'info')
        return;
    }
    if (!observacao.checkValidity()) {
        observacao.reportValidity();
        gerais.msgToastr('Campo observação obrigatório', 'info');
    } else if (!confirmacaoBalanco.checked) {
        confirmacaoBalanco.reportValidity();
        gerais.msgToastr('Você precisa confirmar o balanço', 'info');
    } else {
        $('#observacaoFinalizar').val(observacao.value);
        $('#itens').val(JSON.stringify(itensBalanco));
        $('input[name="finalizar"]').val(isFinalizar ? 'true' : 'false');
        $('#finalizar_balanco_post').submit();
    }
});

$('#estoque_id').change(function () {
    var selectedValue = $(this).val();

    $.ajax({
        url: urlGetEstoque, // URL da sua rota para consulta
        type: 'GET',
        data: {
            id: selectedValue
        },
        success: function (response) {

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
        error: function (error) {
            gerais.msgToastr(error, 'error');

        }
    });
});

$('#form-movimentar-balanco').submit(function (e) {
    e.preventDefault();

    const estoqueId = $('#estoque_id').val();
    const nomeProduto = $('#estoque_id option:selected').text();
    const quantidadeSistema = gerais.converteParaFloat($('#quantidade_estoque_sistema').val());
    const quantidadeReal = gerais.converteParaFloat($('#quantidade_estoque_real').val());
    const resultado = gerais.converteParaFloat($('#quantidade_operacional').val());


    if (!estoqueId || !quantidadeReal) {
        gerais.msgToastr('Verifique a quantidade ou material escolhido.', 'info');
        return
    };

    // Verifica se já existe e atualiza
    const indexExistente = itensBalanco.findIndex(item => item.estoqueId == estoqueId);
    if (indexExistente !== -1) {
        gerais.msgToastr('Item atualizado.', 'success');
        itensBalanco[indexExistente] = {
            estoqueId,
            nomeProduto,
            quantidadeSistema,
            quantidadeReal,
            resultado
        };
    } else {
        gerais.msgToastr('Item adicionado.', 'success');

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


function formataBalancoItens(itens) {
    let itensFormatados = []

    itens.forEach(item => {
        let nome = gerais.montaNomeProduto(item.estoque.produto)
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
        let colunaAcao = podeAlterarAlgo ? `
    <td>
        <button type="button"
                class="btn btn-danger btn-sm btn-delete"
                data-estoque-id="${item.estoqueId}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
` : '';
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

$(document).on('click', '.btn-delete', function () {
    const estoqueId = $(this).data('estoque-id');
    confirmDelete(estoqueId);
});
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
    gerais.msgToastr('Item removido com sucesso.', 'success')
}

/*
 *   Balanço = Estoque real - Estoque teórico
 */
// Associa o evento aos dois inputs
$('#quantidade_estoque_real').on('input', calcularResultadoOperacional);
$('#quantidade_operacional').on('input', calcularResultadoOperacional);
function calcularResultadoOperacional(e) {
    gerais.validarInput(e);

    // Obter os valores dos campos de entrada
    let quantidade_estoque_sistema = $('#quantidade_estoque_sistema').val();
    let quantidade_estoque_real = $('#quantidade_estoque_real').val();

    // Garantir que os valores sejam números válidos
    quantidade_estoque_sistema = gerais.converteParaFloat(quantidade_estoque_sistema);
    quantidade_estoque_real = gerais.converteParaFloat(quantidade_estoque_real);

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
