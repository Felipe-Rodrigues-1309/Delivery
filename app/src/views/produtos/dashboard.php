<?php
require_once __DIR__ . '/../../config/conexao.php';

// Relatórios básicos
$sqlResumo = "SELECT
    COUNT(*) AS total_pedidos,
    SUM(valor) AS total_vendas,
    SUM(CASE WHEN DATE(data_pedido) = CURDATE() THEN 1 ELSE 0 END) AS pedidos_hoje,
    SUM(CASE WHEN DATE(data_pedido) = CURDATE() THEN valor ELSE 0 END) AS vendas_hoje,
    SUM(CASE WHEN MONTH(data_pedido) = MONTH(CURDATE()) AND YEAR(data_pedido) = YEAR(CURDATE()) THEN valor ELSE 0 END) AS vendas_mes
FROM pedido";
$resumo = $conn->query($sqlResumo)->fetch_assoc();

// Vendas diárias últimos 7 dias
$sqlVendasDiarias = "SELECT DATE(data_pedido) AS dia, SUM(valor) AS total FROM pedido WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(data_pedido) ORDER BY dia ASC";
$resultVendasDiarias = $conn->query($sqlVendasDiarias);
$labelsDiarios = [];
$valoresDiarios = [];

for ($i = 6; $i >= 0; $i--) {
    $data = date('Y-m-d', strtotime("-{$i} days"));
    $labelsDiarios[] = date('d/m', strtotime($data));
    $valoresDiarios[$data] = 0;
}

while ($row = $resultVendasDiarias->fetch_assoc()) {
    $data = $row['dia'];
    if (isset($valoresDiarios[$data])) {
        $valoresDiarios[$data] = (float) $row['total'];
    }
}

$valoresDiarios = array_values($valoresDiarios);

// Vendas mensais últimos 12 meses
$sqlVendasMensais = "SELECT DATE_FORMAT(data_pedido, '%Y-%m') AS mes, SUM(valor) AS total FROM pedido WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) GROUP BY DATE_FORMAT(data_pedido, '%Y-%m') ORDER BY mes ASC";
$resultVendasMensais = $conn->query($sqlVendasMensais);
$labelsMensais = [];
$valoresMensais = [];

for ($i = 11; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-{$i} months"));
    $labelsMensais[] = date('M/Y', strtotime($mes . '-01'));
    $valoresMensais[$mes] = 0;
}

while ($row = $resultVendasMensais->fetch_assoc()) {
    $mes = $row['mes'];
    if (isset($valoresMensais[$mes])) {
        $valoresMensais[$mes] = (float) $row['total'];
    }
}

$valoresMensais = array_values($valoresMensais);

// Top 10 produtos
$sqlPedidos = "SELECT item, valor FROM pedido WHERE item IS NOT NULL";
$resultPedidos = $conn->query($sqlPedidos);
$produtoContagem = [];
$produtoValor = [];

while ($row = $resultPedidos->fetch_assoc()) {
    $texto = trim($row['item']);
    if ($texto === '') continue;

    preg_match_all('/(\d+)x\s*([^,\r\n]+)/iu', $texto, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        $nome = preg_replace('/\s+/u', ' ', strip_tags($texto));
        $quantidade = 1;
        $produtoContagem[$nome] = ($produtoContagem[$nome] ?? 0) + $quantidade;
        $produtoValor[$nome] = ($produtoValor[$nome] ?? 0) + (float) $row['valor'];
        continue;
    }

    foreach ($matches as $match) {
        $quantidade = intval($match[1]);
        $nome = trim($match[2]);
        if ($nome === '') continue;
        $produtoContagem[$nome] = ($produtoContagem[$nome] ?? 0) + $quantidade;
        $produtoValor[$nome] = ($produtoValor[$nome] ?? 0) + ((float)$row['valor'] * $quantidade);
    }
}

arsort($produtoContagem);
$topProdutos = array_slice($produtoContagem, 0, 10, true);

