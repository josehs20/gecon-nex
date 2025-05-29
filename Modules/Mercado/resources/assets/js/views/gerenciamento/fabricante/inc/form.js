import * as gerais from '@/gerais.js';
gerais.formatarDocumento();
gerais.aplicarMascaraDataById('data_nascimento');
gerais.buscarCep('cep', 'botaoBuscarCep', 'logradouro', 'bairro', 'cidade', 'uf', 'complemento');
gerais.maskDinheiro('limite_credito')

/*
    quando mudar a opção do select de pessoa (fisica ou juridica),
    limpa o input de documento para prevenir problemas
*/
$('#pessoa').on('change', function () {
    $('#documento').val('');
    verificarSelectPessoa();
})

/* busca os estados para preencher o select */
$.ajax({
    "url": "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
    "dataType": "json",
    "success": function (data) {
        var ufSelect = $('#uf');
        // Ordena os estados por nome
        data.sort(function (a, b) {
            return a.sigla.localeCompare(b.sigla);
        });
        console.log(ufSelect);

        ufSelect.empty(); // Limpar opções existentes

        data.forEach(function (estado) {
            ufSelect.append($('<option>', {
                value: estado.sigla,
                text: estado.sigla
            }));
        });

        let endereco = $('#dataViewForm').data('endereco');
        if (endereco) {
            ufSelect.val(endereco.uf);
        }
    },
    "error": function (error) {
        console.log(error)
    }
});

/*
    faz verificações antes de enviar o formulario
*/
$('#cliente').on('submit', function (event) {
    event.preventDefault();
    let email = $('#email').val();

    /* email é opcional, só verifico o email se o usuario inseri-lo */
    if (email != '') {
        if (verificarEmail(email)) {
            this.submit();
        } else {
            toastr.warning('E-mail inválido!');
        }
    } else {
        this.submit();
    }
});


function submitFormulario() {
    let form = $('#cliente').su;
    let formData = form.serialize();
    let csrfToken = form.find('input[name="_token"]').val();

    $('#bt-salvar-atualizar').prop('disabled', true);
    $('#bt-salvar-atualizar').text('Processando ...');

    $.ajax({
        "url": form.attr('action'),
        "method": form.attr('method'),
        "dataType": "json",
        "data": formData,
        "async":false,
        "headers": {
            'X-CSRF-TOKEN': csrfToken
        },
        "success": function (data) {
            if (form.data('identifier') === 'form-store') {
                toastr.success('Cliente cadastrado com sucesso.');
                form.find('input').val('');
            } else if (form.data('identifier') === 'form-update') {
                toastr.success('Cliente atualizado com sucesso.');
            }

            setTimeout(function () {
                $('#bt-salvar-atualizar').prop('disabled', false);
                $('#bt-salvar-atualizar').text('Salvar');
            }, 1500);
        },
        "error": function (error) {
            if (error.responseJSON && error.responseJSON.message) {
                var mensagemErro = JSON.parse(error.responseText).message;
                toastr.error('Não foi possível cadastrar o cliente. Tente novamente!', mensagemErro);
            } else {
                toastr.error('Não foi possível cadastrar o cliente. Tente novamente!');
            }
        }
    });
}

/*
    retorna true se o email for valido
    email valido é aquele que contém @ e .com ou .com.br
*/
function verificarEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/*
    Aplica a mascara de acordo com a pessoa (fisica ou juridica) selecionada
*/
function verificarSeCpfOuCnpjParaAplicarMascara() {
    $('#documento').on('input', function () {
        if (verificarSelectPessoa() == 'J') {
            $('#documento').mask('00.000.000/0000-00');
        } else {
            $('#documento').mask('000.000.000-00');
        }
    });
}

function aplicarMascaras() {
    /* cpf ou cnpj*/
    verificarSeCpfOuCnpjParaAplicarMascara();

    /* celular */
    $('#celular').mask('(00) 0 0000-0000');

    /* telefone fixo */
    $('#telefone_fixo').mask('(00) 0000-0000');

    /* cep */
    $('#cep').mask('00.000-000');

    $('#data_nascimento').mask('00/00/0000');
}

/*
    Verifico qual pessoa (Fisica ou Juridica) esta selecionada para alterar o label do input
    e tambem informar para o input documento qual formatação usar
 */
function verificarSelectPessoa() {
    let pessoa = $('#pessoa').val();
    pessoa == 'J' ? $('#labelDocumento').html('CNPJ: *') : $('#labelDocumento').html('CPF: *');
    return pessoa;
}
