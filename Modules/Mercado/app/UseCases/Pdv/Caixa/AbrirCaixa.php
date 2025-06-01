<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Application\CaixaApplication;
use Modules\Mercado\Application\PDVApplication;
use Modules\Mercado\Repository\PDV\CaixaPDVRepository;
use Modules\Mercado\Repository\Usuario\UsuarioRepository;
use Modules\Mercado\UseCases\Gerenciamento\Caixa\Requests\CriarCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\AbrirCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarCaixaDiarioRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\EditarStatusCaixaRequest;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\SuprirCaixaRequest;

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
        $evidencia = $this->cria_evidencias();
        $caixa = self::abrir_caixa();
        $diario = $this->criar_caixa_diario($evidencia, $caixa);
        $suprimentos = $this->suprir_caixa($evidencia, $diario);
        
        return $caixa;
    }

    private function validade()
    {
        //valida senha
        $usuario = UsuarioRepository::getUsuarioById($this->request->getUsuarioId());

        $usuario->verificaSenha($this->request->getSenha());


        return true;
    }

    private function suprir_caixa($evidencia, $diario)
    {
        return PDVApplication::suprir_caixa(new SuprirCaixaRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $evidencia->id,
            $diario->id,
            $this->request->getUsuarioId(),
            $this->request->getValorInicial(),
            $this->request->getCriarHistoricoRequest()->getComentario()
        ));
    }

    private function abrir_caixa()
    {
        $status_id = config('config.status.livre');

        return $this->editar_status_caixa($status_id);
    }

    private function editar_status_caixa($status_id)
    {
        return CaixaPDVRepository::editaAttrsCaixa(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            [
                'status_id' => $status_id,
                'usuario_id' => $this->request->getUsuarioId(), //faz o caixa ficar em operação somente com esse usuário
                'token' => gerarToken()
            ]
        );
    }

    private function cria_evidencias()
    {
        return PDVApplication::criar_evidencias($this->request->getCriarEvidenciaRequest());
    }

     private function criar_caixa_diario($evidencia, $caixa)
    {
        return PDVApplication::criar_ou_atualizar_caixa_diario(new CriarCaixaDiarioRequest(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $evidencia->id,
            $this->request->getUsuarioId(),
            $caixa->loja_id,
            $caixa->status_id,
            now(),
        ));
    }
}
