@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicia'], ['rota' => route('admin.empresa.index'), 'titulo' => 'Lista de empresas'],['rota' => route('admin.empresa.edit', ['empresa' => $empresa->id]), 'titulo' => 'Editar empresa'], ['titulo' => 'Cadastro de lojas']]])

@section('content')
@vite('resources/js/views/lojas/form_lojas.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <div class="page-header">
                <h3>Cadastro de lojas para a empresa : <u> {{$empresa->nome_fantasia}}</u></h3>
                <p class="lead">Preencha os campos abaixo para cadastrar uma nova loja.</p>
            </div>
        </div>
    </div>
    @include('admin.lojas.inc.form_loja', ['loja' => $loja, 'empresa' => $empresa])


@endsection

