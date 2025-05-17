@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Lista de usuários'], ['titulo' => 'Editar usuário']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Editar usuário</h3>
            <p class="lead">Nesta tela você pode editar usuários no sistema GECON.</p>
        </div>
    </div>

    <form action="{{route('gecon.usuarios.update', ['usuario_master_cod' => $user->id])}}" method="post">
        @csrf
        @include('mercado::gerenciamento.usuarios.inc.form', ['canCriarSenha' => false])
    </form>

@endsection