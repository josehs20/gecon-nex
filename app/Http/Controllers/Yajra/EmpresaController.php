<?php

namespace App\Http\Controllers\Yajra;

use App\Helpers\YajraQueryBuilder;
use App\Http\Controllers\ControllerBase;
use App\Models\Empresa;
use App\Models\Loja;
use App\Models\QueryBuilder;
use App\System\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmpresaController extends ControllerBase
{
    public function getEmpresas(Request $request)
    {
        $parans = Post::anti_injection_yajra($request->all());

        $query = new YajraQueryBuilder(Empresa::query());
        return $query->limit(100)
        ->reject(['ativo'])
        ->whereYQB($parans->getAttributes())
            ->specifyColumnYQB('ativo', function ($q, $valor) {
                return $q->where('ativo', !str_contains($valor, 'in'));
            })
            ->constructColumns(function ($datatables) {
                return $datatables
                    ->addColumn('id', function ($empresa) {
                        return $empresa->id;
                    })
                    ->addColumn('nome', function ($empresa) {
                        return strtoupper($empresa->nome_fantasia); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('cnpj', function ($empresa) {
                        return $empresa->cnpj;
                    })
                    ->addColumn('ativo', function ($empresa) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $empresa->ativo ? 'Ativo' : 'Inativo';
                        $badgeClass = $empresa->ativo ? 'badge bg-primary' : 'badge bg-danger';  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('acao', function ($empresa) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('admin.empresa.edit', ['empresa' => $empresa->id]) . '" class="btn btn-warning">
                <i class="bi bi-pencil"></i>
            </a>';
                    });
            });
    }

    public function getLojas(Request $request, $empresa_id)
    {
        $parans = Post::anti_injection_yajra($request->all());

        // Criando o objeto de consulta
        $query = new YajraQueryBuilder(Loja::query());

        // Aplica a condição empresa_id primeiro
        return $query->where('empresa_id', $empresa_id)->limit(100)
            ->whereYQB($parans->getAttributes())
            ->constructColumns(function ($datatables) {
                return  $datatables->addColumn('id', function ($loja) {
                    return $loja->id;
                })
                    ->addColumn('nome', function ($loja) {
                        return strtoupper($loja->nome); // Exemplo: Nome em maiúsculas
                    })
                    ->addColumn('cnpj', function ($loja) {
                        return $loja->cnpj;
                    })
                    ->addColumn('status.descricao', function ($loja) {
                        // Verificar se a empresa está ativa ou inativa e retornar o HTML com o badge
                        $status = $loja->status->descricao();
                        $badgeClass = $loja->status->badge();  // 'bg-primary' para azul, 'bg-danger' para vermelho
                        return "<span class='$badgeClass'>$status</span>";
                    })
                    ->addColumn('loja.nfeio', function ($loja) {
                        return $loja->nfeio ? 'Sim' : 'Não';
                    })
                    ->addColumn('acao', function ($loja) {
                        // Gerando o HTML do botão de edição
                        return '<a href="' . route('admin.loja.edit', ['loja_id' => $loja->id]) . '" class="btn btn-warning">
        <i class="bi bi-pencil"></i>
    </a>';
                    });
            });
    }

    public function obterLojasPorEmpresaParaSelect(int $empresa_id){
        $lojas = Loja::where('empresa_id', $empresa_id)->get(['id', 'nome']);
        return response()->json($lojas);
    }
}
