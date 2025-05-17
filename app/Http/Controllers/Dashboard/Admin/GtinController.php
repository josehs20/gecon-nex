<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\ControllerBase;
use App\System\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Mercado\Entities\Gtin;
use Yajra\DataTables\Facades\DataTables;

class GtinController extends ControllerBase
{
    public function index()
    {
        return view('admin.gtin.index');
    }

    public function get_yajra(Request $request)
    {
        $parans = Post::anti_injection_array($request->all());

        //pega somente o valor e coluna do yajra
        $parans = array_map(function ($column) {
            return [
                'value' => $column['search']['value'],
                'coluna' => $column['data'],
            ];
        }, $parans['columns']);
        $query = Gtin::select(['gtins.*'])->limit(100);

        foreach ($parans as $key => $param) {
            $valor = $param['value'];
            if ($valor !== null && $valor !== '') {

                if ($param['coluna'] === 'ultima_verificacao') {
                    // Converte a data do formato brasileiro (DD/MM/YYYY) para americano (YYYY-MM-DD)
                    $dataConvertida = $this->converterDataBrasileiraParaAmericana($valor);
                    if ($dataConvertida) {
                        $query->where($param['coluna'], 'LIKE', '%' . $dataConvertida . '%');
                    } else {
                        // Se a conversão falhar, usa o valor original (para casos parciais)
                        $query->where($param['coluna'], 'LIKE', '%' . $valor . '%');
                    }
                } elseif ($param['coluna'] === 'prioridade') {
                    // Quando a coluna for "prioridade", verificamos o valor de "ncm"
                    if (stripos($valor, 'al') !== false || preg_match('/(\b\w+\b)(?:\s+\1){3,}/i', $valor) || preg_match('/(\d)\1{3,}/', $valor)) {
                        // Prioridade Alta:
                        // 1. ncm é null
                        // 2. ou contém sequência de 4 ou mais palavras consecutivas iguais
                        // 3. ou contém sequência de 4 ou mais números consecutivos iguais
                        $query->where(function ($query) {
                            $query->whereNull('ncm')
                                ->orWhere('ncm', 'REGEXP', '[a-zA-Z]')
                                ->orWhere('ncm', 'REGEXP', '(\\d)\\1{3,}');
                        })->whereNull('ultima_verificacao');
                    } elseif (stripos($valor, 'm') !== false || preg_match('/(\b\w+\b)(?:\s+\1){3,}/i', $valor) || preg_match('/(\d)\1{3,}/', $valor)) {
                        // Prioridade Normal:
                        // 1. ncm não é null nem vazio
                        // 2. tem pelo menos 6 dígitos
                        // 3. não contém letras
                        // 4. não contém sequências de números iguais
                        $query->where(function ($query) {
                            $query->whereNotNull('ncm')
                                ->where('ncm', '!=', '')
                                ->whereRaw('LENGTH(REGEXP_REPLACE(ncm, "[^0-9]", "")) >= 6') // Pelo menos 6 dígitos
                                ->where('ncm', 'NOT REGEXP', '[a-zA-Z]') // Sem letras
                                ->where('ncm', 'NOT REGEXP', '(\\d)\\1{3,}'); // Sem sequência de 4 ou mais números iguais
                        });
                    } else {
                        $query->whereNull('ultima_verificacao')->orWhereNotNull('whereNull');
                    }
                } else {
                    $query->where($param['coluna'], 'LIKE', '%' . $valor . '%');
                }
            }
        }

        $query->orderByRaw('ncm IS NULL DESC, ncm ASC');  // Primeiro os nulos, depois os outros

        return  DataTables::of($query)
            ->addColumn('id', function ($gtin) {
                return $gtin->id;
            })
            ->addColumn('nome', function ($gtin) {
                return $gtin->gtin;
            })
            ->addColumn('descricao', function ($gtin) {
                return $gtin->descricao;
            })
            ->addColumn('ncm', function ($gtin) {
                return $gtin->ncm; // Formatar custo
            })
            ->addColumn('ultima_verificacao', function ($gtin) {
                return $gtin->ultima_verificacao
                    ? Carbon::parse($gtin->ultima_verificacao)->format('d/m/Y H:i:s')  // Formatação de data e hora
                    : 'Não verificado';
            })->addColumn('prioridade', function ($gtin) {
                if ($gtin->ncm === null || preg_match('/[a-zA-Z]/', $gtin->ncm) || preg_match('/(\d)\1{3,}/', $gtin->ncm)) {
                    // Prioridade Alta com ícone de alerta e cor vermelha
                    return '<span class="badge badge-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> Alta
                            </span>';
                } elseif (
                    $gtin->ncm !== null &&
                    !preg_match('/[a-zA-Z]/', $gtin->ncm) &&
                    !preg_match('/(\d)\1{3,}/', $gtin->ncm) &&
                    (is_null($gtin->ultima_atualizacao) || $gtin->ultima_atualizacao === false)
                ) {
                    // Prioridade Média com aviso, somente números, não sequencial, ultima_atualizacao falsa
                    return '<span class="badge badge-warning">
                                <i class="bi bi-info-circle"></i> Média
                            </span>';
                } else {
                    // Prioridade Normal padrão
                    return '<span class="badge badge-success">Normal</span>';
                }
            })->addColumn('acao', function ($gtin) {
                // Gerando o HTML do botão de edição
                $buttonEditar = '<a type="button" data-id="' . $gtin->id . '" class="btn btn-warning">
                     <i class="bi bi-pencil"></i>
                 </a>';

                $buttonExcluir = '<a type="button" data-id="' . $gtin->id . '" class="btn btn-danger mx-1">
                     <i class="bi bi-trash"></i>
                 </a>';
                return $buttonEditar . $buttonExcluir;
            })
            ->rawColumns(['acao', 'prioridade'])  // Informa ao DataTables que a coluna acao contém HTML
            ->make(true);
    }


