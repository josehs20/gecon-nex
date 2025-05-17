@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['titulo' => 'Lista de usuários']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Lista de usuários</h3>
            <p class="lead">Nesta tela você pode ver os usuários cadastrados.</p>
        </div>
        <a href="{{ route('gecon.usuarios.create') }}" class="btn btn-success elevated">
            <i class="bi bi-plus"></i> Usuário
        </a>
    </div>

    <div class="card card-body table-responsive elevated">
        <table class="table table-bordered" id="tabela-listagem-usuarios-mercado" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Documento</th>
                    <th>Grupo</th>
                    <th>Ativo</th>
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
                    <th>Ativo</th>
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
    <script>
        const ROTA = @json(route('gecon.usuarios.obter'));
        montaDatatable("tabela-listagem-usuarios-mercado", ROTA);
    </script>
    @include('admin.usuarios.show')
@endsection

