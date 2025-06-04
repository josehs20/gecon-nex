<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\AbrirCaixa;
use Modules\Mercado\UseCases\Pdv\Caixa\CalculaTotaisVendaTemp;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarEvidencia;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarOrcamento;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarOuAtualizaCaixaDiario;
use Modules\Mercado\UseCases\Pdv\Caixa\DevolucaoVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\EditarValoresEvidencia;
use Modules\Mercado\UseCases\Pdv\Caixa\FinalizarVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\GerarNumeroVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\ReceberConta;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarCaixaDiarioRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\OrcamentoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\ReceberContaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SangriaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SuprirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Sangria;
use Modules\Mercado\UseCases\Pdv\Caixa\SuprirCaixa;

class PDVApplication
{
    public static function abrir_caixa(AbrirCaixaRequest $request)
    {
        $interact = new AbrirCaixa($request);
        return $interact->handle();
    }

    public static function sangria(SangriaRequest $request)
    {
        $interact = new Sangria($request);
        return $interact->handle();
    }

    public static function suprir_caixa(SuprirCaixaRequest $request)
    {
        $interact = new SuprirCaixa($request);
        return $interact->handle();
    }

    public static function criar_evidencias(CriarEvidenciaRequest $request)
    {
        $interact = new CriarEvidencia($request);
        return $interact->handle();
    }

    public static function criar_ou_atualizar_caixa_diario(CriarCaixaDiarioRequest $request, ?int $id = null)
    {
        $interact = new CriarOuAtualizaCaixaDiario($request, $id);
        return $interact->handle();
    }

    public static function finalizar_venda(FinalizarVendaRequest $request)
    {
        $interact = new FinalizarVenda($request);
        return $interact->handle();
    }

    public static function devolucao_venda(DevolucaoVendaRequest $request)
    {
        $interact = new DevolucaoVenda($request);
        return $interact->handle();
    }

    public static function calculaTotaisVendaTemp(int $caixa_id, ?float $desconto_percentual)
    {
        $interact = new CalculaTotaisVendaTemp($caixa_id, $desconto_percentual);
        return $interact->handle();
    }

    public static function gerarNumeroVenda(int $loja_id)
    {
        $interact = new GerarNumeroVenda($loja_id);
        return $interact->handle();
    }

    public static function criar_orcamento(OrcamentoRequest $request)
    {
        $interact = new CriarOrcamento($request);
        return $interact->handle();
    }

    public static function receber_conta(ReceberContaRequest $request)
    {
        $interact = new ReceberConta($request);
        return $interact->handle();
    }

    public static function editar_valores_evidencia(CriarHistoricoRequest $criarVendaRequest, int $evidenciaId, int $valorTotal, int $valorDinheiro)
    {
        $interact = new EditarValoresEvidencia($criarVendaRequest, $evidenciaId, $valorTotal, $valorDinheiro);
        return $interact->handle();
    }
}
