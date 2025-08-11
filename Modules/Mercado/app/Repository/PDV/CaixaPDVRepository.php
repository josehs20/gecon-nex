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
use Modules\Mercado\Entities\EspeciePagamento;
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
use Modules\Mercado\Repository\Caixa\CaixaRepository;
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
        return Venda::with(['usuario.master', 'cliente', 'loja.endereco', 'devolucoes', 'venda_pagamentos' => function ($q) {
            $q->with(['vendaParcelas', 'especiePagamento']);
        }, 'venda_itens.devolucao_itens', 'venda_itens.estoque.produto' => function ($q) {
            $q->with(['fabricante', 'unidade_medida']);
        }])->find($vendaId);
    }

    public static function delete_orcamento(int $orcamentoId, CriarHistoricoRequest $criarHistoricoRequest)
    {
        Orcamento::setHistorico($criarHistoricoRequest);
        return Orcamento::where('id', $orcamentoId)->delete();
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

    public static function get_venda_pagamentos_cliente(
        array $lojas,
        string $busca = ''
    ) {
        return VendaPagamento::with(['vendaParcelas.formaPagamento.especie'])->whereIn('loja_id', $lojas)
            ->whereHas('cliente', function ($q) use ($busca) {
                $q->where('nome', 'like', formataLikeSql($busca));
            })
            ->whereHas('vendaParcelas', function ($q) {
                $q->where('pago', false); //automaticamente busca rodas credito em loja pendentes
            })->get();
    }

    public static function get_venda_pagamento_by_id(
        int $id
    ) {
        return VendaPagamento::with(['vendaParcelas.formaPagamento.especie', 'venda', 'devolucoes'])->where('id', $id)
            ->whereHas('vendaParcelas', function ($q) {
                $q->where('pago', false); //automaticamente busca rodas credito em loja pendentes
            })->first();
    }

    public static function get_detalhes_evidencias_caixa_atual(
        int $caixa_id
    ) {
        $caixa = CaixaRepository::getCaixaById($caixa_id);
        $evidenciaDeInicio = $caixa->diario_atual->caixa_evidencia_id;

        $evidencias = CaixaEvidencia::with([
            'recurso',
            'venda.venda_pagamentos.especiePagamento',
            'devolucao.formaPagamento.especie',
            'sangria.especiePagamento',
            'suprimento.especiePagamento',
            'fichas_cliente.formaPagamento.especie'
        ])->where('caixa_id', $caixa_id)->where('id', '>=', $evidenciaDeInicio)->get();
        //formata objeto para uma melhor visualização das especies de movimentacao do caixa

        $evidencias = $evidencias->map(function ($item) {
            if ($item->caixa_recurso_id == config('config.caixa.recursos.abertura.id')) {
                $item->especies = EspeciePagamento::where('id', config('config.especie_pagamento.dinheiro.id'))->get();
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.venda.id')) {
                $item->especies = $item->venda->venda_pagamentos->map(function ($pagamento) {
                    return $pagamento->especiePagamento;
                });
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.devolucao.id')) {
                $item->especies = collect([$item->devolucao->formaPagamento->especie]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.suprimentos.id')) {
                $item->especies = collect([$item->suprimento->especiePagamento]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.sangria.id')) {
                $item->especies = collect([$item->sangria->especiePagamento]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.recebimento.id')) {
                $item->especies = $item->fichas_cliente->map(function ($f) {
                    return $f->formaPagamento->especie;
                });
            }
            return $item;
        });

        return $evidencias;
    }

    public static function getCaixasFechados($usuario_id, $loja_id)
    {
        $caixas_diario = CaixaDiario::with(['evidencia'])->whereHas('caixa', function ($q) use ($usuario_id, $loja_id) {
            $q->where('loja_id', $loja_id)->whereHas('permissoes', function ($q) use ($usuario_id, $loja_id) {
                $q->where('usuario_id', $usuario_id);
            });
        })->where('status_id', config('config.status.fechado'))->get();
        return $caixas_diario;
    }

    public static function get_detalhes_evidencias_caixa(
        int $caixas_diario_id
    ) {
        //pega evidencia de abertura do caixa diario qcom a evidencia de abertura
        $caixas_diario = CaixaDiario::find($caixas_diario_id);

        //pega evidencia fechamento
        $evidenciaFechamento = CaixaEvidencia::where('caixa_id', $caixas_diario->caixa_id)->where('acao_id', config('config.acoes.fechou_caixa.id'))
            ->where('id', '>', $caixas_diario->caixa_evidencia_id)->first();

        //pega todas evidências do mesmo caixa até a evidência de fechamento do caixa
        $evidenciasDoCaixaDiario = CaixaEvidencia::with([
            'recurso',
            'venda.venda_pagamentos.especiePagamento',
            'devolucao.formaPagamento.especie',
            'sangria.especiePagamento',
            'suprimento.especiePagamento',
            'fichas_cliente.formaPagamento.especie'
        ])->where('caixa_id', $caixas_diario->caixa_id)
            ->where('id', '>=', $caixas_diario->caixa_evidencia_id)
            ->where('id', '<=', $evidenciaFechamento->id)->get();

        $evidencias = $evidenciasDoCaixaDiario->map(function ($item) {
            if ($item->caixa_recurso_id == config('config.caixa.recursos.abertura.id')) {
                $item->especies = EspeciePagamento::where('id', config('config.especie_pagamento.dinheiro.id'))->get();
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.venda.id')) {
                $item->especies = $item->venda->venda_pagamentos->map(function ($pagamento) {
                    return $pagamento->especiePagamento;
                });
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.devolucao.id')) {
                $item->especies = collect([$item->devolucao->formaPagamento->especie]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.suprimentos.id')) {
                $item->especies = collect([$item->suprimento->especiePagamento]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.sangria.id')) {
                $item->especies = collect([$item->sangria->especiePagamento]);
            } elseif ($item->caixa_recurso_id == config('config.caixa.recursos.recebimento.id')) {
                $item->especies = $item->fichas_cliente->map(function ($f) {
                    return $f->formaPagamento->especie;
                });
            }
            return $item;
        });

        return $evidencias;
    }
}
