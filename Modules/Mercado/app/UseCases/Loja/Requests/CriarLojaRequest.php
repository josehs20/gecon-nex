<?php

namespace Modules\Mercado\UseCases\Loja\Requests;

use App\System\Post;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarLojaRequest extends ServiceUseCase
{
    private string $nome;
    private int $empresa_master_cod;
    private int $loja_master_cod;
    private string $cnpj;
    private int $status_id;
    private ?int $endereco_id;

    public function __construct(string $nome, int $empresa_master_cod, int $loja_master_cod, string $cnpj, int $status_id, ?int $endereco_id = null, CriarHistoricoRequest $criarHistoricoRequest)
    {
        parent::__construct($criarHistoricoRequest);
        $this->nome = $nome;
        $this->empresa_master_cod = $empresa_master_cod;
        $this->loja_master_cod = $loja_master_cod;
        $this->endereco_id = $endereco_id;
        $this->cnpj = $cnpj;
        $this->status_id = $status_id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmpresaMasterCod(): int
    {
        return $this->empresa_master_cod;
    }

    public function getLojaMasterCod(): int
    {
        return $this->loja_master_cod;
    }

    public function setEmpresaMasterCod(int $empresa_master_cod): void
    {
        $this->empresa_master_cod = $empresa_master_cod;
    }

    public function getEnderecoId(): ?int
    {
        return $this->endereco_id;
    }

    public function setEnderecoId(?int $endereco_id = null): void
    {
        $this->endereco_id = $endereco_id;
    }

    public function getCnpj(): string
    {
        return Post::so_numero($this->cnpj);
    }

    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getStatusId(): int
    {
        return $this->status_id;
    }

    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }
}
