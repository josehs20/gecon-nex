<?php

namespace Modules\Mercado\Repository\PDV;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\CaixaDiario;
use Modules\Mercado\Entities\CaixaEvidencia;
use Modules\Mercado\Entities\CaixaItemTemp;
use Modules\Mercado\Entities\CreditoCliente;
use Modules\Mercado\Entities\Devolucao;
use Modules\Mercado\Entities\DevolucaoItem;
use Modules\Mercado\Entities\Fechamento;
use Modules\Mercado\Entities\FichaCliente;
use Modules\Mercado\Entities\FormaPagamento;
use Modules\Mercado\Entities\Orcamento;
use Modules\Mercado\Entities\OrcamentoItem;
use Modules\Mercado\Entities\Produto;
use Modules\Mercado\Entities\Sangria;
use Modules\Mercado\Entities\Suprimento;
use Modules\Mercado\Entities\Usuario;
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

    public static function criarFechamentoAttrs(CriarHistoricoRequest $criarHistoricoRequest, $atributos)
    {
        Fechamento::setHistorico($criarHistoricoRequest);
        return Fechamento::create($atributos);
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

    public static function criarSangriaAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        Sangria::setHistorico($criarHistoricoRequest);
        return Sangria::create($atributos);
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
        return CaixaItemTemp::with(['estoque', 'produto.fabricante', 'produto.unidade_medida'])->where('caixa_id', $caixa_id)->get();
    }

    public static function getItemCaixaTemp(int $id)
    {
        return CaixaItemTemp::with(['estoque', 'produto.fabricante', 'produto.unidade_medida'])->find($id);
    }

    public static function limpaCaixaItensTemp(int $caixa_id)
    {
        return CaixaItemTemp::where('caixa_id', $caixa_id)->forceDelete();
    }

    public static function criaItemTempAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        CaixaItemTemp::setHistorico($criarHistoricoRequest);
        return CaixaItemTemp::create($atributos);
    }

    public static function removeCaixaItemTemp(int $id)
    {
        return CaixaItemTemp::where('id', $id)->forceDelete();
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

    public static function editaAttrsVendaParcela(CriarHistoricoRequest $criarHistoricoRequest, int $id, array $atributos)
    {
        VendaParcela::setHistorico($criarHistoricoRequest);
        $updated = VendaParcela::where('id', $id)->update($atributos);

        if ($updated) {
            return VendaParcela::find($id);
        }

        return null;
    }

    public static function editaClienteCreditoAttrs(CriarHistoricoRequest $criarHistoricoRequest, int $id, array $atributos)
    {
        CreditoCliente::setHistorico($criarHistoricoRequest);
        $updated = CreditoCliente::where('id', $id)->update($atributos);

        if ($updated) {
            return CreditoCliente::find($id);
        }

        return null;
    }

    public static function criarCriarEvidenciaAttrs(CriarHistoricoRequest $criarHistoricoRequest, array $atributos)
    {
        CaixaEvidencia::setHistorico($criarHistoricoRequest);
        return CaixaEvidencia::create($atributos);
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
        return Venda::with(['devolucoes', 'venda_itens.estoque.produto' => function ($q) {
            $q->with(['fabricante', 'unidade_medida']);
        }])->find($vendaId);
    }

    public static function getVendaParcelaById(int $vendaId)
    {
        return VendaParcela::with('vendaPagamento.venda')->find($vendaId);
    }

    public static function getSupervisoresCaixa(int $caixa_id, $busca = '')
    {
        return Usuario::with(['master' => function ($q) use ($busca) {
            $q->where('name', 'like', formataLikeSql($busca));
        }])->whereHas('caixa_permissoes', function ($q) use ($caixa_id) {
            $q->where('caixa_id', $caixa_id)->where('superior', true);
        })->get()->filter(function ($u) {
            return $u->master;
        });
    }

    public static function getProdutosVendaCaixa(
        int $loja_id,
        string $busca = ''
    ) {

        $quantasLetras = preg_match_all('/[a-zA-Z]/', $busca);

        $select = [
            'produtos.*',
            't2.preco',
            't2.custo',
            't2.id as estoque_id',
            't2.quantidade_disponivel',
            't2.ncm_id as classificacao',
            't3.nome as loja_nome',
            't3.id as loja_id',
            't4.sigla',
            't5.nome as fabricante_nome',
            't6.descricao as classificacao',
        ];

        return Produto::select($select)
            ->limit(50)
            ->join('estoques as t2', 'produtos.id', '=', 't2.produto_id')
            ->join('lojas as t3', 't2.loja_id', '=', 't3.id')
            ->join('unidade_medida as t4', 'produtos.unidade_medida_id', '=', 't4.id')
            ->join('fabricantes as t5', 'produtos.fabricante_id', '=', 't5.id')
            ->join('classificacao_produto as t6', 'produtos.classificacao_produto_id', '=', 't6.id')
            ->where(function ($query) use ($busca, $quantasLetras) {

                $buscaLike = '%' . str_replace(' ', '%', $busca) . '%';
                if ($quantasLetras) {
                    $query->where('produtos.nome', 'like', $buscaLike);
                } elseif (!$quantasLetras && strlen($busca) <= 6) {
                    $query->where('produtos.cod_aux', 'like', $buscaLike);
                } elseif (!$quantasLetras && strlen($busca) >= 10) {
                    $query->where('produtos.cod_barras',  $busca);
                } else {
                    $query->where('produtos.nome', 'like', $busca)
                        ->orWhere('produtos.cod_barras', 'like', $busca)
                        ->orWhere('produtos.cod_aux', 'like', $busca);
                }
            })
            ->where('t3.id', $loja_id)
            ->get();
    }

    public static function getClientesVendaCaixa(
        int $empresa_master_cod,
        string $busca = ''
    ) {
        $busca = '%' . str_replace(' ', '%', $busca) . '%'; // Formata a busca

        return DB::connection('mercado')->table('clientes as t1')
            ->selectRaw("CONCAT(t1.nome, ' - ', LEFT(t1.documento, LENGTH(t1.documento) - 5), '***-**') as text, t1.id, t1.documento as attr")
            ->limit(50)
            ->where(function ($query) use ($busca) {
                $query->where('nome', 'like', $busca)
                    ->orWhere('documento', 'like', $busca);
            })->where('empresa_master_cod', $empresa_master_cod)->get();
    }

    public static function getFormasPagamento(
        int $loja_id,
        string $busca = ''
    ) {
        $busca = $busca;

        return FormaPagamento::with(['especie'])->when($busca, function ($q) use ($busca) {
            $q->where('descricao', 'like', formataLikeSql($busca));
        })->where('loja_id', $loja_id)->get();
    }

    public static function get_vendas_devolucao(
        int $loja_id,
        ?int $qtdDiasPodeSerDevolvida = null,
        ?string $busca = null
    ) {
        $busca = formataLikeSql($busca); // 'busca' já tratada por formataLikeSql
        return Venda::with(['cliente']) // Eager loading do cliente
            ->where('loja_id', $loja_id) // Filtra pela loja
            ->when($qtdDiasPodeSerDevolvida !== null, function ($q) use ($qtdDiasPodeSerDevolvida) {
                // Filtra vendas criadas a partir do início do dia limite de devolução.
                $q->where('created_at', '>=', now()->subDays($qtdDiasPodeSerDevolvida)->startOfDay());
            })
            ->when($busca, function ($q) use ($busca) {
                // Agrupa todas as condições 'OR' em um 'where' único para a busca.
                $q->where(function ($q) use ($busca) {
                    // Busca no número da venda
                    $q->where('n_venda', 'like', $busca);

                    // Busca no nome ou documento do cliente (via relacionamento).
                    $q->orWhereHas('cliente', function ($clienteRelationQuery) use ($busca) {
                        $clienteRelationQuery->where('nome', 'like', $busca)
                            ->orWhere('documento', 'like', $busca);
                    });
                });
            })
            ->get(); // Executa a query
    }

    public static function getOrcamentos(
        int $empresa_master_cod,
        ?string $busca = '',
        int $limite = 100
    ) {
        return Orcamento::with(['cliente'])->where('empresa_master_cod', $empresa_master_cod)
            ->whereHas('cliente', function ($q) use ($busca) {
                $q->where('nome', 'like', formataLikeSql($busca));
            })->limit($limite)->get();
    }

        public static function getOrcamentoById(
        int $id
    ) {
        return Orcamento::with(['cliente', 'orcamento_itens.estoque.produto.unidade_medida', 'orcamento_itens.estoque.produto.fabricante'])->find($id);
    }
}
