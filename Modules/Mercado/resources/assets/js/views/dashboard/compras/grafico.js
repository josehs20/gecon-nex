$(document).ready(function () {
    const porcentagens = $('#dataViewCompraGrafico').data('porcentagens');

    const dataPoints = Object.entries(porcentagens)
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
            text: "Porcentagem das compras",
            fontSize: 20,
            horizontalAlign: "center"
        },
        data: [{
            type: "doughnut",
            startAngle: 60,
            indexLabelFontSize: 17,
            indexLabel: "{label} - {y}%",
            toolTipContent: "<b>{label}:</b> {y}%",
            dataPoints: dataPoints
        }]
    });

    chart.render();
});
