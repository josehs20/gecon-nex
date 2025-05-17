<?php

namespace Modules\Mercado\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoUsuario;
use Jhslib\HistoricoService\Traits\HistoricoServicesTrait;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;

class ProcessoUsuario extends Model
{
    use HistoricoServicesTrait;

    protected $connection = 'mercado';
    protected $table = 'processo_tipo_usuario';

    protected $fillable = [
        "processo_id",
        "tipo_usuario_id",
    ];

    public function processo()
    {
        return $this->belongsTo(Processos::class, 'processo_id');
    }

    public function tipo_usuario(){
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }

    public static function setHistorico(CriarHistoricoRequest $criarHistoricoRequest)
    {
        self::setHistoricoData(
            $criarHistoricoRequest->getAcaoId(),
            $criarHistoricoRequest->getProcessoId(),
            $criarHistoricoRequest->getUsuarioId(),
            $criarHistoricoRequest->getComentario()
        );
    }
}
