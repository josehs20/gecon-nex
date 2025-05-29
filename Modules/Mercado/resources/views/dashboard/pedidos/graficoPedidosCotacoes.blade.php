@vite('Modules/Mercado/resources/assets/js/views/dashboard/pedidos/graficoPedidos.js', 'build/.vite')
<div id="dataViewGraficoPedido" data-porcentagens='@json($view_renderizada['porcentagens'])'></div>

<div id="chartPedidosCotacoes" style="height: 40vh; width: 100%;"></div>
{{-- <script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script> --}}
