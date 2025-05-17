<?php

namespace Jhslib\HistoricoService\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedModel extends Model
{
    protected $connection = 'historicos';

    protected static $dynamicTable;

    public static function setTableName($tableName)
    {
        self::$dynamicTable = $tableName;
    }

    public function getTable()
    {
        return self::$dynamicTable ?? parent::getTable();
    }

    public function hist()
    {
        return $this->belongsTo(Historico::class, 'historico_id');
    }
}