@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicia'], ['rota' => route('admin.empresa.index'), 'titulo' => 'Lista de empresas'], ['titulo' => 'Cadastro de empresa']]])

@section('content')
@vite('resources/js/views/admin/form_empresa.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <div class="page-header">
                <h3>Cadastro de Empresa</h3>
                <p class="lead">Preencha os campos abaixo para cadastrar uma nova empresa.</p>
            </div>
        </div>
    </div>
    @include('admin.empresas.inc.form_empresa', ['empresa' => false])

@endsection
