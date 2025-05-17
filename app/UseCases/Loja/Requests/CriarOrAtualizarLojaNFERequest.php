<?php

namespace App\UseCases\Loja\Requests;

class CriarOrAtualizarLojaNFERequest
{
    private $empresa_id;
    private $loja_id;
    private $nfeio_id;
    private $account_id;
    private $name;
    private $trade_name;
    private $federal_tax_number;
    private $tax_regime;
    private $status;
    private $address;

    // Construtor
    public function __construct(
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
        $this->empresa_id = $empresa_id;
        $this->loja_id = $loja_id;
        $this->nfeio_id = $nfeio_id;
        $this->account_id = $account_id;
        $this->name = $name;
        $this->trade_name = $trade_name;
        $this->federal_tax_number = $federal_tax_number;
        $this->tax_regime = $tax_regime;
        $this->status = $status;
        $this->address = $address;

    }

    // Métodos GET
    public function getAddress()
    {
        return $this->address;
    }

    public function getEmpresaId()
    {
        return $this->empresa_id;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function getNfeioId()
    {
        return $this->nfeio_id;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTradeName()
    {
        return $this->trade_name;
    }

    public function getFederalTaxNumber()
    {
        return $this->federal_tax_number;
    }

    public function getTaxRegime()
    {
        return $this->tax_regime;
    }

    public function getStatus()
    {
        return $this->status;
    }

    // Métodos SET
    public function setEmpresaId($empresa_id)
    {
        $this->empresa_id = $empresa_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function setNfeioId($nfeio_id)
    {
        $this->nfeio_id = $nfeio_id;
    }

    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTradeName($trade_name)
    {
        $this->trade_name = $trade_name;
    }

    public function setFederalTaxNumber($federal_tax_number)
    {
        $this->federal_tax_number = $federal_tax_number;
    }

    public function setTaxRegime($tax_regime)
    {
        $this->tax_regime = $tax_regime;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
