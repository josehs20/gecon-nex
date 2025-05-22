<?php

namespace App\Repository\Loja;

use App\Models\Certificado;
use App\Models\InscricaoEstadual;
use App\Models\Loja;
use App\Models\NFEIOLoja;
use Illuminate\Support\Carbon;

class LojaRepository
{
    public static function create(
        $nome,
        $empresa_id,
        $matriz,
        $cnpj,
        $modulo_id,
        $status_id,
        $email = null,
        $telefone = null,
        $endereco_id = null
    ) {
        
        return Loja::create([
            'nome' => $nome,
            'empresa_id' => $empresa_id,
            'matriz' => $matriz,
            'cnpj' => $cnpj,
            'modulo_id' => $modulo_id,
            'status_id' => $status_id,
            'email' => $email,
            'telefone' => $telefone,
            'endereco_id' => $endereco_id,
        ]);
    }

    public static function update(
        $id,
        $nome,
        $empresa_id,
        $matriz,
        $cnpj,
        $modulo_id,
        $status_id
    ) {
        $loja = Loja::find($id);
        $loja->update([
            'nome' => $nome,
            'empresa_id' => $empresa_id,
            'matriz' => $matriz,
            'cnpj' => $cnpj,
            'modulo_id' => $modulo_id,
            'status_id' => $status_id
        ]);
        return $loja;
    }

    public static function getLojaByCnpj(string $cnpj)
    {
        return Loja::where('cnpj', $cnpj)->first();
    }

    public static function getLojaById(int $id)
    {
        return Loja::find($id);
    }

    public static function updateMatriz(int $id, bool $matriz)
    {
        $loja = Loja::find($id);
        $loja->update(['matriz' => $matriz]);
        return $loja;
    }

    public static function getLojasSemVinculoComNFE()
    {
        return Loja::whereDoesntHave('nfeio')->get();
    }

    public static function criarLojaNFE(
        $empresa_id,
        $loja_id,
        $nfeio_id,
        $account_id,
        $name = null,
        $trade_name = null,
        $federal_tax_number = null,
        $tax_regime = null,
        $status = null,
        $address = null,
    ) {
        return NFEIOLoja::create([
            'empresa_id' => $empresa_id,
            'loja_id' => $loja_id,
            'nfeio_id' => $nfeio_id,
            'account_id' => $account_id,
            'name' => $name,
            'trade_name' => $trade_name,
            'federal_tax_number' => $federal_tax_number,
            'tax_regime' => $tax_regime,
            'status' => $status,
            'address' => json_encode($address, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public static function atualizaLojaNFE(
        $empresa_id,
        $loja_id,
        $nfeio_id,
        $account_id,
        $name = null,
        $trade_name = null,
        $federal_tax_number = null,
        $tax_regime = null,
        $status = null,
        $address = null,
    ) {
        $NFEIOLoja = NFEIOLoja::where('empresa_id', $empresa_id)->where('loja_id', $loja_id)->first();
        $NFEIOLoja->update([
            'empresa_id' => $empresa_id,
            'loja_id' => $loja_id,
            'nfeio_id' => $nfeio_id,
            'account_id' => $account_id,
            'name' => $name,
            'trade_name' => $trade_name,
            'federal_tax_number' => $federal_tax_number,
            'tax_regime' => $tax_regime,
            'status' => $status,
            'address' => json_encode($address, JSON_UNESCAPED_UNICODE),
        ]);
        return $NFEIOLoja;
    }

    public static function createCertificado(
        $empresa_id,
        $nfeio_loja_id,
        $ativo,
        $loja_id,
        $caminho,
        $senha,
        $expiracao,
        $status
    ) {
        return Certificado::create([
            'empresa_id' => $empresa_id,
            'nfeio_loja_id' => $nfeio_loja_id,
            'ativo' => $ativo,
            'loja_id' => $loja_id,
            'caminho' => $caminho,
            'senha' => $senha,
            'expiracao' => Carbon::parse($expiracao)->format('Y-m-d H:i:s'),
            'status' => $status,
        ]);
    }

    public static function updateCertificado(
        $certificado_id,
        $empresa_id,
        $nfeio_loja_id,
        $ativo,
        $loja_id,
        $caminho,
        $senha,
        $expiracao,
        $status
    ) {
        $certificado = Certificado::find($certificado_id);
        $certificado->update([
            'empresa_id' => $empresa_id,
            'nfeio_loja_id' => $nfeio_loja_id,
            'ativo' => $ativo,
            'loja_id' => $loja_id,
            'caminho' => $caminho,
            'senha' => $senha,
            'expiracao' => Carbon::parse($expiracao)->format('Y-m-d H:i:s'),
            'status' => $status,
        ]);
        return $certificado;
    }

    public static function createInscricaoEstadual(
        $loja_id,
        $nfeio_loja_id,
        $state_tax_id,
        $account_id,
        $company_id,
        $code,
        $special_tax_regime,
        $type,
        $tax_number,
        $status,
        $serie,
        $number,
        $processing_details,
        $security_credential
    ) {
        return InscricaoEstadual::create([
            'loja_id' => $loja_id,
            'nfeio_loja_id' => $nfeio_loja_id,
            'state_tax_id' => $state_tax_id,
            'account_id' => $account_id,
            'company_id' => $company_id,
            'code' => $code,
            'special_tax_regime' => $special_tax_regime,
            'type' => $type,
            'tax_number' => $tax_number,
            'status' => $status,
            'serie' => $serie,
            'number' => $number,
            'processing_details' => json_encode($processing_details, JSON_UNESCAPED_UNICODE),
            'security_credential' => json_encode($security_credential, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public static function atualizaInscricaoEstadual(
        $id,
        $loja_id,
        $nfeio_loja_id,
        $state_tax_id,
        $account_id,
        $company_id,
        $code,
        $special_tax_regime,
        $type,
        $tax_number,
        $status,
        $serie,
        $number,
        $processing_details,
        $security_credential
    ) {
        $inscricao_estadual = InscricaoEstadual::find($id);
        $inscricao_estadual->update([
            'loja_id' => $loja_id,
            'nfeio_loja_id' => $nfeio_loja_id,
            'state_tax_id' => $state_tax_id,
            'account_id' => $account_id,
            'company_id' => $company_id,
            'code' => $code,
            'special_tax_regime' => $special_tax_regime,
            'type' => $type,
            'tax_number' => $tax_number,
            'status' => $status,
            'serie' => $serie,
            'number' => $number,
            'processing_details' => json_encode($processing_details, JSON_UNESCAPED_UNICODE),
            'security_credential' => json_encode($security_credential, JSON_UNESCAPED_UNICODE),
        ]);

        return $inscricao_estadual;
    }

}
