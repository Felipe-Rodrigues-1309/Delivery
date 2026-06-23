<?php
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php?action=listarProdutos');
    exit;
}

$id = intval($_POST['id'] ?? 0);
if (!$id) {
    header('Location: ../../public/index.php?action=listarProdutos&erro=ID+inv%C3%A1lido');
    exit;
}

$sqlImagem = "SELECT imagem FROM produtos WHERE id = ?";
$stmtImagem = $conn->prepare($sqlImagem);
$stmtImagem->bind_param('i', $id);
$stmtImagem->execute();
$resultImagem = $stmtImagem->get_result();
$produto = $resultImagem->fetch_assoc();

if ($produto && !empty($produto['imagem'])) {
    $arquivo = __DIR__ . '/../../../public/uploads/' . $produto['imagem'];
    if (file_exists($arquivo)) {
        unlink($arquivo);
    }
}

$sql = "DELETE FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: ../../public/index.php?action=listarProdutos&sucesso=Produto+exclu%C3%ADdo+com+sucesso');
} else {
    header('Location: ../../public/index.php?action=listarProdutos&erro=Erro+ao+excluir+produto');
}
exit;
?>