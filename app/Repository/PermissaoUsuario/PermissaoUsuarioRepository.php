<?php

namespace App\Repository\PermissaoUsuario;

use Illuminate\Support\Facades\DB;
use Modules\Mercado\Entities\Processos;
use Modules\Mercado\Entities\ProcessoUsuario;

class PermissaoUsuarioRepository
{
    public static function obterPermissoesPorTipoUsuarioId(
        int $tipo_usuario_id
    ){
        return Processos::select('processos.*')
        ->join('mercado.processo_tipo_usuario as ptu', 'ptu.processo_id', '=', 'processos.id')
        ->join('gecon.tipo_usuarios as tu', 'tu.id', '=', 'ptu.tipo_usuario_id')
        ->where('tu.id', $tipo_usuario_id)
        ->get();
    }

    public static function obterTodasPermissoes(
        int $tipo_usuario_id
    ){
        return Processos::select('processos.*')
        ->whereNotExists(function ($query) use ($tipo_usuario_id) {
            $query->select(DB::raw(1))
                ->from('mercado.processo_tipo_usuario as ptu')
                ->whereColumn('ptu.processo_id', 'processos.id')
                ->where('ptu.tipo_usuario_id', $tipo_usuario_id);
        })
        ->get();
    }

    public static function permissaoExiste(
        int $processo_id,
        int $tipo_usuario_id
    ){
        return ProcessoUsuario::where('processo_id', $processo_id)
            ->where('tipo_usuario_id', $tipo_usuario_id)
            ->exists();
    }

    public static function adicionar(
        int $processo_id,
        int $tipo_usuario_id
    ){
        return ProcessoUsuario::create([
            'processo_id' => $processo_id,
            'tipo_usuario_id' => $tipo_usuario_id
        ]);
    }

    public static function remover(
        int $processo_id,
        int $tipo_usuario_id
    ) {
        return ProcessoUsuario::where('processo_id', $processo_id)
            ->where('tipo_usuario_id', $tipo_usuario_id)
            ->delete();
    }
}
