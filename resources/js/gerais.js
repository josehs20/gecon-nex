import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import 'jquery-blockui';  // <- ESSENCIAL para que $.blockUI seja adicionado ao jQuery
import 'jquery-mask-plugin';  // <-- ESSENCIAL

import select2 from 'select2';  // <--- IMPORTE O OBJETO
import 'select2/dist/css/select2.min.css';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.toastr = toastr;  // <-- ESSENCIAL, se quer usar `toastr` global
select2(jQuery);  // <- ***ESSENCIAL!*** Anexa o plugin ao jQuery

const $ = jQuery;  // pode ou não, dependendo do seu uso local


function montaNomeProduto(produto) {
    if (!produto) return '';

    const nome = produto.nome || '';
    const unidade = produto.unidade_medida.sigla || '';
    const fabricante = produto.fabricante?.nome || '';

    return `${nome} - <strong>${unidade}</strong> - ${fabricante}`.trim();
}

export function montaDatatable(tabelaId, urlAjax, dataAjax) {

    if ($.fn.DataTable.isDataTable('#' + tabelaId)) {
        var table = $('#' + tabelaId).DataTable();
        table.destroy();
        $('#' + tabelaId + ' thead tr:eq(1)').remove();
    }
    let buttons = [
        {
            extend: 'excel',
            text: '<i class="bi bi-file-earmark-excel"></i> Excel',
            className: 'btn btn-success',
            exportOptions: {
                columns: removeActionColumn
            }
        },
    ];
    if (urlAjax) {
        var table = $('#' + tabelaId).DataTable({
            dom: '<"row"<"col-sm-12"B>>' +  // Botões de exportação acima
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +  // Seletor de quantidade e pesquisa
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: buttons,
            "ajax": {
                "url": urlAjax,
                "type": "GET", // ou "GET", dependendo do método que você está usando
                "data": dataAjax,
            },
            "language": {
                "searchPlaceholder": "Pesquisa",
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                }
            },
            "drawCallback": function (settings) {
                $('#' + tabelaId + ' tbody td,' + '#' + tabelaId + ' thead th')
                    .each(function () {
                        $(this).attr('title', $(this).text());
                        $('#' + tabelaId + ' tbody td').addClass('custom-body');
                    });
            },
            "initComplete": function () {
                // Aplica o alinhamento ao thead e tfoot após a inicialização do DataTable
                $('#' + tabelaId + ' thead th, #' + tabelaId + ' tfoot th').css('text-align', 'center');
            }
        });
    } else {

        var table = $('#' + tabelaId).DataTable({
            // "order": [[0, 'asc']],
            dom: '<"row"<"col-sm-12"B>>' +  // Botões de exportação acima
                '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +  // Seletor de quantidade e pesquisa
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            buttons: buttons,
            "language": {
                "searchPlaceholder": "Pesquisa",
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                }
            },
            "drawCallback": function (settings) {
                $('#' + tabelaId + ' tbody td,' + '#' + tabelaId + ' thead th')
                    .each(function () {
                        $(this).attr('title', $(this).text());
                    });
                $('#' + tabelaId + ' tbody td').addClass('custom-body');
            },
            "initComplete": function () {
                // Aplica o alinhamento ao thead e tfoot após a inicialização do DataTable
                $('#' + tabelaId + ' thead th, #' + tabelaId + ' tfoot th').css('text-align', 'center');
            }
        });
    }
    $('#' + tabelaId + ' thead tr').clone(true).appendTo('#' + tabelaId + ' thead');
    $('#' + tabelaId + ' thead tr:eq(1) th').each(function (i) {

        if (i < table.columns().header().length) {

            var title = $(this).text();
            if (title != 'Ação') {
                $(this).html('<input type="text" placeholder="Filtrar ' + title +
                    '" style="width: 100%;" class="form-control"/>');
            } else {
                $(this).html(' ');
            }

        } else {
            $(this).html(' ');
        }

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
    // Função para verificar e remover a coluna "Ação"
    function removeActionColumn(index, data, node) {
        var headerText = $(table.column(index).header()).text().trim();
        return headerText !== "Ação";  // Exclui a coluna "Ação"
    }
    return table;
}

function recarregarDataTableYajra(tableId) {
    // Inicializa o DataTable da tabela passada pelo ID
    var tabela = $('#' + tableId).DataTable();

    // Recarrega os dados do DataTable
    tabela.ajax.reload(null, false);
}

// Função para inicializar o DataTables com parâmetros dinâmicos e AJAX
export function montaDatatableYajra(tabelaId, columns, urlAjax, dataAjax) {

    columns = montaColunasParaYajra(columns);
    const table = $(`#${tabelaId}`).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,  // Adiciona a opção lengthChange para garantir que o seletor de registros seja exibido
        ajax: {
            url: urlAjax,
            type: "GET",
            data: function (d) {
                $(`#${tabelaId} thead tr:eq(1) th`).each((i, th) => {
                    const input = $(th).find('input');
                    if (input.length && input.val()) {
                        d.columns[i].search.value = input.val();
                    }
                });
                d.dataAjax = dataAjax;
            },
            error: function (xhr, error, thrown) {
                msgToastr('Erro ao carregar os dados. Tente novamente', 'error')
            }
        },
        columns: columns,
        dom: '<"row"<"col-sm-12"B>>' +  // Botões de exportação acima
            '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +  // Seletor de quantidade e pesquisa
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            // {
            //     extend: 'csv',
            //     text: '<i class="bi bi-file-earmark-text"></i> CSV',
            //     className: 'btn btn-success',
            //     exportOptions: {
            //         columns: removeActionColumn
            //     }
            // },
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Exportar',
                className: 'btn btn-success',
                exportOptions: {
                    columns: removeActionColumn
                }
            },
            // {
            //     extend: 'print',
            //     text: '<i class="bi bi-printer"></i> Imprimir',
            //     className: 'btn btn-primary',
            //     exportOptions: {
            //         columns: removeActionColumn
            //     }
            // }
        ],
        language: {
            searchPlaceholder: "Pesquisa",
            sEmptyTable: "Nenhum registro encontrado",
            sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            sInfoEmpty: "Mostrando 0 até 0 de 0 registros",
            sInfoFiltered: "(Filtrados de _MAX_ registros)",
            sLengthMenu: "_MENU_ resultados por página",
            sLoadingRecords: "Carregando...",
            sProcessing: "Processando...",
            sZeroRecords: "Nenhum registro encontrado",
            sSearch: "Pesquisar",
            oPaginate: {
                sNext: "Próximo",
                sPrevious: "Anterior",
                sFirst: "Primeiro",
                sLast: "Último"
            }
        },
        initComplete: function () {

            const api = this.api();
            const $thead = $(`#${tabelaId} thead`);
            const $filterRow = $('<tr role="row"></tr>').appendTo($thead);

            columns.forEach((column, index) => {
                const $th = $('<th></th>').appendTo($filterRow);

                if (column.searchable !== false) {
                    const input = $(`<input type="text" placeholder="Filtrar ${column.title}" class="form-control">`)
                        .appendTo($th)
                        .on('keyup', debounce(() => {

                            if (input.val() !== api.column(index).search() || input.val() == '' && api.column(index).search() == '') {
                                api.ajax.reload();
                            }

                        }, 1000));
                } else {
                    $th.html('');
                }
            });
            $(".btn, .card").addClass("elevated");
        }
    });

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    // Função para verificar e remover a coluna "Ação"
    function removeActionColumn(index, data, node) {
        var headerText = $(table.column(index).header()).text().trim();
        return headerText !== "Ação";  // Exclui a coluna "Ação"
    }
    //tempo de espera para consulta global do datatables
    $('#' + tabelaId + '_filter input')
        .off()  // Remove o evento padrão
        .on('keyup', debounce(function (event) {
            const searchValue = $(event.target).val(); // Pega o valor do input com $(event.target)

            if (table) {
                // Atualiza a pesquisa e recarrega a tabela
                table.settings()[0].oPreviousSearch.sSearch = searchValue;
                table.ajax.reload(); // Recarrega os dados com AJAX
            }
        }, 1000));  // 🔥 Debounce de 500ms

    // 🔥 Sempre reaplicar a classe elevated após cada recarga da tabela
    table.on('draw.dt', function () {
        $(".btn, .card").addClass("elevated");
    });
}

