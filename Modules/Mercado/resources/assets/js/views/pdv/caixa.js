import * as gerais from '@/gerais.js';
import { param } from 'jquery';

const csrfToken = $('meta[name="csrf-token"]').attr('content');

const rotas = {
    finalizarVenda: $('#dataView').data('rotaFinalizarVenda'),
    devolucaoVenda: $('#dataView').data('rotaDevolucaoVenda'),
    orcamentoVenda: $('#dataView').data('rotaOrcamentoVenda'),
    suprirCaixa: $('#dataView').data('rotaSuprirCaixa'),
    sangriaCaixa: $('#dataView').data('rotaSangriaCaixa'),
    rotaReceberConta: $('#dataView').data('rotaReceberConta'),
    rotaFecharCaixa: $('#dataView').data('rotaFecharCaixa'),
    rotaAdicionarItem: $('#dataView').data('rotaAdicionarItem'),
    rotaRemoverItem: $('#dataView').data('rotaRemoverItem'),
    rotaSupervisores: $('#dataView').data('rotaSupervisores'),
    validarSuperior: $('#dataView').data('validarSuperior'),
    clientesGet: $('#dataView').data('clientesGet'),
    formasPagamentoGet: $('#dataView').data('formasPagamentoGet'),
    rotaBuscaProduto: $('#dataView').data('caixaProdutoGet'),
    rotaVendasDevolucaoGet: $('#dataView').data('rotaVendasDevolucaoGet'),
    rotaVendaDevolverGet: $('#dataView').data('rotaVendaDevolverGet'),
    rotaOrcamento: $('#dataView').data('rotaOrcamento'),
    rotaOrcamentoGet: $('#dataView').data('rotaOrcamentoGet'),
    rotaColocarOrcamentoEmVenda: $('#dataView').data('rotaColocarOrcamentoEmVenda'),
    excluirOrcamento: $('#dataView').data('excluirOrcamento'),
    rotaGetEspecies: $('#dataView').data('rotaGetEspecies'),
    rotaGetCaixa: $('#dataView').data('rotaGetCaixa'),
    rotaGetClienteRecebimentoParcelas: $('#dataView').data('rotaGetClienteRecebimentoParcelas'),
    rotaGetClienteParcelas: $('#dataView').data('rotaGetClienteParcelas'),

};

function ajaxPost(url, requestData, texto = null) {
    return $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: csrfToken,
            data: requestData
        },
        beforeSend: function () {
            gerais.bloquear();
        },
        success: function (response) {
            if (response.success == true) {
                gerais.msgToastr(response.msg, 'success');
            } else {
                gerais.msgToastr(response.msg, 'info');

            }
            // Pode redirecionar ou limpar campos aqui
        },
        error: function (xhr) {
            console.error(`Erro ao ${texto.toLowerCase()}:`, xhr);
        },
        complete: function () {
            gerais.desbloquear();
        }
    });
}

function ajaxGet(url, requestData) {
    return $.ajax({
        url: url,
        type: 'GET',
        data: {
            _token: csrfToken,
            data: requestData
        },
        beforeSend: function () {
            gerais.bloquear();
        },
        success: function (response) {
            // já retorna o valor com a promisie do ajax retornada
            //basta somente usar o response desse atributo
        },
        error: function (xhr) {
            console.error(`Erro ao fazer consulta:`, xhr);
        },
        complete: function () {
            gerais.desbloquear();
        }
    });
}

function montaVendaRequest() {
    const clienteId = $('#cliente-select').val();
    const descontoInputVal = $('#desconto').val();
    const descontoPercentual = parseFloat(descontoInputVal.replace('%', '').replace(',', '.')) || 0;

    const formasPagamento = [];

    $('.valor-forma-pagamento').each(function () {
        const $inputValor = $(this);

        const idFormaPagamento = $inputValor.attr('id').replace('valor-forma-pagamento-', '');
        const valorFormatado = $inputValor.val();

        // **AQUI ESTÁ A CORREÇÃO PRINCIPAL:**
        // Reconstrua o ID completo do input de parcelas
        const idCampoParcelasEsperado = `parcela-parcelas-forma-pagamento-${idFormaPagamento}`;
        const $inputParcelas = $(`#${idCampoParcelasEsperado}`);

        console.log(`Buscando input de parcelas com ID: #${idCampoParcelasEsperado}`);
        console.log(`Encontrado $inputParcelas:`, $inputParcelas); // Verifique se ele encontra algo

        let parcelas = 1;
        if ($inputParcelas.length > 0) {
            parcelas = parseInt($inputParcelas.val()) || 1;
        }

        const isCreditoLoja = $inputValor.data('credito-loja') === true;
        const contemParcela = $inputValor.data('contem-parcela') === true;

        formasPagamento.push({
            id: parseInt(idFormaPagamento),
            valor: valorFormatado,
            parcelas: parcelas,
            credito_loja: isCreditoLoja,
            contem_parcela: contemParcela
        });
    });

    const tipo_finalizacao = $('input[name="tipo_finalizacao"]:checked').val();
    return {
        cliente_id: parseInt(clienteId),
        desconto_percentual: descontoPercentual,
        formas_pagamento: formasPagamento,
        tipo_finalizacao: tipo_finalizacao,
    };
}
// Certifique-se de que esta função esteja disponível no seu escopo global ou onde ela será chamada
function montaRequestDevolucaoVenda() {
    const itensQuantidades = [];

    // Itera sobre cada linha da tabela onde há um input de quantidade a devolver
    // Precisamos pegar o ID do item da venda (venda_item_id) e a quantidade informada
    $('#devolucaoItensTableBody tr').each(function () {
        const $row = $(this);
        const $inputQtdDevolver = $row.find('.qtd-devolver');

        // Pega a quantidade a devolver, garantindo que seja um número e que não seja nula
        const quantidadeDevolver = $inputQtdDevolver.val() || 0;

        // Pega o ID do item da venda que está armazenado no atributo data-item-id da linha (<tr>)
        const vendaItemId = $row.data('item-id');

        // Apenas adiciona itens que tiverem uma quantidade maior que zero e não estiverem desabilitados
        if (quantidadeDevolver != 0 && !$inputQtdDevolver.prop('disabled')) {
            itensQuantidades.push({
                venda_item_id: vendaItemId, // O ID do item da venda
                quantidade: quantidadeDevolver // A quantidade a ser devolvida
            });
        }
    });

    // Pega o ID da venda selecionada no dropdown
    const vendaIdSelecionada = $('#venda-devolucao-select').val();

    // Pega o ID da forma de pagamento selecionada
    const formaPagamentoDevolucaoId = $('#forma-pagamento-devolucao-select').val();

    // Pega o motivo da devolução
    const motivoDevolucao = $('#motivo-devolucao-textarea').val();

    return {
        venda_id: vendaIdSelecionada, // O ID da venda que está sendo devolvida
        especie_pagamento_id: formaPagamentoDevolucaoId, // O ID da forma de pagamento para a devolução
        itens_quantidades: JSON.stringify(itensQuantidades), // O array de itens a devolver, convertido para string JSON
        motivo: motivoDevolucao // O motivo da devolução
    };
}

function montaRequestOrcamento() {
    return {
        forma_pagamento: null,
        cliente_id: 1,
        desconto_porcentagem: 10,
        descricao: 'teste'
    };
}


function montaRequestSuprirCaixa() {
    let especie = $('#suprir-especie-caixa').val();
    if (!especie) {
        gerais.msgToastr('Informe a espécie de para suprir o caixa.', 'info');
        return
    }
    let valor = $('#valor-suprir-caixa').val();
    if (!valor || valor == 0 || valor == '') {
        gerais.msgToastr('Informe o valor..', 'info');
        return
    }
    let motivo = $('#observacao-suprir').val();
    return {
        motivo: motivo,
        valor: valor,
        especie_pagamento_id: especie,
    };
}


