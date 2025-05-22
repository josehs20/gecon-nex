<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    protected $connection = 'gecon';

    protected $fillable = [
        'descricao'
    ];

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
                return 'badge badge-warning';
            case config('config.status.ocupado'):
                return 'badge badge-danger';
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
                return 'badge badge-info';
            default:
                return 'badge badge-light'; // Caso não encontre um status válido
        }
    }
}
