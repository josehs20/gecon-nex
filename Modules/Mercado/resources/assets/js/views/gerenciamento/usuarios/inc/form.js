
import * as gerais from '@/gerais.js';

toggleSwithSelecionadoAtivo();
toggleSwithSelecionadoAbrirCaixa();
gerais.buscarCep('cep', 'botaoBuscarCep', 'logradouro', 'bairro', 'cidade', 'uf', 'complemento');
aplicarMascaras();
function aplicarMascaras() {
    /* cpf */
    $('#documento').mask('000.000.000-00');

    /* celular */
    $('#celular').mask('(00) 0 0000-0000');

    /* telefone */
    $('#telefone').mask('(00) 0000-0000');

    /* cep */
    $('#cep').mask('00.000-000');

    gerais.aplicarMascaraDataById('data_nascimento');
    gerais.aplicarMascaraDataById('data_admissao');
    gerais.aplicarMascaraDataById('data_demissao');
}


function toggleSwithSelecionadoAbrirCaixa() {
    $('#permite_abrir_caixa').on('change', function () {
        const isChecked = $(this).is(':checked');
        var opcao = isChecked ? 'Sim' : 'Não';
        $('#label_permite_abrir_caixa').html(opcao);
    });
}

function toggleSwithSelecionadoAtivo() {
    $('#ativo').on('change', function () {
        const isChecked = $(this).is(':checked');
        var opcao = isChecked ? 'Sim' : 'Não';
        $('#label_ativo').html(opcao);
    });
}

const ROTA = $('#dataView').data('empresaLojas')

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
