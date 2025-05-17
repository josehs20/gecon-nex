<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class YajraQueryBuilder extends Builder
{
    private array $colunasRemovidas = [];
    private array $allColuns = [];

    public function __construct(Builder $query)
    {
        parent::__construct($query->getQuery());
        $this->setModel($query->getModel());
    }

    public function whereYQB($colunsAndValues = [], ?callable $callback = null)
    {
        $this->setColumns($colunsAndValues);
        $pesquisaGlobal = $colunsAndValues['search'] ?? null;
        unset($colunsAndValues['search']);

        foreach ($colunsAndValues as $attr) {
            $coluna = $attr['coluna'] ?? null;
            $valor = $attr['value'] ?? null;

            // Se a coluna está na lista de rejeitadas, ignora
            if (!$this->validateColumn($coluna, $valor)) {
                continue;
            }

            if ($callback) {
                $callback($this, $coluna, $valor);
            }
            // Identificar se é uma relação (possui ".")
            if (str_contains($coluna, '.')) {
                $this->contructWhereHas($attr);
                continue;
            }

            // Pesquisa global, se existir
            if ($pesquisaGlobal) {
                $this->where($coluna, 'like', formataLikeSql($pesquisaGlobal));
                continue;
            }

            // Operador padrão para pesquisa
            $operador = 'like';
            $valor = formataLikeSql($valor);
            $this->where($coluna, $operador, $valor);
        }

        return $this;
    }

    private function validateColumn($coluna, $valor): bool
    {
        if (!$coluna || $valor === null || $valor === '') {
            return false;
        }

        if (in_array(strtolower($coluna), array_map('strtolower', $this->colunasRemovidas), true)) {
            return false;
        }

        return true;
    }

    public function whereDateYQB(array $arrayDeColunasQueSaoDatas)
    {
        foreach ($this->allColuns as $key => $colunaAndValue) {
            $coluna = $colunaAndValue['coluna'];
            $data = $colunaAndValue['value'];

            if (in_array($coluna, $arrayDeColunasQueSaoDatas) && !empty($data)) {
                $data = formataLikeSql(formatarDataPadraoBancoDeDados($data));
                if (str_contains($coluna, '.')) {
                    $colunaAndValue['value'] = formatarDataPadraoBancoDeDados($colunaAndValue['value']);
                    $this->contructWhereHas($colunaAndValue);
                    continue;
                } else {
                    $this->where($coluna, 'like', $data);
                }
            }
        }

        return $this;
    }

    public function reject(array $colunasRemover)
    {
        $this->colunasRemovidas = $colunasRemover;
        return $this;
    }

    private function contructWhereHas($colunaAndValue)
    {
        $coluna = $colunaAndValue['coluna'] ?? null;
        $valor = $colunaAndValue['value'] ?? null;

        $parts = explode('.', $coluna);
        $relation = implode('.', array_slice($parts, 0, -1)); //junta tadas posições menos a última, formando a relação por '.'
        $field = end($parts);

        // Aplicar whereHas na relação
        $this->whereHas($relation, function ($query) use ($field, $valor) {
            $query->where($field, 'like', formataLikeSql($valor));
        });
    }

    public function withColumnsYQB($colunsAndValues)
    {
        $this->with(
            collect($colunsAndValues)->pluck('coluna')
                ->filter(function ($col) {
                    return str_contains($col, '.'); // Filtra apenas os campos que são relações
                })
                ->map(function ($col) {
                    return implode('.', array_slice(explode('.', $col), 0, -1)); // Remove o último nível (campo)
                })
                ->unique() // Remove relações duplicadas
                ->values()
                ->toArray()
        );


        return $this;
    }

    public function specifyColumnYQB(string $coluna, callable $callback)
    {
        $valor = collect($this->allColuns)->firstWhere('coluna', $coluna)['value'];

        if (!empty($valor)) {
           $resultado = $callback($this, $valor);
            return $resultado;
        }
        return $this;
    }

    public function setColumns($colunsAndValues)
    {
        $this->allColuns = $colunsAndValues;
    }

    public function constructColumns(callable $callback)
    {
        $dataTables = DataTables::of($this);
        $dataTables = $callback($dataTables);
        return $dataTables->rawColumns(collect($this->allColuns)->pluck('coluna')->push('acao')->toArray()) // assume que todas com tooltip têm HTML
            ->make(true);
    }

    /**
     * Espera receber um array com pas valore EX: [['coluna', 'titulo'],...]
     */
    public static function constructColumnsView(array $colunsAndValues)
    {
        return array_map(function ($item) {

            return ['data' => $item[0], 'titulo' => $item[1]];
        }, $colunsAndValues);
    }
}
