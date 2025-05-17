<?php

namespace Modules\Mercado\Repository\Fornecedor;

use Modules\Mercado\Entities\Fornecedor;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class FornecedorRepository
{

    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        $empresa_master_cod,
        $nome,
        $nome_fantasia,
        $documento,
        $pessoa,
        $ativo,
        $celular = null,
        $telefone_fixo = null,
        $email = null,
        $site = null,
        $endereco_id = null
    ) {
        Fornecedor::setHistorico($criarHistoricoRequest);
        return Fornecedor::create([
            'empresa_master_cod' => $empresa_master_cod,
            'nome' => $nome,
            'nome_fantasia' => $nome_fantasia,
            'documento' => $documento,
            'pessoa' => $pessoa,
            'ativo' => $ativo,
            'celular' => $celular,
            'telefone_fixo' => $telefone_fixo,
            'email' => $email,
            'site' => $site,
            'endereco_id' => $endereco_id,
        ]);
    }

    public static function update(
        CriarHistoricoRequest $criarHistoricoRequest,
        $empresa_master_cod,
        $id,
        $nome,
        $nome_fantasia,
        $documento,
        $pessoa,
        $ativo,
        $celular,
        $telefone_fixo,
        $email,
        $site,
        $endereco_id
    ) {
        $fornecedor = Fornecedor::find($id);
        Fornecedor::setHistorico($criarHistoricoRequest);

        $fornecedor->update([
            'empresa_master_cod' => $empresa_master_cod,
            'nome' => $nome,
            'nome_fantasia' => $nome_fantasia,
            'documento' => $documento,
            'pessoa' => $pessoa,
            'ativo' => $ativo,
            'celular' => $celular,
            'telefone_fixo' => $telefone_fixo,
            'email' => $email,
            'site' => $site,
            'endereco_id' => $endereco_id,
        ]);
    }

    public static function getFornecedorById(int $id)
    {
        return Fornecedor::with('endereco')->find($id);
    }

    /* Obtém todos fornecedores pela coluna ativo, retornar os ativos ou inativos, depende do valor passado */
    public static function getTodosFornecedoresPorAtivo(bool $ativo)
    {
        return Fornecedor::with('endereco')->where('ativo', $ativo)->get();
    }

    public static function getFornecedorByEmpresaId(int $empresa_master_cod)
    {
        return Fornecedor::with('endereco')->where('empresa_master_cod', $empresa_master_cod)->where('ativo', true)->get();
    }

    public static function getFornecedorByDocumento(string $documento, int $empresa_master_cod)
    {
        return Fornecedor::with('endereco')->where('documento', $documento)->where('ativo', true)->where('empresa_master_cod', $empresa_master_cod)->first();
    }

    public static function getFornecedores(int $empresa_master_cod, ?string $busca = '', $limit = 100)
    {
        return Fornecedor::with('endereco')->limit($limit)
            ->where(function ($query) use ($busca) {
                $query->where('documento', 'like', formataLikeSql($busca))
                    ->orWhere('nome', 'like', formataLikeSql($busca));
            })
            ->where('ativo', true)
            ->where('empresa_master_cod', $empresa_master_cod)
            ->get();
    }

    public static function getFornecedoresById(array $ids)
    {
        return Fornecedor::with('endereco')
            ->whereIn('id', $ids)
            ->get();
    }
}
