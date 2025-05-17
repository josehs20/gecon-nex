<?php

namespace App\UseCases\Usuario\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class UsuarioRequest extends ServiceUseCase
{
    // User
    private string $name;
    private string $login;
    private string $email;
    private string $password;
    private int $modulo_id;
    private bool $permite_abrir_caixa;
    private int $tipo_usuario_id;
    private int $empresa_id;
    private array $loja_id;

    // Usuario
    private ?int $endereco_id;
    private int $status_id;
    private ?string $data_nascimento;
    private string $documento;
    private ?string $telefone;
    private ?string $celular;
    private bool $ativo;
    private ?string $data_admissao;
    private ?float $salario;
    private ?string $tipo_contrato;
    private ?string $data_demissao;
    private ?float $comissao;

    private ?int $user_id;

    public function __construct(
        string $name,
        string $login,
        string $email,
        string $password,
        int $modulo_id,
        bool $permite_abrir_caixa,
        int $tipo_usuario_id,
        int $empresa_id,
        array $loja_id,
        ?int $endereco_id,
        int $status_id,
        ?string $data_nascimento,
        string $documento,
        ?string $telefone,
        ?string $celular,
        bool $ativo,
        ?string $data_admissao,
        ?float $salario,
        ?string $tipo_contrato,
        ?string $data_demissao,
        ?float $comissao,
        CriarHistoricoRequest $criarHistoricoRequest,
        ?int $user_id = null
    ) {

        parent::__construct($criarHistoricoRequest);
        $this->setName($name);
        $this->setLogin($login);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setModuloId($modulo_id);
        $this->setPermiteAbrirCaixa($permite_abrir_caixa);
        $this->setTipoUsuarioId($tipo_usuario_id);
        $this->setEmpresaId($empresa_id);
        $this->setLojaId($loja_id);
        $this->setEnderecoId($endereco_id);
        $this->setStatusId($status_id);
        $this->setDataNascimento($data_nascimento);
        $this->setDocumento($documento);
        $this->setTelefone($telefone);
        $this->setCelular($celular);
        $this->setAtivo($ativo);
        $this->setDataAdmissao($data_admissao);
        $this->setSalario($salario);
        $this->setTipoContrato($tipo_contrato);
        $this->setDataDemissao($data_demissao);
        $this->setComissao($comissao);
        $this->setUserId($user_id);
    }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getLogin(): string { return $this->login; }
    public function setLogin(string $login): void { $this->login = $login; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getModuloId(): int { return $this->modulo_id; }
    public function setModuloId(int $modulo_id): void { $this->modulo_id = $modulo_id; }

    public function getPermiteAbrirCaixa(): bool { return $this->permite_abrir_caixa; }
    public function setPermiteAbrirCaixa(bool $permite_abrir_caixa): void { $this->permite_abrir_caixa = $permite_abrir_caixa; }

    public function getTipoUsuarioId(): int { return $this->tipo_usuario_id; }
    public function setTipoUsuarioId(int $tipo_usuario_id): void { $this->tipo_usuario_id = $tipo_usuario_id; }

    public function getEmpresaId(): int { return $this->empresa_id; }
    public function setEmpresaId(int $empresa_id): void { $this->empresa_id = $empresa_id; }

    public function getLojaId(): array { return $this->loja_id; }
    public function setLojaId(array $loja_id): void { $this->loja_id = $loja_id; }

    public function getEnderecoId(): ?int { return $this->endereco_id; }
    public function setEnderecoId(?int $endereco_id): void { $this->endereco_id = $endereco_id; }

    public function getStatusId(): int { return $this->status_id; }
    public function setStatusId(int $status_id): void { $this->status_id = $status_id; }

    public function getDataNascimento(): ?string { return $this->data_nascimento; }
    public function setDataNascimento(?string $data_nascimento): void { $this->data_nascimento = $data_nascimento; }

    public function getDocumento(): string { return $this->documento; }
    public function setDocumento(string $documento): void { $this->documento = $documento; }

    public function getTelefone(): ?string { return $this->telefone; }
    public function setTelefone(?string $telefone): void { $this->telefone = $telefone; }

    public function getCelular(): ?string { return $this->celular; }
    public function setCelular(?string $celular): void { $this->celular = $celular; }

    public function isAtivo(): bool { return $this->ativo; }
    public function setAtivo(bool $ativo): void { $this->ativo = $ativo; }

    public function getDataAdmissao(): ?string { return $this->data_admissao; }
    public function setDataAdmissao(?string $data_admissao): void { $this->data_admissao = $data_admissao; }

    public function getSalario(): ?float { return $this->salario; }
    public function setSalario(?float $salario): void { $this->salario = $salario; }

    public function getTipoContrato(): ?string { return $this->tipo_contrato; }
    public function setTipoContrato(?string $tipo_contrato): void { $this->tipo_contrato = $tipo_contrato; }

    public function getDataDemissao(): ?string { return $this->data_demissao; }
    public function setDataDemissao(?string $data_demissao): void { $this->data_demissao = $data_demissao; }

    public function getComissao(): ?float { return $this->comissao; }
    public function setComissao(?float $comissao): void { $this->comissao = $comissao; }

    public function getUserId(): ?int { return $this->user_id; }
    public function setUserId(?int $user_id): void { $this->user_id = $user_id; }
}
