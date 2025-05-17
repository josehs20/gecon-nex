@extends('mercado::layouts.app')

@section('content')
<style>
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin: 20px;
        background: #ffffff;
    }
    .card-header {
        background: linear-gradient(45deg, #0A0A1A, #1c2526) !important;
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 20px;
    }
    .card-title {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
    }
    .card-body {
        padding: 30px;
    }
    .chart-container {
        margin: 20px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .chart-container:hover {
        transform: translateY(-5px);
    }
    .chart-title {
        font-size: 1.3rem;
        color: #2c3e50;
        margin-bottom: 15px;
        text-align: center;
        font-weight: 500;
    }
    .btn-success {
        margin: 5px;
        padding: 10px 15px;
        font-size: 0.9rem;
        transition: background 0.3s;
    }
    .btn-success:hover {
        background: #218838;
    }
    .btn-success i {
        margin-right: 5px;
    }
    .small-chart {
        height: 200px !important;
    }
    .large-chart {
        height: 400px !important;
    }
    @media (max-width: 768px) {
        .chart-container {
            margin: 10px 0;
        }
        .btn-success {
            font-size: 0.8rem;
            padding: 8px 10px;
        }
    }
</style>

<div class="">
    <div class="card-header text-center">
        <h5 class="card-title">Home Cliente Master - Dashboard Financeiro</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Filtro para Gráfico de Linhas -->
            <div class="col-md-12 text-center mb-4">
                <button class="btn btn-success" onclick="toggleLineData('revenue')">
                    <i class="bi bi-currency-dollar"></i> Receita
                </button>
                <button class="btn btn-success" onclick="toggleLineData('profit')">
                    <i class="bi bi-graph-up"></i> Lucro
                </button>
                <button class="btn btn-success" onclick="toggleLineData('expenses')">
                    <i class="bi bi-wallet2"></i> Despesas
                </button>
                <button class="btn btn-success" onclick="toggleLineData('investments')">
                    <i class="bi bi-bar-chart-line"></i> Investimentos
                </button>
                <button class="btn btn-success" onclick="toggleLineData('grossMargin')">
                    <i class="bi bi-pie-chart"></i> Margem Bruta
                </button>
                <button class="btn btn-success" onclick="toggleLineData('cashFlow')">
                    <i class="bi bi-cash-stack"></i> Fluxo de Caixa
                </button>
                <button class="btn btn-success" onclick="toggleLineData('averageTicket')">
                    <i class="bi bi-ticket"></i> Ticket Médio
                </button>
                <button class="btn btn-success" onclick="toggleLineData('averageTicket')">
                    <i class="bi bi-database-fill-exclamation"></i> Análisar histórico de transações detalhadamente
                </button>
            </div>
            <!-- Gráfico de Linhas (Grande) -->
            <div class="col-md-8">
                <div class="chart-container">
                    <div class="chart-title">Dados Mensais </div>
                    <div id="lineChart" class="large-chart" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="chart-container">
                    <div class="chart-title">Clientes por Segmento</div>
                    <div id="doughnutChart" class="small-chart" style="height: 200px; width: 100%;"></div>
                </div>
            </div>
            <!-- Gráfico de Colunas (Médio) -->
            <div class="col-md-4">
                <div class="chart-container">
                    <div class="chart-title">Vendas por Categoria </div>
                    <div id="columnChart" style="height: 150px; width: 100%;"></div>
                </div>
                <div style="height: 500px; width: 100%;" class="chart-container">
                    <div class="chart-title">Distribuição de Lucro </div>
                    <div  id="areaChart" class="large-chart" ></div>
                </div>
            </div>

            <!-- Gráfico de Rosca (Pequeno) -->
            <div class="col-md-6">
                {{-- <div class="chart-container">
                    <div class="chart-title">Clientes por Segmento</div>
                    <div id="doughnutChart" class="small-chart" style="height: 200px; width: 100%;"></div>
                </div> --}}
            </div>
            <!-- Gráfico de Barras Empilhadas (Pequeno) -->
            <div class="col-md-12">
                <div class="chart-container">
                    <div class="chart-title">Fontes de Receita </div>
                    <div id="stackedBarChart" class="small-chart" style="height: 200px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
let lineChartInstance = null;

// Conjuntos de dados fictícios
const revenueData = [
    { label: "Jan", y: 1500000 },
    { label: "Fev", y: 1800000 },
    { label: "Mar", y: 2200000 },
    { label: "Abr", y: 1900000 },
    { label: "Mai", y: 2500000 },
    { label: "Jun", y: 2800000 }
];

const profitData = [
    { label: "Jan", y: 1200000 },
    { label: "Fev", y: 1400000 },
    { label: "Mar", y: 1700000 },
    { label: "Abr", y: 1500000 },
    { label: "Mai", y: 2000000 },
    { label: "Jun", y: 2300000 }
];

const expensesData = [
    { label: "Jan", y: 1000000 },
    { label: "Fev", y: 1100000 },
    { label: "Mar", y: 1300000 },
    { label: "Abr", y: 1200000 },
    { label: "Mai", y: 1400000 },
    { label: "Jun", y: 1600000 }
];

const investmentsData = [
    { label: "Jan", y: 1100000 },
    { label: "Fev", y: 1300000 },
    { label: "Mar", y: 1500000 },
    { label: "Abr", y: 1400000 },
    { label: "Mai", y: 1700000 },
    { label: "Jun", y: 1900000 }
];

const grossMarginData = [
    { label: "Jan", y: 1300000 },
    { label: "Fev", y: 1500000 },
    { label: "Mar", y: 1800000 },
    { label: "Abr", y: 1600000 },
    { label: "Mai", y: 2100000 },
    { label: "Jun", y: 2400000 }
];

const cashFlowData = [
    { label: "Jan", y: 1400000 },
    { label: "Fev", y: 1600000 },
    { label: "Mar", y: 1900000 },
    { label: "Abr", y: 1700000 },
    { label: "Mai", y: 2200000 },
    { label: "Jun", y: 2500000 }
];

const averageTicketData = [
    { label: "Jan", y: 1050000 },
    { label: "Fev", y: 1150000 },
    { label: "Mar", y: 1250000 },
    { label: "Abr", y: 1350000 },
    { label: "Mai", y: 1450000 },
    { label: "Jun", y: 1550000 }
];

function renderLineChart(data, title) {
    lineChartInstance = new CanvasJS.Chart("lineChart", {
        animationEnabled: true,
        theme: "light2",
        backgroundColor: "transparent",
        title: { text: "" },
        axisY: {
            title: title + " (R$ Milhões)",
            prefix: "R$",
            labelFormatter: function(e) {
                return "R$" + (e.value / 1000000) + "M";
            },
            gridColor: "#e1e1e1"
        },
        axisX: {
            labelFontColor: "#2c3e50"
        },
        toolTip: {
            content: "{label}: R${y:#,##0.0#}M",
            backgroundColor: "#2c3e50",
            fontColor: "#ffffff"
        },
        data: [{
            type: "line",
            lineColor: "#e74c3c",
            markerColor: "#e74c3c",
            yValueFormatString: "R$#,##0.0#M",
            click: function(e) {
                alert("Mês: " + e.dataPoint.label + "\nValor: R$" + (e.dataPoint.y / 1000000) + "M");
            },
            dataPoints: data
        }]
    });
    lineChartInstance.render();
}

function toggleLineData(type) {
    switch (type) {
        case 'revenue':
            renderLineChart(revenueData, "Receita");
            break;
        case 'profit':
            renderLineChart(profitData, "Lucro");
            break;
        case 'expenses':
            renderLineChart(expensesData, "Despesas");
            break;
        case 'investments':
            renderLineChart(investmentsData, "Investimentos");
            break;
        case 'grossMargin':
            renderLineChart(grossMarginData, "Margem Bruta");
            break;
        case 'cashFlow':
            renderLineChart(cashFlowData, "Fluxo de Caixa");
            break;
        case 'averageTicket':
            renderLineChart(averageTicketData, "Ticket Médio");
            break;
    }
}

window.onload = function () {
    // Inicializa com Receita
    renderLineChart(revenueData, "Receita");

    // Gráfico de Colunas
    var columnChart = new CanvasJS.Chart("columnChart", {
        animationEnabled: true,
        theme: "light2",
        backgroundColor: "transparent",
        title: { text: "" },
        axisY: {
            title: "Vendas (R$ Milhões)",
            prefix: "R$",
            labelFormatter: function(e) {
                return "R$" + (e.value / 1000000) + "M";
            },
            gridColor: "#e1e1e1"
        },
        axisX: {
            labelFontColor: "#2c3e50"
        },
        toolTip: {
            content: "{label}: R${y:#,##0.0#}M",
            backgroundColor: "#2c3e50",
            fontColor: "#ffffff"
        },
        data: [{
            type: "column",
            yValueFormatString: "R$#,##0.0#M",
            color: "#3498db",
            click: function(e) {
                alert("Categoria: " + e.dataPoint.label + "\nVendas: R$" + (e.dataPoint.y / 1000000) + "M");
            },
            dataPoints: [
                { label: "Eletrônicos", y: 3200000 },
                { label: "Moda", y: 1800000 },
                { label: "Casa", y: 2500000 },
                { label: "Alimentos", y: 1200000 }
            ]
        }]
    });

    // Gráfico de Área
    var areaChart = new CanvasJS.Chart("areaChart", {
        animationEnabled: true,
        theme: "light2",
        backgroundColor: "transparent",
        title: { text: "" },
        axisY: {
            title: "Lucro (R$ Milhões)",
            prefix: "R$",
            labelFormatter: function(e) {
                return "R$" + (e.value / 1000000) + "M";
            },
            gridColor: "#e1e1e1"
        },
        axisX: {
            labelFontColor: "#2c3e50"
        },
        toolTip: {
            content: "{label}: R${y:#,##0.0#}M",
            backgroundColor: "#2c3e50",
            fontColor: "#ffffff"
        },
        data: [{
            type: "area",
            yValueFormatString: "R$#,##0.0#M",
            color: "#2ecc71",
            click: function(e) {
                alert("Setor: " + e.dataPoint.label + "\nLucro: R$" + (e.dataPoint.y / 1000000) + "M");
            },
            dataPoints: [
                { label: "Varejo", y: 2800000 },
                { label: "Atacado", y: 1500000 },
                { label: "Online", y: 2000000 },
                { label: "Outros", y: 1000000 }
            ]
        }]
    });

    // Gráfico de Rosca
    var doughnutChart = new CanvasJS.Chart("doughnutChart", {
        animationEnabled: true,
        theme: "light2",
        backgroundColor: "transparent",
        title: { text: "" },
        toolTip: {
            content: "{label}: {y}%",
            backgroundColor: "#2c3e50",
            fontColor: "#ffffff"
        },
        data: [{
            type: "doughnut",
            startAngle: 60,
            innerRadius: 60,
            indexLabel: "{label}: {y}%",
            indexLabelFontSize: 12,
            yValueFormatString: "#,##0.0#%",
            click: function(e) {
                alert("Segmento: " + e.dataPoint.label + "\nProporção: " + e.dataPoint.y + "%");
            },
            dataPoints: [
                { y: 40, label: "Empresas" },
                { y: 30, label: "Autônomos" },
                { y: 20, label: "Consumidores" },
                { y: 10, label: "Outros" }
            ]
        }]
    });

    // Gráfico de Barras Empilhadas
    var stackedBarChart = new CanvasJS.Chart("stackedBarChart", {
        animationEnabled: true,
        theme: "light2",
        backgroundColor: "transparent",
        title: { text: "" },
        axisY: {
            title: "Receita (R$ Milhões)",
            prefix: "R$",
            labelFormatter: function(e) {
                return "R$" + (e.value / 1000000) + "M";
            },
            gridColor: "#e1e1e1"
        },
        axisX: {
            labelFontColor: "#2c3e50"
        },
        toolTip: {
            content: "{name}: R${y:#,##0.0#}M",
            backgroundColor: "#2c3e50",
            fontColor: "#ffffff"
        },
        data: [
            {
                type: "stackedBar",
                name: "Produtos",
                color: "#e67e22",
                yValueFormatString: "R$#,##0.0#M",
                dataPoints: [
                    { label: "Q1", y: 1200000 },
                    { label: "Q2", y: 1500000 },
                    { label: "Q3", y: 1800000 }
                ]
            },
            {
                type: "stackedBar",
                name: "Serviços",
                color: "#9b59b6",
                yValueFormatString: "R$#,##0.0#M",
                dataPoints: [
                    { label: "Q1", y: 800000 },
                    { label: "Q2", y: 1000000 },
                    { label: "Q3", y: 1200000 }
                ]
            }
        ]
    });

    columnChart.render();
    areaChart.render();
    doughnutChart.render();
    stackedBarChart.render();
}
</script>
@endsection