function montaColunasParaYajra(colunas) {

    return colunas.map(coluna => ({
        data: coluna[0],
        title: coluna[1],
        orderable: coluna[2] ?? true,
        searchable: coluna[3] ?? true
    }));
}

export function constructSelect2(idElemento, url, vaiEstarEmAlgumModal = false, options = {}) {
    if (url) {
        $('#' + idElemento).select2({
            dropdownParent: vaiEstarEmAlgumModal ? $('#' + vaiEstarEmAlgumModal) : vaiEstarEmAlgumModal, // Define o modal como pai do dropdown
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page,
                        options: options
                    };
                },
                processResults: function (data, params) {
                    // Verifica se 'data' é um array ou um objeto
                    const results = Array.isArray(data) ? data : [data]; // Se não for um array, transforma em um array

                    // Retorna os resultados no formato que o Select2 espera
                    return {
                        results: results.map(item => ({
                            id: item.id, // ID do cliente
                            text: item.text // Texto que será exibido no dropdown
                        })),
                        pagination: {
                            more: false // Se não há mais páginas a serem carregadas
                        }
                    };
                },
                cache: true
            },
            placeholder: "Digite para pesquisar",
            allowClear: true,
            language: {
                errorLoading: function () {
                    return "Erro ao carregar as informações.";
                },
                inputTooShort: function () {
                    return "Insira pelo menos 1 caractere...";
                },
                loadingMore: function () {
                    return "Carregando mais resultados...";
                },
                noResults: function () {
                    return "Nenhum resultado encontrado";
                },
                searching: function () {
                    return "Procurando...";
                }
            },
            width: 'resolve'
        });
    } else {

        $('#' + idElemento).select2({
            language: {
                errorLoading: function () {
                    return "Erro ao carregar as informações.";
                },
                inputTooShort: function () {
                    return "Insira pelo menos 1 caracteres...";
                },
                loadingMore: function () {
                    return "Carregando mais";
                },
                noResults: function () {
                    return "Nenhum resultado encontrato";
                },
                searching: function () {
                    return "Carregando...";
                },
            },
        });
    }
}

