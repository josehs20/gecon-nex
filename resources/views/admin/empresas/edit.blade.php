@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('admin.empresa.index'), 'titulo' => 'Página inicia'], ['rota' => route('admin.empresa.index'), 'titulo' => 'Lista de empresas'], ['titulo' => 'Editar Informações da empresa']]])

@section('content')
@vite('resources/js/views/admin/form_empresa.js', 'build/.vite')

    <div class="cabecalho">
        <div class="page-header">
            <div class="page-header">
                <h3>Editar Informações da empresa</h3>
                <p class="lead">Atualize os campos abaixo para editar as informações da empresa.</p>
            </div>
        </div>
    </div>
    @include('admin.empresas.inc.form_empresa', ['empresa' => $empresa])

@endsection
