<?php

namespace Modules\Mercado\UseCases\Pdv\Caixa;

use Jenssegers\Agent\Agent;
use Modules\Mercado\Repository\Caixa\CaixaRepository;
use Modules\Mercado\UseCases\Pdv\Caixa\Requests\CriarEvidenciaRequest;
use Stevebauman\Location\Facades\Location;

class CriarEvidencia
{
    private CriarEvidenciaRequest $request;
    public function __construct(CriarEvidenciaRequest $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        $ip = $this->getIp();
        $localizacao = $this->getLocalizacao($ip);
        $sistema_operacional = $this->getBrowserInfo();
        $sessionToken = session()->getId();
        return $this->criarEvidencias($ip, $sistema_operacional, $localizacao, $sessionToken);
    }

    private function getIp()
    {
        return $this->request->getRequest()->header('X-Forwarded-For') ?? $this->request->getRequest()->ip();;
    }

    private function getSistemaOperacional()
    {
        return PHP_OS;
    }

    private function getLocalizacao($ip)
    {
        $location = Location::get($ip);
        return ($location->cityName ?? '') . ', ' . ($location->regionName ?? '') . ' - ' . ($location->countryName ?? '') . ', CEP: ' . ($location->zipCode ?? '') . '-000';
    }

    public function getBrowserInfo()
    {
        $agent = new Agent();

        // Captura informações do navegador
        $browser = $agent->browser();
        $version = $agent->version($browser);
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $os = $this->getSistemaOperacional();
        // Exemplo de retorno das informações
        return $os . ', Browser: ' . $browser . ', ' .
            'Version: ' . $version . ', ' .
            'Platform: ' . $platform . ', ' .
            'Platform Version: ' . $platformVersion;
    }

    private function criarEvidencias(
        $ip_address,
        $sistema_operacional,
        $localizacao,
        $sessionToken
    ) {
        $ativo = true;
        $data_fechamento = null;
        $caixa = CaixaRepository::getCaixaById($this->request->getCaixaId());

        $data_abertura = now();
        if ($this->request->getCriarHistoricoRequest()->getAcaoId() == config('config.acoes.fechou_caixa.id')) {
            $data_fechamento = now();
            $ativo = false;
            $data_abertura = $caixa->ultima_abertura->data_abertura;
        }

        if ($this->request->getCriarHistoricoRequest()->getAcaoId() == config('config.acoes.sangria.id')) {
            $ativo = false;
        }

        return CaixaRepository::criarEvidencia(
            $this->request->getCriarHistoricoRequest(),
            $this->request->getCaixaId(),
            $this->request->getCriarHistoricoRequest()->getAcaoId(),
            $this->request->getUsuarioId(),
            $ip_address,
            $sistema_operacional,
            $localizacao,
            $ativo,
            $sessionToken,
            $this->request->getValorAbertura(),
            $data_abertura,
            $this->request->getValorFechamento(),
            $this->request->getValorSangria(),
            $data_fechamento,
            $this->request->getDescricao()
        );
    }
}
