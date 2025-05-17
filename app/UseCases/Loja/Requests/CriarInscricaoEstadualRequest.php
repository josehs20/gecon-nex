<?php

namespace App\UseCases\Loja\Requests;

class CriarInscricaoEstadualRequest
{
    private $loja_id;
    private $nfeio_loja_id;
    private $state_tax_id;
    private $account_id;
    private $company_id;
    private $code;
    private $special_tax_regime;
    private $type;
    private $tax_number;
    private $status;
    private $serie;
    private $number;
    private $processing_details;
    private $security_credential;

    // Construtor
    public function __construct(
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
        $this->loja_id = $loja_id;
        $this->nfeio_loja_id = $nfeio_loja_id;
        $this->state_tax_id = $state_tax_id;
        $this->account_id = $account_id;
        $this->company_id = $company_id;
        $this->code = $code;
        $this->special_tax_regime = $special_tax_regime;
        $this->type = $type;
        $this->tax_number = $tax_number;
        $this->status = $status;
        $this->serie = $serie;
        $this->number = $number;
        $this->processing_details = $processing_details;
        $this->security_credential = $security_credential;
    }

    // Métodos Getters e Setters
    public function getSecurityCredential()
    {
        return $this->security_credential;
    }

    public function setSecurityCredential($security_credential)
    {
        $this->security_credential = $security_credential;
    }

    public function getLojaId()
    {
        return $this->loja_id;
    }

    public function setLojaId($loja_id)
    {
        $this->loja_id = $loja_id;
    }

    public function getNfeioLojaId()
    {
        return $this->nfeio_loja_id;
    }

    public function setNfeioLojaId($nfeio_loja_id)
    {
        $this->nfeio_loja_id = $nfeio_loja_id;
    }

    public function getStateTaxId()
    {
        return $this->state_tax_id;
    }

    public function setStateTaxId($state_tax_id)
    {
        $this->state_tax_id = $state_tax_id;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function setAccountId($account_id)
    {
        $this->account_id = $account_id;
    }

    public function getCompanyId()
    {
        return $this->company_id;
    }

    public function setCompanyId($company_id)
    {
        $this->company_id = $company_id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getSpecialTaxRegime()
    {
        return $this->special_tax_regime;
    }

    public function setSpecialTaxRegime($special_tax_regime)
    {
        $this->special_tax_regime = $special_tax_regime;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getTaxNumber()
    {
        return $this->tax_number;
    }

    public function setTaxNumber($tax_number)
    {
        $this->tax_number = $tax_number;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getProcessingDetails()
    {
        return $this->processing_details;
    }

    public function setProcessingDetails($processing_details)
    {
        $this->processing_details = $processing_details;
    }
}
