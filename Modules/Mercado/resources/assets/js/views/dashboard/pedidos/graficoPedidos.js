 $(document).ready(function(){
        let dadosDesconsiderar = [
            'pedidos_aguardando_cotação',
            'pedidos_cotados',
            'pedidos_em_aberto',
            'pedidos_em_cotação',
            'pedidos_cancelados',
            'pedidos_comprados'
        ];

        const porcentagens = $('#dataViewGraficoPedido').data('porcentagens');

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
