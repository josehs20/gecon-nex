<?php

namespace Modules\Mercado\Entities;

class Caixa extends ModelBase
{
    protected $table = 'caixas';
    protected $connection = 'mercado';
    protected $fillable = [
        'loja_id',
        'nome',
        'status_id',
        'usuario_id',
        'ativo',
        'token',
    ];

    public function historico()
    {
        return $this->histTable();
    }
    // Relacionamentos
    public function loja()
    {
        return $this->belongsTo(Loja::class, 'loja_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'caixa_recursos', 'caixa_id', 'recurso_id')
            // ->withPivot('nome', 'descricao') // Aqui você acessa os campos extras da tabela pivot
            ->withTimestamps();
    }

    public function permissoes()
    {
        return $this->hasMany(CaixaPermissao::class, 'caixa_id');
    }

    public function diario_atual()
    {
        return $this->hasOne(CaixaDiario::class, 'caixa_id', 'id')->latest('id');
    }

    public function ultima_evidencia()
    {
        return $this->hasOne(CaixaEvidencia::class, 'caixa_id', 'id')->latest('id');
    }
}
