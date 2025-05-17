<?php

namespace App\Http\Controllers\Usuario;

use App\Application\UsuarioApplication;
use App\Http\Controllers\ControllerBase;
use App\Mail\RecuperarSenhaMail;
use App\Models\Empresa;
use App\Models\User;
use App\Repository\Usuario\UsuarioRepository;
use App\System\Post;
use App\UseCases\Usuario\Requests\ObterUsuariosRequest;
use App\UseCases\Usuario\Requests\PreencherListagemUsuariosDatatablesRequest;
use App\UseCases\Usuario\Requests\UsuarioRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\TratarCriacaoDeEnderecoRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UsuarioController extends ControllerBase
{
    private function isAdmin(): bool{
        return auth()->user()->isAdmin();
    }
    private function isUsuarioMaster(): bool{
        return auth()->user()->isUsuarioMaster();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        if($this->isAdmin()){
            return view('admin.usuarios.index');
        }
        return view('mercado::gerenciamento.usuarios.index');
    }

    public function obter_usuarios(){
        $loja_id = auth()->user()->usuarioMercado->loja_id;
        $usuarios = UsuarioApplication::obterUsuarios(
            new ObterUsuariosRequest(
                $loja_id,
                $this->isAdmin()
            )
        );
        $usuarios_datatables = UsuarioApplication::preencherListagemUsuariosDatatables(
            new PreencherListagemUsuariosDatatablesRequest(
                $usuarios,
                $this->isAdmin(),
                $this->isUsuarioMaster()
            )
        );
        return response()->json(['data' => $usuarios_datatables]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->isAdmin()){
            $empresas = Empresa::with(['lojas'])->get();
            return view('admin.usuarios.create', ['empresas' => $empresas]);
        }

        $user = auth()->user();
        $empresa = $user->empresa;
        $loja = $user->usuarioMercado->loja;
        $modulo = $user->modulo;
        
        return view('mercado::gerenciamento.usuarios.create', [
            'usuario_logado' => $user,
            'empresa' => $empresa,
            'loja' => $loja,
            'modulo' => $modulo
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {
            $processo_id = config('config.processos.gerenciamento.usuarios.id');
            $acao_id = config('config.acoes.cadastrou_usuario.id');
            $usuario_id = auth()->user()->id;
            $comentario = null;            
            $historicoRequest = new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id, $comentario);
            $parans = (object) Post::anti_injection_array($request->all());      
            
            $endereco = EnderecoApplication::tratarCriacaoDeEndereco(
                new TratarCriacaoDeEnderecoRequest(
                    $historicoRequest,
                    $parans->logradouro ?? null,
                    $parans->cidade ?? null,
                    $parans->bairro ?? null,
                    $parans->uf ?? null,
                    $parans->cep ?? null,
                    $parans->numero ?? null,
                    $parans->complemento ?? null,
                    $parans->endereco_id ?? null
                )
            );

            $user = UsuarioApplication::criar(
                new UsuarioRequest(
                    $parans->name,
                    $parans->login,
                    $parans->email,
                    $parans->password,
                    $parans->modulo_id,
                    $parans->permite_abrir_caixa ?? false,
                    $parans->tipo_usuario_id,
                    $parans->empresa_id,
                    $parans->loja_id,
                    $endereco->id ?? null,
                    $parans->status_id,
                    $parans->data_nascimento,
                    $parans->documento,
                    $parans->telefone,
                    $parans->celular,
                    $parans->ativo ?? false,
                    $parans->data_admissao,
                    moedaBrToMoedaPadraoBancoDeDados($parans->salario),
                    $parans->tipo_contrato,
                    $parans->data_demissao,
                    moedaBrToMoedaPadraoBancoDeDados($parans->comissao),
                    $historicoRequest
                )
            );

            $this->getDb()->commit();
            session()->flash('success', 'Usuário cadastrado com sucesso.');
            return redirect()->route('cadastro.gecon.usuarios.index');

        } catch (\Exception $ex) {
            $this->getDb()->rollBack();
            Log::error($ex);
            session()->flash('error', 'Não foi possível cadastrar o usuário! ' . $ex->getMessage());
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $usuario_master_cod
     * @return \Illuminate\Http\Response
     */
    public function edit($usuario_master_cod)
    {
        if($this->isAdmin()){
            $empresas = Empresa::with(['lojas'])->get();
            $user = UsuarioRepository::obterUsuarioPorUsuarioMasterCod($usuario_master_cod);
            $endereco = $user->usuarioMercado->enderecos;
            return view('admin.usuarios.edit', ['empresas' => $empresas, 'user' => $user, 'endereco' => $endereco]);
        }
        $user = UsuarioRepository::obterUsuarioPorUsuarioMasterCod($usuario_master_cod);
        $empresa = $user->empresa;
        $loja = $user->usuarioMercado->loja;
        $endereco = $user->usuarioMercado->enderecos;
        $modulo = $user->modulo;
        $usuario_logado = auth()->user();
        return view('mercado::gerenciamento.usuarios.edit',['empresa' => $empresa, 'loja' => $loja, 'modulo' => $modulo , 'user' => $user, 'usuario_logado' => $usuario_logado, 'endereco' => $endereco]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $usuario_master_cod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $usuario_master_cod)
    {
        $this->getDb()->begin();
        try {
            $processo_id = config('config.processos.gerenciamento.usuarios.id');
            $acao_id = config('config.acoes.atualizou_usuario.id');
            $usuario_id = auth()->user()->id;
            $comentario = null;            
            $historicoRequest = new CriarHistoricoRequest($processo_id, $acao_id, $usuario_id, $comentario);
            $parans = (object) Post::anti_injection_array($request->all());      

            $user = UsuarioRepository::obterUsuarioPorUsuarioMasterCod($usuario_master_cod);
            
            $endereco = EnderecoApplication::tratarCriacaoDeEndereco(
                new TratarCriacaoDeEnderecoRequest(
                    $historicoRequest,
                    $parans->logradouro ?? null,
                    $parans->cidade ?? null,
                    $parans->bairro ?? null,
                    $parans->uf ?? null,
                    $parans->cep ?? null,
                    $parans->numero ?? null,
                    $parans->complemento ?? null,
                    optional($user->usuarioMercado->enderecos)->id
                )
            );
            
            $user = UsuarioApplication::atualizar(
                new UsuarioRequest(
                    $parans->name,
                    $parans->login,
                    $parans->email,
                    'Não se atualiza a senha aqui',
                    $parans->modulo_id,
                    $parans->permite_abrir_caixa ?? false,
                    $parans->tipo_usuario_id,
                    $parans->empresa_id,
                    $parans->loja_id,
                    optional($endereco)->id,
                    $parans->status_id,
                    $parans->data_nascimento,
                    $parans->documento,
                    $parans->telefone,
                    $parans->celular,
                    $parans->ativo ?? false,
                    $parans->data_admissao,
                    moedaBrToMoedaPadraoBancoDeDados($parans->salario),
                    $parans->tipo_contrato,
                    $parans->data_demissao,
                    moedaBrToMoedaPadraoBancoDeDados($parans->comissao),
                    $historicoRequest,
                    $usuario_master_cod
                )
            );
            
            $this->getDb()->commit();
            session()->flash('success', 'Usuário atualizado com sucesso.');
            return redirect()->route('cadastro.gecon.usuarios.index');

        } catch (\Exception $ex) {
            $this->getDb()->rollBack();
            Log::error($ex);
            session()->flash('error', 'Não foi possível atualizar o usuário! ' . $ex->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function recupera_senha()
    {
        return view('auth.recuperar_senha');
    }

    public function email_recupera_senha(Request $request)
    {
        try {

            $usuario = User::where('email', $request->email)->first();
            if (!$usuario) {
                return redirect()->back()
                    ->withErrors(['email' => 'O email fornecido não está cadastrado!'])
                    ->withInput();
            }

            // Armazena o token no banco de dados ou em um serviço de cache (por exemplo, Redis)
            // Aqui assumimos que você tem uma tabela de 'password_resets'
            $token = Hash::make('token_verificao');
            DB::table('password_resets')->insert([
                'email' => $usuario->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            Mail::to($usuario->email)->send(new RecuperarSenhaMail($usuario, $token));
            return redirect()->back()
                ->with('success', 'Um link para recuperação de senha foi enviado para o seu e-mail.');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()
                ->withErrors(['email' => $e->getMessage()])
                ->withInput();
        }
    }

    public function atualizar_senha($token)
    {
        // Verifica o token no banco de dados e se foi criado nos últimos 15 minutos
        $tokenData = DB::table('password_resets')
            ->where('token', $token) // Valida o token específico
            ->where('created_at', '>=', now()->subMinutes(15)) // Verifica a validade
            ->first();

        if (!$tokenData) {
            return redirect()->route('recuperar.senha')
                ->withErrors(['email' => 'Token expirado ou inválido.'])
                ->withInput();
        }

        $usuario = User::where('email', $token->email)->first();

        if (!$usuario) {
            return redirect()->route('recuperar.senha')
                ->withErrors(['email' => 'Usuário não encontrado.'])
                ->withInput();
        }

        return view('auth.recuperar_senha_index', compact('usuario', 'token'));
    }

    public function atualizar_senha_post(Request $request)
    {
        $token = DB::table('password_resets')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$token) {
            return redirect()->back()
                ->withErrors(['email' => 'Token expirado ou inválido.'])
                ->withInput();
        }

        $usuario = User::where('email', $token->email)->first();

        if (!$usuario) {
            return redirect()->back()
                ->withErrors(['email' => 'Usuário não encontrado.'])
                ->withInput();
        }
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8',
            'confirmar' => 'required|same:password',
        ], [
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo :min caracteres.',
            'confirmar.required' => 'O campo de confirmação de senha é obrigatório.',
            'confirmar.same' => 'A confirmação de senha deve ser igual à senha.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('email', $token->email)->update(['password' => Hash::make($request->password)]);
        return redirect()->route('login');
    }
}