function montaRequestSangriaCaixa() {

    let especie = $('#especie-sangria').val();
    if (!especie) {
        gerais.msgToastr('Informe a espécie de para sangria do caixa.', 'info');
        return
    }
    let valor = $('#valor-sangria-caixa').val();
    if (!valor || valor == 0 || valor == '') {
        gerais.msgToastr('Informe o valor..', 'info');
        return
    }
    let motivo = $('#motivo-sangria').val();
    return {
        motivo: motivo,
        valor: valor,
        especie_pagamento_id: especie,
    };
}

function montaRequestReceberConta() {
    const vendaParcelas = [];
    let hasValidPayment = false; // Flag para verificar se há pelo menos um pagamento válido

    // Coleta o ID da forma de pagamento selecionada
    const formaPagamentoData = $('#forma-pagamento-recebimento-select').select2('data');
    const formaPagamentoId = formaPagamentoData.length > 0 ? formaPagamentoData[0].id : null;

    // **Validação: Forma de Pagamento Selecionada?**
    if (!formaPagamentoId) {
        gerais.msgToastr('Por favor, selecione uma forma de pagamento.', 'error');
        return null; // Retorna null para indicar que a requisição é inválida
    }

    // Itera sobre cada linha da tabela de parcelas
    $('#recebimentoParcelasTableBody tr').each(function () {
        const $row = $(this);
        const venda_parcela_id = $row.data('item-id'); // Pega o ID da parcela da linha
        const $inputQtdReceber = $row.find('.qtd-receber'); // Encontra o input de quantidade na linha

        let valorReceberCents = gerais.reaisParaCentavos($inputQtdReceber.val());

        // Validação adicional: Se o valor a receber for 0, não inclui na requisição
        if (valorReceberCents > 0) {
            vendaParcelas.push({
                venda_parcela_id: parseInt(venda_parcela_id), // Converte para inteiro
                valor: gerais.centavosParaReais(valorReceberCents) // Mantém no formato "X,XX" para a API
            });
            hasValidPayment = true;
        }
    });

    // **Validação: Algum valor foi inserido para recebimento?**
    if (!hasValidPayment) {
        gerais.msgToastr('Por favor, insira um valor válido para receber em pelo menos uma parcela.', 'error');
        return null; // Retorna null para indicar que a requisição é inválida
    }

    // Assume que você tem um input/textarea para a observação
    const observacao = $('#observacao-recebimento-textarea').val() || ''; // Pega o valor da observação ou string vazia

    return {
        observacao: observacao,
        venda_parcelas: vendaParcelas,
        forma_pagamento: parseInt(formaPagamentoId) // Converte para inteiro
    };
}

function montaRequestFecharCaixa() {
    return {
        observacao: 'Fechamento de caixa',
        total_dinheiro: '1.200,00',
    };
}

function montaRequestAdicionarItem() {
    // Pega o array de dados do Select2
    let selectedData = $('#produto-select').select2('data');
    if (selectedData.length === 0 || !selectedData[0].id) {
        gerais.msgToastr('Por favor, selecione um produto antes de adicionar o item.', 'info')
        $('#produto-select').select2('open'); // Tenta focar o Select2 novamente
        return null; // Sai da função
    }
    // Verifica se há algum item selecionado
    if (selectedData.length > 0) {
        // Pega o primeiro (e único) item do array, já que é uma seleção única
        let produtoSelecionado = selectedData[0];

        // Agora você pode acessar todas as propriedades que você mapeou no processResults
        let estoqueId = produtoSelecionado.id;

        let quantidadeInputVal = $('#quantidade').val();
        let quantidade = parseFloat(quantidadeInputVal.replace(',', '.')) || 0; // Converte para float, lida com vírgula e garante 0 se inválido

        if (quantidadeInputVal.trim() === '' || isNaN(quantidade) || quantidade <= 0) {
            gerais.msgToastr('Por favor, insira uma quantidade válida e maior que zero.', 'info')
            $('#quantidade').focus(); // Foca o campo de quantidade
            return null; // Sai da função
        }
        // Agora você pode usar esses dados para montar o seu objeto de requisição (request body)
        // Por exemplo, para um item de venda:
        let requestBody = {
            estoqueId: estoqueId,
            quantidade: quantidade
        }

        return requestBody;

    } else {
        gerais.msgToastr('Nenhum produto selecionado.', 'info');
        $('#produto-select').focus();
        return null; // Retorna null ou um objeto vazio se nada estiver selecionado
    }
}
/**
 * testes
 */
// $('#finalizaVenda').on('click', function () {
//     ajaxPost(rotas.finalizarVenda, montaVendaRequest(), 'Finalizar Venda');
// });

// $('#devolucaoVenda').on('click', function () {
//     ajaxPost(rotas.devolucaoVenda, montaRequestDevolucaoVenda(), 'Devolução Venda');
// });

// $('#orcamentoVenda').on('click', function () {
//     ajaxPost(rotas.orcamentoVenda, montaRequestOrcamento(), 'Orçamento Venda');
// });

// $('#suprirCaixa').on('click', function () {
//     ajaxPost(rotas.suprirCaixa, montaRequestSuprirCaixa(), 'Surpir caixa');
// });

// $('#sangriaCaixa').on('click', function () {
//     ajaxPost(rotas.sangriaCaixa, montaRequestSangriaCaixa(), 'Sangria caixa');
// });

// $('#receberConta').on('click', function () {
//     ajaxPost(rotas.rotaReceberConta, montaRequestReceberConta(), 'Recebeu conta');
// });

// $('#fecharCaixa').on('click', function () {
//     ajaxPost(rotas.rotaFecharCaixa, montaRequestFecharCaixa(), 'Fechou caixa');
// });



//-----------------parte da seleção de produtos ------------//

$('#quantidade').val(1);//define o vlaor da quantidade como um padrão
// Certifique-se de que esta função seja chamada quando o DOM estiver pronto
gerais.maskQtdByClass('maskQtdByClass');
gerais.maskDinheiro('valor-unitario');
gerais.maskPorcentagem('desconto');
// HTML do cabeçalho que será injetado na lista do Select2
const select2HeaderHtml = `
            <div class="select2-columns-header-internal d-flex justify-content-between align-items-center">
                <div style="flex: 1; text-align: center;">Cód. Aux</div>
                <div style="flex: 3;">Produto</div>
                <div style="flex: 1; text-align: center;">Estoque</div>
                <div style="flex: 1; text-align: right;">Preço</div>
            </div>
        `;

// Função para formatar o resultado na lista suspensa
function formatProductResult(product) {
    if (!product.id) {
        return product.text; // Retorna o texto padrão para a opção "Selecione um produto..."
    }

    const $container = $(
        `<div class="d-flex justify-content-between align-items-center">
                    <div style="flex: 1; text-align: center;">${product.cod_aux || '0'}</div>
            <div style="flex: 3; /* REMOVIDAS: overflow: hidden; text-overflow: ellipsis; white-space: nowrap; */">${product.text || 'N/A'}</div>
                    <div style="flex: 1; text-align: center;">${product.qtd_estoque || '0'}</div>
                    <div style="flex: 1; text-align: right;">R$ ${gerais.centavosParaReais(product.preco || 0)}</div>
                </div>`
    );
    return $container;
}

// Função para formatar o item selecionado (o que aparece na caixa do Select2)
function formatProductSelection(product) {
    return product.text;
}

