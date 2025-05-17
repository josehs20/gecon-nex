<?php

namespace Modules\Mercado\Http\Controllers\Configuracoes;

use App\Application\UsuarioGeconApplication;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\System\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\UseCases\Usuario\Requests\CriarUsuarioGeconRequest;
use Illuminate\Support\Facades\Log;

class ConfiguracaoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return redirect()->route('configuracoes.perfil');
    }

    /**
     * Retorna a tela com os dados do usuario
     */
    public function perfil(){
        $usuario = auth()->user()->usuarioMercado->master;
        return view('mercado::configuracoes.perfil', ['usuario' => $usuario]);
    }

    /**
     * Caso o usuario atualize os dados de seu perfil
     */
    public function perfil_store(Request $request){
        DB::connection(config('database.connections.gecon.database'))->beginTransaction();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $usuarioGecon = UsuarioGeconApplication::getUsuarioGeconById((int)$parans->usuario_id);

            if (Hash::check($parans->senha, $usuarioGecon->password)) {
                UsuarioGeconApplication::editarUsuario(
                    $parans->usuario_id,
                    new CriarUsuarioGeconRequest(
                        $parans->nome,
                        $parans->email,
                        '',
                        $usuarioGecon->modulo_id,
                        $usuarioGecon->tipo_usuario_id,
                        $usuarioGecon->permite_abrir_caixa
                    )
                );
                DB::connection(config('database.connections.gecon.database'))->commit();
                return response()->json(['success' => true, 'message' => 'Usuário atualizado com sucesso.']);
            } else {
                return response()->json(['success' => false, 'message' => 'Senha incorreta!']);
            }

        } catch (\Exception $e) {
            DB::connection(config('database.connections.gecon.database'))->rollBack();
            Log::error($e);
            return response()->json(['message' => 'Erro ao atualizar usuário! Erro: ' . $e->getMessage()], 500);
        }

    }

    /**
     * Caso o usuario va em alterar senha
     */
    public function alterar_senha_store(Request $request){
        DB::connection(config('database.connections.gecon.database'))->beginTransaction();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $usuarioGecon = UsuarioGeconApplication::getUsuarioGeconById((int)$parans->usuario_id);

            if (!(empty($parans->repetir_nova_senha))) {
                if (Hash::check($parans->senha_atual, $usuarioGecon->password)) {
                    UsuarioGeconApplication::editarUsuario(
                        $parans->usuario_id,
                        new CriarUsuarioGeconRequest(
                            $usuarioGecon->name,
                            $usuarioGecon->email,
                            $parans->repetir_nova_senha,
                            $usuarioGecon->modulo_id,
                            $usuarioGecon->tipo_usuario_id,
                            $usuarioGecon->permite_abrir_caixa
                        )
                    );
                    DB::connection(config('database.connections.gecon.database'))->commit();
                    return response()->json(['success' => true, 'message' => 'Senha alterada com sucesso.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Senha atual incorreta! Verifique e tente novamente!']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'A nova senha não poder estar vazia!']);
            }

        } catch (\Exception $e) {
            DB::connection(config('database.connections.gecon.database'))->rollBack();
            Log::error($e);
            return response()->json(['message' => 'Erro ao atualizar senha! Erro: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
