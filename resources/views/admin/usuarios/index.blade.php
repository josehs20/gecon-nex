@extends('layouts.app', ['trilhaPaginas' => [['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Lista de usuários']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Lista de usuários</h3>
            <p class="lead">Nesta tela você pode ver os usuários cadastrados no sistema GECON.</p>
        </div>
        <a href="{{ route('gecon.usuarios.create') }}" class="btn btn-success elevated">
            <i class="bi bi-plus"></i> Usuário
        </a>
    </div>

    <div class="card card-body table-responsive elevated">
        <table class="table table-bordered" id="tabela-listagem-usuarios" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Documento</th>
                    <th>Grupo</th>
                    <th>Empresa</th>
                    <th>Ativo</th>
                    <th>Modulo</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Documento</th>
                    <th>Grupo</th>
                    <th>Empresa</th>
                    <th>Ativo</th>
                    <th>Modulo</th>
                    <th>Ação</th>
                </tr>
            </tfoot>
        </table>
        <div class="card-footer">
            <a href="{{ route('cadastro.gecon.usuarios.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
    @include('admin.usuarios.show')
@endsection

@section('scripts')
    <script>
        const ROTA = @json(route('gecon.usuarios.obter'));
        montaDatatable("tabela-listagem-usuarios", ROTA);
    </script>
@endsection
