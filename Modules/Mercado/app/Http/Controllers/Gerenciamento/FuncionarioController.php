<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\Application\UsuarioGeconApplication;
use App\System\Post;
use App\UseCases\Usuario\Requests\CriarUsuarioGeconRequest;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Application\FuncionarioApplication;
use Modules\Mercado\Application\SenhaApplication;
use Modules\Mercado\Entities\Usuario;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Funcionario\Requests\FuncionarioRequest;
use Modules\Mercado\UseCases\Senha\Requests\ConfirmarComSenhaRequest;
use Modules\Mercado\UseCases\Usuario\Requests\CriarUsuarioRequest;
use Illuminate\Support\Str;
use Modules\Mercado\Application\PermissaoUsuarioApplication;

class FuncionarioController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mercado::gerenciamento.funcionario.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mercado::gerenciamento.funcionario.create');
    }

    /**
     * Lista os funcionarios no datatables
     */
    public function listarFuncionarios(Request $request)
    {
        /* convertendo valor para booleano */
        $ativo = filter_var($request->input('funcionariosInativos'), FILTER_VALIDATE_BOOLEAN);
        $loja_id = auth()->user()->usuarioMercado->loja_id;

        $funcionarios = FuncionarioApplication::getTodosFuncionariosPorAtivo(!$ativo, $loja_id);
        return response()->json(['data' => $this->funcionariosParaDatatables($funcionarios)]);
    }

    public function funcionariosParaDatatables($funcionarios)
    {
        $data = [];
        $dadosFuncionarios = [];

        foreach ($funcionarios as $key => $funcionario) {
            if($funcionario->endereco_id){
                $endereco = EnderecoApplication::getEnderecoById($funcionario->endereco_id);
                $funcionario->endereco = $endereco;
            }

            if($funcionario->endereco){
                $endereco = $funcionario->endereco->logradouro   .
                ', Nº ' . $funcionario->endereco->numero     .
                ', '    . $funcionario->endereco->bairro     .
                ', '    . $funcionario->endereco->cidade     .
                '/'     . $funcionario->endereco->uf         .
                ', '    . aplicarMascaraCep($funcionario->endereco->cep)        .
                ', '    . $funcionario->endereco->complemento;
            } else {
                $endereco = 'Não informado';
            }

            $permissoes = PermissaoUsuarioApplication::get_permissoes($funcionario->tipo_usuario_id);

            $dadosFuncionarios = [
                'id' => $funcionario->id,
                'nome' => $funcionario->master->name,
                'login' => $funcionario->master->login,
                'documento' => $funcionario->documento,
                'tipo_usuario' => $funcionario->tipoUsuario->descricao,
                'celular' => $funcionario->celular,
                'telefone' => $funcionario->telefone,
                'email' => $funcionario->master->email,
                'data_nascimento' => $funcionario->data_nascimento,
                'data_admissao' => $funcionario->data_admissao,
                'data_demissao' => $funcionario->data_demissao,
                'salario' => $funcionario->salario,
                'tipo_contrato' => $funcionario->tipo_contrato,
                'comissao' => $funcionario->comissao,
                'endereco' => $endereco,
                'permissoes' => $this->formatarProcesso($permissoes)
            ];

            $botoes =
            '<a href="' . route('funcionarios.edit', ['funcionario_id' => $funcionario->id]) . '" type="button" class="btn btn-warning mx-2">
                <i class="bi bi-pencil"></i> Editar
            </a>' .
            '<button type="button" id="mostrarDadosFuncionario" data-info="' . htmlspecialchars(json_encode($dadosFuncionarios)) . '" class="btn btn-info mx-2">
                <i class="bi bi-eye"></i> Ver
            </button>';

            $data[] = [
                $funcionario->id,
                $funcionario->master->name,
                $funcionario->master->login,
                // aplicarMascaraDocumento($funcionario->documento),
                // aplicarMascaraCelular($funcionario->celular),
                // aplicarMascaraTelefoneFixo($funcionario->telefone),
                $funcionario->master->email,
                $funcionario->tipoUsuario->descricao,
                // $funcionario->data_nascimento,
                // $funcionario->data_admissao,
                // $funcionario->data_demissao,
                // $funcionario->salario,
                $funcionario->tipo_contrato,
                // $funcionario->comissao,
                // $endereco,
                $botoes
            ];
        }

        return $data;
    }

    private function formatarProcesso($permissoes){
        $funcionario = [];
        foreach ($permissoes as $key => $permissao) {
            $processo = $permissao->processo;

            if($processo->posicao_menu >= 1000 && $processo->posicao_menu < 2000){
                $funcionario[] =  'Cadastro/'.$processo->nome;
            }

            if($processo->posicao_menu >= 2000 && $processo->posicao_menu < 3000){
                $funcionario[] =  'Estoque/'.$processo->nome;
            }

            if($processo->posicao_menu >= 3000 && $processo->posicao_menu < 4000){
                $funcionario[] =  'Usuário/'.$processo->nome;
            }

            if($processo->posicao_menu >= 4000 && $processo->posicao_menu < 5000){
                $funcionario[] =  'PDV/'.$processo->nome;
            }
        }
        return $funcionario;
    }

    private function gerarEndereco($parans, $historico)
    {
        $campos = [
            $parans->logradouro,
            $parans->cidade,
            $parans->bairro,
            $parans->uf,
            $parans->cep
        ];

        if (!$this->verificarExistenciaDeEnderecoNoRequest($parans, $campos)) {
            throw new \Exception("Para salvar com endereço, somente o número e o complemento são opcionais! Para salvar sem endereço, deixe todos os campos vazios!", 1);
        }

        if(!($this->contagemDeCampos($campos) === 0)){
            return EnderecoApplication::criarEndereco(
                new EnderecoRequest(
                    $historico,
                    $parans->logradouro,
                    $parans->cidade,
                    $parans->bairro,
                    $parans->uf,
                    $parans->cep,
                    $parans->numero,
                    $parans->complemento
                )
            );
        }
    }

    private function verificarExistenciaDeEnderecoNoRequest($parans, $campos)
    {
        // Retorna true se todos os campos estão preenchidos ou se nenhum está preenchido
        return $this->contagemDeCampos($campos) === 0 || $this->contagemDeCampos($campos) === count($campos);
    }

    private function contagemDeCampos($campos){
        return count(array_filter($campos, fn($campo) => !empty($campo)));
    }

    private function criarUsuarioMaster($parans){
        $modulo_id = config('config.modulos.mercado');
        $usuario = auth()->user()->usuarioMercado;
        $permite_abrir_caixa = filter_var($parans->permite_abrir_caixa, FILTER_VALIDATE_BOOLEAN);

        $usuarioGecon = UsuarioGeconApplication::criarUsuario(
            new CriarUsuarioGeconRequest(
                $parans->nome,
                $parans->login,
                $parans->email,
                $parans->senha,
                $modulo_id,
                $parans->grupo_usuario_id,
                $usuario->master->empresa_id,
                $permite_abrir_caixa
            )
        );

        return $usuarioGecon;
    }

    private function verificarSenha(string $senha, string $confirmar_senha){
        if($senha !== $confirmar_senha){
            throw new \Exception("As senhas são diferentes!", 1);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $this->verificarSenha($parans->senha, $parans->confirmar_senha);
            $historico = $this->getCriarHistoricoRequest($request);
            $usuario_master_cod = $this->criarUsuarioMaster($parans)->id;

            $loja_id = $parans->lojas[0];
            $endereco = $this->gerarEndereco($parans, $historico);
            $ativo = filter_var($parans->ativo, FILTER_VALIDATE_BOOLEAN);

            FuncionarioApplication::createFuncionario(
                new FuncionarioRequest(
                    $historico,
                    $usuario_master_cod,
                    $loja_id,
                    $parans->grupo_usuario_id,
                    config('config.status.ativo'),
                    $parans->lojas,
                    $endereco->id ?? null,
                    $ativo,
                    converterDataParaSalvarNoBanco($parans->data_nascimento),
                    limparCaracteres($parans->documento),
                    limparCaracteres($parans->telefone),
                    limparCaracteres($parans->celular),
                    converterDataParaSalvarNoBanco($parans->data_admissao),
                    converteDinheiroParaFloat($parans->salario),
                    $parans->tipo_contrato ?? null,
                    converterDataParaSalvarNoBanco($parans->data_demissao),
                    converteDinheiroParaFloat($parans->comissao)
                )
            );

            $this->getDb()->commit();
            session()->flash('success', 'Funcionário adicionado com sucesso!');
            return redirect()->route('funcionarios.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();

            session()->flash('error', $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
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
    public function edit(int $funcionario_id)
    {
        $funcionario = FuncionarioApplication::getFuncionarioPorId($funcionario_id);
        if($funcionario->endereco_id){
            $endereco = EnderecoApplication::getEnderecoById($funcionario->endereco_id);
            return view('mercado::gerenciamento.funcionario.edit', compact('funcionario', 'endereco'));
        }
        return view('mercado::gerenciamento.funcionario.edit', compact('funcionario'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, int $funcionario_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $funcionario = FuncionarioApplication::getFuncionarioPorId($funcionario_id);
            $loja_id = $funcionario->loja_id;
            $ativo = filter_var($parans->ativo, FILTER_VALIDATE_BOOLEAN);
            $modulo_id = config('config.modulos.mercado');
            $permite_abrir_caixa = filter_var($parans->permite_abrir_caixa, FILTER_VALIDATE_BOOLEAN);
            /** ATUALIZA O FUNCIONARIO */
            $historico = $this->getCriarHistoricoRequest($request);
            FuncionarioApplication::editarFuncionario(
                $funcionario,
                new CriarUsuarioGeconRequest(
                    $parans->nome,
                    $parans->login,
                    $parans->email,
                    $funcionario->master->password,
                    $modulo_id,
                    $parans->grupo_usuario_id,
                    $funcionario->master->empresa_id,
                    $permite_abrir_caixa
                ),
                new CriarUsuarioRequest(
                    $historico,
                    $funcionario->usuario_master_cod,
                    $loja_id,
                    $parans->grupo_usuario_id,
                    config('config.status.ativo'),
                    [$funcionario->loja_id],
                    $funcionario->endereco_id ?? null,
                    $ativo,
                    converterDataParaSalvarNoBanco($parans->data_nascimento),
                    limparCaracteres($parans->documento),
                    limparCaracteres($parans->telefone),
                    limparCaracteres($parans->celular),
                    converterDataParaSalvarNoBanco($parans->data_admissao),
                    converteDinheiroParaFloat($parans->salario),
                    $parans->tipo_contrato ?? null,
                    converterDataParaSalvarNoBanco($parans->data_demissao),
                    converteDinheiroParaFloat($parans->comissao)
                )
            );

            /** ATUALIZA O ENDERECO DO FUNCIONARIO */
            if($funcionario->endereco_id !== null){
                EnderecoApplication::atualizarEndereco(
                    new EnderecoRequest(
                        $historico,
                        $parans->logradouro,
                        $parans->cidade,
                        $parans->bairro,
                        $parans->uf,
                        $parans->cep,
                        $parans->numero,
                        $parans->complemento
                    ),
                    $funcionario->endereco_id
                );
            }

            $this->getDb()->commit();
            session()->flash('success', 'Funcionário editado com sucesso!');
            return redirect()->route('funcionarios.index');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', $e->getMessage());
            Log::error($e);
            return redirect()->back();
        }
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
