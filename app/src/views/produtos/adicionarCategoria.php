<?php
require_once __DIR__ . '/../../config/conexao.php';

$categorias = $_POST['categorias'] ?? '';

$sql = "INSERT INTO categorias (nome) VALUES (?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s",$categorias);

if ($stmt->execute()) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    echo "<script>alert('Erro ao cadastrar usuário.'); window.history.back();</script>";
}


$stmt->close();
$conn->close();
?>
