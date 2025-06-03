<?php

namespace Modules\Mercado\Repository\PDV;

use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\CaixaDiario;
use Modules\Mercado\Entities\CaixaEvidencia;
use Modules\Mercado\Entities\CaixaItemTemp;
use Modules\Mercado\Entities\Devolucao;
use Modules\Mercado\Entities\DevolucaoItem;
use Modules\Mercado\Entities\FichaCliente;
use Modules\Mercado\Entities\Orcamento;
use Modules\Mercado\Entities\OrcamentoItem;
use Modules\Mercado\Entities\Suprimento;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Entities\VendaItem;
use Modules\Mercado\Entities\VendaPagamento;
use Modules\Mercado\Entities\VendaParcela;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CaixaPDVRepository
{
    public static function editaAttrsCaixa(CriarHistoricoRequest $criarHistoricoRequest, int $id, array $atributos)
    {
        Caixa::setHistorico($criarHistoricoRequest);
        $updated = Caixa::where('id', $id)->update($atributos);

        if ($updated) {
            return Caixa::find($id);
        }

        return null;
    }

    public static function criarAttrsCaixa(CriarHistoricoRequest $criarHistoricoRequest, $atributos)
    {
        Caixa::setHistorico($criarHistoricoRequest);
        return Caixa::create($atributos);
    }

    public static function editaAttrsCaixaDiario(CriarHistoricoRequest $criarHistoricoRequest, int $id, array $atributos)
    {
        CaixaDiario::setHistorico($criarHistoricoRequest);
        $updated = CaixaDiario::where('id', $id)->update($atributos);

        if ($updated) {
            return CaixaDiario::find($id);
        }

        return null;
    }

    public static function criarAttrsCaixaDiario(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        CaixaDiario::setHistorico($criarHistoricoRequest);
        return CaixaDiario::create($atributos);
    }


    public static function criarSuprimentosAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        Suprimento::setHistorico($criarHistoricoRequest);
        return Suprimento::create($atributos);
    }

    public static function criarVendaAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        Venda::setHistorico($criarHistoricoRequest);
        return Venda::create($atributos);
    }

    public static function criarVendaItemAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        VendaItem::setHistorico($criarHistoricoRequest);
        return VendaItem::create($atributos);
    }

    public static function criarVendaPagamentoAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        VendaPagamento::setHistorico($criarHistoricoRequest);
        return VendaPagamento::create($atributos);
    }

    public static function criarVendaParcelaAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        VendaParcela::setHistorico($criarHistoricoRequest);
        return VendaParcela::create($atributos);
    }

    public static function criarFichaClienteAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        FichaCliente::setHistorico($criarHistoricoRequest);
        return FichaCliente::create($atributos);
    }

    public static function getItensCaixaTemp(int $caixa_id)
    {
        return CaixaItemTemp::where('caixa_id', $caixa_id)->get();
    }

    public static function limpaCaixaItensTemp(int $caixa_id)
    {
        return CaixaItemTemp::where('caixa_id', $caixa_id)->forceDelete();
    }

    public static function editaCaixaEvidenciaAttrs(CriarHistoricoRequest $criarHistoricoRequest, int $id, array $atributos)
    {
        CaixaEvidencia::setHistorico($criarHistoricoRequest);
        $updated = CaixaEvidencia::where('id', $id)->update($atributos);

        if ($updated) {
            return CaixaEvidencia::find($id);
        }

        return null;
    }

    public static function criarDevolucaoAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        Devolucao::setHistorico($criarHistoricoRequest);
        return Devolucao::create($atributos);
    }


    public static function criarDevolucaoItensAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        DevolucaoItem::setHistorico($criarHistoricoRequest);
        return DevolucaoItem::create($atributos);
    }

    public static function criarOrcamentoAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        Orcamento::setHistorico($criarHistoricoRequest);
        return Orcamento::create($atributos);
    }

    public static function criarOrcamentoItemAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        OrcamentoItem::setHistorico($criarHistoricoRequest);
        return OrcamentoItem::create($atributos);
    }

    public static function getVendaById(int $vendaId)
    {
        return Venda::with('venda_itens.estoque.produto')->find($vendaId);
    }
}