$('#produto-select').select2({
    placeholder: "Selecione um produto...",
    allowClear: true,
    language: "pt-BR",
    ajax: {
        url: rotas.rotaBuscaProduto,
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.id,
                        text: item.nome + ' - ' + item.fabricante_nome + ' - ' + item.sigla,
                        qtd_estoque: item.quantidade_disponivel,
                        preco: item.preco,
                        cod_aux: item.cod_aux
                    }
                })
            };
        },
        cache: true
    },
    templateResult: formatProductResult,
    templateSelection: formatProductSelection,
    dropdownParent: $('body') // Garante que o dropdown é anexado ao body para evitar problemas de z-index ou corte
});

// Evento para quando o Select2 é aberto
$('#produto-select').on('select2:open', function () {
    const $dropdown = $('.select2-container--open .select2-dropdown');
    const $searchField = $dropdown.find('.select2-search__field');
    const $results = $dropdown.find('.select2-results__options');

    // Verifica se o cabeçalho já existe para não duplicar
    if ($dropdown.find('.select2-columns-header-internal').length === 0) {
        // Insere o cabeçalho logo após o campo de busca
        $searchField.closest('.select2-search').after(select2HeaderHtml);
    }
});

setTimeout(function () {
    // $('#produto-select').select2('open');
}, 500);

// Evento disparado quando um item é selecionado
$('#produto-select').on('select2:select', function (e) {
    // O objeto 'e.params.data' contém todas as propriedades do item selecionado
    const selectedProduct = e.params.data;
    let quantidade = $('#quantidade').val();
    $('#valor-unitario').val(gerais.centavosParaReais(selectedProduct.preco)).data('preco', selectedProduct.preco);
    let totalItem = quantidade * selectedProduct.preco;
    $('#total-item').val(gerais.centavosParaReais(totalItem));
    $('#quantidade').focus();

});

$('#quantidade').on('blur', function () {
    calcularTotalItem();
    // calcularTotalVendaComDesconto()
});

$('#quantidade').on('keydown', function (e) {
    // Verifica se a tecla pressionada é 'Enter' (keyCode 13)
    if (e.which === 13 || e.keyCode === 13) {
        e.preventDefault(); // Impede a submissão padrão do formulário ou outros comportamentos indesejados

        calcularTotalItem(); // Executa o cálculo, como você já faz

        $('#adicionarItemButton').focus();
    }
});

$('#adicionarItemButton').on('click', adicionarItem)

async function adicionarItem() {
    const data = montaRequestAdicionarItem();
    if (!data) {
        return;
    }
    ajaxPost(rotas.rotaAdicionarItem, data, 'Item adicionado com sucesso!').done(function (response) {
        $('#total-venda').data('total', response.total);
        calcularTotalVendaComDesconto();
        renderizarTabelaItensVenda(response.itens);
        $('#produto-select').val(null).trigger('change');
        $('#produto-select').select2('open');
        $('#valor-unitario').val('0.00');
        $('#quantidade').val(1);
        $('#total-item').val('0.00');
    }).fail(function (error) {
        // Código de erro (sua função ajaxPost já deve lidar com alertas de erro)
        console.error('Erro na requisição AJAX ao adicionar item:', error);
    });

}
//quando é selecionado o item
function calcularTotalItem() {
    // Garante que os valores são números e lida com casos vazios ou não numéricos
    const quantidade = parseFloat($('#quantidade').val().replace(',', '.')) || 0; // Substitui vírgula por ponto para parse
    const valorUnitario = $('#valor-unitario').data('preco');

    const totalItem = quantidade * valorUnitario;

    // Atualiza o campo "Total do Item" com duas casas decimais
    $('#total-item').val(gerais.centavosParaReais(totalItem));
}
//valor final da venda com todos os itens
$('#desconto').on('input', function () {
    calcularTotalVendaComDesconto();
});
function calcularTotalVendaComDesconto() {

    // 1. Converte o total bruto de centavos para reais (float)
    let totalBrutoReais = $('#total-venda').data('total') / 100;

    // Seus seletores de input
    const $descontoInput = $('#desconto'); // Corrigido para ID: #desconto
    const $totalVendaDisplay = $('#total-venda');
    const $descontoReaisDisplay = $('#desconto-reais'); // Assumindo que este é o elemento para exibir o valor em reais

    let descontoInputVal = $descontoInput.val();
    let descontoDigitado = 0; // Inicializa com 0
    let valorDescontoReais = 0;
    let totalComDescontoReais = totalBrutoReais; // Começa com o total bruto em reais

    // Verifica se há algo digitado no campo de desconto e processa
    if (descontoInputVal) {
        // Converte o valor de desconto digitado (que está em reais formatado com vírgula) para float
        descontoDigitado = parseFloat(descontoInputVal.replace(',', '.')) || 0;

        // Lógica para aplicar o desconto (assumindo que #desconto é uma porcentagem)
        if (descontoDigitado > 0 && descontoDigitado <= 100) {
            valorDescontoReais = (totalBrutoReais * (descontoDigitado / 100));
            totalComDescontoReais = totalBrutoReais - valorDescontoReais;
        } else if (descontoDigitado < 0) {
            // Se o desconto é negativo, reseta para 0
            valorDescontoReais = 0;
            totalComDescontoReais = totalBrutoReais;


            $descontoInput.val('0,00'); // Reseta o input de desconto para '0,00'
        } else if (descontoDigitado > 100) {
            gerais.msgToastr("Desconto não pode ser maior que 100%.", 'info');
            $('#desconto').val('').change();

            return
        }
        // Se descontoDigitado for 0, as variáveis já estão corretas (valorDescontoReais = 0, totalComDescontoReais = totalBrutoReais).
    } else {
        // Se o campo de desconto estiver vazio, o desconto é 0
        valorDescontoReais = 0;
        totalComDescontoReais = totalBrutoReais;
    }

    totalComDescontoReais = totalComDescontoReais * 100;
    valorDescontoReais = valorDescontoReais * 100;
    $descontoReaisDisplay.text('R$ ' + gerais.centavosParaReais(valorDescontoReais));

    // Atualiza o display do total final da venda
    $totalVendaDisplay.val(gerais.centavosParaReais(totalComDescontoReais));

    // Retorna o valor final do total da venda em reais (float)
    return totalComDescontoReais;
}

