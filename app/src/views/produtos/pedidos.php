<?php
require_once __DIR__ . '/../../config/conexao.php';

// Adicionar status aos pedidos se atualizados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = intval($_POST['id']);
        $status = $_POST['status'];
        
        $sql = "UPDATE pedido SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        
        if ($stmt->execute()) {
            $mensagem = "Status atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar status!";
        }
    }
}

// Buscar pedidos
$sql = "SELECT * FROM pedido ORDER BY data_pedido DESC";
$result = $conn->query($sql);

$pedidosNovos = [];
$pedidosEmPreparo = [];
$pedidosSaiuEntrega = [];
$pedidosConcluidos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = strtolower(trim($row['status'] ?? ''));
        
        if ($status === '' || $status === null) {
            $pedidosNovos[] = $row;
        } elseif ($status === 'em preparo') {
            $pedidosEmPreparo[] = $row;
        } elseif ($status === 'saiu para entrega') {
            $pedidosSaiuEntrega[] = $row;
        } elseif ($status === 'concluído' || $status === 'entregue') {
            $pedidosConcluidos[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <style>

        body{
            background: #020024
        }
        .pedido-card{
            color:white;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(15px);
            box-shadow: 0 1px 15px #00ff00;
            border: 2px solid #00ff00;
            margin-bottom: 30px;
        }
        @media print {
            .btn, .no-print { display: none; }
            body { padding: 0; }
            .pedido-card { page-break-inside: avoid; }
        }
    </style>
</head>
<body>


<div class="container mt-4">
<h2 style="color:white;">📋Pedidos</h2>
    
    <?php if (isset($mensagem)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Abas -->
    <ul class="nav nav-tabs mb-4 no-print" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="novos-tab" data-bs-toggle="tab" data-bs-target="#novos" type="button">
                🔴 Novos <span class="badge bg-danger" id="novosPedidosBadge"><?= count($pedidosNovos) ?></span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="preparo-tab" data-bs-toggle="tab" data-bs-target="#preparo" type="button">
                👨‍🍳 Em Preparo (<?= count($pedidosEmPreparo) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="entrega-tab" data-bs-toggle="tab" data-bs-target="#entrega" type="button">
                🚚 Saiu para Entrega (<?= count($pedidosSaiuEntrega) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="concluido-tab" data-bs-toggle="tab" data-bs-target="#concluido" type="button">
                ✅ Concluído (<?= count($pedidosConcluidos) ?>)
            </button>
        </li>
    </ul>

    <!-- Conteúdo das Abas -->
    <div class="tab-content">
        
        <!-- Pedidos Novos -->
        <div class="tab-pane fade show active" id="novos" role="tabpanel">
            <?php if (count($pedidosNovos) > 0): ?>
                <?php foreach ($pedidosNovos as $pedido): ?>
                    <div class="pedido-card card" id="pedido-<?= $pedido['id'] ?>">
                        <div class="card-header bg-danger text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">
                                        <span class="badge bg-light text-dark">NOVO</span>
                                        <strong>Pedido #<?= $pedido['id'] ?></strong>
                                        <small><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></small>
                                    </h6>
                                </div>
                                <div class="col-auto no-print">
                                    <button class="btn btn-sm btn-light" onclick="imprimirPedido(<?= $pedido['id'] ?>)">🖨️ Imprimir</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>👤 Cliente:</strong> <?= htmlspecialchars($pedido['nome'] ?? 'N/A') ?></p>
                                    <p><strong>📞 Telefone:</strong> <?= htmlspecialchars($pedido['telefone_cliente'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>💳 Pagamento:</strong> 
                                        <span class="badge bg-info"><?= htmlspecialchars($pedido['pagamento'] ?? 'Não especificado') ?></span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>📍 Endereço de Entrega:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <?php if ($pedido['rua'] || $pedido['numero'] || $pedido['bairro'] || $pedido['cidade']): ?>
                                        <p class="mb-1">
                                            <?= htmlspecialchars($pedido['rua'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['numero'] ?? '') ?> - 
                                            <?= htmlspecialchars($pedido['bairro'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['cidade'] ?? '') ?>
                                        </p>
                                        <?php if ($pedido['ponto_de_referencia']): ?>
                                            <p class="mb-0"><small>Referência: <?= htmlspecialchars($pedido['ponto_de_referencia']) ?></small></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Endereço não informado</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>🛒 Produtos:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <p><?= htmlspecialchars($pedido['item'] ?? 'Sem descrição') ?></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>💰 Total: <span class="text-success">R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></span></h5>
                                </div>
                                <div class="col-auto no-print">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="">Mudar status...</option>
                                            <option value="em preparo">👨‍🍳 Em Preparo</option>
                                            <option value="saiu para entrega">🚚 Saiu para Entrega</option>
                                            <option value="concluído">✅ Concluído</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary ms-2">Atualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pedido novo no momento! 🎉</div>
            <?php endif; ?>
        </div>

        <!-- Pedidos Em Preparo -->
        <div class="tab-pane fade" id="preparo" role="tabpanel">
            <?php if (count($pedidosEmPreparo) > 0): ?>
                <?php foreach ($pedidosEmPreparo as $pedido): ?>
                    <div class="pedido-card card" id="pedido-<?= $pedido['id'] ?>">
                        <div class="card-header bg-warning">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">
                                        <span class="badge bg-dark">EM PREPARO</span>
                                        <strong>Pedido #<?= $pedido['id'] ?></strong>
                                        <small><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></small>
                                    </h6>
                                </div>
                                <div class="col-auto no-print">
                                    <button class="btn btn-sm btn-light" onclick="imprimirPedido(<?= $pedido['id'] ?>)">🖨️ Imprimir</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>👤 Cliente:</strong> <?= htmlspecialchars($pedido['nome'] ?? 'N/A') ?></p>
                                    <p><strong>📞 Telefone:</strong> <?= htmlspecialchars($pedido['telefone_cliente'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>💳 Pagamento:</strong> 
                                        <span class="badge bg-info"><?= htmlspecialchars($pedido['pagamento'] ?? 'Não especificado') ?></span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>📍 Endereço de Entrega:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <?php if ($pedido['rua'] || $pedido['numero'] || $pedido['bairro'] || $pedido['cidade']): ?>
                                        <p class="mb-1">
                                            <?= htmlspecialchars($pedido['rua'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['numero'] ?? '') ?> - 
                                            <?= htmlspecialchars($pedido['bairro'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['cidade'] ?? '') ?>
                                        </p>
                                        <?php if ($pedido['ponto_de_referencia']): ?>
                                            <p class="mb-0"><small>Referência: <?= htmlspecialchars($pedido['ponto_de_referencia']) ?></small></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Endereço não informado</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>🛒 Produtos:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <p><?= htmlspecialchars($pedido['item'] ?? 'Sem descrição') ?></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>💰 Total: <span class="text-success">R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></span></h5>
                                </div>
                                <div class="col-auto no-print">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="">Mudar status...</option>
                                            <option value="saiu para entrega">🚚 Saiu para Entrega</option>
                                            <option value="concluído">✅ Concluído</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary ms-2">Atualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pedido em preparo no momento.</div>
            <?php endif; ?>
        </div>

        <!-- Pedidos Saiu para Entrega -->
        <div class="tab-pane fade" id="entrega" role="tabpanel">
            <?php if (count($pedidosSaiuEntrega) > 0): ?>
                <?php foreach ($pedidosSaiuEntrega as $pedido): ?>
                    <div class="pedido-card card" id="pedido-<?= $pedido['id'] ?>">
                        <div class="card-header bg-info text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">
                                        <span class="badge bg-light text-dark">SAIU PARA ENTREGA</span>
                                        <strong>Pedido #<?= $pedido['id'] ?></strong>
                                        <small><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></small>
                                    </h6>
                                </div>
                                <div class="col-auto no-print">
                                    <button class="btn btn-sm btn-light" onclick="imprimirPedido(<?= $pedido['id'] ?>)">🖨️ Imprimir</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>👤 Cliente:</strong> <?= htmlspecialchars($pedido['nome'] ?? 'N/A') ?></p>
                                    <p><strong>📞 Telefone:</strong> <?= htmlspecialchars($pedido['telefone_cliente'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>💳 Pagamento:</strong> 
                                        <span class="badge bg-secondary"><?= htmlspecialchars($pedido['pagamento'] ?? 'Não especificado') ?></span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>📍 Endereço de Entrega:</strong>
                                <div class="bg-dack p-2 rounded mt-2">
                                    <?php if ($pedido['rua'] || $pedido['numero'] || $pedido['bairro'] || $pedido['cidade']): ?>
                                        <p class="mb-1">
                                            <?= htmlspecialchars($pedido['rua'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['numero'] ?? '') ?> - 
                                            <?= htmlspecialchars($pedido['bairro'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['cidade'] ?? '') ?>
                                        </p>
                                        <?php if ($pedido['ponto_de_referencia']): ?>
                                            <p class="mb-0"><small>Referência: <?= htmlspecialchars($pedido['ponto_de_referencia']) ?></small></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Endereço não informado</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>🛒 Produtos:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <p><?= htmlspecialchars($pedido['item'] ?? 'Sem descrição') ?></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>💰 Total: <span class="text-success">R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></span></h5>
                                </div>
                                <div class="col-auto no-print">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="">Mudar status...</option>
                                            <option value="concluído">✅ Concluído</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary ms-2">Atualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pedido saiu para entrega no momento.</div>
            <?php endif; ?>
        </div>

        <!-- Pedidos Concluídos -->
        <div class="tab-pane fade" id="concluido" role="tabpanel">
            <?php if (count($pedidosConcluidos) > 0): ?>
                <?php foreach ($pedidosConcluidos as $pedido): ?>
                    <div class="pedido-card card" id="pedido-<?= $pedido['id'] ?>">
                        <div class="card-header bg-success text-white">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="mb-0">
                                        <span class="badge bg-light text-dark">CONCLUÍDO</span>
                                        <strong>Pedido #<?= $pedido['id'] ?></strong>
                                        <small><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></small>
                                    </h6>
                                </div>
                                <div class="col-auto no-print">
                                    <button class="btn btn-sm btn-light" onclick="imprimirPedido(<?= $pedido['id'] ?>)">🖨️ Imprimir</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>👤 Cliente:</strong> <?= htmlspecialchars($pedido['nome'] ?? 'N/A') ?></p>
                                    <p><strong>📞 Telefone:</strong> <?= htmlspecialchars($pedido['telefone_cliente'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>💳 Pagamento:</strong> 
                                        <span class="badge bg-secondary"><?= htmlspecialchars($pedido['pagamento'] ?? 'Não especificado') ?></span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>📍 Endereço de Entrega:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <?php if ($pedido['rua'] || $pedido['numero'] || $pedido['bairro'] || $pedido['cidade']): ?>
                                        <p class="mb-1">
                                            <?= htmlspecialchars($pedido['rua'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['numero'] ?? '') ?> - 
                                            <?= htmlspecialchars($pedido['bairro'] ?? '') ?>, 
                                            <?= htmlspecialchars($pedido['cidade'] ?? '') ?>
                                        </p>
                                        <?php if ($pedido['ponto_de_referencia']): ?>
                                            <p class="mb-0"><small>Referência: <?= htmlspecialchars($pedido['ponto_de_referencia']) ?></small></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Endereço não informado</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <strong>🛒 Produtos:</strong>
                                <div class="bg-darck p-2 rounded mt-2">
                                    <p><?= htmlspecialchars($pedido['item'] ?? 'Sem descrição') ?></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>💰 Total: <span class="text-success">R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">Nenhum pedido concluído ainda.</div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
    // Função para imprimir apenas o pedido clicado
    function imprimirPedido(pedidoId) {
        const pedido = document.getElementById('pedido-' + pedidoId);
        if (!pedido) return;

        const conteudoOriginal = document.body.innerHTML;
        document.body.innerHTML = pedido.innerHTML;
        window.print();
        document.body.innerHTML = conteudoOriginal;
        
        // Reinicializar eventlisteners após restaurar conteúdo
        location.reload();
    }

    let ultimoNumeroPedidos = <?= count($pedidosNovos) ?>;
    let temNotificacaoLida = false;

    // Solicitar permissão de notificação
    function pedirPermissaoNotificacao() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Tocar som de notificação
    function tocarSomNotificacao() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Som de "ding" - frequência aguda
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 1000; // Hz
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
        
        // Segundo som mais grave
        setTimeout(() => {
            const osc2 = audioContext.createOscillator();
            const gain2 = audioContext.createGain();
            
            osc2.connect(gain2);
            gain2.connect(audioContext.destination);
            
            osc2.frequency.value = 1500;
            osc2.type = 'sine';
            
            gain2.gain.setValueAtTime(0.3, audioContext.currentTime);
            gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            osc2.start(audioContext.currentTime);
            osc2.stop(audioContext.currentTime + 0.5);
        }, 300);
    }

    // Mostrar notificação do navegador
    function mostrarNotificacao(titulo, opcoes = {}) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(titulo, {
                icon: '🔔',
                badge: '🔔',
                ...opcoes
            });
        }
    }

    // Verificar novos pedidos periodicamente
    function verificarNovosPedidos() {
        fetch('index.php?action=verificarNovosPedidos')
            .then(response => response.json())
            .then(data => {
                const novosPedidos = data.novosPedidos;
                
                // Se aumentou o número de pedidos, notificar
                if (novosPedidos > ultimoNumeroPedidos) {
                    const pedidosAdicionados = novosPedidos - ultimoNumeroPedidos;
                    
                    // Tocar som
                    tocarSomNotificacao();
                    
                    // Mostrar notificação do navegador
                    mostrarNotificacao(
                        `🎉 ${pedidosAdicionados} NOVO${pedidosAdicionados > 1 ? 'S' : ''} PEDIDO${pedidosAdicionados > 1 ? 'S' : ''}!`,
                        {
                            body: `Total de ${novosPedidos} pedido${novosPedidos > 1 ? 's' : ''} não processado${novosPedidos > 1 ? 's' : ''}`
                        }
                    );
                    
                    // Animar o badge de novos pedidos
                    const badge = document.getElementById('novosPedidosBadge');
                    if (badge) {
                        badge.style.animation = 'none';
                        setTimeout(() => {
                            badge.textContent = novosPedidos;
                            badge.style.animation = 'pulse 1s';
                        }, 10);
                    }
                    
                    // Recarregar a página após 2 segundos
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
                
                ultimoNumeroPedidos = novosPedidos;
            })
            .catch(err => console.log('Erro ao verificar pedidos:', err));
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', () => {
        pedirPermissaoNotificacao();
        
        // Verificar a cada 10 segundos
        setInterval(verificarNovosPedidos, 10000);
    });

    // Estilo para animação de pulse
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        #novosPedidosBadge {
            display: inline-block;
        }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>
