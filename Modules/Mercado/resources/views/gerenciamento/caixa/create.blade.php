@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.caixa.index'), 'titulo' => 'Caixas'], ['titulo' => 'Cadastro de caixa']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de caixa</h3>
            <p class="lead">Nesta tela você pode cadastrar um novo caixa.</p>
        </div>
    </div>


        @include('mercado::gerenciamento.caixa.inc.form')
   
@endsection