montaTabelaItensCaixa();
function montaTabelaItensCaixa() {
    const itens = $('#dataView').data('caixaItensTemp');
    const total = $('#dataView').data('caixaItensTempTotal');
    // exite item e monta a tabela
    renderizarTabelaItensVenda(itens);
    $('#total-venda').data('total', total)

    if (total != 0) {
        calcularTotalVendaComDesconto();
    }

}
function renderizarTabelaItensVenda(items) {
    // Seletores da tabela
    const $itensVendaTableBody = $('#itensVendaTableBody');
    const $noItemsMessage = $('#noItemsMessage'); // Mensagem de "Nenhum item adicionado"
    $itensVendaTableBody.empty(); // Limpa o corpo da tabela antes de adicionar novos itens
    if (items && items.length > 0) {
        $noItemsMessage.hide(); // Esconde a mensagem "Nenhum item"
        items.forEach(item => {
            // Extrai e formata os dados
            const codAux = item.produto ? item.produto.cod_aux : 'N/A';
            const nomeProduto = item.produto ? item.produto.nome : 'Produto Desconhecido';
            // Quantidade vem como string "1.000", precisa converter para float e formatar se necessário.
            const quantidade = parseFloat(item.quantidade.replace(',', '.')) || 0;
            // Preço e total vêm em centavos, precisa dividir por 100 e formatar para reais.
            const precoUnitarioReais = gerais.centavosParaReais(item.preco);
            const totalItemReais = gerais.centavosParaReais(item.total);

            const row = `
                        <tr>
                            <td>${codAux}</td>
                            <td>${nomeProduto}</td>
                            <td class="text-center">${quantidade}</td>
                            <td class="text-right">R$ ${precoUnitarioReais}</td>
                            <td class="text-right">R$ ${totalItemReais}</td>
                            <td>
                                <button type="button" data-temp="${item.id}" class="btn btn-danger btn-sm" title="Remover Item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
            $itensVendaTableBody.append(row);
        });
    } else {
        $noItemsMessage.show(); // Mostra a mensagem "Nenhum item"
    }
}

//------REMOVER ITEM----------//
$('#itensVendaTableBody').on('click', '.btn-danger[data-temp]', function () {
    const idTemp = $(this).data('temp');
    const isMasterCaixa = $('#dataView').data('isMasterCaixa');

    // Faz a pergunta de senha padrão
    if (!isMasterCaixa) {
        // Passa a ação e os parâmetros para validaSuperior
        validaSuperior('remover_item_temp', idTemp);
    } else {
        // Se não for MasterCaixa, remove diretamente
        removeItemTemp(idTemp);
    }
});

function removeItemTemp(idItemTemp) {
    ajaxPost(rotas.rotaRemoverItem, { id: idItemTemp }).done(function (response) {
        $('#total-venda').data('total', response.total);
        calcularTotalVendaComDesconto();
        renderizarTabelaItensVenda(response.itens);
        $('#produto-select').val(null).trigger('change');
        $('#produto-select').select2('open');
        $('#valor-unitario').val('0.00');
        $('#quantidade').val(1);
        $('#total-item').val('0.00');
    }).fail(function (error) {
        // Código de erro (sua função ajaxPost já deve lidar com alertas de erro)
        console.error('Erro na requisição AJAX ao adicionar item:', error);
    });;
}

//-----------SOLICITACAO SUBPERVISOR-----------//
gerais.constructSelect2('supervisor-select', rotas.rotaSupervisores, 'modalSenhaSuperior');
$('#supervisor-select').on('select2:select', function (e) {
    // Quando um item é selecionado no Select2, foca no input de senha
    $('#senhaSuperior').focus();
});

function validaSuperior(proximaAcao, parametros) {
    modalSolicitarSenha('show', proximaAcao, parametros);
}

function modalSolicitarSenha(showOrHide, proximaAcao, parametros) {
    $('#modalSenhaSuperior').modal(showOrHide);
    $('#formSenhaSuperior').data('proximaAcao', proximaAcao);
    $('#formSenhaSuperior').data('proximaAcaoParametros', parametros);

    if (showOrHide === 'show') {
        $('#modalSenhaSuperior').one('shown.bs.modal', function () {
            // Agora o modal está totalmente visível, é seguro abrir o Select2
            $('#supervisor-select').select2('open');
        });
    }

    if (showOrHide === 'show') {
        $('#mensagemErroSenha').hide().text('');
        $('#senhaSuperior').val('');
    }
}

$('#formSenhaSuperior').on('submit', function (e) {
    e.preventDefault();

    let usuario = $('#supervisor-select').val();
    let senha = $('#senhaSuperior').val();
    const data = {
        usuario_id: usuario,
        senha: senha
    }
    let proximaAcao = $(this).data('proximaAcao');
    let proximaAcaoParametros = $(this).data('proximaAcaoParametros');

    ajaxPost(rotas.validarSuperior, data).done(function (response) {

        if (response.success == true) {
            modalSolicitarSenha('hide');
            executaProximaAcao(proximaAcao, proximaAcaoParametros)
        }
    }).fail(function (error) {
        // Código de erro (sua função ajaxPost já deve lidar com alertas de erro)
        console.error('Erro na requisição AJAX ao adicionar item:', error);
    });
});

function executaProximaAcao(acao, parametros) {

    switch (acao) {
        case 'remover_item_temp':
            removeItemTemp(parametros);
            break;
        case 'devolucao_venda':
            devolucaoVenda();
            break;
        case 'excluir_orcamento':
            excluir_orcamento(parametros);
            break;
        case 'suprir_caixa':
            suprirCaixa();
            break;
        case 'sangria_caixa':
            sangriaCaixa();
            break;
        default:
            break;
    }
}

//----------FINALIZAR VENDA------------//
gerais.constructSelect2('cliente-select', rotas.clientesGet);
// gerais.constructSelect2('forma-pagamento-select', rotas.formasPagamentoGet);
$('#forma-pagamento-select').select2({
    placeholder: "Selecione as formas de pagamento...",
    allowClear: true,
    language: "pt-BR",
    ajax: {
        url: rotas.formasPagamentoGet,
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.id,
                        text: item.descricao,
                        contem_parcela: item.especie.contem_parcela,
                        troco: item.especie.afeta_troco,
                        credito_loja: item.especie.credito_loja,
                    }
                })
            };
        },
        cache: true
    },
    templateSelection: formatProductSelection,
    dropdownParent: $('body') // Garante que o dropdown é anexado ao body para evitar problemas de z-index ou corte
});

$('#cliente-select').on('select2:select', function (e) {
    // Quando um cliente é selecionado, focamos no Select2 de formas de pagamento.
    // Usamos 'open' para abrir o dropdown automaticamente.
    $('#fcorma-pagamento-select').val(null).trigger('change'); // Limpa seleção anterior, se houver

    $('#forma-pagamento-select').select2('open');
});

// --- Listener para o Select2 de Formas de Pagamento ---
$('#forma-pagamento-select').on('change', function () {
    const $formasPagamentoValoresContainer = $('#formasPagamentoValores');
    $formasPagamentoValoresContainer.empty();

    const selectedFormsOfPayment = $(this).select2('data');
    const creditoLojaItem = selectedFormsOfPayment.find(forma => forma.credito_loja);
    const isCreditoLojaSelected = !!creditoLojaItem;

    if (isCreditoLojaSelected && selectedFormsOfPayment.length > 1) {
        $(this).val(null).trigger('change');
        gerais.msgToastr('Forma de pagamento em crédito loja não permite outras formas por questões de segurança.', 'info');
        return;
    }

    if (selectedFormsOfPayment.length > 0) {
        selectedFormsOfPayment.forEach(forma => {
            const inputValorId = `valor-forma-pagamento-${forma.id}`;
            const inputParcelasId = `parcelas-forma-pagamento-${forma.id}`;
            const trocoInputId = `troco-${forma.id}`;

            let inputHtml = `
                <div class="form-group d-flex align-items-end mb-2">
                    <div class="flex-grow-1 mr-2">
                        <label for="${inputValorId}">${forma.text} (Valor)</label>
                        <input type="text"
                               class="form-control valor-forma-pagamento"
                               id="${inputValorId}"
                               name="formas_pagamento_valores[${forma.id}][valor]"
                               placeholder="R$ 0,00"
                               ${forma.credito_loja ? 'readonly' : ''}
                               required
                               data-contem-troco="${forma.troco ? true : false}"
                               data-forma-id="${forma.id}"
                               data-credito-loja="${forma.credito_loja ? true : false}"
                               data-contem-parcela="${forma.contem_parcela ? true : false}">
                    </div>
            `;

            if (forma.troco) {
                inputHtml += `
                    <div style="width: 100px;">
                        <label for="${trocoInputId}">Troco</label>
                        <input type="text"
                               class="form-control troco-display"
                               id="${trocoInputId}"
                               placeholder="Troco"
                               value="R$ 0,00"
                               disabled>
                    </div>
                `;
            }

            if (forma.contem_parcela) {
                inputHtml += `
                    <div style="width: 100px;">
                        <label for="parcela-${inputParcelasId}">Parcelas</label>
                        <input type="number"
                               class="form-control parcelas-forma-pagamento"
                               id="parcela-${inputParcelasId}"
                               name="formas_pagamento_valores[${forma.id}][parcelas]"
                               placeholder="1"
                               value="1"
                               min="1"
                               required>
                    </div>
                `;
            }

            inputHtml += `</div>`;

            $formasPagamentoValoresContainer.append(inputHtml);

            gerais.maskDinheiro(inputValorId);
            if (forma.troco) {
                gerais.maskDinheiro(trocoInputId);
            }
        });

        $('.valor-forma-pagamento').off('input', distribuirValoresRestantes);
        $('.valor-forma-pagamento').on('input', distribuirValoresRestantes);
    }

    if (selectedFormsOfPayment.length === 1) {
        const unicaFormaPagamento = selectedFormsOfPayment[0];
        const inputValorUnicoId = `valor-forma-pagamento-${unicaFormaPagamento.id}`;
        const $inputValorUnico = $(`#${inputValorUnicoId}`);

        let totalComDescontoCents = calcularTotalVendaComDesconto();
        const totalVendaFormatado = gerais.centavosParaReais(totalComDescontoCents);

        $inputValorUnico.val(totalVendaFormatado);
        gerais.maskDinheiro(inputValorUnicoId);

        // Se a forma única tem 'contem_parcela' (o que pode ser o caso do Crédito Loja), adiciona o campo de parcelas
        if (unicaFormaPagamento.contem_parcela) {
            const inputParcelasUnicoId = `parcelas-forma-pagamento-${unicaFormaPagamento.id}`;
            if ($(`#parcela-${inputParcelasUnicoId}`).length === 0) {
                const inputParcelasHtml = `
                    <div style="width: 100px;">
                        <label for="parcela-${inputParcelasUnicoId}">Parcelas</label>
                        <input type="number"
                               class="form-control parcelas-forma-pagamento"
                               id="parcela-${inputParcelasUnicoId}"
                               name="formas_pagamento_valores[${unicaFormaPagamento.id}][parcelas]"
                               placeholder="1"
                               value="1"
                               min="1"
                               required>
                    </div>
                 `;
                // Usamos .parent().after() para garantir que as parcelas fiquem na mesma linha do valor
                $inputValorUnico.closest('.form-group.d-flex').append(inputParcelasHtml);
            }
        }

        if (unicaFormaPagamento.troco) {
            const totalPagoCents = gerais.reaisParaCentavos($inputValorUnico.val());
            const trocoCents = totalPagoCents - totalComDescontoCents;
            const trocoInputId = `troco-${unicaFormaPagamento.id}`;
            if (trocoCents > 0) {
                $(`#${trocoInputId}`).val('R$ ' + gerais.centavosParaReais(trocoCents));
            } else {
                $(`#${trocoInputId}`).val('R$ 0,00');
            }
        }

        setTimeout(function () {
            $('#buttonSubmitFormFinalizarVenda').focus();
        }, 10);
    }
});

// --- Função para Distribuir os Valores Restantes e Calcular Troco ---
function distribuirValoresRestantes() {
    const totalVendaComDescontoCents = calcularTotalVendaComDesconto();
    let valorJaPreenchidoSequentialCents = 0; // Para preenchimento sequencial
    let totalPagoEmFormasCents = 0; // Para calcular o total pago para o troco

    const $valorInputs = $('.valor-forma-pagamento');
    let inputQueDisparouEventoIndex = -1;
    let inputDinheiroTrocoId = null; // ID do input de valor da forma de pagamento que permite troco
    let trocoInputId = null; // ID do input de troco (o disabled)

    // Primeira iteração: Identifica o input que disparou o evento, soma valores e identifica o input de dinheiro
    $valorInputs.each(function (index) {
        const $currentInput = $(this);
        const currentInputValueCents = gerais.reaisParaCentavos($currentInput.val());

        totalPagoEmFormasCents += currentInputValueCents; // Soma todos os valores preenchidos para o cálculo do troco total

        // Verifica se é o input que disparou o evento
        if ($currentInput[0] === event.target) {
            inputQueDisparouEventoIndex = index;
        }

        // Verifica se esta forma de pagamento pode gerar troco
        if ($currentInput.data('contem-troco') === true) { // O data-attribute armazena como booleano
            inputDinheiroTrocoId = $currentInput.attr('id');
            // O ID do input de troco é 'troco-' seguido do ID da forma de pagamento
            trocoInputId = `troco-${$currentInput.data('forma-id')}`;
        }
    });

    // Reset o valor preenchido para a lógica sequencial (distribuir o restante)
    valorJaPreenchidoSequentialCents = 0;
    $valorInputs.each(function (index) {
        const $currentInput = $(this);
        const currentInputValueCents = gerais.reaisParaCentavos($currentInput.val());

        if (index < inputQueDisparouEventoIndex) {
            valorJaPreenchidoSequentialCents += currentInputValueCents;
        }
    });
    // Adiciona o valor do input que disparou o evento para a soma sequencial
    valorJaPreenchidoSequentialCents += gerais.reaisParaCentavos($($valorInputs[inputQueDisparouEventoIndex]).val());


    // Segunda iteração: Preenche os inputs restantes sequencialmente
    if (inputQueDisparouEventoIndex !== -1) {
        $valorInputs.each(function (index) {
            if (index > inputQueDisparouEventoIndex) {
                const $nextInput = $(this);
                const valorAtualNextInputCents = gerais.reaisParaCentavos($nextInput.val());

                const remainingCents = totalVendaComDescontoCents - valorJaPreenchidoSequentialCents;
                const valueToSet = Math.max(0, remainingCents);

                // Só preenche o próximo se o valor atual for zero E ainda houver restante a ser pago
                if (valorAtualNextInputCents === 0 && remainingCents > 0) {
                    const formattedValue = gerais.centavosParaReais(valueToSet);
                    $nextInput.val(formattedValue);
                    gerais.maskDinheiro($nextInput.attr('id'));
                }
                // Adiciona o valor que foi preenchido (ou já estava lá) para o próximo cálculo sequencial
                valorJaPreenchidoSequentialCents += gerais.reaisParaCentavos($nextInput.val());
            }
        });
    }

    // --- Cálculo e Exibição do Troco ---
    const trocoCents = totalPagoEmFormasCents - totalVendaComDescontoCents;
    if (trocoInputId && trocoInputId !== null) {
        const $trocoInput = $(`#${trocoInputId}`);
        if ($trocoInput.length) { // Garante que o input de troco existe
            if (trocoCents > 0) {
                $trocoInput.val('R$ ' + gerais.centavosParaReais(trocoCents));
            } else {
                $trocoInput.val('R$ 0,00');
            }
        }
    }
}


$('#formFinalizarVenda').on('submit', function (e) {
    e.preventDefault();
    const request = montaVendaRequest();
    if (!request) {
        return;
    }
    let rotaExecutar = rotas.finalizarVenda;

    if (request.tipo_finalizacao == 'orcamento') {
        rotaExecutar = rotas.orcamentoVenda
    }
    ajaxPost(rotaExecutar, request).done(function (response) {

        if (response.success == true) {
            closeSidebar();
            resetFinalizarVendaForm();
            $('#total-venda').data('total', response.total);
            calcularTotalVendaComDesconto();
            renderizarTabelaItensVenda(response.itens);
            $('#produto-select').val(null).trigger('change');
            $('#produto-select').select2('open');
            $('#valor-unitario').val('0.00');
            $('#quantidade').val(1);
            $('#total-item').val('0.00');
        }
    });
});

function resetFinalizarVendaForm() {
    // 1. Resetar o Select2 de Cliente
    $('#cliente-select').val(null).trigger('change'); // Limpa a seleção e dispara o evento de mudança

    // 2. Resetar o Select2 de Formas de Pagamento
    $('#forma-pagamento-select').val(null).trigger('change'); // Limpa a seleção e dispara o evento de mudança
    // O evento 'change' acima já vai esvaziar #formasPagamentoValores

    // 3. Resetar o input de Desconto (se existir e tiver um ID)
    // Se o seu input de desconto tiver um ID, adicione aqui. Por exemplo, se for `#desconto`:
    $('#desconto').val('0,00'); // Ou o valor padrão que você usa

    // 4. Resetar as opções de Tipo de Finalização (Radio Buttons)
    // Seleciona novamente o "Emitir Cupom Fiscal" (ou qual for seu padrão)
    $('input[name="tipo_finalizacao"][value="cupom"]').prop('checked', true); // Marca o radio button desejado
}

//-----------------ORCAMENTO------------//
gerais.constructSelect2('orcamento-select', rotas.rotaOrcamento);

$('#buttonExcluirOrcamento').on('click', function () {
    let orcamentoId = $('#orcamento-select').val();
    if (!orcamentoId) {
        gerais.msgToastr('Selecione um orçamento.', 'info');
        return;
    }
    const isMasterCaixa = $('#dataView').data('isMasterCaixa');

    // Faz a pergunta de senha padrão
    if (!isMasterCaixa) {
        validaSuperior('excluir_orcamento', orcamentoId);

    } else {
        excluir_orcamento(orcamentoId);
    }
});

function excluir_orcamento(orcamentoId) {
    ajaxPost(rotas.excluirOrcamento, { orcamentoId: orcamentoId }).done(function (response) {
        if (response.success) {
            closeSidebar();
        }
    });
}

$('#orcamento-select').on('select2:select', function (e) {
    const orcamentoId = e.params.data.id;
    const text = e.params.data.text;

    const attrs = e.params.data.attrs;

    if (orcamentoId) {
        $('#ocamentoText').text(`Itens no orçamento`);

        ajaxGet(rotas.rotaOrcamentoGet, { orcamentoId }).done(function (response) {

            if (response.success) {
                renderizarTabelaItensOrcamento(response.orcamento);

                // Garante que a sidebar principal continue aberta
                if (!$('#orcamentoSidebar').hasClass('open')) {
                    $('#orcamentoSidebar').addClass('open');
                }

                // Abre a sidebar filha à direita da principal
                $('#orcamentoItensSidebar').addClass('open');
            }
        });
    }
});

function renderizarTabelaItensOrcamento(orcamento) {
    const tbody = $('#orcamentoItensTableBody');
    tbody.empty(); // Limpa antes de adicionar novos

    orcamento.orcamento_itens.forEach(item => {
        const preco = parseFloat(item.preco || 0);
        const quantidade = parseFloat(item.quantidade || 0); // Quantidade disponível para devolução
        const total = parseFloat(item.total || 0);

        const linha = `
            <tr data-item-id="${item.id}" data-estoque-id="${item.estoque_id}">
                <td>${item.estoque.produto.cod_aux}</td>
                <td>${gerais.montaNomeProduto(item.estoque.produto)}</td>
                <td class="text-center">${quantidade}</td>
                <td>R$ ${gerais.centavosParaReais(preco)}</td>
                <td>R$ ${gerais.centavosParaReais(total)}</td>
            </tr>
        `;
        tbody.append(linha);
    });
    $('#valor-total-orcamento').val(gerais.centavosParaReais(orcamento.total));
    $('#valor-total-desconto-orcamento').val(orcamento.desconto_porcentagem + '%');
    $('#desconto-orcamento-reais').text('R$ ' + gerais.centavosParaReais(orcamento.desconto_dinheiro));
}

$('#colocarOrcamentoEmVenda').on('click', function () {
    let orcamentoId = $('#orcamento-select').val();
    if (!orcamentoId) {
        gerais.msgToastr('Selecione um orçamento.', 'info');
        return
    }

    ajaxPost(rotas.rotaColocarOrcamentoEmVenda, { orcamentoId: orcamentoId }).done(function (response) {
        if (response.success) {
            $('#total-venda').data('total', response.total);
            $('#desconto').val(response.orcamento.desconto_porcentagem);

            calcularTotalVendaComDesconto();
            renderizarTabelaItensVenda(response.itens);
            closeSidebar();
        }
    });
})

//------------------DEVOLUCAO-----------//
gerais.constructSelect2('venda-devolucao-select', rotas.rotaVendasDevolucaoGet);

$('#venda-devolucao-select').on('select2:select', function (e) {
    const vendaId = e.params.data.id;
    const text = e.params.data.text;

    const attrs = e.params.data.attrs;

    if (vendaId) {
        $('#vendaNumeroDevolucao').text(`Seleção de itens`);

        ajaxGet(rotas.rotaVendaDevolverGet, { vendaId }).done(function (response) {

            if (response.success) {
                renderizarTabelaItensDevolucao(response.venda);

                // Garante que a sidebar principal continue aberta
                if (!$('#devolucaoSidebar').hasClass('open')) {
                    $('#devolucaoSidebar').addClass('open');
                }

                // Abre a sidebar filha à direita da principal
                $('#devolucaoItensSidebar').addClass('open');
            }
        });
    }
});
function renderizarTabelaItensDevolucao(venda) {
    const tbody = $('#devolucaoItensTableBody');
    tbody.empty(); // Limpa antes de adicionar novos

    venda.venda_itens.forEach(item => {
        const preco = parseFloat(item.preco || 0);
        const quantidade = parseFloat(item.quantidade || 0); // Quantidade disponível para devolução
        const total = preco * quantidade;
        const totalQuantidadeDevolvida = item.devolucao_itens.reduce((acumulador, itemAtual) => {
            return acumulador + (+itemAtual.quantidade);
        }, 0);

        const linha = `
            <tr data-item-id="${item.id}" data-estoque-id="${item.estoque_id}">
                <td>${item.estoque.produto.cod_aux}</td>
                <td>${gerais.montaNomeProduto(item.estoque.produto)}</td>
                <td class="text-center">${quantidade}</td>
                <td>R$ ${gerais.centavosParaReais(preco)}</td>
                <td>R$ ${gerais.centavosParaReais(total)}</td>
                <td>${totalQuantidadeDevolvida}</td>
                <td>
                    <input type="text" class="form-control form-control-sm qtd-devolver"
                           data-preco-unitario="${preco}"
                           data-quantidade-max="${quantidade}"
                           min="0" max="${quantidade}" value="0">
                </td>
            </tr>
        `;
        tbody.append(linha);
    });
    $('#valor-total-venda-devolucao').val(gerais.centavosParaReais(venda.total));
    $('#valor-total-desconto-devolucao').val(venda.desconto_porcentagem + '%');
    $('#desconto-devolucao-reais').text('R$ ' + gerais.centavosParaReais(venda.desconto_dinheiro));

    let idCreditoLoja = $('#dataView').data('especieCreditoLoja');
    if (venda && venda.venda_pagamentos) {
        const existePagamentoEspecieCreditoLoja = venda.venda_pagamentos.some(pagamento => {
            // Acessa o ID da espécie de pagamento dentro de especie_pagamento

            return pagamento.especie_pagamento && pagamento.especie_pagamento.id == idCreditoLoja;
        });

        if (existePagamentoEspecieCreditoLoja) {
            console.log(idCreditoLoja, existePagamentoEspecieCreditoLoja);

            $('#forma-pagamento-devolucao-select').val(idCreditoLoja).trigger('change');
            $('#forma-pagamento-devolucao-select').prop('disabled', true);

            $('#aviso-especie-devolucao').text('Venda com crédito em loja, o valor será adicionado ao crédito do cliente, devolução somente na mesma forma pagamento da venda.')
            // Coloque aqui a lógica que você deseja executar quando encontrar o pagamento
        } else {
            $('#forma-pagamento-devolucao-select').prop('disabled', false);
            $('#aviso-especie-devolucao').text('');

        }
    } else {
        console.log("O objeto 'venda' ou 'venda_pagamentos' não está definido.");
    }
    gerais.maskQtdByClass('qtd-devolver');

    // Attach the event listener to all quantity input fields
    // Use 'input' for real-time validation as the user types, and 'blur' for final calculation
    $('.qtd-devolver').on('blur', function () {
        // Agora, dentro desta função anônima, 'this' ainda se refere ao input que disparou o evento.
        // Você chama handleQuantityInput e passa o parâmetro necessário.
        handleQuantityInput.call(this, venda.desconto_porcentagem);
    });
    // Initial calculation (useful if you pre-fill values)
    // calcularTotalDevolucao();
}

function handleQuantityInput(desconto_porcentagem) {
    const $input = $(this);
    let quantidadeDevolver = parseFloat($input.val()) || 0;
    const quantidadeMax = parseFloat($input.data('quantidade-max')) || 0;

    // 1. Prevent entering more than available quantity
    if (quantidadeDevolver > quantidadeMax) {
        quantidadeDevolver = quantidadeMax;
        $input.val(quantidadeMax); // Set the input value to the maximum allowed
        // Optionally, provide user feedback, e.g., a toast message or alert
        // alert(`You can't return more than ${quantidadeMax} items.`);
        gerais.msgToastr('Quantidade máxima para devolucao:' + quantidadeMax, 'info');
    }

    // 2. Disable input if quantity is zero
    if (quantidadeMax === 0) {
        $input.prop('disabled', true);
        $input.val(0); // Ensure the value is 0 if disabled
    }

    // Recalculate total after validation
    calcularTotalDevolucao(desconto_porcentagem);
}

function calcularTotalDevolucao(descontoPorcentagemVenda) { // Recebe a porcentagem de desconto como parâmetro
    let subtotalDevolucao = 0; // Este será o total dos itens a devolver, sem o desconto da venda

    $('.qtd-devolver').each(function () {
        // Considera apenas inputs que não estão desabilitados
        if (!$(this).prop('disabled')) {
            const quantidadeDevolver = gerais.stringParaFloat($(this).val()) || 0;
            console.log("Quantidade Devolver (float):", quantidadeDevolver, "Valor do input:", $(this).val());

            const precoUnitario = parseFloat($(this).data('preco-unitario')) || 0;
            subtotalDevolucao += quantidadeDevolver * precoUnitario;
        }
    });

    // Converte a porcentagem de desconto da venda (ex: "5.00") para um fator decimal (ex: 0.05)
    // Usamos parseFloat para garantir que seja um número, mesmo que venha como string.
    const fatorDesconto = parseFloat(descontoPorcentagemVenda || 0) / 100;

    // Aplica o desconto ao subtotal da devolução
    // Se a venda teve 5% de desconto, o valor a devolver será 95% do subtotal de devolução
    const totalLiquidoDevolucao = subtotalDevolucao * (1 - fatorDesconto);

    // Atualiza o campo de exibição do valor total a devolver
    $('#valor-total-devolucao').val('R$ ' + gerais.centavosParaReais(totalLiquidoDevolucao));
}

//formas de pagamento para devolução
gerais.constructSelect2('forma-pagamento-devolucao-select');

$('#formDevolucao').on('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário
    const isMasterCaixa = $('#dataView').data('isMasterCaixa');

    // Faz a pergunta de senha padrão
    if (!isMasterCaixa) {
        // Passa a ação e os parâmetros para validaSuperior
        validaSuperior('devolucao_venda');
    } else {
        // Se não for MasterCaixa, remove diretamente
        devolucaoVenda();
    }
});

