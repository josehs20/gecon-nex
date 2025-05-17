<?php

namespace Modules\Mercado\Application;

use Modules\Mercado\Entities\Venda;
use Modules\Mercado\UseCases\Pdv\Caixa\EditarStatus;
use Modules\Mercado\UseCases\Pdv\Caixa\FinalizarVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\GetClientes;
use Modules\Mercado\UseCases\Pdv\Caixa\GetProdutos;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FinalizarVendaRequest;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\AtualizarCaixa;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\CriaOuAtualizaPermissaoCaixa;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\CriaOuAtualizaRecursosCaixa;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\CriarCaixa;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\DeletePermissaoCaixa;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\AtualizarCaixaRequest;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\CriarCaixaRequest;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\TrocarDispositivoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\AbrirCaixa;
use Modules\Mercado\UseCases\Pdv\Caixa\CalculaFechamentoCaixa;
use Modules\Mercado\UseCases\Pdv\Caixa\CancelarVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarEvidencia;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarSangria;
use Modules\Mercado\UseCases\Pdv\Caixa\CriarVendaPagamento;
use Modules\Mercado\UseCases\Pdv\Caixa\DevolucaoVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\FecharCaixa;
use Modules\Mercado\UseCases\Pdv\Caixa\GerarNumeroVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\GetVendaById;
use Modules\Mercado\UseCases\Pdv\Caixa\GetVendasDevolucao;
use Modules\Mercado\UseCases\Pdv\Caixa\GetVendasVoltar;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CancelarVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarSangriaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarVendaPagamentoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\DevolucaoVendaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FecharCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\SalvarVenda;
use Modules\Mercado\UseCases\Pdv\Caixa\TrocarDispositivo;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;

class CaixaApplication
{
    public static function abrir_caixa(AbrirCaixaRequest $request)
    {
        $interact = new AbrirCaixa($request);
        return $interact->handle();
    }

    public static function criar_caixa(CriarCaixaRequest $request)
    {
        $interact = new CriarCaixa($request);
        return $interact->handle();
    }

    public static function trocar_dispositivo(TrocarDispositivoRequest $request)
    {
        $interact = new TrocarDispositivo($request);
        return $interact->handle();
    }

    public static function atualizar_caixa(AtualizarCaixaRequest $request)
    {
        $interact = new AtualizarCaixa($request);
        return $interact->handle();
    }

    public static function get_produtos(string $busca = '')
    {
        $interact = new GetProdutos($busca);
        return $interact->handle();
    }

    public static function get_cliente(string $busca = '')
    {
        $interact = new GetClientes($busca);
        return $interact->handle();
    }

    public static function editar_status(EditarStatusCaixaRequest $request)
    {
        $interact = new EditarStatus($request);
        return $interact->handle();
    }

    public static function finalizar_venda(FinalizarVendaRequest $request)
    {
        $interact = new FinalizarVenda($request);
        return $interact->handle();
    }

    public static function salvar_venda(CriarVendaRequest $request)
    {
        $interact = new SalvarVenda($request);
        return $interact->handle();
    }

    public static function gerarNumeroVenda(int $loja_id)
    {
        $interact = new GerarNumeroVenda($loja_id);
        return $interact->handle();
    }

    public static function get_vendas_voltar(string $busca = '')
    {
        $interact = new GetVendasVoltar($busca);
        return $interact->handle();
    }

    public static function get_venda_by_id(int $id)
    {
        $interact = new GetVendaById($id);
        return $interact->handle();
    }

    public static function cancelar_venda(CancelarVendaRequest $request)
    {
        $interact = new CancelarVenda($request);
        return $interact->handle();
    }

    public static function criar_evidencias(CriarEvidenciaRequest $request)
    {
        $interact = new CriarEvidencia($request);
        return $interact->handle();
    }

    public static function getVendaDevolucao(string $busca = '')
    {
        $interact = new GetVendasDevolucao($busca);
        return $interact->handle();
    }

    public static function devolucao_venda(DevolucaoVendaRequest $request)
    {
        $interact = new DevolucaoVenda($request);
        return $interact->handle();
    }

    public static function calculaFechamento(int $caixa_id)
    {
        $interact = new CalculaFechamentoCaixa($caixa_id);
        return $interact->handle();
    }

    public static function criarVendaPagamento(CriarVendaPagamentoRequest $request)
    {
        $interact = new CriarVendaPagamento($request);
        return $interact->handle();
    }

    public static function criarSangria(CriarSangriaRequest $request)
    {
        $interact = new CriarSangria($request);
        return $interact->handle();
    }

    public static function fechar_caixa(FecharCaixaRequest $request)
    {
        $interact = new FecharCaixa($request);
        return $interact->handle();
    }

    public static function criaOuAtualizaRecursosCaixa(array $recursos, int $caixa_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new CriaOuAtualizaRecursosCaixa($recursos, $caixa_id, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function criaOuAtualizaPermissaoCaixa(int $usuario_id, int $caixa_id, bool $superior, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new CriaOuAtualizaPermissaoCaixa($usuario_id, $caixa_id, $superior, $criarHistoricoRequest);
        return $interact->handle();
    }

    public static function deletePermissaoCaixa(int $caixa_permissao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $interact = new DeletePermissaoCaixa($caixa_permissao_id, $criarHistoricoRequest);
        return $interact->handle();
    }
}
