<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Endereco;

use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\TratarCriacaoDeEnderecoRequest;

class TratarCriacaoDeEndereco
{
    private TratarCriacaoDeEnderecoRequest $request;

    public function __construct(TratarCriacaoDeEnderecoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        if(!$this->validarInputsEndereco()){
            throw new \Exception("Para salvar com endereço, apenas número e complemento são opcionais!", 400);
        }
        
        if(!$this->todosPreenchidos($this->inputsObrigatorios())){
            return null;
        }
        
        return $this->tratar();
    }   

    private function tratar(){
        if($this->request->getEnderecoId()){
            return $this->atualizarEndereco();
        }

        return $this->criarEndereco();
    }

    private function atualizarEndereco(){
        return EnderecoRepository::update(
            $this->request->getEnderecoId(),
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLogradouro(),
            $this->request->getNumero(),
            $this->request->getCidade(),
            $this->request->getBairro(),
            $this->request->getUf(),
            limparCaracteres($this->request->getCep()),
            $this->request->getComplemento()
        );
    }

    private function criarEndereco()
    {
        return EnderecoRepository::create(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getLogradouro(),
            $this->request->getNumero(),
            $this->request->getCidade(),
            $this->request->getBairro(),
            $this->request->getUf(),
            limparCaracteres($this->request->getCep()),
            $this->request->getComplemento()
        );
    }

    private function validarInputsEndereco(): bool
    {
        $inputs_obrigatorios = $this->inputsObrigatorios();    
        $inputs_opcionais = $this->inputsOpcionais();
    
        $algum_obrigatorio = $this->peloMenosUmPreenchido($inputs_obrigatorios);
    
        if (!$algum_obrigatorio) {            
            $algum_opcional = $this->peloMenosUmPreenchido($inputs_opcionais);    
            return !$algum_opcional;
        }
    
        return $this->todosPreenchidos($inputs_obrigatorios);
    }

    private function inputsObrigatorios(){
        return [
            'logradouro' => $this->request->getLogradouro(),
            'cidade'     => $this->request->getCidade(),
            'bairro'     => $this->request->getBairro(),
            'uf'         => $this->request->getUf(),
            'cep'        => $this->request->getCep(),
        ];
    }

    private function inputsOpcionais(){
        return [
            'numero'      => $this->request->getNumero(),
            'complemento' => $this->request->getComplemento(),
        ];
    }

    private function peloMenosUmPreenchido(array $inputs){
        return collect($inputs)->contains(fn($input) => !blank($input));
    }

    private function todosPreenchidos(array $inputs){
        return collect($inputs)->every(fn($input) => !blank($input));
    }
}