function isNanOrEmpty(value) {
    // Verifica se é NaN ou vazio
    return value === '' || Number.isNaN(Number(value));
}
function maskDinehiroReturnVal(valor) {
    return parseFloat(valor).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}
function maskDinheiro(idElemento) {
    $("#" + idElemento).mask('000.000.000.000.000,00', {
        reverse: true // para que o valor seja inserido da direita para a esquerda
    });
}

function maskDinheiroByClass(classElement) {
    $("." + classElement).mask('000.000.000.000.000,00', {
        reverse: true // para que o valor seja inserido da direita para a esquerda
    });
}

function maskPorcentagem(idElemento, limite) {
    $("#" + idElemento).mask('##0,00', {
        translation: {
            '#': {
                pattern: /[0-9]/, // Permite apenas dígitos
                recursive: true
            }
        },
        reverse: true // Permite que os números sejam inseridos no formato correto de percentual
    });

    // Adiciona um evento de input para garantir que o valor máximo seja 100
    $("#" + idElemento).on('input', function () {
        let valor = parseFloat($(this).val().replace(',', '.')); // Converte para número
        if (valor > limite) {
            $(this).val(limite); // Reseta para 100%
            msgToastr('Desconto mais que o permitido, limite de ' + limite + '%', 'warning');
        } else if (isNaN(valor)) {
            $(this).val(''); // Limpa o campo se não for um número válido
        }
    });
}
function maskQtd(idElemento) {
    $('#' + idElemento).mask('#.##0,000', { reverse: true });
}

