<?php

namespace App\Repository\Empresa;

use App\Models\Empresa;

class EmpresaRepository
{
    public static function create(string $razao_social, string $nome_fantasia, string $cnpj, int $ativo, int $status_id, ?string $foto = null, ?string $caixa_cor = null): ?Empresa
    {
        $data = [
            'razao_social' => $razao_social,
            'nome_fantasia' => $nome_fantasia,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'ativo' => $ativo
        ];

        if (!is_null($foto)) {
            $data['foto'] = $foto;
        }

        if (!is_null($caixa_cor)) {
            $data['caixa_cor'] = $caixa_cor;
        }

        return Empresa::create($data);
    }

    public static function update(int $id, string $razao_social, string $nome_fantasia, string $cnpj, int $ativo, int $status_id, ?string $foto = null, ?string $caixa_cor = null): ?Empresa
    {
        $empresa = Empresa::find($id);
        $data = [
            'razao_social' => $razao_social,
            'nome_fantasia' => $nome_fantasia,
            'cnpj' => $cnpj,
            'status_id' => $status_id,
            'ativo' => $ativo,
        ];

        if (!is_null($foto)) {
            $data['foto'] = $foto;
        }

        if (!is_null($caixa_cor)) {
            $data['caixa_cor'] = $caixa_cor;
        }

        $empresa->update($data);

        return $empresa->refresh();
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
