<script>
    $(document).ready(function(){
        dadosDesconsiderar = [
            'pedidos_aguardando_cotação',
            'pedidos_cotados',
            'pedidos_em_aberto',
            'pedidos_em_cotação',
            'pedidos_cancelados',
            'pedidos_comprados'
        ];

        const porcentagens = @json($view_renderizada['porcentagens']);

        const dataPointsGraficoCotacoesPedidos = Object.entries(porcentagens)
        .filter(([label, _]) => !dadosDesconsiderar.includes(label))
        .map(([label, y]) => ({
            label: label
                .replace(/_/g, ' ')
                .toLowerCase()
                .replace(/^./, str => str.toUpperCase()),
            y: y
        }));

        const chartPedidosCotacoes = new CanvasJS.Chart("chartPedidosCotacoes", {
            animationEnabled: true,
            title: {
                text: "Pedidos e Cotações",
                fontSize: 20, 
                horizontalAlign: "center"
            },
            data: [{
                type: "doughnut",
                startAngle: 60,
                indexLabelFontSize: 17,
                indexLabel: "{label} - {y}%",
                toolTipContent: "<b>{label}:</b> {y}%",
                dataPoints: dataPointsGraficoCotacoesPedidos
            }]
        });

        chartPedidosCotacoes.render();
    });
</script>

<div id="chartPedidosCotacoes" style="height: 40vh; width: 100%;"></div>
<script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>
