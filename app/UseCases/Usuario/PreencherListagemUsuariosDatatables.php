<?php

namespace App\UseCases\Usuario;

use App\Models\User;
use App\UseCases\Usuario\Requests\PreencherListagemUsuariosDatatablesRequest;

class PreencherListagemUsuariosDatatables
{
    private PreencherListagemUsuariosDatatablesRequest $request;

    public function __construct(PreencherListagemUsuariosDatatablesRequest $request)
    {
       $this->request = $request;
    }

    public function handle()
    {
       return $this->preencherDadosDosUsuarios();
    }

    private function preencherDadosDosUsuarios(){        
        return $this->request->getUsuarios()->map(function($usuario){
            if($this->verificarUsuarioLogadoParaSaberQuaisUsuariosExibir($usuario)){
                
                $dados = $this->request->isAdmin()
                    ? $this->renderizarTabelaUsuariosParaAdmin($usuario)
                    : $this->renderizarTabelaUsuariosParaOutrosUsuarios($usuario);
                
                $dados[] = $this->renderizarBotao($usuario);    

                return $dados;
            }
        })->filter()->values();
    }

    private function verificarUsuarioLogadoParaSaberQuaisUsuariosExibir(User $usuario){
        if($this->request->isAdmin()){ /** Se admin */
            return true; /** Exibi todos usuários */
        }
        if($this->request->isUsuarioMaster()){ /** Se cliente master */
            return !$usuario->isAdmin(); /**Ignora admin */
        }
        return !$usuario->isAdmin() && !$usuario->isUsuarioMaster(); /** Se usuario qualquer: Ignora admin e cliente master */
    }

    private function renderizarBotao(User $usuario){
        return $this->botaoEditar($usuario->id) . $this->botaoVisualizar($usuario);
    }

    private function botaoEditar(int $usuario_master_cod){
        return "
            <a class='btn btn-warning' title='Editar' href='" . route('gecon.usuarios.edit', ['usuario_master_cod' => $usuario_master_cod]) . "'>
                <i class='bi bi-pencil'></i>
            </a>
        ";
    }

    private function botaoVisualizar(User $usuario){
        $usuario = htmlspecialchars(json_encode($usuario), ENT_QUOTES, 'UTF-8');
        return "
            <button type='button' class='btn btn-info' title='Visualizar' onclick='showUsuario($usuario)'>
                <i class='bi bi-eye'></i>
            </button>
        ";
    }

    private function renderizarTabelaUsuariosParaAdmin(User $usuario){
        return [
            $usuario->id,
            $usuario->name,
            $usuario->login,
            aplicarMascaraDocumento($usuario->usuarioMercado->documento),
            $usuario->tipoUsuario->descricao,
            $usuario->empresa->nome_fantasia ?? '',
            getSpanAtivo($usuario->usuarioMercado->ativo),
            strtoupper($usuario->modulo->nome)
        ];
    }

    private function renderizarTabelaUsuariosParaOutrosUsuarios(User $usuario){
        return [
            $usuario->id,
            $usuario->name,
            $usuario->login,
            aplicarMascaraDocumento($usuario->usuarioMercado->documento),
            $usuario->tipoUsuario->descricao,
            getSpanAtivo($usuario->usuarioMercado->ativo)
        ];
    }

}
