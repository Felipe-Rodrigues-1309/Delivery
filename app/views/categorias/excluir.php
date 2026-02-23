<?php
require_once __DIR__ . '/../conexao.php';

// Verifica se o ID foi enviado
if (!isset($_POST['id'])) {
    die("ID do produto não recebido!");
}

$id = intval($_POST['id']); // segurança

// Query de exclusão
$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Redireciona de volta para a página onde lista os cards
    header("Location: excluir_card.php"); 
    exit;
} else {
    echo "Erro ao excluir: " . $stmt->error;
}
