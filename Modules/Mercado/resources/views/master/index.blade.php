@extends('mercado::layouts.app')

@section('content')
    <!-- Menu Sections com Tiles -->
    <div class="container py-5">
        @if (session()->has('menu') && auth()->user())
            @php
                // Agrupa itens que não têm submenus
                $menus_unicos = [];
                foreach (session('menu') as $chave => $valor) {
                    if (!is_array($valor['subMenus'])) {
                        $menus_unicos[] = $valor['subMenus'];
                    }

                }
                $menus_unicos = array_reverse($menus_unicos);
            @endphp

            <!-- Seção para Menus Únicos -->
            @if (!empty($menus_unicos))
                <div class="mb-5">
                    <h2 class="mb-4" style="color: white">Menu Rápido</h2>
                    <div class="row">

                        @foreach ($menus_unicos as $index => $processo)
                            <div class="col-6 col-md-4 col-lg-3 mb-4">
                                <a href="{{ route($processo->rota) }}" class="text-decoration-none">
                                    <div class="card text-center h-100 shadow-sm border-0 tile-{{ $index % 5 }}">
                                        <div class="card-body d-flex flex-column justify-content-center">
                                            <!-- Ícone do Bootstrap Icons -->
                                            <i class="{{ $processo->icon ?? ($defaultIcons[$processo->nome] ?? 'bi bi-gear') }} mb-2 tile-icon-{{ $index % 5 }}"
                                               style="font-size: 3rem;"></i>
                                            <!-- Nome do submenu -->
                                            <h5 class="card-title tile-text-{{ $index % 5 }}">{{ $processo->nome }}</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Categorias com SubMenus -->
            @foreach (session('menu') as $key => $menu)
                @if (isset($menu['subMenus']) && is_array($menu['subMenus']))
                    <div class="mb-5">
                        {{-- <h2 class="mb-4" style="color: white">{{ $menu['nome'] }}</h2> --}}
                        @foreach ($menu['subMenus'] as $subMenuName => $subMenus)
                            <div class="mb-4">
                                <h3 class="mb-3" style="color: white">{{ ucfirst($subMenuName) }}</h3>
                                <div class="row">
                                    @foreach ($subMenus as $index => $processo)
                                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                                            <a href="{{ route($processo->rota) }}" class="text-decoration-none">
                                                <div class="card text-center h-100 shadow-sm border-0 tile-{{ $index % 5 }}">
                                                    <div class="card-body d-flex flex-column justify-content-center">
                                                        <!-- Ícone do Bootstrap Icons -->
                                                        <i class="{{ $processo->icon ?? ($defaultIcons[$processo->nome] ?? 'bi bi-gear') }} mb-2 tile-icon-{{ $index % 5 }}"
                                                           style="font-size: 3rem;"></i>
                                                        <!-- Nome do submenu -->
                                                        <h5 class="card-title tile-text-{{ $index % 5 }}">{{ $processo->nome }}</h5>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <!-- CSS Customizado para estilizar os tiles -->
    <style>
        /* Estilo dos tiles */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        /* Cores variadas para os tiles */
        .tile-0 { background-color: #007bff; } /* Azul */
        .tile-1 { background-color: #28a745; } /* Verde */
        .tile-2 { background-color: #6f42c1; } /* Roxo */
        .tile-3 { background-color: #fd7e14; } /* Laranja */
        .tile-4 { background-color: #dc3545; } /* Vermelho */

        /* Cores das fontes para contraste */
        .tile-text-0, .tile-icon-0 { color: #ffffff; }
        .tile-text-1, .tile-icon-1 { color: #ffffff; }
        .tile-text-2, .tile-icon-2 { color: #ffffff; }
        .tile-text-3, .tile-icon-3 { color: #212529; }
        .tile-text-4, .tile-icon-4 { color: #ffffff; }

        /* Ajuste para responsividade */
        @media (max-width: 576px) {
            .card-title { font-size: 0.9rem; }
            .card i { font-size: 2rem; }
        }
    </style>
@endsection
