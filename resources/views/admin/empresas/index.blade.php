@extends('layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Lista de empresas']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Lista de empresas</h3>
            <p class="lead">Nesta tela você pode ver as empresas cadastradas no sistema Gecon.</p>
        </div>
        <a href="{{ route('admin.empresa.create') }}" class="btn btn-success elevated">
            <i class="bi bi-plus"></i> Empresa
        </a>
    </div>

    <div class="card card-body table-responsive elevated">
        <table class="table table-bordered" id="tabela-home-admin" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Ativo</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('admin.empresa.index') }}" class="btn btn-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const routeGetEmpresas = @json(route('yajra.service.empresas.get'));
        const columns = @json($columns);
        montaDatatableYajra("tabela-home-admin", columns, routeGetEmpresas);
    </script>
@endsection
