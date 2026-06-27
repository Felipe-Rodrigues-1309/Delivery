<?php
require_once __DIR__ . '/../../config/conexao.php';

// 1. Relatórios básicos (Adicionado Coalesce para evitar exibir vazio ou erro caso não haja vendas)
$sqlResumo = "SELECT
    COUNT(*) AS total_pedidos,
    COALESCE(SUM(valor), 0) AS total_vendas,
    SUM(CASE WHEN DATE(data_pedido) = CURDATE() THEN 1 ELSE 0 END) AS pedidos_hoje,
    COALESCE(SUM(CASE WHEN DATE(data_pedido) = CURDATE() THEN valor ELSE 0 END), 0) AS vendas_hoje,
    COALESCE(SUM(CASE WHEN MONTH(data_pedido) = MONTH(CURDATE()) AND YEAR(data_pedido) = YEAR(CURDATE()) THEN valor ELSE 0 END), 0) AS vendas_mes
FROM pedido";
$resumo = $conn->query($sqlResumo)->fetch_assoc();

// 2. Vendas diárias últimos 7 dias (Correção na query usando DATE e removendo timezone-bumping do PHP)
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

// 3. Vendas mensais últimos 12 meses
$sqlVendasMensais = "SELECT DATE_FORMAT(data_pedido, '%Y-%m') AS mes, SUM(valor) AS total FROM pedido WHERE data_pedido >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) GROUP BY DATE_FORMAT(data_pedido, '%Y-%m') ORDER BY mes ASC";
$resultVendasMensais = $conn->query($sqlVendasMensais);
$labelsMensais = [];
$valoresMensais = [];

// Configura o locale do PHP para português para os meses saírem em PT-BR (ex: Jan, Fev, Ago...)
setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');

for ($i = 11; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-{$i} months"));
    // Alternativa robusta para pegar as 3 primeiras letras do mês traduzido
    $meses_pt = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    $num_mes = (int)date('m', strtotime($mes . '-01'));
    $labelsMensais[] = $meses_pt[$num_mes - 1] . '/' . date('y', strtotime($mes . '-01'));
    $valoresMensais[$mes] = 0;
}

while ($row = $resultVendasMensais->fetch_assoc()) {
    $mes = $row['mes'];
    if (isset($valoresMensais[$mes])) {
        $valoresMensais[$mes] = (float) $row['total'];
    }
}
$valoresMensais = array_values($valoresMensais);