function devolucaoVenda() {
    ajaxPost(rotas.devolucaoVenda, montaRequestDevolucaoVenda(), 'Devolução Venda').done(function (response) {
        if (response.success == true) {
            closeSidebar();
        }
    });
}

// --------- Suprir caixa-----------//
gerais.constructSelect2('suprir-especie-caixa', rotas.rotaGetEspecies);
gerais.maskDinheiro('valor-suprir-caixa');

$('#formSuprir').on('submit', function (e) {
    e.preventDefault();

    const isMasterCaixa = $('#dataView').data('isMasterCaixa');

    // Faz a pergunta de senha padrão
    if (!isMasterCaixa) {
        // Passa a ação e os parâmetros para validaSuperior
        validaSuperior('suprir_caixa');
    } else {
        // Se não for MasterCaixa, remove diretamente
        suprirCaixa();
    }
});

function suprirCaixa() {
    const data = montaRequestSuprirCaixa();
    if (!data) {
        return;
    }
    ajaxPost(rotas.suprirCaixa, data).done(function (response) {
        if (response.success) {
            closeSidebar();
        }
    });
}
// --------- Sangria caixa-----------//
gerais.constructSelect2('especie-sangria', rotas.rotaGetEspecies);
gerais.maskDinheiro('valor-sangria-caixa');

