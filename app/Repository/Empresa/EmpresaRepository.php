<?php

namespace App\Repository\Empresa;

use App\Models\Empresa;

class EmpresaRepository
{
    public static function create(string $razao_social, string $nome_fantasia, string $cnpj, int $ativo, int $status_id): ?Empresa
    {
        return Empresa::create([
            'razao_social' => $razao_social,
            'nome_fantasia' => $nome_fantasia,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'ativo' => $ativo
        ]);
    }

    public static function update(int $id, string $razao_social, string $nome_fantasia, string $cnpj, int $ativo, int $status_id): ?Empresa
    {
        $empresa = Empresa::find($id);
        $empresa->update([
            'razao_social' => $razao_social,
            'nome_fantasia' => $nome_fantasia,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'ativo' => $ativo
        ]);
        return $empresa;
    }


    public static function getEmpresaByCnpj(string $cnpj)
    {
        return Empresa::where('cnpj', $cnpj)->first();
    }

    public static function getEmpresaById($id)
    {
        return Empresa::find($id);
    }

    public static function updateCnpj($id, $cnpj)
    {
        $empresa = Empresa::find($id);
        $empresa->update([
            'cnpj' => $cnpj
        ]);
        return $empresa;
    }

}
