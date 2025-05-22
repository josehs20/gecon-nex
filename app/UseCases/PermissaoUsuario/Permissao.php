<?php

namespace App\UseCases\PermissaoUsuario;

use App\Repository\PermissaoUsuario\PermissaoUsuarioRepository;

class Permissao
{
    protected function obterPermissoes(int $tipo_usuario_id, bool $is_permissao_que_usuario_tem){

        if($is_permissao_que_usuario_tem){
            return $this->obterPermissoesQueTipoUsuarioTem($tipo_usuario_id);
        }
        return $this->obterPermissoesQueTipoUsuarioNaotem($tipo_usuario_id);
    }

    private function obterPermissoesQueTipoUsuarioNaotem(int $tipo_usuario_id){
        return PermissaoUsuarioRepository::obterTodasPermissoes($tipo_usuario_id);
    }

    private function obterPermissoesQueTipoUsuarioTem(int $tipo_usuario_id){
        return PermissaoUsuarioRepository::obterPermissoesPorTipoUsuarioId($tipo_usuario_id);
    }

    protected function renderizarBotao(int $processo_id, int $tipo_usuario_id, bool $shouldAdicionar){

        $classeBotao = $shouldAdicionar ? 'btn btn-success' : 'btn btn-danger';
        $icone = $shouldAdicionar ? 'bi bi-plus' : 'bi bi-trash';
        $onclick = $shouldAdicionar ? 'adicionarPermissao' : 'removerPermissao';

        return "
            <button
                class='$classeBotao'
                onclick='$onclick($processo_id, $tipo_usuario_id)'
            >
                <i class='$icone'></i>
            </button>
        ";
    }

    protected function permissao_existe(
        int $processo_id,
        int $tipo_usuario_id
    ){
        return PermissaoUsuarioRepository::permissaoExiste(
            $processo_id,
            $tipo_usuario_id
        );
    }
}
