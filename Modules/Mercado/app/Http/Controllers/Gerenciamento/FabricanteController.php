<?php

namespace Modules\Mercado\Http\Controllers\Gerenciamento;

use App\System\Post;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Mercado\Application\EnderecoApplication;
use Modules\Mercado\Application\FabricanteApplication;
use Modules\Mercado\Application\UnidadeMedidaApplication;
use Modules\Mercado\Http\Controllers\ControllerBaseMercado;
use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\Repository\Fabricante\FabricanteRepository;
use Modules\Mercado\Repository\UnidadeMedida\UnidadeMedidaRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\AtualizarFabricanteRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fabricante\Requests\CriarFabricanteRequest;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\CriarUnidadeMedidaRequest;
use Modules\Mercado\UseCases\Gerenciamento\UnidadeMedida\Requests\EditarUnidadeMedidaRequest;

class FabricanteController extends ControllerBaseMercado
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('mercado::gerenciamento.fabricante.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('mercado::gerenciamento.fabricante.create');
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
            $parans = (object)Post::anti_injection_array($request->all());
            $empresa_master_cod = auth()->user()->empresa_id;
            $historico = $this->getCriarHistoricoRequest($request);
            $endereco = $this->gerarEndereco($parans, $historico);

            $fabricante = FabricanteApplication::criarFabricante(
                new CriarFabricanteRequest(
                    $historico,
                    $parans->nome,
                    $parans->descricao,
                    limparCaracteres($parans->cnpj),
                    $parans->razao_social,
                    $parans->inscricao_estadual,
                    $endereco ? $endereco->id : null,
                    limparCaracteres($parans->celular),
                    limparCaracteres($parans->telefone),
                    $parans->email,
                    $parans->site,
                    $parans->ativo,
                    $empresa_master_cod
                )
            );
        
            $this->getDb()->commit();
            session()->flash('success', 'Fabricante cadastrado com sucesso!');

            return response()->json('Fabricante tualizado com sucesso');
        } catch (\Exception $e) {
            $this->getDb()->rollBack();
            session()->flash('error', 'Não foi possível cadastrar o fabricante. Motivo: ' . $e->getMessage());
            Log::error($e);
            return response()->json('Não foi possível atualizar os dados do fabricante! Motivo: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($fabricante_id)
    {
        $fabricante = FabricanteRepository::getFabricantePorId($fabricante_id);
        
        $endereco = $fabricante->endereco ? EnderecoRepository::getEnderecoById($fabricante->endereco_id) : null;
        
        return view('mercado::gerenciamento.fabricante.edit', ['fabricante' => $fabricante, 'endereco' => $endereco]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, int $fabricante_id, int $endereco_id)
    {
        $this->getDb()->begin();

        try {
            $parans = (object)Post::anti_injection_array($request->all());
            $empresa_master_cod = auth()->user()->empresa_id;
            $historico = $this->getCriarHistoricoRequest($request);
            $endereco = $this->gerarOuAtualizarEndereco($endereco_id,$parans, $historico);
            $ativo = filter_var($parans->ativo, FILTER_VALIDATE_BOOLEAN);

            FabricanteApplication::atualizarFabricante(
                new AtualizarFabricanteRequest(
                    $fabricante_id,
                    $historico,
                    $parans->nome,
                    $parans->descricao,
                    limparCaracteres($parans->cnpj),
                    $parans->razao_social,
                    $parans->inscricao_estadual,
                    $endereco ? $endereco->id : null,
                    limparCaracteres($parans->celular),
                    limparCaracteres($parans->telefone),
                    $parans->email,
                    $parans->site,
                    $ativo,
                    $empresa_master_cod
                )
            );
            
            $this->getDb()->commit();
            session()->flash('success', 'Fabricante atualizado com sucesso!');
            return response()->json();
        } catch (\Exception $e) {
            debugException($e);
            $this->getDb()->rollBack();
            session()->flash('error', 'Não foi possível atualizar os dados do fabricante!. Motivo: ' . $e->getMessage());
            Log::error($e);
            return response()->json();
        }
    }

    private function gerarOuAtualizarEndereco(int $endereco_id, $parans, $historico){
        if($endereco_id == 0){
            return $this->gerarEndereco($parans, $historico);
        } else {
            return EnderecoApplication::atualizarEndereco(
                new EnderecoRequest(
                    $historico,
                    $parans->logradouro,
                    $parans->cidade,
                    $parans->bairro,
                    $parans->uf,
                    $parans->cep,
                    $parans->numero,
                    $parans->complemento
                ), $endereco_id);
        }
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
        return null;
    }

    private function verificarExistenciaDeEnderecoNoRequest($parans, $campos)
    {
        // Retorna true se todos os campos estão preenchidos ou se nenhum está preenchido
        return $this->contagemDeCampos($campos) === 0 || $this->contagemDeCampos($campos) === count($campos);
    }

    private function contagemDeCampos($campos){
        return count(array_filter($campos, fn($campo) => !empty($campo)));
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
