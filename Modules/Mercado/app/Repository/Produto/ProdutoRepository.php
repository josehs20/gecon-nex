<?php

namespace Modules\Mercado\Repository\Produto;

use Carbon\Carbon;
use Modules\Mercado\Entities\Produto;
use Modules\Mercado\UseCases\Historicos\Requests\CriarHistoricoRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdutoRepository
{
    public static array $select = [
        'produtos.*',  // Usando diretamente o nome da tabela 'produtos'
        't2.preco',
        't2.custo',
        't2.id as estoque_id',
        't2.quantidade_disponivel',
        't3.nome as loja_nome',
        't3.id as loja_id',
        't4.sigla',
        't5.nome as fabricante_nome',
        't6.descricao as classificacao',
    ];

    // Método para construir a consulta padrão
    public static function queryBase($somenteLojaLogada = true)
    {
        $query = Produto::select(self::$select)
        ->limit(100)
        ->join('estoques as t2', 'produtos.id', '=', 't2.produto_id')  // Usando 'produtos' diretamente no join
        ->join('lojas as t3', 't2.loja_id', '=', 't3.id')
        ->join('unidade_medida as t4', 'produtos.unidade_medida_id', '=', 't4.id')  // Usando 'produtos' diretamente no join
        ->join('fabricantes as t5', 'produtos.fabricante_id', '=', 't5.id')  // Usando 'produtos' diretamente no join
        ->join('classificacao_produto as t6', 'produtos.classificacao_produto_id', '=', 't6.id');  // Usando 'produtos' diretamente no join

        if ($somenteLojaLogada === true) {
            $query->where('t3.id', auth()->user()->usuarioMercado->loja_id);
        } else {
            $query->whereIn('t3.id', auth()->user()->usuarioMercado->lojas->pluck('id'));
        };

        return $query;
    }

    public static function create(
        $nome,
        $descricao = null,
        $cod_barras,
        $cod_aux,
        $unidade_medida_id,
        $classificacao_produto_id,
        $data_validade,
        $fabricante_id = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Produto {
        Produto::setHistorico($criarHistoricoRequest);
        return Produto::create([
            'nome' => $nome,
            'descricao' => $descricao,
            'cod_barras' => $cod_barras,
            'cod_aux' => $cod_aux,
            'unidade_medida_id' => $unidade_medida_id,
            'classificacao_produto_id' => $classificacao_produto_id,
            'data_validade' => $data_validade,
            'fabricante_id' => $fabricante_id,

        ]);
    }

    public static function editar(
        $id,
        $nome,
        $descricao,
        $cod_barras,
        $cod_aux,
        $unidade_medida_id,
        $classificacao_produto_id,
        $data_validade,
        $fabricante_id = null,
        CriarHistoricoRequest $criarHistoricoRequest
    ): ?Produto {
        $produto = Produto::find($id);
        Produto::setHistorico($criarHistoricoRequest);

        $produto->update([
            'nome' => $nome,
            'descricao' => $descricao,
            'cod_barras' => $cod_barras,
            'cod_aux' => $cod_aux,
            'unidade_medida_id' => $unidade_medida_id,
            'classificacao_produto_id' => $classificacao_produto_id,
            'data_validade' => $data_validade,
            'fabricante_id' => $fabricante_id,

        ]);

        return $produto;
    }

    public static function getProdutoByNomeAndCodAux($busca = '')
    {
        $query = self::queryBase();
        $tableName = $query->getQuery()->from; // Pega o nome da tabela diretamente da query

        return $query->where(function ($q) use ($busca, $tableName) {
            $q->where("{$tableName}.nome", 'like', '%' . $busca . '%')
              ->orWhere("{$tableName}.cod_aux", 'like', '%' . $busca . '%');
        })->get();
    }

    public static function getProdutoById($id)
    {
        $query = self::queryBase();

        return  $query->where('produtos.id', $id)->first();
    }

    public static function getProdutoByIds(array $ids)
    {
        self::$select[] = 't4.pode_ser_float';

        $query = self::queryBase();

        return  $query->whereIn('produtos.id', $ids)->get();
    }

    public static function getProdutosYajra($data)
    {
        $colunaMap = [
            'id' => 'produtos.id',
            'nome' => 'produtos.nome',
            'cod_aux' => 'produtos.cod_aux',
            'data_validade' => 'produtos.data_validade',
            'custo' => 't2.custo',
            'preco' => 't2.preco',
            'un' => 't4.sigla',
            'fabricante_nome' => 't5.nome',
            'classificacao' => 't6.descricao',
            'loja_nome' => 't3.nome',
        ];
        // Mapeia as colunas para adicionar o 'as' com as chaves do $colunaMap
        $selectColumns = [];
        foreach ($colunaMap as $alias => $column) {

            $selectColumns[$alias] = "{$column} as {$alias}";
        }

        self::$select = $selectColumns;
        $query = self::queryBase();

        foreach ($data as $key => $v) {
            $valor = $v['value'];
            if ($valor != null) {
                $coluna = $colunaMap[$v['coluna']];
                $query->where($coluna, 'LIKE', '%' . $valor . '%');
            }
        }

        // Adicionando uma ordenação padrão para evitar o erro
        $query->orderBy('produtos.id', 'asc');

        return $query;
    }
    public static function getProdutoByCodAux($codigo, $loja_id)
    {
        return  Produto::where('cod_aux', $codigo)->join('estoques', 'produtos.id', '=', 'estoques.produto_id')
            ->where('produtos.cod_aux', $codigo)
            ->where('estoques.loja_id', $loja_id)
            ->exists();
    }

    public static function getUltimoCodAux(int $loja_id): ?int
    {
        return Produto::join('estoques', 'produtos.id', '=', 'estoques.produto_id')
            ->where('estoques.loja_id', $loja_id)
            ->max('produtos.cod_aux'); // Retorna o maior código auxiliar para a loja
    }
}