$topLabels = array_keys($topProdutos);
$topQuantidades = array_values($topProdutos);
$topValores = array_map(function($produto) use ($produtoValor) {
    return round($produtoValor[$produto] ?? 0, 2);
}, $topLabels);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Vendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
        }
        .dashboard-header {
            margin: 35px 0 20px;
        }
        .estatistica-card {
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .card-title {
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        .chart-card {
            border-radius: 18px;
            box-shadow: 0 14px 35px rgba(0,0,0,0.08);
        }
        .top-produtos-table td,
        .top-produtos-table th {
            vertical-align: middle;
        }

        /* Sidebar */
    .sidebar {
      width: 240px;
      height: 100vh;
      position: fixed;
      background: #190268;
      color: white;
      padding: 20px;
    }

    .sidebar h3 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 10px;
      text-decoration: none;
      border-radius: 8px;
    }

    .sidebar a:hover {
      background: #1f2937;
      color: #fff;
    }

        /* Conteúdo */
    .container {
      margin-left: 260px;
      padding: 20px;
    }


    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h3>🍔 Delivery Admin</h3>
  <a href="?action=pedidos">Pedidos</a>  
  <a href="?action=cadastroDeProduto">Cadastro de Produtos</a>
  <a href="?action=dashboard">Dashboard</a>
  <a href="?action=listarProdutos">Produto</a>

</div>

<div class="container">
    <div class="dashboard-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="h3">Dashboard de Vendas</h1>
                <p class="text-muted">Relatórios de vendas por dia, mês e produtos mais vendidos.</p>
            </div>
        </div>
    </div>

    <div class="row gy-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <h5 class="card-title">Total de Pedidos</h5>
                <p class="display-6 mb-0"><?= number_format($resumo['total_pedidos'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <h5 class="card-title">Vendas Totais</h5>
                <p class="display-6 mb-0">R$ <?= number_format($resumo['total_vendas'] ?? 0, 2, ',', '.') ?></p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <h5 class="card-title">Pedidos Hoje</h5>
                <p class="display-6 mb-0"><?= number_format($resumo['pedidos_hoje'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <h5 class="card-title">Vendas do Mês</h5>
                <p class="display-6 mb-0">R$ <?= number_format($resumo['vendas_mes'] ?? 0, 2, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-12 col-xl-7">
            <div class="card p-4 chart-card bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Vendas Diárias (Últimos 7 dias)</h5>
                        <p class="text-muted mb-0">Receita diária em reais.</p>
                    </div>
                </div>
                <canvas id="vendasDiariasChart" height="180"></canvas>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card p-4 chart-card bg-white">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5>Vendas Mensais (Últimos 12 meses)</h5>
                        <p class="text-muted mb-0">Receita por mês.</p>
                    </div>
                </div>
                <canvas id="vendasMensaisChart" height="180"></canvas>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-12 col-xl-6">
            <div class="card p-4 chart-card bg-white">
                <h5 class="mb-3">Top 10 Produtos Mais Vendidos</h5>
                <div class="table-responsive">
                    <table class="table table-borderless top-produtos-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produto</th>
                                <th class="text-end">Quantidade</th>
                                <th class="text-end">Faturamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topLabels as $index => $produto): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($produto) ?></td>
                                    <td class="text-end"><?= number_format($topQuantidades[$index], 0, ',', '.') ?></td>
                                    <td class="text-end">R$ <?= number_format($topValores[$index], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card p-4 chart-card bg-white">
                <h5 class="mb-3">Faturamento por Produto</h5>
                <canvas id="topProdutosChart" height="320"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
const labelsDiarios = <?= json_encode($labelsDiarios, JSON_UNESCAPED_UNICODE) ?>;
const valoresDiarios = <?= json_encode($valoresDiarios) ?>;
const labelsMensais = <?= json_encode($labelsMensais, JSON_UNESCAPED_UNICODE) ?>;
const valoresMensais = <?= json_encode($valoresMensais) ?>;
const topLabels = <?= json_encode($topLabels, JSON_UNESCAPED_UNICODE) ?>;
const topValores = <?= json_encode($topValores) ?>;

new Chart(document.getElementById('vendasDiariasChart'), {
    type: 'line',
    data: {
        labels: labelsDiarios,
        datasets: [{
            label: 'Vendas',
            data: valoresDiarios,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.15)',
            fill: true,
            tension: 0.35,
            pointRadius: 4,
            pointBackgroundColor: '#4e73df'
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }
            }
        }
    }
});

new Chart(document.getElementById('vendasMensaisChart'), {
    type: 'bar',
    data: {
        labels: labelsMensais,
        datasets: [{
            label: 'Vendas',
            data: valoresMensais,
            backgroundColor: '#1cc88a',
            borderRadius: 8,
            maxBarThickness: 36
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }
            }
        }
    }
});

new Chart(document.getElementById('topProdutosChart'), {
    type: 'bar',
    data: {
        labels: topLabels,
        datasets: [{
            label: 'Faturamento',
            data: topValores,
            backgroundColor: '#f6c23e',
            borderRadius: 8,
            maxBarThickness: 40
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}) }
            }
        }
    }
});
</script>
</body>
</html>
