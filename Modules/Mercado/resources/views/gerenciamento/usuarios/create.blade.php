@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.gecon.usuarios.index'), 'titulo' => 'Lista de usuários'], ['titulo' => 'Cadastrar usuário']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3 class="card-title">Cadastrar usuário</h3>
            <p class="lead">Nesta tela você pode cadastrar usuários no sistema GECON.</p>
        </div>
    </div>

    <form action="{{route('gecon.usuarios.store')}}" method="post">
        @csrf
        @include('mercado::gerenciamento.usuarios.inc.form', ['canCriarSenha' => true])
    </form>

@endsection