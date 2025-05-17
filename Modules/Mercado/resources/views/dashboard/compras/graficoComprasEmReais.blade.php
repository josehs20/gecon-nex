<script>
    $(document).ready(function () {
        const comprasMensaisRaw = @json($view_renderizada['valores']['compras_em_reais_por_mes']);

        // Transforma os dados no formato esperado pelo CanvasJS
        const dataPoints = Object.entries(comprasMensaisRaw).map(([mes, valor]) => {
            const [ano, mesNum] = mes.split('-');
            const meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            const label = `${meses[parseInt(mesNum) - 1]}/${ano}`;

            return { 
                label: label, 
                y: valor,
                formattedValue: valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
            };
        });

        const chartComprasEmReais = new CanvasJS.Chart("chartComprasEmReais", {
            animationEnabled: true,
            title: {
                text: 'Compras em reais (R$)',
                fontSize: 20,
                horizontalAlign: "center"
            },

            subtitles: [{
                text: "Últimos 12 meses",
                fontSize: 14,
                horizontalAlign: "center",
                fontColor: "#555"
            }],
            axisY: {
                prefix: "R$ ",
                title: "Valor em reais"
            },
            axisX: {
                title: "Meses"
            },
            data: [{
                type: "line",
                indexLabelFontSize: 14,
                indexLabel: "{y}",
                toolTipContent: "<b>{label}</b>: R$ {y}",
                dataPoints: dataPoints
            }]
        });

        chartComprasEmReais.render();
    });
</script>

<div id="chartComprasEmReais" style="height: 40vh; width: 100%;"></div>
<script src="{{ asset('js/canvasjs/canvasjs.min.js') }}"></script>
