<?php

namespace Modules\Mercado\Entities;

use App\Models\Empresa;

class Cotacao extends ModelBase
{
    protected $table = 'cotacoes';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'status_id',
        'usuario_id',
        'descricao',
        'data_abertura',
        'data_encerramento',
    ];

    public function cot_fornecedores()
    {
        return $this->hasMany(CotacaoFornecedor::class, 'cotacao_id', 'id');
    }

    public function cot_for_itens()
    {
        return $this->hasMany(CotacaoFornecedorItem::class, 'cotacao_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function compra(){
        return $this->hasOne(Compra::class, 'cotacao_id', 'id')->where('status_id', '!=', config('config.status.cancelado'));
    }
}
