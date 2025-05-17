<?php

namespace Modules\Mercado\UseCases\Pdv\Venda\Rules;

use Exception;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\Repository\Pagamento\PagamentoRepository;
use Modules\Mercado\UseCases\Pdv\Venda\Requests\CriarVendaRequest;

class SomaValoresItens
{
    public static function handle(CriarVendaRequest $request)
    {
        return self::somaValores($request);
    }

    private static function somaValores(CriarVendaRequest $request)
    {
        $subTotal = 0;
        $total = 0;
        $desconto_dinheiro = 0;
        $desconto_porcentagem = $request->getDescontoPorcentagem() ?? 0;
        $itens = [];

        foreach ($request->getItens() as $key => $item) {
            $itens[$item['estoqueId']] = $item;
        }

        $estoques = EstoqueRepository::getEstoqueByIds(array_keys($itens));
        foreach ($estoques as $key => $e) {
            $qtd = converteDinheiroParaFloat($itens[$e->id]['qtd']);
            $itens[$e->id]['qtd'] = $qtd;
            $itens[$e->id]['preco'] = $e->preco;
            $itens[$e->id]['produtoId'] = $e->produto_id;
            $itens[$e->id]['total'] = $e->preco * $qtd;
            $subTotal += $itens[$e->id]['total'];
        }
     
        //seta os itens com valores convertidos para seus tipos
        $request->setItens($itens);

        // Calcula o desconto em dinheiro
        if ($desconto_porcentagem > 0) {
            $desconto_dinheiro = round(($desconto_porcentagem / 100) * $subTotal, 2);
        }

        // Calcula o total com desconto
        $total = round($subTotal - $desconto_dinheiro);
        $totalPago = 0;
        if ($request->getFormaPagamentoId() && count($request->getFormaPagamentoId()) > 0) {
            $formaPagamentos = array_map(function ($item) {
                $item['forma'] = PagamentoRepository::getFormaPagamentoById($item['pagamentoId']);
                $item['valor'] = converteDinheiroParaFloat($item['valor']);
                return (object) $item;
            }, $request->getFormaPagamentoId());
        
            foreach ($formaPagamentos as $key => $f) {
                //caso afeta troco e o valor qeu recebe menor que o valor total da venda
                if ($f->forma->especie->afeta_troco == true && $request->getValoRecebido() < $f->valor) {
                    throw new Exception("Valor recebido menor que valor total em dinheiro.", 1);
                } elseif ($f->forma->especie->afeta_troco == true) {
                    //caso passe, confere se vai afetar troco, vai setar o alor recebido em dinheiro
                    $request->setTroco(($request->getValoRecebido() - $f->valor));
                }
                $totalPago += converterParaCentavos($f->valor);
            }
            //depois que percorrer todos as formas de pagamento e passar pela verificação de afetação de troco ele seta o valor total como o valore recebido
            $request->setValoRecebido($total);
            //seta as formas de pagamento atualizada
            $request->setFormaPagamentoId($formaPagamentos);
        }
   
        if ($request->getStatusId() != config('config.status.salvo') && $totalPago < $total) {
            throw new Exception("Faltam ". converterParaReais(($total - $totalPago)). ' para completar o valor da venda.', 1);   
        }
        return (object) [
            'subTotal' => $subTotal,
            'total' => $total,
            'desconto_dinheiro' => $desconto_dinheiro,
            'desconto_porcentagem' => $desconto_porcentagem,
        ];
    }
}