function maskQtdByClass(classElement) {
    // Aplicar a máscara inicial
    $("." + classElement).mask('#.##0,000', { reverse: true });

    // Evento de 'keyup' para cada input com a classe
    $("." + classElement).on('keyup', function () {
        let value = $(this).val().replace(/[^0-9,]/g, ''); // Remover caracteres inválidos

        // Separar por vírgula (parte inteira e decimal)
        let parts = value.split(',');

        // Limitar a três casas decimais
        if (parts[1] && parts[1].length > 3) {
            parts[1] = parts[1].substring(0, 3);
        }

        // Recombinar as partes (parte inteira e decimal)
        $(this).val(parts.join(','));
    });

    // Garante que valores existentes sejam formatados corretamente ao carregar
    $("." + classElement).each(function () {
        $(this).trigger('keyup'); // Força a formatação no carregamento
    });
}


function buscarCep(idInputCep, idBotaoBuscar, idInputLogradouro, idInputBairro, idInputCidade, idInputUf, idInputComplemento) {
    $('#' + idBotaoBuscar).on('click', function () {
        let cep = $('#' + idInputCep).val();
        let cepSemCaracteresEspeciais = cep.replace(/[.-]/g, '');
        if (!cep || cep == '') {
            toastr.warning('Insira o CEP.');
        }
        $.ajax({
            url: 'https://viacep.com.br/ws/' + cepSemCaracteresEspeciais + '/json/',
            dataType: 'json',
            success: function (data) {
                if (!data.erro) {
                    $('#' + idInputLogradouro).val(data.logradouro);
                    $('#' + idInputBairro).val(data.bairro);
                    $('#' + idInputCidade).val(data.localidade);
                    $('#' + idInputUf).val(data.uf);
                    $('#' + idInputComplemento).val(data.complemento);
                } else {
                    toastr.info('CEP não encontrado!');
                }
            },
            error: function () {
                toastr.warning('Ocorreu um erro ao buscar o CEP. Por favor, tente novamente mais tarde.');
            }
        });
    })
}

function verificarPessoa(documento) {
    if (documento.length === 14) {
        return 'CNPJ';
    } else {
        return 'CPF';
    }
}

function aplicarMascaraDocumento(documento) {
    var tipoPessoa = verificarPessoa(documento);
    if (tipoPessoa === 'CNPJ') {
        // Aplica máscara para CNPJ
        return documento.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
    } else {
        // Aplica máscara para CPF
        return documento.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
    }
}

function aplicarMascaraCelular(celular) {
    if (celular) {
        return celular.replace(/^(\d{2})(\d{1})(\d{4})(\d{4})$/, '($1) $2 $3-$4');
    }
}

function aplicarMascaraTelefoneFixo(telefoneFixo) {
    if (telefoneFixo) {
        return telefoneFixo.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    }
}

export function maskTelefoneById(elemento) {
    $('#' + elemento).mask('(00) 0000-00009').on('input', function () {
        // Verifica o tamanho do valor digitado
        var value = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos
        if (value.length > 10) {
            // Se for celular (11 dígitos), aplica a máscara com o nono dígito
            $(this).mask('(00) 00000-0000');
        } else {
            // Se for telefone fixo (10 dígitos), aplica a máscara padrão
            $(this).mask('(00) 0000-00009');
        }
    });
}

function aplicarMascaraCep(cep) {
    if (cep) {
        return cep.replace(/^(\d{2})(\d{3})(\d{3})$/, '$1.$2-$3');
    }
}

function aplicarMascaraData(data) {
    if (data) {
        var particionado = data.split('-');

        return particionado[2] + '/' + particionado[1] + '/' + particionado[0];
    }
}

function aplicarMascaraDataHora(data) {
    // Certifique-se de que a data está no formato 'yyyy-mm-dd HH:MM:SS'
    const partesData = data.split(' '); // Separar data e hora

    // Separa a data 'yyyy-mm-dd' em partes
    const [ano, mes, dia] = partesData[0].split('-');

    // Separa a hora 'HH:MM:SS'
    const [hora, minuto, segundo] = partesData[1].split(':');

    // Retorna a data no formato brasileiro 'dd/mm/yyyy HH:mm:ss'
    return `${dia}/${mes}/${ano} às ${hora}:${minuto}:${segundo}`;
}

