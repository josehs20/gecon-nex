<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\Repository\Usuario\UsuarioRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarSangriaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\FecharCaixaRequest;

class FecharCaixa
{
    private FecharCaixaRequest $request;

    public function __construct(FecharCaixaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $this->validate();
        $caixa = $this->realizaSangriaDeFechamento();

        $caixa = $this->fechaCaixa();

        return $caixa;
    }

    private function validate()
    {
        $usuario = UsuarioRepository::getUsuarioById($this->request->getCriarHistoricoRequest()->getUsuarioId());

        if (!Hash::check($this->request->getSenha(), $usuario->master->password)) {
            throw new Exception("Senha incorreta!.", 1);
        }

        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());
        if ($caixa->status_id == config('config.status.fechado')) {
            throw new Exception("Caixa já foi fechado!.", 1);
        }
    }

    private function realizaSangriaDeFechamento()
    {
        return CaixaApplication::criarSangria(new CriarSangriaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getSenha(),
            null,
            $this->request->getObservacao(),
            $this->request->getRequest()
        ));
    }

    private function fechaCaixa()
    {
        $status_id = config('config.status.fechado');
        $usuario = $this->request->getCriarHistoricoRequest()->getUsuarioId();
        return CaixaRepository::fecha_caixa($this->request->getCriarHistoricoRequest(), $this->request->getCaixaId(), $status_id, $usuario);
    }
}
