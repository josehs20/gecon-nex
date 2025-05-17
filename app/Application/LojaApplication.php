<?php

namespace App\Application;

use App\UseCases\Loja\AtualizaInscricaoEstadual;
use App\UseCases\Loja\AtualizaLoja;
use App\UseCases\Loja\CriarInscricaoEstadual;
use App\UseCases\Loja\CriarLoja;
use App\UseCases\Loja\CriarOrAtualizarLojaNFE;
use App\UseCases\Loja\Requests\CriarInscricaoEstadualRequest;
use App\UseCases\Loja\Requests\CriarLojaRequest;
use App\UseCases\Loja\Requests\CriarOrAtualizarLojaNFERequest;
use App\UseCases\Loja\Requests\UploadCertificadoRequest;
use App\UseCases\Loja\UploadCertificado;

class LojaApplication
{
    public static function criaLoja(CriarLojaRequest $request)
    {
        $interact = new CriarLoja($request);
        return $interact->handle();
    }

    public static function atualizaLoja($id, CriarLojaRequest $request)
    {
        $interact = new AtualizaLoja($id, $request);
        return $interact->handle();
    }

    public static function criaOrAtualizarLojaNFE(CriarOrAtualizarLojaNFERequest $request)
    {
        $interact = new CriarOrAtualizarLojaNFE($request);
        return $interact->handle();
    }

    public static function uploadCertificado(UploadCertificadoRequest $request)
    {
        $interact = new UploadCertificado($request);
        return $interact->handle();
    }

    public static function criarInscricaoEstadual(CriarInscricaoEstadualRequest $request)
    {
        $interact = new CriarInscricaoEstadual($request);
        return $interact->handle();
    }

    public static function atualizaInscricaoEstadual($id, CriarInscricaoEstadualRequest $request)
    {
        $interact = new AtualizaInscricaoEstadual($id, $request);
        return $interact->handle();
    }
}
