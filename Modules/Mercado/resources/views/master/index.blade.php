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

@endsection
