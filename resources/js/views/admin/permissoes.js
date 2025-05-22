import jQuery from 'jquery';
const $ = jQuery;
import { montaDatatable } from '../../gerais.js';

const ROTA_BUSCAR_PERMISSOES = '/usuarios/permissao/buscar_permissoes/TIPO_USUARIO_ID';
const ROTA_BUSCAR_PERMISSOES_POR_TIPO_USUARIO = '/usuarios/permissao/buscar_permissoes_por_tipo_usuario/TIPO_USUARIO_ID';
const ROTA_ADICIONAR = '/usuarios/permissao/adicionar/__processo_id__/__tipo_usuario_id__';
const ROTA_REMOVER = '/usuarios/permissao/remover/__processo_id__/__tipo_usuario_id__';

iniciarTabelas();
selecionarTipoUsuario();

function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function selecionarTipoUsuario() {
    $('#tipo_usuario_id').on('change', async function () {
        let tipo_usuario_id = $(this).val();
        let tipo_usuario_label = $(this).find("option:selected").text();
        $('#label-permissao-do-tipo-usuario').text(tipo_usuario_label);
        await buscarPermissoes(tipo_usuario_id);
    });
}

async function buscarPermissoes(tipo_usuario_id) {
    try {
        const URL_TODAS_PERMISSOES = ROTA_BUSCAR_PERMISSOES.replace('TIPO_USUARIO_ID', tipo_usuario_id);
        const URL_PERMISSAO_TIPO_USUARIO = ROTA_BUSCAR_PERMISSOES.replace('TIPO_USUARIO_ID',
            tipo_usuario_id);

        $("#tabela-permissoes-sistema").DataTable().clear().draw();
        $("#tabela-permissoes-do-usuario").DataTable().clear().draw();

        montaDatatable("tabela-permissoes-sistema", URL_TODAS_PERMISSOES);
        montaDatatable("tabela-permissoes-do-usuario", URL_PERMISSAO_TIPO_USUARIO);
    } catch (error) {
        iniciarTabelas();
        console.error('Não foi possível buscar as permissões: ', error);
        return [];
    }
}

function iniciarTabelas() {
    montaDatatable("tabela-permissoes-sistema");
    montaDatatable("tabela-permissoes-do-usuario");
}

async function adicionarPermissao(processo_id, tipo_usuario_id) {
    try {
        const URL = ROTA_ADICIONAR.replace('__processo_id__', processo_id).replace('__tipo_usuario_id__', tipo_usuario_id);

        const response = await fetch(URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });

        const data = await response.json();

        if (data.success) {
            toastr.success(data.msg);
            await buscarPermissoes(data.tipo_usuario_id);
        } else {
            toastr.warning(data.msg);
        }
    } catch (error) {
        toastr.error('Não foi possível adicionar permissão:');
    }
}

async function removerPermissao(processo_id, tipo_usuario_id) {
    try {
        const URL = ROTA_REMOVER.replace('__processo_id__', processo_id).replace('__tipo_usuario_id__', tipo_usuario_id);

        const response = await fetch(URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });

        const data = await response.json();

        if (data.success) {
            toastr.success(data.msg);
            await buscarPermissoes(data.tipo_usuario_id);
        } else {
            toastr.warning(data.msg);
        }
    } catch (error) {
        toastr.error('Não foi possível remover permissão!');
    }
}
