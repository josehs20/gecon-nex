
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

document.addEventListener('DOMContentLoaded', () => {
    const appData = document.getElementById('app_data');
    const empresaId = appData?.dataset.empresaId;  // aqui acessa exatamente "empresaId"

    if (empresaId) {
        const routeEmpresaGetLojas = '/yajra/empresas/lojas/get/' + empresaId;

        const columns = [
            ['id', '#'],
            ['nome', 'Nome'],
            ['cnpj', 'CNPJ'],
            ['status.descricao', 'Status'],
            ['acao', 'Ação', false, false]
        ];

        montaDatatableYajra('tabela-lojas', columns, routeEmpresaGetLojas);
    }

});

constructSelect2('modulo_id');
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

                    $('#razao_social').val(empresa.razao_social);
                    $('#nome_fantasia').val(empresa.nome_fantasia);
                    $('#email').val(empresa.email);
                    $('#telefone').val(empresa.ddd_telefone_1 ?? empresa
                        .ddd_telefone_2);
                    $('#telefone').trigger('input');
                    $('#endereco_brasil_api').val(JSON.stringify(endereco));
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
