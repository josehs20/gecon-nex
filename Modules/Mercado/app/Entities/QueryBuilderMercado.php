<?php

namespace Modules\Mercado\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;

class QueryBuilderMercado
{
    private $model;
    private $itensRejeitar;

    public function __construct(Model $model)
    {
        $this->model = $model->query();
        $this->itensRejeitar = [];
    }

    public function construirQueryYajra(Fluent $colunsAndValues)
    {
        $colunsAndValues = $colunsAndValues->getAttributes();

        // Verifica se existe uma pesquisa global
        $pesquisaGlobal = isset($colunsAndValues['search']) && $colunsAndValues['search'] != null && $colunsAndValues['search'] != '' ? $colunsAndValues['search'] : null;
        unset($colunsAndValues['search']);

        if ($this->itensRejeitar && count($this->itensRejeitar) > 0) {
            //remove colunas da query
            foreach ($colunsAndValues as $key => $value) {
                $coluna = $value['coluna']; // Obtém o nome da coluna
                if (in_array($coluna, $this->itensRejeitar)) {
                    unset($colunsAndValues[$key]);
                }
            }
        }
        // Se existir valor de pesquisa global, aplica em todas as colunas
        if ($pesquisaGlobal) {
            $pesquisaGlobal = formataLikeSql($pesquisaGlobal); // Formata para LIKE

            foreach ($colunsAndValues as $key => $attr) {
                $coluna = $attr['coluna']; // Obtém o nome da coluna

                if ($coluna != 'id') {
                    $relacao = explode('@', $coluna);
                    //o @ significa onde vai fazer relacao, caso for 1 é na mesma tabela

                    if (count($relacao) > 1) {
                        $coluna = array_pop($relacao);
                        $relacao = implode('.', $relacao);

                        $this->whereHas($relacao, $coluna, $pesquisaGlobal);
                    } else {

                        $this->where($coluna, $pesquisaGlobal); // Usar where nas demais condições
                    }
                }
            }
        } else {
            // Caso não haja pesquisa global, aplica os filtros nas colunas específicas
            foreach ($colunsAndValues as $key => $attr) {
                $valor = $attr['value'];
                $coluna = $attr['coluna'];

                // Ignora valores nulos ou vazios
                if ($valor === null || $valor === '') {
                    continue;
                }

                // Verifica o operador de pesquisa
                $operador = 'like'; // Valor padrão
                if (is_bool($valor)) {
                    $operador = '=';
                } else {
                    $valor = formataLikeSql($valor); // Formata para o LIKE
                }

                $relacao = explode('@', $coluna);
                //o @ significa onde vai fazer relacao, caso for 1 é na mesma tabela

                if (count($relacao) > 1) {
                    $coluna = array_pop($relacao);
                    $relacao = implode('.', $relacao);

                    $this->whereHas($relacao, $coluna, $valor, $operador);
                } else {

                    $this->where($coluna, $valor, $operador); // Usar where nas demais condições
                }
            }
        }

        return $this;
    }


    // Função para limitar os resultados
    public function limit(int $limit)
    {
        $this->model = $this->model->limit($limit);
        return $this;
    }

    public function reject(array $itensRejeitar)
    {
        $this->itensRejeitar = $itensRejeitar;
        return $this;
    }

    public function whereHas(string $relacao, mixed $coluna, $valor, $operador = 'like')
    {
        // Divide a string da relação em partes (ex.: "estoque.produto.fabricante" -> ['estoque', 'produto', 'fabricante'])
        $relacoes = explode('.', $relacao);
        // Função auxiliar para aplicar whereHas recursivamente
        $this->model = $this->applyNestedWhereHas($this->model, $relacoes, $coluna, $operador, $valor);

        return $this; // Para permitir encadeamento, se desejar
    }

    protected function applyNestedWhereHas($query, array $relacoes, $coluna, $operador, $valor)
    {
        // Pega a primeira relação do array
        $relacaoAtual = array_shift($relacoes);

        if (empty($relacoes)) {
            // Se não há mais relações, aplica o where na relação atual
            return $query->whereHas($relacaoAtual, function ($q) use ($coluna, $operador, $valor) {
                $q->where($coluna, $operador, $valor);
            });
        }

        // Se há mais relações, continua aninhando
        return $query->whereHas($relacaoAtual, function ($q) use ($relacoes, $coluna, $operador, $valor) {
            $this->applyNestedWhereHas($q, $relacoes, $coluna, $operador, $valor);
        });
    }

    public function where(string $coluna, mixed $valor, $operador = 'like')
    {
        $this->model = $this->model->where($coluna, $operador, $valor);
        return $this;
    }

    public function whereIn(string $coluna, array $valor)
    {
        $this->model = $this->model->whereIn($coluna, $valor);
        return $this;
    }

    public function orWhere(string $coluna, mixed $valor, $operador = 'like')
    {
        $this->model = $this->model->orWhere($coluna, $operador, $valor);
        return $this;
    }

    public function getQuery()
    {
        return $this->model;
    }

    public function with(string $relacao){
        $this->model = $this->model->with($relacao);
        return $this;
    }
}
