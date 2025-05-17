<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Usuario\UsuarioRepository;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;

class AbrirCaixa
{
    private AbrirCaixaRequest $request;
    public function __construct(AbrirCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validade();
        $this->cria_evidencias();
        $caixa = self::abrir_caixa();
        return $caixa;
    }

    private function validade()
    {
        //valida senha
        $usuario = UsuarioRepository::getUsuarioById($this->request->getUsuarioId());

        if (!Hash::check($this->request->getSenha(), $usuario->master->password)) {
            throw new Exception("Senha incorreta!.", 1);
        }
        
        return true;
    }

    private function abrir_caixa()
    {
        //atualiza caixa para aberto e cria o audite de como ele esta em aberto
        $status_id = config('config.status.aberto');

        CaixaApplication::editar_status(new EditarStatusCaixaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $status_id,
            $this->request->getUsuarioId()
        ));

        $status_id = config('config.status.livre');

        return CaixaApplication::editar_status(new EditarStatusCaixaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $status_id,
            $this->request->getUsuarioId()
        ));
    }

    private function cria_evidencias()
    {
        return CaixaApplication::criar_evidencias($this->request->getCriarEvidenciaRequest());
    }
}
