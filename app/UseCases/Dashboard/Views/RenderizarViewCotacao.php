<?php

namespace App\UseCases\Dashboard\Views;

use App\UseCases\Dashboard\HelperRenderizarViews;
use Modules\Mercado\Repository\Cotacao\CotacaoRepository;

class RenderizarViewCotacao extends HelperRenderizarViews
{
    private int $loja_id;

    public function __construct(int $loja_id) {
        $this->loja_id = $loja_id;
    }

    public function handle(): array
    {
        return [
            'view_cotacao' => true,
            'listagem_cotacoes' => $this->getCotacoes(),
            'quantidade_cotacoes_cotados' => $this->obterQuantidade($this->getCotacoesCotados()),
            'quantidade_cotacoes_canceladas' => $this->obterQuantidade($this->getCotacoesCanceladas()),
            'quantidade_cotacoes_em_aberto' => $this->obterQuantidade($this->getCotacoesEmAberto()),
            'quantidade_cotacoes_em_cotacao' => $this->obterQuantidade($this->getCotacoesEmCotacao()),
            'quantidade_cotacoes' => $this->getQuantidadeTotalCotacoes(),
            'quantidade_cotacoes_compradas' => $this->obterQuantidade($this->getCotacoesCompradas()),
            'porcentagens' => $this->getPorcentagens()
        ];
    }

    private function getPorcentagens(){
        return [
            'cotações_finalizadas' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesCotados())),
            'cotações_em_aberto' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesEmAberto())),
            'cotações_em_cotação' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesEmCotacao())),
            'cotações_canceladas' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesCanceladas())),
            'cotações_compradas' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesCompradas())),
            'cotações_sem_compras' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesSemCompras())),
            'cotações_com_compras' => $this->calcularPorcentagem($this->getQuantidadeTotalCotacoes(), $this->obterQuantidade($this->getCotacoesComCompras())),
        ];
    }

    private function getQuantidadeTotalCotacoes(){
        return $this->obterQuantidade($this->getCotacoes());
    }

    private function getCotacoes(){
        return CotacaoRepository::getCotacoes($this->loja_id);
    }

    private function getCotacoesCotados(){
        return CotacaoRepository::getCotacoesCotado($this->loja_id);
    }

    private function getCotacoesEmAberto(){
        return CotacaoRepository::getCotacoesEmAberto($this->loja_id);
    }

    private function getCotacoesEmCotacao(){
        return CotacaoRepository::getCotacoesEmCotacao($this->loja_id);
    }

    private function getCotacoesCanceladas(){
        return CotacaoRepository::getCotacoesCanceladas($this->loja_id);
    }

    private function getCotacoesCompradas(){
        return CotacaoRepository::getCotacoesCompradas($this->loja_id);
    }

    private function getCotacoesSemCompras(){
        return CotacaoRepository::getCotacoesSemCompras($this->loja_id);
    }

    private function getCotacoesComCompras(){
        return CotacaoRepository::getCotacoesComCompras($this->loja_id);
    }

}
