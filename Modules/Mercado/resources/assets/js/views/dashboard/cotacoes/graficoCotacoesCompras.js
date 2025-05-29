$(document).ready(function(){
      let  dadosDesconsiderar = [
            'cotações_finalizadas',
            'cotações_em_aberto',
            'cotações_em_cotação',
            'cotações_canceladas',
            'cotações_compradas'
        ];

        const porcentagens = $('#cotacoesGraficoCompra').data('porcentagens');

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
