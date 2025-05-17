@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Unidade de medidas']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Unidades de medidas</h3>
            <p class="lead">Nesta tela você pode visualizar, cadastrar e editar as unidades de medida.</p>
        </div>
        <div>
            <a href="{{ route('cadastro.unidade_medida.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Unidade de medida
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="tabela-unidade-media" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Sigla</th>
                            <th>Porde ser fracionado</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($unidadeMedidas))
                            @foreach ($unidadeMedidas as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td>{{ $u->descricao }}</td>
                                    <td>{{ $u->sigla }}</td>
                                    <td>{{ $u->pode_ser_float == true ? 'Sim' : 'Não' }}</td>

                                    <td>
                                        <a href="{{ route('cadastro.unidade_medida.edit', ['id' => $u->id]) }}"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Sigla</th>
                            <th>Porde ser fracionado</th>
                            <th>Ação</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('home.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script>
        var getUnidadeMedidas = @json(route('yajra.service.unidade_medida.get'));
        const columns = [
            ['id', 'ID'],
            ['descricao', 'Nome'],
            ['sigla', 'Sigla'],
            ['pode_ser_float', 'Pode ser fracionado'],
            ['acao', 'Ação', false, false]
        ];

        montaDatatableYajra('tabela-unidade-media', montaColunasParaYajra(columns), getUnidadeMedidas);
    </script>
@endsection
