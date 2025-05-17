<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Usuario\UsuarioRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarSangriaRequest;

class CriarSangria
{
    private CriarSangriaRequest $request;
    public function __construct(CriarSangriaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $caixa = $this->validade();

        $evidencia = $this->criaEvidencia($caixa);
        return $caixa;
    }

    private function validade()
    {
        //valida senha
        $usuario = UsuarioRepository::getUsuarioById($this->request->getCriarHistoricoRequest()->getUsuarioId());

        if (!Hash::check($this->request->getSenha(), $usuario->master->password)) {
            throw new Exception("Senha incorreta!.", 1);
        }

        //validacao sangria normal
        if ($this->request->getCriarHistoricoRequest()->getAcaoId() == config('config.acoes.sangria.id')) {
            $caixa = $this->getSangria();
            $totalPorFormaPagamento = $caixa->total_por_forma_pagamento;
            $totalPorFormaDevolucao = $caixa->total_por_forma_devolucao;

            $totalPodeRetirarEmDinheiro = $caixa->ultimo_registro->valor_abertura;

            if (array_key_exists('Dinheiro', $totalPorFormaPagamento)) {
                $totalPodeRetirarEmDinheiro = $totalPodeRetirarEmDinheiro + $totalPorFormaPagamento['Dinheiro'];
            }

            if (array_key_exists('Dinheiro', $totalPorFormaDevolucao)) {
                $totalPodeRetirarEmDinheiro = $totalPodeRetirarEmDinheiro - $totalPorFormaDevolucao['Dinheiro'];
            }

            if ($this->request->getValorSangria() > $totalPodeRetirarEmDinheiro) {
                throw new Exception("O valor em Dinheiro é maior do que contém em caixa.", 1);
            }

            $caixa->total_pode_retirar_em_dinheiro = $totalPodeRetirarEmDinheiro;
        } else {
            //sangria de fechamento
            $caixa = $this->getSangria();
         
            $totalPorFormaPagamento = $caixa->total_por_forma_pagamento;
            $totalPorFormaDevolucao = $caixa->total_por_forma_devolucao;

            $totalPodeRetirarEmDinheiro = $caixa->ultimo_registro->valor_abertura;

            if (array_key_exists('Dinheiro', $totalPorFormaPagamento)) {
                $totalPodeRetirarEmDinheiro = $totalPodeRetirarEmDinheiro + $totalPorFormaPagamento['Dinheiro'];
            }

            if (array_key_exists('Dinheiro', $totalPorFormaDevolucao)) {
                $totalPodeRetirarEmDinheiro = $totalPodeRetirarEmDinheiro - $totalPorFormaDevolucao['Dinheiro'];
            }
   
            $caixa->total_pode_retirar_em_dinheiro = $totalPodeRetirarEmDinheiro;
        }

        return $caixa;
    }

    private function getSangria()
    {
        return CaixaRepository::getSangria($this->request->getCaixaId());
    }

    private function criaEvidencia($caixa)
    {
        //acao for fechar o caixa ele retira tudo do caixa
        $valorAbertura = $caixa->total_pode_retirar_em_dinheiro - $this->request->getValorSangria();
      
        //caso seja uma sangria de fechamento de caixa
        if ($this->request->getCriarHistoricoRequest()->getAcaoId() == config('config.acoes.fechou_caixa.id')) {
            
            $this->request->setValorSangria(($caixa->total_pode_retirar_em_dinheiro < 0 ? 0 : $caixa->total_pode_retirar_em_dinheiro));
            $valorAbertura = 0;

        }

        $valorFechamento = $caixa->total_pode_retirar_em_dinheiro;
        $valorSangria = $this->request->getValorSangria();
        $descricao = $this->request->getDescricao();

        return CaixaApplication::criar_evidencias(new CriarEvidenciaRequest(
            $this->request->getRequest(),
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getUsuarioId(),
            $valorAbertura,
            $valorFechamento,
            $valorSangria,
            $descricao
        ));
    }
}
