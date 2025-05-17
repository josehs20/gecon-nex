@extends('mercado::layouts.app')

@section('content')
    <style>
        .titulo-view{
            margin-bottom: 30px;
            color: #fff;
        }
    </style>
    <div>
        <div class="text-center">
            <h3 style="color: #fff">DASHBOARD</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 text-center mb-4">
                    @php
                        $botoes = [
                            [
                                'view' => 'pedido',
                                'nome' => 'Pedidos',
                                'icone' => 'bi bi-bookshelf'
                            ], 
                            [
                                'view' => 'cotacao',
                                'nome' => 'Cotações',
                                'icone' => 'bi bi-c-square'
                            ], 
                            [
                                'view' => 'compra',
                                'nome' => 'Compras',
                                'icone' => 'bi bi-basket'
                            ], 
                        ];
                    @endphp
                    @foreach($botoes as $botao)
                        <a class="btn btn-success me-2" href="{{ route('dashboard.renderizar', ['view' => $botao['view']]) }}">
                            <i class="{{ $botao['icone'] }}"></i>
                            {{ ucfirst($botao['nome']) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    

    @php
        $dados_view = [
            'pedidos' => [
                'nome_view_renderizada' => 'view_pedido',
                'caminho_da_view' => 'mercado::dashboard.pedidos.pedidos'
            ],
            'cotacoes' => [
                'nome_view_renderizada' => 'view_cotacao',
                'caminho_da_view' => 'mercado::dashboard.cotacoes.cotacoes'
            ],
            'compras' => [
                'nome_view_renderizada' => 'view_compra',
                'caminho_da_view' => 'mercado::dashboard.compras.compras'
            ],
        ];
    @endphp

    @foreach ($dados_view as $dados)
        @if (isset($view_renderizada) && isset($view_renderizada[$dados['nome_view_renderizada']]))        
            <div>   
                @include($dados['caminho_da_view'])
            </div>
        @endif
    @endforeach

@endsection