$('#formSangria').on('submit', function (e) {
    e.preventDefault();

    const isMasterCaixa = $('#dataView').data('isMasterCaixa');

    // Faz a pergunta de senha padrão
    if (!isMasterCaixa) {
        // Passa a ação e os parâmetros para validaSuperior
        validaSuperior('sangria_caixa');
    } else {
        // Se não for MasterCaixa, remove diretamente
        sangriaCaixa();
    }
});

function sangriaCaixa() {
    const data = montaRequestSangriaCaixa();
    if (!data) {
        return;
    }
    ajaxPost(rotas.sangriaCaixa, data).done(function (response) {
        if (response.success) {
            closeSidebar();
        }
    });
}

function preencheSiedBarSangria() {
    ajaxGet(rotas.rotaGetCaixa).done(function (response) {
        if (response.success) {
            let valorTotalCaixa = response.caixa.ultima_evidencia.valor_total;
            let valorTotalDinheiro = response.caixa.ultima_evidencia.valor_dinheiro;
            $('#valor-total-caixa-sangria').val(gerais.centavosParaReais(valorTotalCaixa));
            $('#valor-total-em-dinheiro-sangria').val(gerais.centavosParaReais(valorTotalDinheiro));
        }
    });
}

// ---------------Recebimento ------------//
gerais.constructSelect2('cliente-recebimento-select', rotas.rotaGetClienteRecebimentoParcelas);

