<?php

namespace Jhslib\HistoricoService\Traits;

use Illuminate\Support\Facades\Auth;
use Jhslib\HistoricoService\Models\RelatedModel;
use Jhslib\HistoricoService\Services\HistoricoServices;
use Modules\Mercado\Entities\Historico;

trait HistoricoServicesTrait
{
    protected static $historicoData = [];

    /**
     * Registra o histórico sempre que o modelo for salvo ou deletado
     */
    public static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            $service = new HistoricoServices($model->connection);
            // Se houver dados para o histórico, cria o histórico
            if (!array_key_exists('usuario_id', self::$historicoData) || self::$historicoData['usuario_id'] == null) {
                $usuario = Auth::user();

                self::$historicoData['usuario_id'] = $usuario->id ?? null;
            }

            $usuario_id = array_key_exists('usuario_id', self::$historicoData) ? self::$historicoData['usuario_id'] : null;
            $acao_id = array_key_exists('acao_id', self::$historicoData) ? self::$historicoData['acao_id'] : null;
            $processo_id = array_key_exists('processo_id', self::$historicoData) ? self::$historicoData['processo_id'] : null;
            $comentario = array_key_exists('comentario', self::$historicoData) ? self::$historicoData['comentario'] : null;
            if ($usuario_id) {
                $service->criaHistoricoTable($model->id, $model->table, $usuario_id, $acao_id, $processo_id, $comentario);
            }
        });

        static::deleted(function ($model) {
            // Cria o histórico para a exclusão
            $service = new HistoricoServices($model->connection);
            // Verifica se a model foi criada ou atualizada
            if (!array_key_exists('usuario_id', self::$historicoData) || self::$historicoData['usuario_id'] == null) {
                $usuario = Auth::user();

                self::$historicoData['usuario_id'] = $usuario->id ?? null;
            }

            $usuario_id = array_key_exists('usuario_id', self::$historicoData) ? self::$historicoData['usuario_id'] : null;
            $acao_id = array_key_exists('acao_id', self::$historicoData) ? self::$historicoData['acao_id'] : null;
            $processo_id = array_key_exists('processo_id', self::$historicoData) ? self::$historicoData['processo_id'] : null;
            $comentario = array_key_exists('comentario', self::$historicoData) ? self::$historicoData['comentario'] : null;

            $service->criaHistoricoTable($model->id, $model->table, $usuario_id, $acao_id, $processo_id, $comentario);
        });
    }

    /**
     * Método para configurar as informações do histórico a partir do controller.
     */
    public static function setHistoricoData(int $acao_id = null, int $processo_id = null, int $usuario_id = null, string $comentario = null)
    {
        self::$historicoData = [
            'acao_id' => $acao_id,
            'processo_id' => $processo_id,
            'usuario_id' => $usuario_id,
            'comentario' => $comentario,
        ];
    }

    //------------------------Consultas---------------------//

    //faz a relação com laravel de forma dinamica sem a necessidade de crair models de cada tabela
    public function histTable()
    {
        $tableName = $this->table; // Obtém o nome da tabela atual

        if (!HistoricoServices::tableExistsInCache($tableName)) {
            $service = new HistoricoServices($this->connection);
            $service->criarTabelaHist($tableName);
        }

        RelatedModel::setTableName($tableName);
        return $this->hasMany(
            RelatedModel::class,
            $tableName . '_id',
            'id'
        )->with('hist')->orderBy('id', 'desc')->where('banco', $this->connection);
    }
}
