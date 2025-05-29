@vite('Modules/Mercado/resources/assets/js/views/dashboard/pedidos/grafico.js', 'build/.vite')
<div id="graficoPedido" data-porcentagens='@json($view_renderizada['porcentagens'])'></div>


<div id="chartContainer" style="height: 40vh; width: 100%;"></div>
{{-- <script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script> --}}