// gerais.constructSelect2('forma-pagamento-select', rotas.formasPagamentoGet);
$('#forma-pagamento-recebimento-select').select2({
    placeholder: "Selecione as formas de pagamento...",
    allowClear: true,
    language: "pt-BR",
    ajax: {
        url: rotas.formasPagamentoGet,
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.id,
                        text: item.descricao,
                        contem_parcela: item.especie.contem_parcela,
                        troco: item.especie.afeta_troco,
                        credito_loja: item.especie.credito_loja,
                    }
                })
            };
        },
        cache: true
    },
    templateSelection: formatProductSelection,
    dropdownParent: $('body') // Garante que o dropdown é anexado ao body para evitar problemas de z-index ou corte
});

$('#forma-pagamento-recebimento-select').on('change', function () {
    const selectedData = $(this).select2('data');

    // O Select2 retorna um array, mesmo que seja uma seleção única.
    // Verificamos o primeiro (e geralmente único) item selecionado.
    if (selectedData.length > 0) {
        const selectedFormaPagamento = selectedData[0]; // Pega o primeiro item selecionado

        // Verifica se a propriedade 'credito_loja' é verdadeira
        if (selectedFormaPagamento.credito_loja) {
            // Exibe a mensagem de aviso
            gerais.msgToastr(
                'Crédito Loja não pode ser usado como forma de pagamento para receber contas.',
                'error' // Ou 'warning', dependendo da severidade do aviso
            );

            // Deseleciona a opção
            $(this).val(null).trigger('change');
        }
    }
});

