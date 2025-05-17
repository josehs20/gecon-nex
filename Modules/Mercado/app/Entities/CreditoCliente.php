<?php

namespace Modules\Mercado\Entities;


class CreditoCliente extends ModelBase
{
    protected $table = 'credito_cliente'; // Nome da tabela associada
    protected $connection = 'mercado'; // Conexão com o banco 'mercado'
    protected $fillable = [
        'cliente_id',
        'credito_loja',
        'credito_ave',
        'credito_loja_usado',
    ]; // Colunas que podem ser preenchidas em massa

    /**
     * Relacionamento com o cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

}