    // busca gtin pelo id
    public function show(Request $request)
    {
        return response()->json(Gtin::find(Post::anti_injection($request->id)), 200);
    }

    public function post(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $comprimento = $parans->comprimento ? number_format(str_replace(',', '.', $parans->comprimento), 2, '.', '') : null;
            $altura = $parans->altura ? number_format(str_replace(',', '.', $parans->altura), 2, '.', '') : null;
            $largura = $parans->largura ? number_format(str_replace(',', '.', $parans->largura), 2, '.', '') : null;
            $pesoBruto = $parans->peso_bruto ? number_format(str_replace(',', '.', $parans->peso_bruto), 3, '.', '') : null;
            $pesoLiquido = $parans->peso_liquido ? number_format(str_replace(',', '.', $parans->peso_liquido), 3, '.', '') : null;

            // Verifica se o GTIN já existe (ignora soft-deleted)
            $gtinExistente = Gtin::withTrashed()->where('gtin', $parans->gtin)->first();

            if ($gtinExistente) {
                // Se o GTIN já existir e estiver soft-deletado, restaura ele e atualiza os dados
                if ($gtinExistente->trashed()) {
                    $gtinExistente->restore(); // Restaura o registro soft-deletado
                }

                // Atualiza os dados do GTIN existente com os dados fornecidos
                $gtinExistente->update([
                    'descricao' => $parans->descricao,
                    'tipo' => $parans->tipo,
                    'quantidade' => $parans->quantidade,
                    'comprimento' => $comprimento,
                    'altura' => $altura,
                    'largura' => $largura,
                    'peso_bruto' => $pesoBruto,
                    'peso_liquido' => $pesoLiquido,
                    'ultima_verificacao' => now(),
                    'ncm' => $parans->ncm,
                ]);

                $this->getDb()->commit();
                return response()->json(['success' => true, 'msg' => 'Gtin restaurado e atualizado com sucesso!']);
            }

            // Caso o GTIN não exista, cria um novo registro
            $gtin = Gtin::create([
                'gtin' => $parans->gtin,
                'descricao' => $parans->descricao,
                'tipo' => $parans->tipo,
                'quantidade' => $parans->quantidade,
                'comprimento' => $comprimento,
                'altura' => $altura,
                'largura' => $largura,
                'peso_bruto' => $pesoBruto,
                'peso_liquido' => $pesoLiquido,
                'ultima_verificacao' => now(),
                'ncm' => $parans->ncm,
            ]);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Gtin criado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    // busca gtin pelo id
    public function update(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $gtin = Gtin::where('gtin', $parans->gtin)->first();
            $gtin->update([
                'gtin' => $parans->gtin,
                'descricao' => $parans->descricao,
                'tipo' => $parans->tipo,
                'quantidade' => $parans->quantidade,
                'comprimento' => $parans->comprimento,
                'altura' => $parans->altura,
                'largura' => $parans->largura,
                'peso_bruto' => $parans->peso_bruto,
                'peso_liquido' => $parans->peso_liquido,
                'ultima_verificacao' => now(),
                'ncm' => $parans->ncm,
            ]);

            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Gtin atualizado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    // busca gtin pelo id
    public function delete(Request $request)
    {
        $this->getDb()->begin();
        try {
            $parans = (object) Post::anti_injection_array($request->all());
            $gtin = Gtin::find($parans->id)->delete();


            $this->getDb()->commit();
            return response()->json(['success' => true, 'msg' => 'Gtin deletado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    // Método auxiliar para converter a data
    private function converterDataBrasileiraParaAmericana($dataBr)
    {
        // Verifica se a string parece uma data completa no formato DD/MM/YYYY
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dataBr, $matches)) {
            $dia = $matches[1];
            $mes = $matches[2];
            $ano = $matches[3];

            // Valida a data
            if (checkdate($mes, $dia, $ano)) {
                return "$ano-$mes-$dia"; // Retorna no formato YYYY-MM-DD
            }
        }

        // Se não for uma data completa ou válida, retorna null para usar o valor original
        return null;
    }
}
