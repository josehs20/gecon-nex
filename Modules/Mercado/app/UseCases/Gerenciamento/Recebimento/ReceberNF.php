<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Recebimento;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\Mercado\Application\ArquivoApplication;
use Modules\Mercado\Application\FornecedorApplication;
use Modules\Mercado\Application\MovimentacaoEstoqueApplication;
use Modules\Mercado\Application\PedidoApplication;
use Modules\Mercado\Application\ProdutoApplication;
use Modules\Mercado\Application\RecebimentoApplication;
use Modules\Mercado\Repository\Fornecedor\FornecedorRepository;
use Modules\Mercado\Repository\Pedido\PedidoRepository;
use Modules\Mercado\Repository\Recebimento\RecebimentoRepository;
use Modules\Mercado\UseCases\Gerenciamento\Endereco\Requests\EnderecoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Fornecedor\Requests\FornecedorRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueItemRequest;
use Modules\Mercado\UseCases\Gerenciamento\MovimentacaoEstoque\Requests\MovimentacaoEstoqueRequest;
use Modules\Mercado\UseCases\Gerenciamento\Pedido\Requests\CriarPedidoRequest;
use Modules\Mercado\UseCases\Gerenciamento\Produto\Requests\CriarOrAtualizarProdutoPorNFRequest;
use Modules\Mercado\UseCases\Gerenciamento\Recebimento\Requests\ReceberNFRequest;

class ReceberNF
{
    private ReceberNFRequest $request;

    public function __construct(ReceberNFRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $nf = $this->validade();
        $arquivo = $this->salvarArquivo($nf);
        $pedido = $this->criarPedido($nf, $arquivo);
        $recebimento = $this->criarRecebimento($pedido, $arquivo_id);
        $itemRecebidos = $this->criarRecebimentoItens($pedido, $recebimento);
        $this->movimentaEstoques($recebimento, $itemRecebidos);
        $this->verificaPedidoStatusPedido($recebimento);
        return $recebimento;
    }

    private function validade()
    {
        $chaveExiste = DB::table('nf')->where('chave', $this->request->getChaveNota())->first();

        if (!$chaveExiste) {
            throw new Exception("Chave nota não existe.", 1);
        }

        return $chaveExiste;
    }

    private function salvarArquivo($nf)
    {
        //gera o arquivo no fomato correto para sim salvar internamente
        $danfePdf = ArquivoApplication::geraArquivoNF(json_decode($nf->nota, true));

        // Gerar o caminho temporário do arquivo
        $tempPath = tempnam(sys_get_temp_dir(), 'danfe_') . '.pdf';

        // Salvar o conteúdo do PDF no arquivo temporário
        file_put_contents($tempPath, $danfePdf);

        // Criar o arquivo UploadedFile
        $uploadedFile = new UploadedFile(
            $tempPath,
            'danfe_' . $this->request->getChaveNota() . '.pdf',  // Nome do arquivo que será exibido
            'application/pdf',  // Tipo MIME
            null,  // Tamanho do arquivo (pode ser nulo ou um valor válido)
            true,  // Se o arquivo é movido com sucesso
            true   // Se o arquivo é uma instância de UploadedFile
        );

        //executa e retorna a criação do arquivo
        return ArquivoApplication::createArquivo($uploadedFile, config('config.tipo_arquivo.danfe'), $this->request->getLojaId(), $this->request->getCriarHistoricoRequest());
    }

    private function criarPedido($nf, $arquivo)
    {
        $nota = json_decode($nf->nota, TRUE);
        $fornecedor = $this->criaFornecedor($nota, $arquivo->loja->empresa_master_cod);

        if (!$fornecedor) {
            throw new Exception("Não foi possível indentificar o fornecedor na nota.", 1);
        }

        $frete = isset($nota['nfeProc']['NFe']['infNFe']['total']['ICMSTot']['vFrete']) ? $nota['nfeProc']['NFe']['infNFe']['total']['ICMSTot']['vFrete'] : 0;
        $data_pedido = isset($nota['nfeProc']['NFe']['infNFe']['ide']['dEmi']) ? $nota['nfeProc']['NFe']['infNFe']['ide']['dEmi'] : now();
        $previsao_entrega = now();

        /**
         * pega os materiais da nota
         */
        $materiais = $this->getMateriaisNota($nota);

        // return PedidoApplication::criarPedido(new CriarPedidoRequest(
        //     $this->request->getCriarHistoricoRequest(),
        //     $fornecedor->id,
        //     $this->request->getLojaId(),
        //     config('config.status.aberto'),
        // ));
    }

