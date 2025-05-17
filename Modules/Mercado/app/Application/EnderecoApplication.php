<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Repository\Endereco\EnderecoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\AtualizarEndereco;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\CriarEndereco;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\TratarCriacaoDeEndereco;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\TratarCriacaoDeEnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;

class EnderecoApplication
{
    public static function criarEndereco(EnderecoRequest $request)
    {
        $interact = new CriarEndereco($request);
        return $interact->handle();
    }

    public static function atualizarEndereco(EnderecoRequest $request, int $id)
    {
        $interact = new AtualizarEndereco($request, $id);
        return $interact->handle();
    }

    public static function getEnderecoById(int $endereco_id){    
        return EnderecoRepository::getEnderecoById($endereco_id);
    }

    /**
     * Responsável por validar e por
     * verificar se irá criar ou atualizar.
     */
    public static function tratarCriacaoDeEndereco(TratarCriacaoDeEnderecoRequest $request){
        $interact = new TratarCriacaoDeEndereco($request);
        return $interact->handle();
    }
}
