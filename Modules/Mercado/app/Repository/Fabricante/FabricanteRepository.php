<?php

namespace Modules\Mercado\Repository\Fabricante;

use Modules\Mercado\Entities\Fabricante;

class FabricanteRepository
{
    public static function criar(
        string $nome,
        ?string $descricao = null,
        string $cnpj,
        string $razao_social,
        ?string $inscricao_estadual = null,
        ?int $endereco_id = null,
        ?string $celular = null,
        ?string $telefone = null,
        ?string $email = null,
        ?string $site = null,
        bool $ativo,
        int $empresa_master_cod
    ): ?Fabricante {
        return Fabricante::create([
            'nome' => $nome,
            'descricao' => $descricao,
            'cnpj' => $cnpj, 
            'razao_social' => $razao_social, 
            'inscricao_estadual' => $inscricao_estadual, 
            'endereco_id' => $endereco_id, 
            'celular' => $celular, 
            'telefone' => $telefone, 
            'email' => $email, 
            'site' => $site, 
            'ativo' =>$ativo,
            'empresa_master_cod' =>$empresa_master_cod,
        ]);
    }

    public static function getUnByDescricao(
        string $descricao
    ): ?Fabricante {
        return Fabricante::where('nome', $descricao)->first();
    }


    public static function getFabricanteLikeNome(
        ?string $nome = null
    ) {
        return Fabricante::where('nome', 'like', '%' . $nome . '%')
        ->get();
    }

    public static function getUnById(
        int $id
    ) {
        return Fabricante::find($id);
    }

    public static function getFabricantePorId(
        int $fabricante_id
    ): ?Fabricante{
        return Fabricante::find($fabricante_id);
    }

    public static function atualizar(
        int $fabricante_id,
        string $nome,
        ?string $descricao = null,
        string $cnpj,
        string $razao_social,
        ?string $inscricao_estadual = null,
        ?int $endereco_id = null,
        ?string $celular = null,
        ?string $telefone = null,
        ?string $email = null,
        ?string $site = null,
        bool $ativo,
        int $empresa_master_cod
    ){
      $fabricante = Fabricante::find($fabricante_id);
      $fabricante->update([
        'nome' => $nome,
        'descricao' => $descricao,
        'cnpj' => $cnpj, 
        'razao_social' => $razao_social, 
        'inscricao_estadual' => $inscricao_estadual, 
        'endereco_id' => $endereco_id, 
        'celular' => $celular, 
        'telefone' => $telefone, 
        'email' => $email, 
        'site' => $site, 
        'ativo' =>$ativo,
        'empresa_master_cod' =>$empresa_master_cod,
      ]);  
      return $fabricante;
    }
}
