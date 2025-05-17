<script>
    $(document).ready(function () {
        dadosDesconsiderar = [
            'pedidos_sem_cotação',
            'pedidos_com_cotação'
        ];
        const porcentagens = @json($view_renderizada['porcentagens']);

        const dataPoints = Object.entries(porcentagens)
        .filter(([label, _]) => !dadosDesconsiderar.includes(label))
        .map(([label, y]) => ({
            label: label
                .replace(/_/g, ' ')
                .toLowerCase()
                .replace(/^./, str => str.toUpperCase()),
            y: y
        }));

        const chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Porcentagem dos pedidos",
                fontSize: 20, 
                horizontalAlign: "center"
            },
            data: [{
                type: "column",
                startAngle: 60,
                indexLabelFontSize: 17,
                indexLabel: "{y}%",
                toolTipContent: "<b>{label}:</b> {y}%",
                dataPoints: dataPoints
            }]
        });

        chart.render();
    });
</script>

<div id="chartContainer" style="height: 40vh; width: 100%;"></div>
<script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>
