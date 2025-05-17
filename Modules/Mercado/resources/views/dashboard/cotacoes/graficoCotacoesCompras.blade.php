<script>
    $(document).ready(function(){
        dadosDesconsiderar = [
            'cotações_finalizadas',
            'cotações_em_aberto',
            'cotações_em_cotação',
            'cotações_canceladas',
            'cotações_compradas'
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

        const chartCotacoesCompras = new CanvasJS.Chart("chartCotacoesCompras", {
            animationEnabled: true,
            title: {
                text: "Cotações e Compras",
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

        chartCotacoesCompras.render();
    });
</script>

<div id="chartCotacoesCompras" style="height: 40vh; width: 100%;"></div>
<script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>
