<?php

namespace Modules\Mercado\Repository\Caixa;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Caixa;
use Modules\Mercado\Entities\CaixaEvidencia;
use Modules\Mercado\Entities\CaixaPermissao;
use Modules\Mercado\Entities\Produto;
use Modules\Mercado\Entities\Recurso;
use Modules\Mercado\Entities\Usuario;
use Modules\Mercado\Entities\Venda;
use Modules\Mercado\Entities\VendaPagamento;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class CaixaRepository
{
    public static function create(
        string $nome,
        int $loja_id,
        int $status_id,
        bool $ativo = true,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Caixa {
        Caixa::setHistorico($criarHistoricoRequest);
        return Caixa::create([
            'nome' => $nome,
            'loja_id' => $loja_id,
            'status_id' => $status_id,
            'ativo' => $ativo
        ]);
    }

    public static function update(
        int $id,
        string $nome,
        int $status_id,
        bool $ativo = true,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Caixa {
        Caixa::setHistorico($criarHistoricoRequest);
        $caixa = Caixa::find($id);
        $caixa->update([
            'nome' => $nome,
            'status_id' => $status_id,
            'ativo' => $ativo
        ]);

        return $caixa;
    }

    public static function atualizaRecursosCaixas(
        int $id,
        array $recuros,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Caixa {
        $caixa = Caixa::find($id);
        Recurso::setHistorico($criarHistoricoRequest);
        $caixa->recursos()->sync($recuros);

        return $caixa;
    }

    public static function criaCaixaPermissao(
        int $caixa_id,
        int $usuario_id,
        bool $superior,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?CaixaPermissao {
        CaixaPermissao::setHistorico($criarHistoricoRequest);
        return CaixaPermissao::create([
            'caixa_id' => $caixa_id,
            'usuario_id' => $usuario_id,
            'superior' => $superior,
        ]);
    }

    public static function getCaixas(
        string $nome = ''
    ) {
        $caixas = Caixa::where('nome', 'like', '%' . $nome . '%')->where('loja_id', auth()->user()->getUserModulo->loja_id)->get();

        return $caixas;
    }

    public static function getCaixaById(
        int $id
    ) {
        $caixas = Caixa::with(['recursos'])->find($id);
        return $caixas;
    }

    public static function getCaixaDisponiveis(
        string $nome = ''
    ) {
        $caixas = Caixa::where('loja_id', auth()->user()->usuarioMercado->loja_id)->where('nome', 'like', '%' . $nome . '%')->where('ativo', 1)->where('status_id', config('config.status.fechado'))->get();
        return $caixas;
    }


    public static function updateStatus(
        int $id,
        int $status_id,
        int $usuario_id,
        bool $ativo,
        CriarHistoricoRequest $criarHistoricoRequest
    ) {
        $caixa = Caixa::find($id);
        Caixa::setHistorico($criarHistoricoRequest);
        $caixa->update([
            'status_id' => $status_id,
            'usuario_id' => $usuario_id,
            'ativo' => $ativo
        ]);
        // $caixa->setAudit($criarHistoricoRequest);
        return $caixa;
    }

    public static function getProdutosVendaCaixa(
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
            ->limit(100)
            ->join('estoques as t2', 'produtos.id', '=', 't2.produto_id')
            ->join('lojas as t3', 't2.loja_id', '=', 't3.id')
            ->join('unidade_medida as t4', 'produtos.unidade_medida_id', '=', 't4.id')
            ->join('fabricantes as t5', 'produtos.fabricante_id', '=', 't5.id')
            ->join('classificacao_produto as t6', 'produtos.classificacao_produto_id', '=', 't6.id')
            ->where('t3.id', auth()->user()->usuarioMercado->loja_id)
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
            })->get();
    }

    public static function getClientesVendaCaixa(
        string $busca = ''
    ) {
        $busca = '%' . str_replace(' ', '%', $busca) . '%'; // Formata a busca

        return DB::connection('mercado')->table('clientes as t1')
            ->selectRaw("CONCAT(t1.nome, ' - ', LEFT(t1.documento, LENGTH(t1.documento) - 5), '***-**') as text, t1.id, t1.documento as attr")
            ->limit(50)
            ->where(function ($query) use ($busca) {
                $query->where('nome', 'like', $busca)
                    ->orWhere('documento', 'like', $busca);
            })->where('empresa_master_cod', auth()->user()->usuarioMercado->loja->empresa_master_cod)->get();
    }

    public static function getVendasVoltar(
        string $busca = ''
    ) {

        $busca = formataLikeSql($busca); // Formata a busca

        return Venda::selectRaw('vendas.id as id,CONCAT(t3.nome, " - ", vendas.n_venda) as text')
            ->limit(100)->where('vendas.loja_id', auth()->user()->usuarioMercado->loja_id)
            ->join('caixas as t2', 'vendas.caixa_id', 't2.id')
            ->join('clientes as t3', 'vendas.cliente_id', 't3.id')
            ->where('vendas.status_id', config('config.status.salvo'))
            ->where(function ($query) use ($busca) {
                $query->where('t3.nome', 'LIKE', $busca)
                    ->orWhere('vendas.n_venda', 'LIKE', $busca);
            })
            ->get();
    }

    public static function getVendaById(
        int $id
    ) {
        return Venda::with(['venda_itens' => function ($q) {
            $q->with(['estoque' => function ($q) {
                $q->with(['produto' => function ($q) {
                    $q->with('unidade_medida');
                }]);
            }, 'devolucao_item']);
        }, 'cliente'])->find($id);
    }

    public static function criarEvidencia(
        CriarHistoricoRequest $criarHistoricoRequest,
        int $caixa_id,
        int $acao_id,
        int $usuario_id,
        mixed $ip_address,
        mixed $sistema_operacional,
        mixed $localizacao,
        bool $ativo,
        string $sessionToken,
        int $valor_abertura,
        mixed $data_abertura = null,
        ?int $valor_fechamento = null,
        ?int $valor_sangria = null,
        mixed $data_fechamento = null,
        ?string $descricao
    ) {
        CaixaEvidencia::setHistorico($criarHistoricoRequest);
        return CaixaEvidencia::create([
            'caixa_id' => $caixa_id,
            'acao_id' => $acao_id,
            'usuario_id' => $usuario_id,
            'ip_address' => $ip_address,
            'sistema_operacional' => $sistema_operacional,
            'localizacao' => $localizacao,
            'ativo' => $ativo,
            'token' => $sessionToken,
            'valor_abertura' => $valor_abertura,
            'valor_fechamento' => $valor_fechamento,
            'data_abertura' => $data_abertura,
            'valor_sangria' => $valor_sangria,
            'data_fechamento' => $data_fechamento,
            'descricao' => $descricao,
        ]);
    }

    public static function atualizaAtivoEvidencia(
        int $id,
        bool $ativo
    ) {
        $evidencia = CaixaEvidencia::find($id);
        $evidencia->update([
            'ativo' => $ativo,
        ]);
        return $evidencia;
    }

    public static function fecha_evidencia_caixa(
        int $id,
        bool $ativo,
        mixed $valor_fechamento,
        mixed $data_fechamento,
    ) {
        $evidencia = CaixaEvidencia::find($id);
        $evidencia->update([
            'ativo' => $ativo,
            'valor_fechamento' => $valor_fechamento,
            'data_fechamento' => $data_fechamento
        ]);

        return $evidencia;
    }

    public static function getVendasDevolucao(
        string $busca = ''
    ) {
        $busca = formataLikeSql($busca); // Formata a busca
        $dataLimite = Carbon::now()->subDays(15); // Data limite de 8 dias atrás
        return Venda::selectRaw('vendas.id as id, CONCAT(t3.nome, " - ", vendas.n_venda) as text')
            ->limit(50)
            ->where('vendas.loja_id', auth()->user()->usuarioMercado->loja_id)
            ->join('caixas as t2', 'vendas.caixa_id', '=', 't2.id')
            ->join('clientes as t3', 'vendas.cliente_id', '=', 't3.id')
            ->whereIn('vendas.status_id', [config('config.status.concluido'), config('config.status.devolucao_parcial')])
            ->where('vendas.data_concluida', '>=', $dataLimite)
            ->where(function ($query) use ($busca) {
                $query->where('t3.nome', 'LIKE', $busca)
                    ->orWhere('vendas.n_venda', 'LIKE', $busca);
            })
            ->whereDoesntHave('venda_pagamentos', function ($query) {
                // Exclui as vendas que possuem pagamento de 'credito_loja' com 'valor_pago' maior que 0
                $query->where('especie_pagamento_id', '=', config('config.especie_pagamento.credito_loja.id'))
                    ->where('valor_pago', '>', 0);
            })
            ->whereHas('venda_pagamentos', function ($query) {
                // Inclui as vendas com pagamentos de outras espécies
                $query->where('especie_pagamento_id', '!=', config('config.especie_pagamento.credito_loja.id'));
            })
            ->orderBy('vendas.id', 'desc')
            ->get();
    }

    public static function getVendasByDataAberturaCaixa(
        int $caixa_id,
        mixed $data_abertura,
        array $status
    ) {
        return Venda::with(['venda_itens' => function ($q) {
            $q->with('devolucao_item');
        }])->where('caixa_id', $caixa_id)->whereIn('status_id', $status)
            ->where('data_concluida', '>=', $data_abertura)
            ->get();
    }

    public static function getSangria(
        int $caixa_id
    ) {

        $caixa = self::getCaixaById($caixa_id);
        $dataAbertura = $caixa->ultima_abertura->data_abertura;
        $sangriasRealizadas = $caixa->evidencias()->where('data_abertura', '>=', $dataAbertura)->where('acao_id', config('config.acoes.sangria.id'))->get();
        $sangrias_realizadas = $sangriasRealizadas->toBase();
        if ($sangrias_realizadas->count() == 0) {
            $evidenciasIds = $sangriasRealizadas->push($caixa->ultima_abertura)->pluck('id')->toArray();
        } else {
            $evidenciasIds = $sangriasRealizadas->pluck('id')->toArray();
        }

        $sangria = Caixa::where('usuario_id', auth()->user()->usuarioMercado->id)
            ->with([
                'ultima_abertura',
                'ultimo_registro', //pega ultimos registros do caixa
                'vendas' => function ($q) use ($evidenciasIds) { //pega ultimos registros das vendas realizadas a partir da ultima sangria ou abertura de caixa
                    $q->whereIn('caixa_evidencia_id', $evidenciasIds)
                        ->whereIn('status_id', [
                            config('config.status.concluido'),
                            config('config.status.devolucao'),
                            config('config.status.devolucao_parcial'),
                        ])
                        ->with([
                            'venda_pagamentos' => function ($q) { //pega os pagamentos
                                $q->with(['forma', 'especie', 'venda_pagamento_devolucao']);
                            }
                        ]);
                },
                'devolucoes' => function ($q) use ($evidenciasIds) {
                    $q->whereIn('caixa_evidencia_id', $evidenciasIds)->with(['venda_pagamentos_devolucao' => function ($q) use ($evidenciasIds) {
                        $q->whereIn('caixa_evidencia_id', $evidenciasIds)->with(['venda_pagamento' => function ($q) { //pega os pagamentos
                            $q->with(['forma', 'especie']);
                        }]);
                    }]);
                },
                'pagamentos' => function ($q) use ($evidenciasIds) {
                    $q->whereIn('caixa_evidencia_id', $evidenciasIds)->with(['forma', 'especie']);
                },
            ])->first();

        if (!$sangria) {
            return false;
        }


        $sangria->vendas = $sangria->vendas;

        $sangria->total_venda = $sangria->vendas->sum('total');
        $sangria->sub_total_venda = $sangria->vendas->sum('sub_total');
        $sangria->descontos = $sangria->vendas->sum('desconto_dinheiro');
        $sangria->sangrias_realizadas = $sangrias_realizadas;
        $sangria->devolucoes = $sangria->devolucoes;
        $sangria->recebimentos = $sangria->pagamentos;

        // Calcular o total por forma de pagamento
        $totalVendidoEmCreditoLoja = $sangria->vendas->flatMap(function ($venda) {
            return $venda->venda_pagamentos;
        })->where('especie_pagamento_id', config('config.especie_pagamento.credito_loja.id'))->sum('valor');

        $totalPorFormaDevolucao = $sangria->devolucoes->flatMap(function ($devolucao) {
            return $devolucao->venda_pagamentos_devolucao;
        })->reduce(function ($carry, $pgDevolucao) {
            $forma = $pgDevolucao->venda_pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pgDevolucao->valor;
            } else {
                $carry[$forma] = $pgDevolucao->valor;
            }
            return $carry;
        }, []);

        // Calcular o total por forma de pagamento em recebiemntos
        $totalPorFormaPagamento = $sangria->recebimentos->reduce(function ($carry, $pagamento) {
            $forma = $pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pagamento->valor;
            } else {
                $carry[$forma] = $pagamento->valor;
            }
            return $carry;
        }, []);

        $totalPorFormaPagamento[config('config.especie_pagamento.credito_loja.nome')] = $totalVendidoEmCreditoLoja;
        $sangria->total_recebimento = array_sum($totalPorFormaPagamento);
        $sangria->total_por_forma_pagamento = $totalPorFormaPagamento;
        $sangria->total_por_forma_devolucao = $totalPorFormaDevolucao;

        return $sangria;
    }

    public static function getFechamentoCaixa(
        int $caixa_id,
    ) {
        $caixa = self::getCaixaById($caixa_id);
        $abertura = $caixa->ultima_abertura;
        $dataAbertura = $abertura->data_abertura;
        $sangriasRealizadas = $caixa->evidencias()->where('data_abertura', '>=', $dataAbertura)->where('acao_id', config('config.acoes.sangria.id'))->get();
        $fechamento = Caixa::where('usuario_id', auth()->user()->usuarioMercado->id)
            ->with([
                'ultima_abertura',
                'ultimo_registro', //pega ultimos registros do caixa
                'vendas' => function ($q) use ($dataAbertura) { //pega ultimos registros das vendas realizadas a partir da ultima sangria ou abertura de caixa
                    $q->where('data_concluida', '>=', $dataAbertura)
                        ->whereIn('status_id', [
                            config('config.status.concluido'),
                            config('config.status.devolucao'),
                            config('config.status.devolucao_parcial'),
                        ])
                        ->with([
                            'venda_pagamentos' => function ($q) { //pega os pagamentos
                                $q->with(['forma', 'especie', 'venda_pagamento_devolucao']);
                            },
                            'cliente', //pega as devolucoes das vendas
                            'devolucoes' => function ($q) {
                                $q->with(['venda' => function ($q) {
                                    $q->with('cliente');
                                }, 'venda_pagamentos_devolucao' => function ($q) {
                                    $q->with(['venda_pagamento' => function ($q) { //pega os pagamentos
                                        $q->with(['forma', 'especie']);
                                    }]);
                                }]);
                            }
                        ]);
                }
            ])->first();

        $fechamento->sangrias_realizadas = $sangriasRealizadas;
        $fechamento->total_venda = $fechamento->vendas->sum('total');
        $fechamento->sub_total_venda = $fechamento->vendas->sum('sub_total');
        $fechamento->descontos = $fechamento->vendas->sum('desconto_dinheiro');
        $fechamento->valor_abertura = $abertura->valor_abertura;
        $fechamento->valor_fechamento = $abertura->valor_fechamento;
        $fechamento->devolucoes = $fechamento->vendas->map(function ($venda) {
            return $venda->devolucoes; // Retorna as devoluções associadas a cada venda
        })->filter(function ($devolucao) {
            return !is_null($devolucao); // Remove os valores null
        })
            ->flatten(); // Achata a coleção se necessário

        // Calcular o total por forma de pagamento
        $totalPorFormaPagamento = $fechamento->vendas->flatMap(function ($venda) {
            return $venda->venda_pagamentos;
        })->reduce(function ($carry, $pagamento) {
            $forma = $pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pagamento->valor;
            } else {
                $carry[$forma] = $pagamento->valor;
            }
            return $carry;
        }, []);

        $totalPorFormaDevolucao = $fechamento->devolucoes->flatMap(function ($devolucao) {
            return $devolucao->venda_pagamentos_devolucao;
        })->reduce(function ($carry, $pgDevolucao) {
            $forma = $pgDevolucao->venda_pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pgDevolucao->valor;
            } else {
                $carry[$forma] = $pgDevolucao->valor;
            }
            return $carry;
        }, []);

        $fechamento->total_por_forma_pagamento = $totalPorFormaPagamento;
        $fechamento->total_por_forma_devolucao = $totalPorFormaDevolucao;

        return $fechamento;
    }

    public static function atualizaDataFechamentoEvidencia($evidenciaId, $data)
    {
        $evidencia = CaixaEvidencia::find($evidenciaId);
        $evidencia->update(['data_fechamento' => $data]);

        return $evidencia;
    }

    public static function fecha_caixa(
        CriarHistoricoRequest $historicoRequest,
        int $id,
        int $status_id,
    ) {
        $caixa = Caixa::find($id);
        Caixa::setHistorico($historicoRequest);
        $caixa->update([
            'status_id' => $status_id,
            'usuario_id' => null,
        ]);
        $evidencias_ativas = $caixa->evidencias()->where('caixa_evidencias.acao_id', [config('config.acoes.abriu_caixa.id')])->where('ativo', 1)->update(['data_fechamento' => now()]);
        $evidencias_ativas = $caixa->evidencias()->where('ativo', 1)->update(['ativo' => 0]);

        return $caixa;
    }

    public static function getFechamentosCaixaByUsuario(
        int $usuario_id,
    ) {
        return CaixaEvidencia::with(['caixa' => function ($q) {
            $q->with('loja');
        }])->where('caixa_evidencias.usuario_id', $usuario_id)
            ->where('caixa_evidencias.acao_id', [config('config.acoes.abriu_caixa.id')])
            ->whereNotNull('data_abertura')
            ->orderBy('caixa_evidencias.data_abertura', 'desc') // Ordenar pela data de abertura em ordem crescente
            ->get();
    }

    public static function getFechamentoCaixaByEvidencia(
        int $evidenciaId,
    ) {
        $status = [
            config('config.status.concluido'),
            config('config.status.devolucao'),
            config('config.status.devolucao_parcial'),
        ];
        $evidencia = CaixaEvidencia::find($evidenciaId); //evidencia de abertura de caixa
        $dataAbertura = Carbon::parse($evidencia->data_abertura)->toDateTimeString();
        $dataFechamento = Carbon::parse($evidencia->data_fechamento)->toDateTimeString();
        $sangriasRealizadas = $evidencia->evidencias()
            ->where('acao_id', config('config.acoes.sangria.id'))
            ->where('data_abertura', '>=', $dataAbertura)
            ->where('data_abertura', '<=', $dataFechamento)
            ->get();

        $sangrias_realizadas = $sangriasRealizadas->toBase();

        $evidencias = $sangriasRealizadas->push($evidencia);

        $evidenciaIds = $evidencias->pluck('id')->toArray();
        /**
         * garante que os resultados consultados só estarão dentro das evidencias salvas
         */
        $evidencias = CaixaEvidencia::with([
            'caixa' => function ($q) {
                $q->with('loja');
            },
            'vendas' => function ($q) use ($status, $evidenciaIds) {
                $q->whereIn('status_id', $status)->whereIn('caixa_evidencia_id', $evidenciaIds)->with([
                    'venda_pagamentos' => function ($q) { //pega os pagamentos
                        $q->with(['forma', 'especie', 'venda_pagamento_devolucao']);
                    },
                    'cliente'
                ]);
            },
            'pagamentos' => function ($q) use ($evidenciaIds) {
                $q->whereIn('caixa_evidencia_id', $evidenciaIds)->with(['forma', 'especie', 'venda', 'venda_pagamento' => function ($q) {
                    $q->with('venda_pagamento_devolucao');
                }]);
            },
            'devolucoes' => function ($q) use ($evidenciaIds) {
                $q->whereIn('caixa_evidencia_id', $evidenciaIds)->with(['venda' => function ($q) {
                    $q->with('cliente');
                }, 'venda_pagamentos_devolucao' => function ($q) use ($evidenciaIds) {
                    $q->whereIn('caixa_evidencia_id', $evidenciaIds)->with(['venda_pagamento' => function ($q) { //pega os pagamentos
                        $q->with(['forma', 'especie']);
                    }]);
                }]);
            }
        ])->whereIn('id', $evidenciaIds)->get();



        $evidencia->vendas = $evidencias->map(function ($item) {
            return $item->vendas;
        })->flatten();

        $evidencia->devolucoes = $evidencias->map(function ($item) {
            return $item->devolucoes;
        })->flatten();

        $evidencia->recebimentos = $evidencias->map(function ($item) {
            return $item->pagamentos;
        })->flatten();

        $evidencia->total_venda = $evidencia->vendas->sum('total');
        $evidencia->sub_total_venda = $evidencia->vendas->sum('sub_total');
        $evidencia->descontos = $evidencia->vendas->sum('desconto_dinheiro');
        $evidencia->valor_abertura = $evidencia->valor_abertura;
        $evidencia->sangrias_realizadas = $sangrias_realizadas;
        $evidencia->valor_fechamento = $evidencia->valor_fechamento;

        $totalVendidoEmCreditoLoja = $evidencia->vendas->flatMap(function ($venda) {
            return $venda->pagamentos;
        })->where('especie_pagamento_id', config('config.especie_pagamento.credito_loja.id'))->sum('valor');

        $totalPorFormaPagamento = $evidencia->recebimentos->reduce(function ($carry, $pagamento) {
            $forma = $pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pagamento->valor;
            } else {
                $carry[$forma] = $pagamento->valor;
            }
            return $carry;
        }, []);

        $totalPorFormaDevolucao = $evidencia->devolucoes->flatMap(function ($devolucao) {
            return $devolucao->venda_pagamentos_devolucao;
        })->reduce(function ($carry, $pgDevolucao) {
            $forma = $pgDevolucao->venda_pagamento->forma->descricao ?? 'Desconhecida';
            // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
            if (isset($carry[$forma])) {
                $carry[$forma] += $pgDevolucao->valor;
            } else {
                $carry[$forma] = $pgDevolucao->valor;
            }
            return $carry;
        }, []);

        $totalPorFormaPagamento[config('config.especie_pagamento.credito_loja.nome')] = $totalVendidoEmCreditoLoja;
        $evidencia->total_recebimento = array_sum($totalPorFormaPagamento);
        $evidencia->total_por_forma_pagamento = $totalPorFormaPagamento;
        $evidencia->total_por_forma_devolucao = $totalPorFormaDevolucao;
        //    dd($evidencia);
        return $evidencia;
    }

    public static function getSegundaViaSangria($caixa_id)
    {
        $caixa = self::getCaixaById($caixa_id);
        $dataAbertura = $caixa->ultima_abertura->data_abertura;
        $sangriasRealizadas = $caixa->evidencias()->where('data_abertura', '>=', $dataAbertura)->where('acao_id', config('config.acoes.sangria.id'))->get();
        $sangriasData = [];
        foreach ($sangriasRealizadas as $key => $evidenciaSangria) {
            # code..
            $sangria = Caixa::where('usuario_id', auth()->user()->usuarioMercado->id)
                ->with([
                    'ultima_abertura',
                    'ultimo_registro', //pega ultimos registros do caixa
                    'vendas' => function ($q) use ($evidenciaSangria) { //pega ultimos registros das vendas realizadas a partir da ultima sangria ou abertura de caixa
                        $q->where('caixa_evidencia_id', $evidenciaSangria->id)
                            ->whereIn('status_id', [
                                config('config.status.concluido'),
                                config('config.status.devolucao'),
                                config('config.status.devolucao_parcial'),
                            ])
                            ->with([
                                'venda_pagamentos' => function ($q) { //pega os pagamentos
                                    $q->with(['forma', 'especie', 'venda_pagamento_devolucao']);
                                }
                            ]);
                    },
                    'devolucoes' => function ($q) use ($evidenciaSangria) {
                        $q->where('caixa_evidencia_id', $evidenciaSangria->id)->with(['venda_pagamentos_devolucao' => function ($q) use ($evidenciaSangria) {
                            $q->where('caixa_evidencia_id', $evidenciaSangria->id)->with(['venda_pagamento' => function ($q) { //pega os pagamentos
                                $q->with(['forma', 'especie']);
                            }]);
                        }]);
                    },
                    'pagamentos' => function ($q) use ($evidenciaSangria) {
                        $q->where('caixa_evidencia_id', $evidenciaSangria->id)->with(['forma', 'especie']);
                    },
                ])->first();

            if (!$sangria) {
                return false;
            }


            $sangria->vendas = $sangria->vendas;

            $sangria->total_venda = $sangria->vendas->sum('total');
            $sangria->sub_total_venda = $sangria->vendas->sum('sub_total');
            $sangria->descontos = $sangria->vendas->sum('desconto_dinheiro');
            $sangria->sangrias_realizadas = $sangria;
            $sangria->devolucoes = $sangria->devolucoes;
            $sangria->recebimentos = $sangria->pagamentos;

            // Calcular o total por forma de pagamento
            $totalVendidoEmCreditoLoja = $sangria->vendas->flatMap(function ($venda) {
                return $venda->venda_pagamentos;
            })->where('especie_pagamento_id', config('config.especie_pagamento.credito_loja.id'))->sum('valor');

            $totalPorFormaDevolucao = $sangria->devolucoes->flatMap(function ($devolucao) {
                return $devolucao->venda_pagamentos_devolucao;
            })->reduce(function ($carry, $pgDevolucao) {
                $forma = $pgDevolucao->venda_pagamento->forma->descricao ?? 'Desconhecida';
                // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
                if (isset($carry[$forma])) {
                    $carry[$forma] += $pgDevolucao->valor;
                } else {
                    $carry[$forma] = $pgDevolucao->valor;
                }
                return $carry;
            }, []);

            // Calcular o total por forma de pagamento em recebiemntos
            $totalPorFormaPagamento = $sangria->recebimentos->reduce(function ($carry, $pagamento) {
                $forma = $pagamento->forma->descricao ?? 'Desconhecida';
                // Verificar se já existe um valor para a forma de pagamento, e se existir, somar o valor pago
                if (isset($carry[$forma])) {
                    $carry[$forma] += $pagamento->valor;
                } else {
                    $carry[$forma] = $pagamento->valor;
                }
                return $carry;
            }, []);

            $totalPorFormaPagamento[config('config.especie_pagamento.credito_loja.nome')] = $totalVendidoEmCreditoLoja;
            $sangria->total_recebimento = array_sum($totalPorFormaPagamento);
            $sangria->total_por_forma_pagamento = $totalPorFormaPagamento;
            $sangria->total_por_forma_devolucao = $totalPorFormaDevolucao;
            $sangriasData[] = $sangria;
        }
        dd($sangriasData);
        return $sangriasData;
    }

    public static function getEvidenciaAbertudaCaixa(int $caixa_id)
    {
        return CaixaEvidencia::where('caixa_id', $caixa_id)->where('acao_id', config('config.acoes.abriu_caixa.id'))
            ->latest()->first();
    }

    public static function getRecebimentos(string $busca = '', array $lojas = [])
    {
        return VendaPagamento::whereIn('loja_id', $lojas)
            ->where('forma_pagamento_id', config('config.especie_pagamento.credito_loja'))
            ->whereIn('status_id', [config('config.status.pendente')])
            ->whereHas('venda.cliente', function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                    ->orWhere('documento', 'like', "%{$busca}%");
            })
            ->with(['venda.cliente' => function ($q) {
                $q->select('id', 'nome', 'documento'); // Inclui apenas os campos necessários
            }])
            ->limit(100)
            ->get()
            ->pluck('venda.cliente') // Extrai somente os clientes
            ->unique('id'); // Remove clientes duplicados
    }

    public static function getVendaRecebimentos(int $clienteId, array $lojas = [])
    {
        return VendaPagamento::whereIn('loja_id', $lojas)
            ->whereHas('venda.cliente', function ($q) use ($clienteId) {
                $q->where('id', $clienteId);
            })
            ->with(['venda.cliente' => function ($q) {
                $q->select('id', 'nome', 'documento'); // Inclui apenas os campos necessários
            }, 'forma', 'status', 'venda_pagamento_devolucao'])
            ->whereIn('status_id', [config('config.status.pendente')])->get();
    }

    public static function getCaixaPermissoes(int $loja_id, $caixa_id)
    {
        return Caixa::with(['permissoes'])->whereHas('permissoes', function ($q) use ($caixa_id) {
            $q->where('caixa_id', $caixa_id);
        })->where('loja_id', $loja_id)->get();
    }

    public static function getCaixaPermissaoByUsuario(int $caixa_id, int $usuario_id)
    {
        return CaixaPermissao::where('caixa_id', $caixa_id)->where('usuario_id', $usuario_id)->first();
    }

    public static function getUsuarios(int $loja_id, $busca)
    {
        return Usuario::with(['master.tipoUsuario', 'caixa_permissoes'])
            ->whereIn('usuario_master_cod', User::select('id')->where('name', 'like', formataLikeSql($busca))->get())
            ->where('loja_id', $loja_id)->get();
    }

    public static function getCaixaPermissaoById(int $id)
    {
        return CaixaPermissao::with(['caixa'])->find($id);
    }

    public static function deleteCaixaPermissao(int $caixa_permissao_id, CriarHistoricoRequest $criarHistoricoRequest)
    {
        CaixaPermissao::setHistorico($criarHistoricoRequest);
        CaixaPermissao::where('id', $caixa_permissao_id)->delete();
        return true;
    }
}
