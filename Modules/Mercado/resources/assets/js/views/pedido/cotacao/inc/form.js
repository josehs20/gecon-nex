import * as gerais from '@/gerais.js';
var routeSalvarCotacao = $('#dataView').data('routeSalvarCotacao');
var cotacao = $('#dataView').data('cotacao');

gerais.maskDinheiroByClass('preco-unitario');
gerais.maskDinheiroByClass('total-item');
gerais.maskDinheiroByClass('frete-fornecedor');
gerais.maskDinheiroByClass('desconto-fornecedor');
$(document).on('click', '[data-cancelar-cotacao]', function() {
    const cotacaoId = $(this).data('cancelar-cotacao');
    cancelarCotacao(cotacaoId);
});

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
    $(`#tabela-${fornecedorId} .total-item`).each(function () {
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
    $(`#tabela-${fornecedorId} .preco-unitario`).each(function () {
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
        $(`#tabela-${fornecedorId} .preco-unitario`).each(function () {
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
        $(`#tabela-${fornecedorId} .total-item`).each(function () {
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
        $(`#tabela-${fornecedorId} .total-item`).each(function () {
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
$(document).on('input', '.preco-unitario', function () {
    calcularTotalItem($(this));
});

// Monitora mudanças no frete e desconto
$(document).on('input', '.frete-fornecedor', function () {
    const fornecedorId = $(this).data('fornecedor-id');
    atualizarTotalFornecedor(fornecedorId);
});

$(document).on('input', '.desconto-fornecedor', function () {
    const $input = $(this);
    const fornecedorId = $input.data('fornecedor-id');
    const desconto = parseFloat($input.val().replace(',', '.')) || 0;
    let subTotal = 0;
    $(`#tabela-${fornecedorId} .total-item`).each(function () {
        const valor = parseFloat($(this).val().replace(',', '.')) || 0;
        subTotal += valor;
    });
    const frete = parseFloat($(`#frete-${fornecedorId}`).val().replace(',', '.')) || 0;
    const totalFornecedor = subTotal - desconto + frete;

    if (totalFornecedor < 0) {
        gerais.msgToastr('O desconto não pode tornar o total negativo.', 'warning');
        $input.val('0,00'); // Redefine o desconto para 0
        atualizarTotalFornecedor(fornecedorId); // Atualiza com o desconto redefinido
    } else {
        atualizarTotalFornecedor(fornecedorId);
    }
});

// Evento de clique no botão Salvar Cotação
$('#btn_salvar_cotacao, #btn_alterar_cotacao').on('click', function () {
    const cotacaoAtualizada = montarObjetoCotacao();
    if (!cotacaoAtualizada) {
        gerais.msgToastr('Erro ao montar cotação', 'error');
        return;
    }
    const isBotaoAlterar = $(this).is('#btn_alterar_cotacao');
    cotacaoAtualizada.finalizar = false;
    gerais.bloquear();
    $.ajax({
        url: routeSalvarCotacao,
        method: 'POST',
        data: JSON.stringify(cotacaoAtualizada),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                'content') // Add CSRF token to headers
        },
        success: function (response) {
            if (response.success == true) {
                gerais.msgToastr(response.msg, 'success');

                if (isBotaoAlterar) {
                    setTimeout(function () {
                        location.reload();
                    }, 500); // Aguarda 1 segundo antes de recarregar
                } else {
                    $('#statusCotacao')
                        .removeClass() // remove todas as classes
                        .addClass(response.cotacao.status_badge) // adiciona a nova
                        .text(response.cotacao.status_descricao); // atualiza o texto
                }

            } else {
                gerais.msgToastr(response.msg, 'warning');

            }
        },
        error: function (xhr) {
            gerais.msgToastr('Erro ao salvar cotação: ' + (xhr.responseText || 'Tente novamente.'),
                'error');
        },
        complete: function () {
            gerais.desbloquear();
        }
    });
});

// Evento de clique no botão Finalizar Cotação
$('#btn_finalizar_cotacao').on('click', function () {
    const {
        erro,
        fornecedorId
    } = validarCotacao();

    if (erro) {
        gerais.msgToastr(erro, 'warning');
        if (fornecedorId) {
            mostrarCotacaoFornecedor(fornecedorId);
        }
    } else {
        const cotacaoAtualizada = montarObjetoCotacao();
        cotacaoAtualizada.finalizar = true;
        gerais.bloquear();
        $.ajax({
            url: routeSalvarCotacao, // Substitua pelo endpoint correto
            method: 'POST',
            data: JSON.stringify(cotacaoAtualizada),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content') // Add CSRF token to headers
            },
            success: function (response) {
                if (response.success == true) {
                    gerais.msgToastr(response.msg, 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 500); // Aguarda 1 segundo antes de recarregar
                } else {
                    gerais.msgToastr(response.msg, 'warning');
                }
            },
            error: function (xhr) {
                gerais.msgToastr('Erro ao finalizar cotação: ' + (xhr.responseText ||
                    'Tente novamente.'), 'error');
            },
            complete: function () {
                gerais.desbloquear();
            }
        });
    }
});
 $('.mostrar-cotacao').on('click', function() {
        const fornecedorId = $(this).data('fornecedor-id');
        mostrarCotacaoFornecedor(fornecedorId);
    });
// Função para exibir o card do fornecedor selecionado
function mostrarCotacaoFornecedor(fornecedorId) {
    gerais.bloquear();
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
        gerais.desbloquear();
    }, 500);
}

// Inicializa as DataTables e pendentes
cotacao.cot_fornecedores.forEach(function (element) {
    gerais.montaDatatable('tabela-' + element.fornecedor_id);
    atualizarPendentes(element.fornecedor_id);
    // Inicializa os totais dos itens
    $(`#tabela-${element.fornecedor_id} .preco-unitario`).each(function () {
        calcularTotalItem($(this));
    });
});

// Seleciona o primeiro fornecedor ao carregar a página
if (cotacao.cot_fornecedores.length > 0) {
    var primeiroFornecedorId = cotacao.cot_fornecedores[0].fornecedor_id;
    mostrarCotacaoFornecedor(primeiroFornecedorId);
}
