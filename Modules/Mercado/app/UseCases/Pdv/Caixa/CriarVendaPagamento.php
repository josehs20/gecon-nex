<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Modules\Mercado\Repository\Cliente\ClienteRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarVendaPagamentoRequest;

class CriarVendaPagamento
{
    private CriarVendaPagamentoRequest $request;
    public function __construct(CriarVendaPagamentoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        return $this->criaVendaPagamento();
    }

    private function criaVendaPagamento()
    {
        $dataPagamentos = $this->montaDataVendaPagamentos();
        $pagamentos = [];

        foreach ($dataPagamentos as $key => $pg) {
            $pagamentos[] = PagamentoRepository::criaVendaPagamento(
                $this->request->getCriarVendaRequest()->getCriarHistoricoRequest(),
                $pg['venda_id'],
                $pg['forma_pagamento_id'],
                $pg['especie_pagamento_id'],
                $pg['loja_id'],
                $pg['valor_pago'],
                $pg['valor'],
                $pg['status_id'],
                $pg['parcela'],
                $pg['troco']
            );
        }

        $this->criaPagamentos($pagamentos);

        return $pagamentos;
    }

    private function criaPagamentos($vendaPagamentos)
    {
        $pagamentos = [];
      
        $pagamentosFiltrados = collect($vendaPagamentos)->groupBy('forma_pagamento_id')
        ->map(function ($group) {
            // Retorna o pagamento com a maior parcela dentro do grupo
            $elemento = $group->sortByDesc('parcela')->first();
            $elemento->valor_total_recebido = $group->sum('valor_pago');
            return $elemento;
        });
     
        foreach ($pagamentosFiltrados as $key => $vp) {
            if ($vp->valor_pago > 0) {
                $pagamentos[] = PagamentoRepository::criaPagamento(
                    $this->request->getCriarVendaRequest()->getCriarHistoricoRequest(),
                    $this->request->getCriarVendaRequest()->getLojaId(),
                    $this->request->getCriarVendaRequest()->getCaixaId(),
                    $this->request->getCriarVendaRequest()->getCaixaEvidenciaId(),
                    $vp->venda_id,
                    $vp->id,
                    $vp->forma_pagamento_id,
                    $vp->especie_pagamento_id,
                    $vp->valor_total_recebido,
                    now(),
                    $vp->parcela
                );
            }
        }

        return $pagamentos;
    }

    private function atualizaCreditoUsadoCliente($cliente, $valor)
    {
        $valor = $cliente->credito->credito_loja_usado + $valor;

        return ClienteRepository::atualizar_credito_loja_usado($this->request->getCriarVendaRequest()->getCriarHistoricoRequest(), $cliente->id, $valor);
    }


    public function montaDataVendaPagamentos()
    {
        $vendaPagamentos = [];

        // Define os valores de timestamps para todos os registros
        $timestamps = [
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $total = 0;

        foreach ($this->request->getCriarVendaRequest()->getFormaPagamentoId() as $key => $pg) {
            // Dados comuns a todas as condições
            $dadosBase = [
                'venda_id' => $this->request->getVenda()->id,
                'forma_pagamento_id' => $pg->forma->id,
                'especie_pagamento_id' => $pg->forma->especie_pagamento_id,
                'loja_id' => $this->request->getVenda()->loja_id,
                'status_id' => config('config.status.pago'),
            ];

            $valor = converterParaCentavos($pg->valor);
            $total += $valor;
            if ($valor == 0) {
                throw new Exception("O valor recebido em " . $pg->forma->especie->nome . ' está inválido.', 1);
            }
            // Verifica se a forma de pagamento afeta troco
            if ($pg->forma->especie->afeta_troco) {
                $vendaPagamentos[] = array_merge($dadosBase, [
                    'parcela' => 0,
                    'valor' => $valor,
                    'valor_pago' => $valor,
                    'troco' => converterParaCentavos($this->request->getCriarVendaRequest()->getTroco()),
                ], $timestamps);
            } else {
                // Verifica se há parcelas na forma de pagamento
                if ($pg->forma->especie->contem_parcela) {
                    //caso contem parcela e não foi passado a quantidade de parcela
                    if (!isset($pg->parcelas)) {
                        throw new Exception("É necessário informar a quantidade de parcelas par pagamentos com cartão de crédito.", 1);
                    }
                    $parcelas = intval($pg->parcelas);
                    if ($parcelas == 0) {
                        throw new Exception("É necessário informar a quantidade de parcelas par pagamentos com cartão de crédito.", 1);
                    }
                    // Divide o valor pela quantidade de parcelas
                    $valorCadaParcela = floor($valor / $parcelas);

                    // Calcula a diferença (resto) que precisa ser ajustada na última parcela
                    $diferencaCentavos = $valor - ($valorCadaParcela * $parcelas);

                    // Inicia o array para armazenar os pagamentos
                    for ($i = 1; $i <= $parcelas; $i++) {
                        // Se for a última parcela, ajusta o valor para incluir a diferença
                        $valorParcela = $valorCadaParcela;
                        if ($i === $parcelas) {
                            // Ajusta a última parcela com a diferença de centavos
                            $valorParcela += $diferencaCentavos;
                        }

                        // Adiciona os dados da parcela no array
                        $vendaPagamentos[] = array_merge($dadosBase, [
                            'parcela' => $i,
                            'valor' => $valorParcela, // Valor da parcela em centavos
                            'valor_pago' => $valorParcela, // Valor pago também em centavos
                            'troco' => null,
                        ], $timestamps);
                    }
                } else {
                    //valida credito cliente
                    $valorPago = $valor;
                    $parcela = 0;
                    if ($pg->forma->especie->credito_loja) {
                        //valida limite de crédito cliente
                        $cliente = $this->request->getVenda()->cliente;

                        if ($cliente->documento == config('config.cliente_padrao.documento')) {
                            throw new Exception("Cliente inválido, ecolha um cliente diferente do PADRÃO, com limite de crédito em loja.", 1);
                        }

                        if ($cliente->credito == null) {
                            throw new Exception("Cliente não possui crédito cadastrado.", 1);
                        }
                        if ($valor > $cliente->getLimiteDisponivel()) {
                            throw new Exception("Limite excedido! O cliente " . $cliente->nome . ' possui um limite disponível de ' .  converterParaReais($cliente->getLimiteDisponivel()), 1);
                        }
                        $dadosBase['status_id'] = config('config.status.pendente');
                        $this->atualizaCreditoUsadoCliente($cliente, $valor);
                        $valorPago = 0; //credito em loja o valor qu efoi pago é 0
                        $parcela = 1;
                    }
                    // Caso não exista parcela, cria um registro sem troco
                    $vendaPagamentos[] = array_merge($dadosBase, [
                        'parcela' => $parcela,
                        'valor' => $valor,
                        'valor_pago' => $valorPago,
                        'troco' => null,
                    ], $timestamps);
                }
            }
        }

        return $vendaPagamentos;
    }
}
