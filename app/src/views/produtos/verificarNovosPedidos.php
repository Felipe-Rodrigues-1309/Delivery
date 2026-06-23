<?php
require_once __DIR__ . '/../../config/conexao.php';

header('Content-Type: application/json');

// Buscar quantos pedidos novos existem
$sql = "SELECT COUNT(*) as total FROM pedido WHERE status IS NULL OR status = ''";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    'novosPedidos' => $row['total'] ?? 0,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>
