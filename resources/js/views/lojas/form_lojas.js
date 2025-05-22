import jQuery from 'jquery';
const $ = jQuery;
import {
    montaDatatableYajra,
    constructSelect2,
    maskCNPJById,
    maskTelefoneById,
    bloquear,
    desbloquear,
    msgToastr
} from '../../gerais.js';
var routeGetDataEmpresa = '/admin/admin/empresa/api/brasil/get';

constructSelect2('status');
maskCNPJById('cnpj');
maskTelefoneById('telefone');


$('#cnpj').on('input', function () {
    var cnpj = $(this).val().replace(/\D/g, ''); // Remove qualquer caractere não numérico

    if (cnpj.length === 14) {
        bloquear();
        // Quando o CNPJ tiver 14 dígitos, fazer a consulta Ajax
        $.ajax({
            url: routeGetDataEmpresa, // A URL para a qual a requisição será feita
            method: 'GET', // Ou 'POST', dependendo do seu caso
            data: {
                cnpj: cnpj
            },
            success: function (response) {
                // Aqui você pode manipular a resposta da consulta, por exemplo:
                if (response.success == true) {
                    msgToastr(response.msg, 'info');
                    const empresa = response.empresa;
                    const endereco = {
                        logradouro: empresa.logradouro,
                        numero: empresa.numero,
                        complemento: empresa.complemento,
                        bairro: empresa.bairro,
                        municipio: empresa.municipio,
                        uf: empresa.uf,
                        cep: empresa.cep,
                        tipoLogradouro: empresa.descricao_tipo_de_logradouro
                    };

                    $('#nome').val(empresa.razao_social);
                    $('#email').val(empresa.email);
                    $('#telefone').val(empresa.ddd_telefone_1 ?? empresa
                        .ddd_telefone_2);
                    $('#telefone').trigger('input');
                    //preenche endereco

                    $('#logradouro').val(endereco.logradouro);
                    $('#numero').val(endereco.numero);
                    $('#complemento').val(endereco.complemento);
                    $('#bairro').val(endereco.municipio);
                    $('#cidade').val(endereco.municipio)
                    $('#uf').val(endereco.uf);
                    $('#cep').val(endereco.cep);

                } else {
                    msgToastr(response.msg, 'warning');
                    $('input').val('');


                }
            },
            error: function (xhr, status, error) {
                // Lidar com erros da requisição
                msgToastr('Erro na consulta: ', 'error');
                $('input').val('');

            },
            complete: function () {
                // Esta função será chamada independentemente de sucesso ou erro
                desbloquear(); // Desbloqueia após a resposta do servidor (ou erro)
            }
        });
    }
});