    private function getMateriaisNota($nf)
    {
        dd($nf);
        $detNota = isset($nf['nfeProc']['NFe']['infNFe']['det']) && count($nf['nfeProc']['NFe']['infNFe']['det']) > 0 ? $nf['nfeProc']['NFe']['infNFe']['det'] : [];
       
        if (count($detNota) == 0) {
            throw new Exception("Nenhum produto encontrado na nota", 1);
        }

        foreach ($detNota as $key => $detItemNota) {
            ProdutoApplication::criarOrAtualizaProdutoPorNF(new CriarOrAtualizarProdutoPorNFRequest(
                $this->request->getCriarHistoricoRequest(),
                $this->request->getLojaId(),
                $detItemNota
            ));
        }

        dd($produtos);
    }

    private function criaFornecedor($nf, $empresa_master_cod)
    {
        $existeFornecedor = null;
        if (isset($nf['nfeProc']['NFe']['infNFe']['emit']['CNPJ'])) {
            $fornecedor = $nf['nfeProc']['NFe']['infNFe']['emit'];
            $cnpj = $fornecedor['CNPJ'];
            $existeFornecedor = FornecedorRepository::getFornecedorByDocumento($cnpj, $empresa_master_cod);
            if (!$existeFornecedor) {
                $tel = isset($fornecedor['enderEmit']['fone']) ? $fornecedor['enderEmit']['fone'] : null;
                $logradouro = isset($fornecedor['enderEmit']['xLgr']) ? $fornecedor['enderEmit']['xLgr'] : null;
                $cidade = isset($fornecedor['enderEmit']['xMun']) ? $fornecedor['enderEmit']['xMun'] : null;
                $bairro = isset($fornecedor['enderEmit']['xBairro']) ? $fornecedor['enderEmit']['xBairro'] : null;
                $uf = isset($fornecedor['enderEmit']['UF']) ? $fornecedor['enderEmit']['UF'] : null;
                $cep = isset($fornecedor['enderEmit']['CEP']) ? $fornecedor['enderEmit']['CEP'] : null;
                $numero = isset($fornecedor['enderEmit']['nro']) ? $fornecedor['enderEmit']['nro'] : null;

                $enderecoRequest = new EnderecoRequest($this->request->getCriarHistoricoRequest(), $logradouro, $cidade, $bairro, $uf, $cep, $numero);
                $existeFornecedor = FornecedorApplication::criarFornecedor(new FornecedorRequest(
                    $this->request->getCriarHistoricoRequest(),
                    $empresa_master_cod,
                    $fornecedor['xNome'],
                    $fornecedor['xFant'],
                    $cnpj,
                    'J',
                    true,
                    $tel,
                    null,
                    null,
                    null,
                    $enderecoRequest
                ));
            }

            return $existeFornecedor;
        }
    }

    private function criarRecebimento($pedido, $arquivo_id = null)
    {
        if ($pedido->recebimento) {

            return RecebimentoRepository::update(
                $this->request->getCriarHistoricoRequest(),
                $pedido->recebimento->id,
                $pedido->id,
                $this->request->getCriarHistoricoRequest()->getUsuarioId(),
                $pedido->loja_id,
                $pedido->recebimento->status_id,
                $this->request->getDataRecebimento(),
                $arquivo_id,
                $this->request->getCriarHistoricoRequest()->getComentario()

            );
        } else {
            return RecebimentoRepository::create(
                $this->request->getCriarHistoricoRequest(),
                $pedido->id,
                $this->request->getCriarHistoricoRequest()->getUsuarioId(),
                $pedido->loja_id,
                config('config.status.concluido'),
                $this->request->getDataRecebimento(),
                $arquivo_id,
                $this->request->getCriarHistoricoRequest()->getComentario()

            );
        }
    }

