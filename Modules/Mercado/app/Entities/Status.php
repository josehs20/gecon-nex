<?php

namespace Modules\Mercado\Entities;

class Status extends ModelBase
{
    protected $table = 'status';
    protected $connection = 'mercado';
    protected $fillable = ['descricao'];
    // Sempre que a model for convertida em array/json, incluir o campo formatado
    protected $appends = ['descricao_formatada', 'badge'];

    // Accessor que será usado automaticamente como campo adicional
    public function getDescricaoFormatadaAttribute()
    {
        return $this->descricao();
    }

    public function getBadgeAttribute()
    {
        return $this->badge();
    }

    public function loja()
    {
        return $this->belongsTo(Loja::class);
    }

    public function descricao()
    {
        switch ($this->id) {
            case config('config.status.ativo'):
                return 'Ativo';
            case config('config.status.aberto'):
                return 'Aberto';
            case config('config.status.ocupado'):
                return 'Ocupado';
            case config('config.status.fechado'):
                return 'Fechado';
            case config('config.status.em_dia'):
                return 'Em Dia';
            case config('config.status.em_atraso'):
                return 'Em Atraso';
            case config('config.status.quitado'):
                return 'Quitado';
            case config('config.status.bloqueado'):
                return 'Bloqueado';
            case config('config.status.livre'):
                return 'Livre';
            case config('config.status.salvo'):
                return 'Salvo';
            case config('config.status.cancelado'):
                return 'Cancelado';
            case config('config.status.concluido'):
                return 'Concluído';
            case config('config.status.parcelada'):
                return 'Parcelada';
            case config('config.status.devolucao'):
                return 'Devolução';
            case config('config.status.pago'):
                return 'Pago';
            case config('config.status.pendente'):
                return 'Pendente';
            case config('config.status.devolucao_parcial'):
                return 'Devolução Parcial';
            case config('config.status.recebimento_iniciado'):
                return 'Recebimento Iniciado';
            case config('config.status.aguardando_cotacao'):
                return 'Aguardando cotação';
            case config('config.status.em_cotacao'):
                return 'Em cotação';
            case config('config.status.cotado'):
                return 'Cotado';
            case config('config.status.comprado'):
                return 'Comprado';
            default:
                return 'Desconhecido'; // Caso não encontre um status válido
        }
    }

    public function badge()
    {
        switch ($this->id) {
            case config('config.status.ativo'):
                return 'badge badge-success';
            case config('config.status.aberto'):
                return 'badge badge-secondary';
            case config('config.status.ocupado'):
                return 'badge badge-warning';
            case config('config.status.fechado'):
                return 'badge badge-danger';
            case config('config.status.em_dia'):
                return 'badge badge-success';
            case config('config.status.em_atraso'):
                return 'badge badge-warning';
            case config('config.status.quitado'):
                return 'badge badge-success';
            case config('config.status.bloqueado'):
                return 'badge badge-dark';
            case config('config.status.livre'):
                return 'badge badge-info';
            case config('config.status.salvo'):
                return 'badge badge-secondary';
            case config('config.status.cancelado'):
                return 'badge badge-danger';
            case config('config.status.concluido'):
                return 'badge badge-success';
            case config('config.status.parcelada'):
                return 'badge badge-info';
            case config('config.status.devolucao'):
                return 'badge badge-danger';
            case config('config.status.pago'):
                return 'badge badge-success';
            case config('config.status.pendente'):
                return 'badge badge-warning';
            case config('config.status.devolucao_parcial'):
                return 'badge badge-danger';
            case config('config.status.recebimento_iniciado'):
                return 'badge badge-primary';
            case config('config.status.aguardando_cotacao'):
                return 'badge badge-warning';
            case config('config.status.em_cotacao'):
                return 'badge badge-info';
            case config('config.status.cotado'):
                return 'badge badge-primary';
            case config('config.status.comprado'):
                return 'badge badge-success';
            default:
                return 'badge badge-light'; // Caso não encontre um status válido
        }
    }
}