/* Quando for formulario para editar e ja tiver data registrada, essa função vai formatar a data para o formato brasileiro para exibir no input */
function formatarData() {
    var data = $('#data_nascimento').val();
    var partes = data.split('-');
    var dataFormatada = partes[2] + '/' + partes[1] + '/' + partes[0];
    $('#data_nascimento').val(dataFormatada);
}
function aplicarMascaraDataNascimento(dataAmericana) {
    const partes = dataAmericana.split('-'); // Divide a data americana no formato Y-m-d
    return `${partes[2]}/${partes[1]}/${partes[0]}`; // Retorna no formato d/m/Y
}
function formatarQuantidade(valor) {
    if (!valor) return 0; // Garante que um valor vazio retorne 0
    return parseFloat(valor.replace(/\./g, '').replace('.', ','));
}
/* Quando for formulario para editar, formatara o documento para exibir corretamento no input */
function formatarDocumento(retornar = null) {
    var documento = $('#documento').val();

    if (retornar) {
        documento = retornar;
        if (documento.length === 14) {
            // Aplica máscara para CNPJ: 00.000.000/0000-00
            documentoFormatado = documento.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
        } else if (documento.length === 11) {
            // Aplica máscara para CPF: 000.000.000-00
            documentoFormatado = documento.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, '$1.$2.$3-$4');
        } else if (!documento || documento == '') {
            documentoFormatado = '';
        } else {
            // Documento inválido (não possui 11 nem 14 dígitos)
            documentoFormatado = 'Documento inválido';
        }
        return documentoFormatado;
    }
    // Verifica se é CNPJ (14 dígitos) ou CPF (11 dígitos)
    if (documento) {

        // Define o valor formatado no campo de documento (se necessário)
        $('#documento').val(documentoFormatado);
    }
}
function converteParaFloat(qtd) {
    return parseFloat((qtd.replace(/\./g, '').replace(',', '.')));
}
function trocarPontoPorVirgula(valor) {
    if (typeof valor === 'number') {
        valor = valor.toString();
    }
    return valor.replace(/\./g, ','); // Substitui todos os pontos por vírgulas
}
function abreModalAutenticacao() {
    $('#modalAutenticacaoUsuario').modal('show');
}

function fechaModalAutenticacao() {
    $('#modalAutenticacaoUsuario').modal('hide');
}

function autenticar() {
    $.ajax({
        url: url, // Substitua com o endpoint do servidor
        type: 'POST',
        data: objectData, // Serializa os dados do formulário
        dataType: 'json', // Espera uma resposta JSON
        success: function (response) {
            // Manipula a resposta com sucesso
            $('#response').html('<p>Success: ' + response.message + '</p>');
        },
        error: function (xhr, status, error) {
            // Manipula erros
            $('#response').html('<p>Error: ' + error + '</p>');
        }
    });
}

function disableButtons(botaoComProcessando) {
    $('.btn').attr('disabled', true);
    $('#' + botaoComProcessando).text('Processando...');
}

function habilitaButtons(botaoMudarTexto, texto) {
    $('.btn').attr('disabled', false);
    $('#' + botaoMudarTexto).text(texto);
}

export function msgToastr(text, tipo, posicao, custom_toast) {
    if (!posicao) {
        posicao = 'right';
    }

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true, // Ativa a barra de progresso
        "positionClass": "toast-top-" + posicao, // Posição no canto superior direito
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000", // Tempo que a mensagem ficará visível
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    switch (tipo) {
        case 'success':
            toastr.success(text)
            break;
        case 'error':
            toastr.error(text)
            break;
        case 'info':
            toastr.info(text)
            break;
        case 'warning':
            toastr.warning(text)
            break;
        default:
            break;
    }
}

function somenteInteiro(elemento) {
    $('#' + elemento).mask('999');
}

function somenteInteiroByClass(elemento) {
    $('.' + elemento).mask('999999999999999999999999999999999999999999999999999999999999999999999999999');
}

