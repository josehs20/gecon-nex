@vite('Modules/Mercado/resources/assets/js/views/dashboard/compras/graficoComprasEmReais.js', 'build/.vite')
<div id="dataViewCompraEmReais"
data-compras='@json($view_renderizada['valores']['compras_em_reais_por_mes'])'
></div>


<div id="chartComprasEmReais" style="height: 40vh; width: 100%;"></div>
{{-- <script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script> --}}