$('#cliente-recebimento-select').on('select2:select', function (e) {
    const venda_pagamento_id = e.params.data.id;
    if (venda_pagamento_id) {
        // $('#vendaNumeroDevolucao').text(`Seleção de itens`);
        ajaxGet(rotas.rotaGetClienteParcelas, { venda_pagamento_id }).done(function (response) {

            if (response.success) {

                renderizarTabelaParcelasCliente(response.venda_pagamento);

                // Garante que a sidebar principal continue aberta
                if (!$('#recebimentoSidebar').hasClass('open')) {
                    $('#recebimentoSidebar').addClass('open');
                }

                // Abre a sidebar filha à direita da principal
                $('#recebimentoParcelasSidebar').addClass('open');
            }
        });
    }
});
function renderizarTabelaParcelasCliente(venda_pagamento) {
    const tbody = $('#recebimentoParcelasTableBody');
    tbody.empty(); // Limpa antes de adicionar novos
    $("#clienteRecebimento").text('Informações das parcelas da venda: ' + venda_pagamento.venda.n_venda)
    venda_pagamento.venda_parcelas.forEach(item => {
        const linha = `
            <tr data-item-id="${item.id}">
                <td>${item.id}</td>
                <td>R$ ${gerais.centavosParaReais(item.valor)}</td>
                <td>R$ ${gerais.centavosParaReais(item.valor_pago)}</td>
                <td>R$ ${gerais.centavosParaReais((item.valor - item.valor_pago))}</td>
                <td>${gerais.aplicarMascaraDataNascimento(item.data_vencimento)}</td>
                <td>
                    <input type="text" class="form-control form-control-sm qtd-receber"
                           data-valor-max="${(item.valor - item.valor_pago)}"
                           min="0" max="${(item.valor - item.valor_pago)}" value="">
                </td>
            </tr>
        `;
        tbody.append(linha);
    });

    gerais.maskDinheiroByClass('qtd-receber');
    $('.qtd-receber').off('blur', calcularTotalReceber); // Desvincula eventos anteriores para evitar duplicação
    $('.qtd-receber').on('blur', calcularTotalReceber);

    // Chama a função uma vez para calcular o total inicial (se houver valores pré-preenchidos)
    calcularTotalReceber();
}
function calcularTotalReceber() {
    let totalGeralReceberCents = 0;

    $('.qtd-receber').each(function () {
        const $input = $(this);
        const valorMaxCents = parseFloat($input.data('valor-max')); // Pega o valor máximo do atributo data-
        let valorReceberCents = gerais.reaisParaCentavos($input.val()); // Converte o valor digitado para centavos

        // --- Lógica de Validação da Quantidade Máxima ---
        if (valorReceberCents > valorMaxCents) {
            gerais.msgToastr(
                `O valor a receber (R$ ${gerais.centavosParaReais(valorReceberCents)}) excede o máximo permitido (R$ ${gerais.centavosParaReais(valorMaxCents)}). O valor será ajustado.`,
                'warning'
            );
            valorReceberCents = valorMaxCents; // Ajusta o valor para o máximo permitido
            $input.val(gerais.centavosParaReais(valorReceberCents)); // Atualiza o input visualmente
        }
        // Garante que o valor não seja negativo
        if (valorReceberCents < 0) {
            gerais.msgToastr('O valor a receber não pode ser negativo. O valor será ajustado para R$ 0,00.', 'warning');
            valorReceberCents = 0;
            $input.val('0,00');
        }

        // --- Fim da Lógica de Validação ---
        totalGeralReceberCents += valorReceberCents;
    });

    const totalReceberFormatado = 'R$ ' + gerais.centavosParaReais(totalGeralReceberCents);
    $('#valor-total-venda-recebimento').val(totalReceberFormatado);
}

$('#formRecebimento').on('submit', function (event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    let request = montaRequestReceberConta();
    if (!request) {
        return;
    }
    ajaxPost(rotas.rotaReceberConta, request).done(function (response) {
        if (response.success == true) {
            closeSidebar();
        }
    });
});


//-------------------------manusear sied bars-------------------//
// VARIÁVEIS GLOBAIS SEDBARs
const $body = $('body'); // Assumindo que $body já está definido
const $overlay = $('<div class="overlay"></div>'); // Cria o overlay uma vez

// Função genérica para abrir uma sidebar
function openSidebar(sidebarId) {
    // Se for a sidebar principal (esquerda), fecha todas as outras
    if (sidebarId === '#devolucaoSidebar' || sidebarId === '#orcamentoSiedbar' || sidebarId === '#recebimentoSidebar') {
        $('.sidebar, .sidebar-filha').removeClass('open');
    }

    $(sidebarId).addClass('open');

    if ($overlay.parent().length === 0) {
        $body.append($overlay);
    }
    $overlay.addClass('active');
}


// Função genérica para fechar uma sidebar
function closeSidebar(id) {
    $(id).removeClass('open');
    fecharTodasSidebars(); // Fecha também as filhas e o overlay
}


// --- EVENTOS DE CLIQUE PARA ABRIR SIDEBARS ---
$('.sidebar-toggle-btn').on('click', function (event) {
    event.preventDefault();
    const targetSidebarId = $(this).data('sidebar-target'); // Pega o ID da sidebar do atributo data-sidebar-target

    openSidebar(targetSidebarId); // Abre a sidebar genérica

    // Lógica específica APÓS ABRIR CADA SIDEBAR
    setTimeout(function () {
        if (targetSidebarId === '#finalizarVendaSidebar') {

            $('#cliente-select').val(null).trigger('change');
            $('#cliente-select').select2('open');
            $('.valor-forma-pagamento').val(null).trigger('change'); // Limpa a seleção e dispara o evento 'change'

            $('.troco-display').val(0);
        } else if (targetSidebarId === '#devolucaoSidebar') {

            $('#forma-pagamento-devolucao-select').val(null);
            $('#valor-total-venda-devolucao').val(0);
            $('#valor-total-desconto-devolucao').val(0);
            $('#valor-total-devolucao').val(0);
            $('#motivo-devolucao-textarea').val('');
            $('#desconto-devolucao-reais').text('');

        } else if (targetSidebarId === '#orcamentoSiedbar') {
            $('#orcamento-select').val(null).trigger('change');

        } else if (targetSidebarId === '#suprirSidebar') {
            $('#suprir-especie-caixa').val(null).trigger('change');
            $('#valor-suprir-caixa').val('');
        } else if (targetSidebarId === '#sangriaSidebar') {
            $('#formSangria')[0].reset();
            preencheSiedBarSangria();
        } else if (targetSidebarId === '#recebimentoSidebar') {
            $('#formRecebimento')[0].reset();
            $('#cliente-recebimento-select').val(null).trigger('change');
        }
    }, 500); // Atraso para a transição da sidebar
});

// --- EVENTOS DE CLIQUE PARA FECHAR SIDEBARS ---
$('.close-sidebar-btn').on('click', function () {
    const targetSidebarId = $(this).data('sidebar-target'); // Pega o ID da sidebar do atributo data-sidebar-target
    closeSidebar(targetSidebarId);
});

// --- EVENTO DE CLIQUE NO OVERLAY PARA FECHAR QUALQUER SIDEBAR ABERTA ---
$overlay.on('click', function () {
    // Encontra qual sidebar está aberta e a fecha
    const $openSidebar = $('.sidebar.open');
    if ($openSidebar.length > 0) {
        closeSidebar('#' + $openSidebar.attr('id'));
    }
});

// --- FECHAR COM ESC (AJUSTADO) ---
$(document).on('keydown', function (event) {
    if (event.key === 'Escape') {
        fecharTodasSidebars();

    }
});
function fecharTodasSidebars() {
    $('.sidebar, .sidebar-filha').removeClass('open');
    $('.overlay').removeClass('active');
    $('#venda-devolucao-select').val(null).trigger('change');
}

