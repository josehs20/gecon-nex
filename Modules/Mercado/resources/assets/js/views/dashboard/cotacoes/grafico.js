  $(document).ready(function (){
       let dadosDesconsiderar = [
            'cotações_sem_compras',
            'cotações_com_compras'
        ];

        const porcentagens = $('#cotacoesGrafico').data('porcentagens');

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
                text: "Porcentagem das cotações",
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