function centavosParaFloat(valorEmCentavos) {
    // Verifica se o valor é um número e não é NaN
    if (isNaN(valorEmCentavos)) {
        throw new Error('O valor deve ser um número válido');
    }

    // Divide por 100 e retorna como float
    return valorEmCentavos / 100;
}

function centavosParaReais(valorEmCentavos) {
    // Verifica se o valor é um número e não é NaN
    if (isNaN(valorEmCentavos)) {
        valorEmCentavos = 0;
    }

    // Divide por 100 para converter em reais
    const valorEmReais = valorEmCentavos / 100;

    // Formata o valor em reais com duas casas decimais e vírgula
    return valorEmReais.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function floatParaCentavos(valorEmFloat) {
    // Verifica se o valor é um número e não é NaN
    if (isNaN(valorEmFloat)) {
        throw new Error('O valor deve ser um número válido');
    }

    // Multiplica por 100 e arredonda para evitar problemas de precisão
    return Math.round(valorEmFloat * 100);
}
function validarInput(event) {
    const input = event.target;
    const cursorPosition = input.selectionStart;
    let value = input.value;

    // Remove caracteres inválidos
    let sanitizedValue = value.replace(/[^\d,]/g, '');

    // Limita a quantidade de vírgulas a 1
    const parts = sanitizedValue.split(',');
    if (parts.length > 2) {
        sanitizedValue = parts[0] + ',' + parts.slice(1).join('');
    }

    // Verifica o comprimento máximo de 8 dígitos (sem contar a vírgula)
    const digitsOnly = sanitizedValue.replace(/,/g, '');
    if (digitsOnly.length > 8) {
        sanitizedValue = value.slice(0, cursorPosition - 1) + value.slice(cursorPosition);
    }

    input.value = sanitizedValue;

    // Restaura a posição do cursor
    const newCursorPosition = cursorPosition - (value.length - sanitizedValue.length);
    input.setSelectionRange(newCursorPosition, newCursorPosition);
}

function confirmarAlteracoesComSenha() {
    return new Promise((resolve, reject) => {
        Swal.fire({
            title: `Informe a senha para continuar`,
            html: `<input required type="password" id="senha" name="senha" class="form-control">`,
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#ff0000",
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let senha = $('#senha').val();
                $.ajax({
                    url: `/confirmarComSenha`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { senha: senha },
                    success: function (response) {
                        if (response) {
                            toastr.success('Senha confirmada com sucesso!');
                            resolve(true); // Resolve a promessa com sucesso
                        } else {
                            toastr.error('Senha incorreta!');
                            resolve(false); // Resolve como falso
                        }
                    },
                    error: function () {
                        toastr.warning('Erro ao confirmar a senha. Tente novamente mais tarde.');
                        reject('Erro ao confirmar a senha.'); // Rejeita a promessa em caso de erro
                    }
                });
            } else {
                resolve(false); // Resolve como falso se o usuário cancelar
            }
        }).catch((error) => {
            reject(error); // Rejeita a promessa caso haja erro inesperado
        });
    });
}

