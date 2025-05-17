<?php

namespace App\Repository\Usuario;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Modules\Mercado\Entities\Usuario;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class UsuarioRepository
{
    public static function criar(
        string $name,
        string $login,
        string $email,
        string $password,
        int $modulo_id,
        bool $permite_abrir_caixa,
        int $tipo_usuario_id,
        int $empresa_id,
        array $loja_id,
        ?int $endereco_id = null,
        int $status_id,
        ?string $data_nascimento = null,
        string $documento,
        ?string $telefone = null,
        ?string $celular = null,
        bool $ativo,
        ?string $data_admissao = null,
        ?float $salario = 0,
        ?string $tipo_contrato = null,
        ?string $data_demissao = null,
        ?float $comissao = 0,
        CriarHistoricoRequest $criarHistoricoRequest
    ): User {

        $user = User::create([
            'name' => $name,
            'login' => $login,
            'email' => $email,
            'password' => Hash::make($password),
            'modulo_id' => $modulo_id,
            // 'permite_abrir_caixa' => $permite_abrir_caixa,
            'tipo_usuario_id' => $tipo_usuario_id,
            'empresa_id' => $empresa_id,
        ]);

        Usuario::setHistorico($criarHistoricoRequest);
        //ja cria no modulo mercado
        $usuario = Usuario::create([
            'usuario_master_cod' => $user->id,
            'loja_id' => $loja_id[0],
            'endereco_id' => $endereco_id,
            'status_id' => $status_id,
            'data_nascimento' => $data_nascimento,
            'documento' => limparCaracteres($documento),
            'telefone' => limparCaracteres($telefone),
            'celular' => limparCaracteres($celular),
            'ativo' => $ativo,
            'data_admissao' => $data_admissao,
            'salario' => $salario ?? 0,
            'tipo_contrato' => $tipo_contrato,
            'data_demissao' => $data_demissao,
            'comissao' => $comissao ?? 0,
        ]);

        $usuario->lojas()->sync($loja_id); // Aqui sim, vai funcionar corretamente

        return $user;
    }

    public static function atualizar(
        int $user_id,
        string $name,
        string $login,
        string $email,
        int $modulo_id,
        bool $permite_abrir_caixa,
        int $tipo_usuario_id,
        int $empresa_id,
        int $loja_id,
        ?int $endereco_id = null,
        int $status_id,
        ?string $data_nascimento = null,
        string $documento,
        ?string $telefone = null,
        ?string $celular = null,
        bool $ativo,
        ?string $data_admissao = null,
        ?float $salario = 0,
        ?string $tipo_contrato = null,
        ?string $data_demissao = null,
        ?float $comissao = 0,
        CriarHistoricoRequest $criarHistoricoRequest
    ): User {
        $user = User::find($user_id);
        $user->update([
            'name' => $name,
            'login' => $login,
            'email' => $email,
            'modulo_id' => $modulo_id,
            // 'permite_abrir_caixa' => $permite_abrir_caixa,
            'tipo_usuario_id' => $tipo_usuario_id,
            'empresa_id' => $empresa_id,
        ]);

        Usuario::setHistorico($criarHistoricoRequest);
        $user->usuarioMercado->update([
            'usuario_master_cod' => $user->id,
            'loja_id' => $loja_id,
            'endereco_id' => $endereco_id,
            'status_id' => $status_id,
            'data_nascimento' => $data_nascimento,
            'documento' => limparCaracteres($documento),
            'telefone' => limparCaracteres($telefone),
            'celular' => limparCaracteres($celular),
            'ativo' => $ativo,
            'data_admissao' => $data_admissao,
            'salario' => $salario ?? 0,
            'tipo_contrato' => $tipo_contrato,
            'data_demissao' => $data_demissao,
            'comissao' => $comissao ?? 0,
        ]);

        return $user;
    }

    public static function obterUsuariosPorLojaId(
        int $loja_id
    ): Collection {
        return User::with(['modulo', 'usuarioMercado.loja', 'tipoUsuario.processos.processo'])->join('mercado.usuarios as u', 'u.usuario_master_cod', '=', 'users.id')
            ->where('u.loja_id', $loja_id)
            ->select('users.*')
            ->get();
    }

    public static function obterTodosUsuarios(): Collection
    {
        return User::with(['modulo', 'empresa', 'usuarioMercado.enderecos', 'usuarioMercado.loja', 'usuarioMercado.status', 'tipoUsuario.processos.processo'])->get();
    }

    public static function obterUsuarioPorUsuarioMasterCod(
        int $usuario_master_cod
    ): User {
        return User::with(['modulo', 'empresa', 'usuarioMercado.enderecos', 'usuarioMercado.loja', 'usuarioMercado.status', 'tipoUsuario.processos.processo'])->find($usuario_master_cod);
    }
}
