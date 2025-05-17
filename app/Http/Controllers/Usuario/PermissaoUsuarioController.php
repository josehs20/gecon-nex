<?php

namespace App\Http\Controllers\Usuario;

use App\Application\PermissaoUsuarioApplication;
use App\Http\Controllers\ControllerBase;
use App\UseCases\PermissaoUsuario\Requests\AdicionarPermissaoRequest;
use App\UseCases\PermissaoUsuario\Requests\BuscarPermissoesPorTipoUsuarioIdRequest;
use App\UseCases\PermissaoUsuario\Requests\RemoverPermissaoRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissaoUsuarioController extends ControllerBase
{
    private function isAdmin(): bool{
        return auth()->user()->isAdmin();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if($this->isAdmin()){
            return view('admin.permissoes.index');
       }
       return view('mercado::gerenciamento.permissoes.index');
    }

    public function buscar_permissoes_por_tipo_usuario_id(int $tipo_usuario_id){
        return PermissaoUsuarioApplication::buscarPermissoesPorTipoUsuarioId(
            new BuscarPermissoesPorTipoUsuarioIdRequest(
                $tipo_usuario_id
            )
        );
    }

    public function buscar_permissoes(int $tipo_usuario_id){
        return PermissaoUsuarioApplication::buscarPermissoes(
            new BuscarPermissoesPorTipoUsuarioIdRequest(
                $tipo_usuario_id
            )
        );
    }

    public function adicionar(Request $request, int $processo_id, int $tipo_usuario_id){
        $this->getDb()->begin();
        try {
            PermissaoUsuarioApplication::adicionarPermissao(
                new AdicionarPermissaoRequest(
                    $processo_id,
                    $tipo_usuario_id
                )
            );
            $this->getDb()->commit();
            return response()->json([
                'success' => true,
                'tipo_usuario_id' => $tipo_usuario_id,
                'msg' => 'Permissão adicionada com sucesso!'
            ]);
        } catch (\Exception $ex) {
            $this->getDb()->rollBack();
            Log::error($ex);
            return response()->json([
                'success' => false,
                'msg' => $ex->getMessage()
            ]);
        }
    }

    public function remover(Request $request, int $processo_id, int $tipo_usuario_id){
        $this->getDb()->begin();
        try {
            PermissaoUsuarioApplication::removerPermissao(
                new RemoverPermissaoRequest(
                    $processo_id,
                    $tipo_usuario_id
                )
            );
            $this->getDb()->commit();
            return response()->json([
                'success' => true,
                'tipo_usuario_id' => $tipo_usuario_id,
                'msg' => 'Permissão removida com sucesso!'
            ]);
        } catch (\Exception $ex) {
            $this->getDb()->rollBack();
            Log::error($ex);
            return response()->json([
                'success' => false,
                'msg' => $ex->getMessage()
            ]);
        }
    }

}
