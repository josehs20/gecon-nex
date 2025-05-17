<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Usuario\UsuarioRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\TrocarDispositivoRequest;

class TrocarDispositivo
{
    private TrocarDispositivoRequest $request;
    public function __construct(TrocarDispositivoRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $caixa = $this->criaEvidencias();
        $caixa = $this->atualizaCaixa();
        return $caixa;
    }

    private function validade()
    {
        //valida senha
        $usuario = UsuarioRepository::getUsuarioById($this->request->getUsuarioId());

        if (Hash::check($this->request->getSenha(), $usuario->master->password)) {
            return true;
        }

        throw new Exception("Senha incorreta!.", 1);
    }

    // private function atualizaEvidencia($valores)
    // {
    //     $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
    //     $envidenciaId = $caixa->evidencia->id;
    //     $ativo = false;
    //     return CaixaRepository::fecha_evidencia_caixa($envidenciaId, $ativo, $valores->total_vendas, now());
    // }

    private function criaEvidencias()
    {
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        $valor_abertura = $caixa->ultimo_registro->valor_abertura;
        return CaixaApplication::criar_evidencias(new CriarEvidenciaRequest($this->request->getRequest(), $this->request->getCriarHistoricoRequest(),$this->request->getCaixaId(), $this->request->getUsuarioId(), $valor_abertura));
    }

    private function atualizaCaixa()
    {
        $status_id = config('config.status.livre');
        return CaixaApplication::editar_status(new EditarStatusCaixaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $status_id,
            $this->request->getUsuarioId()
        ));
    }

    // //calcula os novos valores do caixa atual ao fazer a transferencia de dispositivo
    // private function calculaNovosValores() {
    //     //pega a data de abertura mais recente do caixa 
    //     return CaixaApplication::calculaFechamento($this->request->getCaixaId());
    // }   

    private function getSangria()
    {
        return CaixaRepository::getSangria($this->request->getCaixaId());
    }
}
