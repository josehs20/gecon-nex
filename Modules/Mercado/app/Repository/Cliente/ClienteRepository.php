<?php

namespace Modules\Mercado\Repository\Cliente;

use Carbon\Carbon;
use Modules\Mercado\Entities\Cliente;
use Modules\Mercado\Entities\CreditoCliente;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ClienteRepository
{

    public static function create(
        CriarHistoricoRequest $criarHistoricoRequest,
        $empresa_master_cod,
        $nome,
        $documento,
        $pessoa,
        $ativo,
        $status,
        $celular,
        $telefone_fixo,
        $email,
        $data_nascimento,
        $observacao,
        $endereco_id = null
    ) {
        Cliente::setHistorico($criarHistoricoRequest);
        return Cliente::create([
            'empresa_master_cod' => $empresa_master_cod,
            'nome' => $nome,
            'documento' => somenteNumeros($documento),
            'pessoa' => $pessoa,
            'ativo' => $ativo,
            'status_id' => $status,
            'celular' => $celular,
            'telefone_fixo' => $telefone_fixo,
            'email' => $email,
            'data_nascimento' => converterDataParaSalvarNoBanco($data_nascimento),
            'observacao' => $observacao,
            'endereco_id' => $endereco_id,
        ]);
    }

    public static function update(
        CriarHistoricoRequest $criarHistoricoRequest,
        $empresa_master_cod,
        $id,
        $nome,
        $documento,
        $pessoa,
        $ativo,
        $status,
        $celular,
        $telefone_fixo,
        $email,
        $data_nascimento,
        $observacao,
        $endereco_id = null
    ) {
        $cliente = Cliente::find($id);
        Cliente::setHistorico($criarHistoricoRequest);

        $cliente->update([
            'empresa_master_cod' => $empresa_master_cod,
            'nome' => $nome,
            'documento' => somenteNumeros($documento),
            'pessoa' => $pessoa,
            'ativo' => $ativo,
            'status_id' => $status,
            'celular' => $celular,
            'telefone_fixo' => $telefone_fixo,
            'email' => $email,
            'data_nascimento' => $data_nascimento,
            'observacao' => $observacao,
            'endereco_id' => $endereco_id,
        ]);
        return $cliente;
    }

    public static function getClienteById(int $id)
    {
        return Cliente::with('endereco', 'credito')->find($id);
    }

    /* Obtém todos clientes pela coluna ativo, retornar os ativos ou inativos, depende do valor passado */
    public static function getTodosClientesPorAtivo(bool $ativo)
    {

        return Cliente::with('endereco')->where('ativo', $ativo)->where('documento', '!=', 00000000000)->where('empresa_master_cod', auth()->user()->usuarioMercado->loja->empresa_master_cod)->get();
    }

    public static function getClienteByEmail(string $email)
    {

        return Cliente::where('email', $email)->where('empresa_master_cod', auth()->user()->usuarioMercado->loja->empresa_master_cod)->first();
    }

    public static function getClienteByDocumento(string $documento)
    {

        return Cliente::where('documento', $documento)->where('empresa_master_cod', auth()->user()->usuarioMercado->loja->empresa_master_cod)->first();
    }

    public static function criar_credito(CriarHistoricoRequest $criarHistoricoRequest, int $cliente_id, $credito_loja)
    {
        CreditoCliente::setHistorico($criarHistoricoRequest);
        return CreditoCliente::create([
            'cliente_id' => $cliente_id,
            'credito_loja' => $credito_loja,
        ]);
    }

    public static function atualizar_credito(CriarHistoricoRequest $criarHistoricoRequest, int $cliente_id, int $credito_loja)
    {
        $credito = CreditoCliente::where('cliente_id', $cliente_id)->first();
        CreditoCliente::setHistorico($criarHistoricoRequest);
        $credito->update([
            'credito_loja' => $credito_loja,
        ]);
        return $credito;
    }

    public static function atualizar_credito_loja_usado(CriarHistoricoRequest $criarHistoricoRequest, int $cliente_id, int $credito_loja_usado, $credito_loja = null)
    {
        CreditoCliente::setHistorico($criarHistoricoRequest);
        $credito = CreditoCliente::where('cliente_id', $cliente_id)->first();
        $credito->update([
            'credito_loja_usado' => $credito_loja_usado,
            'credito_loja' => $credito_loja ?? $credito->credito_loja,
        ]);
        return $credito;
    }
}
