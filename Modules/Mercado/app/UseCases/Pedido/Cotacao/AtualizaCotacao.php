<?php

namespace Modules\Mercado\UseCases\Pedido\Cotacao;

use Exception;
use Modules\Mercado\Application\CotacaoApplication;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;
use Modules\Mercado\Repository\Cotacao\CotForItemRepository;
use Modules\Mercado\Repository\Cotacao\CotFornecedorRepository;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pedido\Cotacao\Requests\CriarCotFornecedorRequest;

class AtualizaCotacao
{
    private array $request;
    private CriarHistoricoRequest $criarHistoricoRequest;

    public function __construct(array $request, CriarHistoricoRequest $criarHistoricoRequest)
    {
        $this->request = $request;
        $this->criarHistoricoRequest = $criarHistoricoRequest;
    }

    public function handle()
    {
        $this->validate();
        $this->atualizaCotFornecedores();
        $this->atualizaCotForItens();
        return $this->atualizaCotacao();
    }

    private function validate()
    {
        if (!array_key_exists('cot_fornecedores', $this->request)) {
            throw new Exception("Fornecedores não encontrados.", 1);
        }
        $cotacao = CotacaoRepository::getCotacaoById($this->request['id']);

        if ($cotacao->status_id == config('config.status.comprado')) {
            throw new Exception("Já foi realizado uma compra com a cotacao.", 1);
        }
    }

    private function atualizaCotFornecedores()
    {
        $cot_fornecedores = $this->request['cot_fornecedores'];
        foreach ($cot_fornecedores as $key => $cf) {
            CotacaoApplication::atualizaCotFornecedor($cf['id'], new CriarCotFornecedorRequest(
                $this->criarHistoricoRequest,
                $cf['cotacao_id'],
                $cf['loja_id'],
                $cf['fornecedor_id'],
                converterParaCentavos(converteDinheiroParaFloat($cf['desconto'])),
                converterParaCentavos(converteDinheiroParaFloat($cf['total'])),
                converterParaCentavos(converteDinheiroParaFloat($cf['subTotal'])),
                converterParaCentavos(converteDinheiroParaFloat($cf['frete'])),
                $cf['observacao'],
                $cf['previsao_entrega'],
            ));
        }
    }

    private function atualizaCotForItens()
    {
        $cot_fornecedores = $this->request['cot_fornecedores'];
        foreach ($cot_fornecedores as $key => $cf) {
            if (!array_key_exists('cot_for_itens', $cf)) {
                $fornecedor = FornecedorRepository::getFornecedorById($cf['fornecedor_id']);
                throw new Exception("O fornecedor: " . $fornecedor->nome . ' não foi encontrados itens para ser cotados.', 1);
            }
            //pega somente cotforitens cotados
            $cot_for_itens_com_preco = array_filter($cf['cot_for_itens'], function ($item) {
                return !is_null($item['preco_unitario']);
            });

            //pega todos os pedidos item que tem o mesmo produto para cotar com mesmo fornecedor
            $cot_fornecedor_do_banco = CotFornecedorRepository::getCotFornecedorById($cf['id']);

            foreach ($cot_for_itens_com_preco as $key => $cfi) {
                $itens_repetidos_na_cotacao = $cot_fornecedor_do_banco->cot_for_itens->where('estoque_id', $cfi['estoque_id']);
                $preco_unitario = converterParaCentavos(converteDinheiroParaFloat($cfi['preco_unitario']));
                $status_id = config('config.status.cotado');
                foreach ($itens_repetidos_na_cotacao as $key => $irnc) {
                    CotForItemRepository::updatePrecoUnitario($this->criarHistoricoRequest, $irnc->id, $preco_unitario, $status_id);
                    PedidoApplication::atualizaStatusPedidoItem($this->criarHistoricoRequest, $irnc->pedido_item_id, $status_id);
                }
            }
        }
    }

    private function atualizaCotacao()
    {
        $cotacao = CotacaoRepository::getCotacaoById($this->request['id']);
        if ($this->request['finalizar'] == true) {
            return CotacaoApplication::finalizarCotacao($cotacao->id, $this->criarHistoricoRequest);
        } else {
            $status_id = config('config.status.aberto');
            if ($cotacao->cot_for_itens()->where('preco_unitario', '!=', 0)->count()) {
                $status_id = config('config.status.em_cotacao');
            }

            return CotacaoRepository::updateStatus($this->criarHistoricoRequest, $cotacao->id, $status_id);
        }
    }
}