function consultanNF(chave, rota) {
    return $.ajax({
        url: rota, // Rota configurada no Laravel
        type: 'GET',
        data: { chave: chave }, // Parâmetro da chave da NF-e
        dataType: 'json',
        beforeSend: function () {
            console.log('Consultando a NF-e...');
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.error('Erro na consulta:', jqXHR.responseJSON.message || errorThrown);
    });
}

function maskCNPJByClass(classe) {
    $("." + classe).mask('00.000.000/0000-00', {
        reverse: false // A máscara será aplicada da esquerda para a direita
    });
}
export function maskCNPJById(elemento) {
    $("#" + elemento).mask('00.000.000/0000-00', {
        reverse: false // A máscara será aplicada da esquerda para a direita
    });
}
export function bloquear() {
    $.blockUI({
        message: '<h1><img src="/img/loading4-unscreen.gif" alt="Carregando..."></h1>',
        css: {
            border: 'none',
            padding: '15px',
            background: 'transparent', // Sem fundo
            color: '#fff',
            cursor: 'wait',
            fontSize: '18px',
            'text-align': 'center',
            width: 'auto', // Ajusta a largura automaticamente para o conteúdo
            position: 'fixed', // Fixar o conteúdo na tela
            top: '50%', // Posicionar 50% da altura da tela
            left: '50%', // Posicionar 50% da largura da tela
            transform: 'translate(-50%, -50%)', // Ajusta para que o centro do conteúdo fique no centro da tela
            zIndex: 9999 // Garantir que o carregamento fique sobre todos os outros elementos
        }
    });
}

export function desbloquear() {
    $.unblockUI();
}

function getSpanAtivo($ativo) {
    if ($ativo) {
        return "<a class='w-100 badge badge-success'>Ativo</a>";
    }
    return "<a class='w-100 badge badge-danger'>Inativo</a>";
}

/**
 * Chamar essa função aqui no input! Exemplo: onkeyup="formatarCampo(this)"
 * As duas andam juntas.
 *
 * Responsáveis por formatar campos de dinheiro, exemplo: campo salário.
 */
function formatarCampo(input) {
    let cursorPos = input.selectionStart;

    let valorSemFormatacao = input.value.replace(/[^\d,]/g, '');
    let valorFormatado = formatarValor(valorSemFormatacao);

    input.value = valorFormatado;

    // Ajusta o cursor para o final (opcional, para manter a usabilidade)
    input.setSelectionRange(valorFormatado.length, valorFormatado.length);
}

function formatarValor(valor) {
    valor = valor.replace(/[^\d,]/g, '');

    let partes = valor.split(',');
    let parteInteira = partes[0];
    let parteDecimal = partes.length > 1 ? ',' + partes[1].slice(0, 2) : '';

    parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return parteInteira + parteDecimal;
}
function fecharModal(element) {
    $('#' + element).modal('hide');
}
function getDescricaoStatus(id) {
    const statusMap = {
        1: 'Ativo',
        2: 'Inativo',
        3: 'Aberto',
        4: 'Ocupado',
        5: 'Fechado',
        6: 'Em Dia',
        7: 'Em Atraso',
        8: 'Quitado',
        9: 'Bloqueado',
        10: 'Livre',
        11: 'Salvo',
        12: 'Cancelado',
        13: 'Concluído',
        14: 'Parcelada',
        15: 'Devolução',
        16: 'Pago',
        17: 'Pendente',
        18: 'Devolução Parcial',
        19: 'Recebimento Iniciado',
        20: 'Aguardando cotação',
        21: 'Em cotação',
        22: 'Cotado',
        23: 'Comprado'
    };

    return statusMap[id] || 'Desconhecido';
}

function getBadgeStatus(id) {
    const badgeMap = {
        1: 'badge badge-success',     // Ativo
        2: 'badge badge-secondary',   // Inativo
        3: 'badge badge-info',        // Aberto
        4: 'badge badge-danger',      // Ocupado
        5: 'badge badge-danger',      // Fechado
        6: 'badge badge-primary',     // Em Dia
        7: 'badge badge-warning',     // Em Atraso
        8: 'badge badge-success',     // Quitado
        9: 'badge badge-dark',        // Bloqueado
        10: 'badge badge-info',       // Livre
        11: 'badge badge-secondary',  // Salvo
        12: 'badge badge-danger',     // Cancelado
        13: 'badge badge-success',    // Concluído
        14: 'badge badge-info',       // Parcelada
        15: 'badge badge-danger',     // Devolução
        16: 'badge badge-success',    // Pago
        17: 'badge badge-warning',    // Pendente
        18: 'badge badge-danger',     // Devolução Parcial
        19: 'badge badge-primary',    // Recebimento Iniciado
        20: 'badge badge-dark',       // Aguardando cotação
        21: 'badge badge-info',       // Em cotação
        22: 'badge badge-success',    // Cotado
        23: 'badge badge-success'     // Comprado
    };

    return badgeMap[id] || 'badge badge-light';
}

/** ------------------------------------------------------------------------------ */