    private function criarRecebimentoItens($pedido, $recebimento)
    {
        $recebimentoItens = [];
        $itensRecebidos = collect($this->request->getItens());
        foreach ($pedido->pedido_itens as $key => $item) {
            $status = config('config.status.concluido');

            $itemRecebido = $itensRecebidos->first(function ($vl) use ($item) {
                return $vl['pedido_item_id'] == $item->id;
            });

            if ($itemRecebido) {
                $itemRecebido = (object) $itemRecebido;
                $validade = $itemRecebido->validade ?? null;
                $lote = $itemRecebido->lote ?? null;

                $qtdRecebidaItem = converteDinheiroParaFloat($itemRecebido->quantidade_recebida);

                $recebimentoItem = RecebimentoRepository::getRecebimentoItemByPedidoItemID($item->id);

                if (!$recebimentoItem) {
                    $qtdRecebida = $qtdRecebidaItem; //fazer a logica depois de quantidade recebida
                    $status = round(floatval($qtdRecebida), 3) >= round(floatval($item->quantidade_pedida), 3) ? $status : config('config.status.aberto');
                    $recebimentoItem = RecebimentoRepository::createRecebimentoItem(
                        $this->request->getCriarHistoricoRequest(),
                        $recebimento->id,
                        $recebimento->loja_id,
                        $item->produto_id,
                        $item->estoque_id,
                        $item->id,
                        $status,
                        $qtdRecebida,
                        $item->quantidade_pedida,
                        $item->preco_unitario,
                        $item->total,
                        $lote,
                        $validade
                    );
                } else {
                    $qtdRecebida = $qtdRecebidaItem + $item->recebimento_item->quantidade_recebida; //fazer a logica depois de quantidade recebida
                    $status = round(floatval($qtdRecebida), 3) >= round(floatval($item->quantidade_pedida), 3) ? $status : config('config.status.aberto');

                    $recebimentoItem = RecebimentoRepository::updateRecebimentoItem(
                        $this->request->getCriarHistoricoRequest(),
                        $recebimentoItem->id,
                        $recebimento->id,
                        $recebimento->loja_id,
                        $item->produto_id,
                        $item->estoque_id,
                        $item->id,
                        $status,
                        $qtdRecebida,
                        $item->quantidade_pedida,
                        $item->preco_unitario,
                        $item->total,
                        $recebimentoItem->lote,
                        $recebimentoItem->validade
                    );
                }
                $recebimentoItem->recebida_agora = $qtdRecebidaItem;
                $recebimentoItens[] = $recebimentoItem;
            }
        }

        return $recebimentoItens;
    }

    private function verificaPedidoStatusPedido($recebimento)
    {
        $existeItemPendente = $recebimento->recebimento_itens->first(function ($item) {
            return $item->status_id == config('config.status.aberto');
        });

        if ($existeItemPendente) {
            $recebimento = RecebimentoApplication::atualizaStatus($recebimento->id, config('config.status.aberto'), $this->request->getCriarHistoricoRequest());
        } else {
            $recebimento = RecebimentoApplication::atualizaStatus($recebimento->id, config('config.status.concluido'), $this->request->getCriarHistoricoRequest());
        }

        if ($recebimento->status_id == config('config.status.concluido')) {
            return PedidoApplication::atualizaStatusPedido($this->request->getCriarHistoricoRequest(), $recebimento->pedido_id, $recebimento->status_id);
        }
    }

    private function movimentaEstoques($recebimento, $itensRecebidos)
    {

        $movimentacao = MovimentacaoEstoqueApplication::criarMovimentacaoEstoque(new MovimentacaoEstoqueRequest(
            $recebimento->loja_id,
            config('config.status.concluido'),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            config('config.tipo_movimentacao_estoque.recebimento'),
            $this->request->getCriarHistoricoRequest()
        ));

        foreach ($itensRecebidos as $key => $item) {
            MovimentacaoEstoqueApplication::movimentar(new MovimentacaoEstoqueItemRequest(
                $item->estoque_id,
                $movimentacao->id,
                config('config.tipo_movimentacao_estoque.recebimento'),
                $item->recebida_agora,
                $this->request->getCriarHistoricoRequest()
            ));
        }

        return MovimentacaoEstoqueApplication::finalizarMovimentacao($movimentacao->id, $this->request->getCriarHistoricoRequest());
    }
}
