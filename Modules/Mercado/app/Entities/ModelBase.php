<?php

namespace Modules\Mercado\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jhslib\HistoricoService\Traits\HistoricoServicesTrait;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ModelBase extends Model
{
    use HistoricoServicesTrait;
    use SoftDeletes; // Adiciona o SoftDeletes para permitir soft deletes

    public static function setHistorico(CriarHistoricoRequest $criarHistoricoRequest)
    {
        self::setHistoricoData(
            $criarHistoricoRequest->getAcaoId(),
            $criarHistoricoRequest->getProcessoId(),
            $criarHistoricoRequest->getUsuarioId(),
            $criarHistoricoRequest->getComentario()
        );
    }

    public function setAudit(CriarHistoricoRequest $criarHistoricoRequest)
    {
        // Verifica se existe um registro de auditoria. Se não, cria um novo.
        if ($this->audits && $this->audits->isNotEmpty()) {
            // Atualiza o último registro de auditoria
            $this->audits->last()->update([
                'processo_id' => $criarHistoricoRequest->getProcessoId(),
                'acao_id' => $criarHistoricoRequest->getAcaoId(),
                'comentario' => $criarHistoricoRequest->getComentario(),
            ]);
        } else {
            // Cria um novo registro de auditoria se não houver nenhum
            $user = auth()->user();
            $this->audits()->create([
                'processo_id' => $criarHistoricoRequest->getProcessoId(),
                'acao_id' => $criarHistoricoRequest->getAcaoId(),
                'comentario' => $criarHistoricoRequest->getComentario(),
                'auditable_id' => $this->id, // ID do registro auditado
                'auditable_type' => get_class($this), // Tipo do modelo
                'event' => 'created', // Define o evento como 'created'
                'user_id' => $user ? $user->id : null, // ID do usuário
                'user_type' => $user ? get_class($user) : null, // Tipo do usuário
                'ip_address' => request()->ip(), // IP do usuário
                'user_agent' => request()->header('User-Agent'), // User agent
                'old_values' => $this->getOriginal(), // Valores antigos
                'new_values' => $this->getAttributes(), // Valores novos
            ]);
        }
        return $this;
    }


}
