<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Estoque;

use App\Services\NFEIOService;
use Modules\Mercado\Entities\NCM;
use Modules\Mercado\Repository\Estoque\EstoqueRepository;
use Modules\Mercado\UseCases\Gerenciamento\Estoque\Requests\AtualizaNCMRequest;

class AtualizaNCM
{
    private AtualizaNCMRequest $request;

    public function __construct(AtualizaNCMRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $ncm = $this->atualizaNCM();
        // $impostoItem = $this->calculaImpostoUmParaUm();

        // return $estoque;
    }

    private function calculaImpostoUmParaUm()
    {
        $estoque = EstoqueRepository::getEstoqueById($this->request->getEstoqueId());
        $ncm = NCM::find($this->request->getNcmId());
        $nfceioService = new NFEIOService();
        $impostos_item = $nfceioService->calculaImpostoUmParaUm($estoque, $ncm);

        dd($estoque);
    }

    private function atualizaNCM()
    {
        $estoque = EstoqueRepository::atualizaNCM($this->request->getEstoqueId(), $this->request->getNcmId());
        $ncm = NCM::find($this->request->getNcmId());
        $nfceioService = new NFEIOService();
        $impostos_item = $nfceioService->calculaImpostoUmParaUm($estoque, $ncm);

        dd($estoque);
    }
}