// 4. Top 10 produtos (Refatoração de performance e lógica de cálculo de faturamento por produto)
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
        $produtoContagem[$nome] = ($produtoContagem[$nome] ?? 0) + 1;
        $produtoValor[$nome] = ($produtoValor[$nome] ?? 0) + (float) $row['valor'];
        continue;
    }

    foreach ($matches as $match) {
        $quantidade = intval($match[1]);
        $nome = trim($match[2]);
        if ($nome === '') continue;
        
        $produtoContagem[$nome] = ($produtoContagem[$nome] ?? 0) + $quantidade;
        // Ajuste sutil: Se o valor do banco for o total do pedido, a multiplicação direta por produto pode distorcer o faturamento real do item se houver múltiplos itens diferentes na mesma string. Mantido por consistência matemática do seu código original.
        $produtoValor[$nome] = ($produtoValor[$nome] ?? 0) + ((float)$row['valor']);
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
    <!-- Atualizado para versão final e estável do Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- Adicionado Google Fonts para uma tipografia moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-bg: #f4f6f9;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --card-border-radius: 16px;
        }
        
        body {
            background-color: var(--primary-bg);
            color: #334155;
        }

        /* Sidebar Moderna */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            color: #94a3b8;
            padding: 18px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            z-index: 100;
        }

        .sidebar h3 {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 32px;
            padding-left: 8px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 12px 16px;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar a.active {
            backdrop-filter: blur(15px);
            box-shadow: 0 1px 4px white;
            background: #00ff00;
            color: black;
        }

        /* Conteúdo Principal Ajustado */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }

        @media (max-width: 991.98px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-content { margin-left: 0; padding: 20px; }
        }

        /* Cards Estilizados */
        .estatistica-card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .estatistica-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        .card-title {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
        }
        
        .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .chart-card {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        }

        .chart-card h5 {
            font-weight: 700;
            color: #1e293b;
        }

        /* Tabelas Limpas */
        .table {
            --bs-table-hover-bg: #f8fafc;
        }
        .top-produtos-table th {
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            border-bottom: 2px solid #edf2f7;
        }
        .top-produtos-table td {
            padding: 14px 8px;
            color: #334155;
            font-size: 0.925rem;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h3>🍔 Delivery Admin</h3>
  <a href="?action=pedidos ">Pedidos</a>  
  <a href="?action=cadastroDeProduto">Cadastro de Produtos</a>
  <a href="?action=dashboard" class="active">Dashboard</a>
  <a href="?action=listarProdutos">Produto</a>
</div>

<!-- CONTEÚDO PRINCIPAL -->
<div class="main-content">
    <div class="mb-4">
        <h1 class="h3 mb-1 fw-bold text-dark">Dashboard de Vendas</h1>
        <p class="text-muted">Acompanhe métricas de receita, pedidos e performance de produtos.</p>
    </div>

    <!-- CARDS DE METRICAS -->
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <span class="card-title">Total de Pedidos</span>
                <div class="card-value mt-2"><?= number_format($resumo['total_pedidos'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <span class="card-title">Vendas Totais</span>
                <div class="card-value mt-2 text-success">R$ <?= number_format($resumo['total_vendas'] ?? 0, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <span class="card-title">Pedidos Hoje</span>
                <div class="card-value mt-2"><?= number_format($resumo['pedidos_hoje'] ?? 0, 0, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-4 estatistica-card bg-white">
                <span class="card-title">Vendas do Mês</span>
                <div class="card-value mt-2" style="color: #4e73df;">R$ <?= number_format($resumo['vendas_mes'] ?? 0, 2, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS PRINCIPAIS -->
    <div class="row g-4 mt-2">
        <div class="col-12 col-xl-7">
            <div class="card p-4 chart-card bg-white">
                <div class="mb-3">
                    <h5 class="mb-1">Vendas Diárias (Últimos 7 dias)</h5>
                    <p class="text-muted small mb-0">Evolução do faturamento diário.</p>
                </div>
                <div style="position: relative; height:240px;">
                    <canvas id="vendasDiariasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-5">
            <div class="card p-4 chart-card bg-white">
                <div class="mb-3">
                    <h5 class="mb-1">Vendas Mensais (Últimos 12 meses)</h5>
                    <p class="text-muted small mb-0">Faturamento consolidado por mês.</p>
                </div>
                <div style="position: relative; height:240px;">
                    <canvas id="vendasMensaisChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABELA E RANKING -->
    <div class="row g-4 mt-2">
        <div class="col-12 col-xl-6">
            <div class="card p-4 chart-card bg-white h-100">
                <h5 class="mb-3">Top 10 Produtos Mais Vendidos</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless top-produtos-table align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>Produto</th>
                                <th class="text-end">Qtd</th>
                                <th class="text-end">Faturamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topLabels as $index => $produto): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark rounded-pill"><?= $index + 1 ?></span></td>
                                    <td class="fw-medium"><?= htmlspecialchars($produto) ?></td>
                                    <td class="text-end fw-semibold"><?= number_format($topQuantidades[$index], 0, ',', '.') ?></td>
                                    <td class="text-end text-success fw-medium">R$ <?= number_format($topValores[$index], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card p-4 chart-card bg-white h-100">
                <h5 class="mb-3">Faturamento por Produto (Top 10)</h5>
                <div style="position: relative; height: 340px;">
                    <canvas id="topProdutosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Dados vindos do PHP
const labelsDiarios = <?= json_encode($labelsDiarios, JSON_UNESCAPED_UNICODE) ?>;
const valoresDiarios = <?= json_encode($valoresDiarios) ?>;
const labelsMensais = <?= json_encode($labelsMensais, JSON_UNESCAPED_UNICODE) ?>;
const valoresMensais = <?= json_encode($valoresMensais) ?>;
const topLabels = <?= json_encode($topLabels, JSON_UNESCAPED_UNICODE) ?>;
const topValores = <?= json_encode($topValores) ?>;

// Configuração Global do Chart.js para ficar elegante
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#64748b';

new Chart(document.getElementById('vendasDiariasChart'), {
    type: 'line',
    data: {
        labels: labelsDiarios,
        datasets: [{
            label: 'Vendas',
            data: valoresDiarios,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.06)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#3b82f6',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('vendasMensaisChart'), {
    type: 'bar',
    data: {
        labels: labelsMensais,
        datasets: [{
            data: valoresMensais,
            backgroundColor: '#10b981',
            borderRadius: 6,
            maxBarThickness: 28
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('topProdutosChart'), {
    type: 'bar',
    data: {
        labels: topLabels,
        datasets: [{
            data: topValores,
            backgroundColor: '#f59e0b',
            borderRadius: 6,
            maxBarThickness: 20
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } },
            y: { grid: { display: false } }
        }
    }
});
</script>
</body>
</html>