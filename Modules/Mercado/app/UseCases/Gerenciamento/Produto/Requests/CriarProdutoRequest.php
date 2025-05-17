<?php

namespace Modules\Mercado\UseCases\Gerenciamento\Produto\Requests;

use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Modules\Mercado\UseCases\ServiceUseCase;

class CriarProdutoRequest extends ServiceUseCase
{
    private string $nome;
    private float $preco_custo;
    private float $preco_venda;
    private int $cod_barras;
    private string $cod_aux;
    private int $unidade_medida;
    private int $classificacao_id;
    private array $lojas;
    private string $data_validade;
    private ?string $descricao;
    private ?int $fabricante_id;

    // Constructor to initialize properties
    public function __construct(
        $nome,
        $preco_custo,
        $preco_venda,
        $cod_barras,
        $cod_aux,
        $unidade_medida,
        $classificacao_id,
        $lojas,
        $data_validade,
        $fabricante_id = null,
        $descricao = null,
        CriarHistoricoRequest $historicoRequest
    ) {
        $this->nome = $nome;
        $this->preco_custo = $preco_custo;
        $this->preco_venda = $preco_venda;
        $this->cod_barras = $cod_barras;
        $this->cod_aux = $cod_aux;
        $this->unidade_medida = $unidade_medida;
        $this->classificacao_id = $classificacao_id;
        $this->lojas = $lojas;
        $this->descricao = $descricao;
        $this->fabricante_id = $fabricante_id;
        $this->data_validade = $data_validade;
        parent::__construct($historicoRequest);
    }

    public function getFabrcanteId(): ?int
    {
        return $this->fabricante_id;
    }

    public function setFabrcanteId($fabricante_id): void
    {
        $this->fabricante_id = $fabricante_id;
    }

    public function getDataValidade(): string
    {
        return $this->data_validade;
    }

    public function setDataValidade(string $data_validade): void
    {
        $this->data_validade = $data_validade;
    }

    // Getters and Setters
    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getPrecoCusto()
    {
        return converterParaCentavos($this->preco_custo);
    }

    public function setPrecoCusto($preco_custo)
    {
        $this->preco_custo = $preco_custo;
    }

    public function getPrecoVenda()
    {
        return converterParaCentavos($this->preco_venda);
    }

    public function setPrecoVenda($preco_venda)
    {
        $this->preco_venda = $preco_venda;
    }

    public function getCodBarras()
    {
        return $this->cod_barras;
    }

    public function setCodBarras($cod_barras)
    {
        $this->cod_barras = $cod_barras;
    }

    public function getCodAux()
    {
        return $this->cod_aux;
    }

    public function setCodAux($cod_aux)
    {
        $this->cod_aux = $cod_aux;
    }

    public function getUnidadeMedida()
    {
        return $this->unidade_medida;
    }

    public function setUnidadeMedida($unidade_medida)
    {
        $this->unidade_medida = $unidade_medida;
    }

    public function getClassificacaoId()
    {
        return $this->classificacao_id;
    }

    public function setClassificacaoId($classificacao_id)
    {
        $this->classificacao_id = $classificacao_id;
    }

    public function getLojas()
    {
        return $this->lojas;
    }

    public function setLojas($lojas)
    {
        $this->lojas = $lojas;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
