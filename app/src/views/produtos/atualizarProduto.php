<?php
require_once __DIR__ . '/../../config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php?action=listarProdutos');
    exit;
}

$id = intval($_POST['id'] ?? 0);
$cod = trim($_POST['cod'] ?? '');
$item = trim($_POST['item'] ?? '');
$valor = floatval($_POST['valor'] ?? 0);
$descricao = trim($_POST['descricao'] ?? '');
$categoria = intval($_POST['categoria'] ?? 0);
$imagemAtual = trim($_POST['imagem_atual'] ?? '');

if (!$id || !$cod || !$item || !$valor || !$categoria) {
    header('Location: ../../public/index.php?action=listarProdutos&erro=Preencha+todos+os+campos+obrigat%C3%B3rios');
    exit;
}

$novoNomeImagem = $imagemAtual;

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $imagem = $_FILES['imagem']['name'];
    $tmp = $_FILES['imagem']['tmp_name'];
    $pastaUpload = __DIR__ . '/../../../public/uploads';

    if (!is_dir($pastaUpload)) {
        mkdir($pastaUpload, 0777, true);
    }

    $novoNomeImagem = uniqid() . '_' . basename($imagem);

    if (move_uploaded_file($tmp, $pastaUpload . '/' . $novoNomeImagem)) {
        if ($imagemAtual && file_exists($pastaUpload . '/' . $imagemAtual)) {
            unlink($pastaUpload . '/' . $imagemAtual);
        }
    } else {
        $novoNomeImagem = $imagemAtual;
    }
}

$sql = "UPDATE produtos SET cod = ?, item = ?, valor = ?, descricao = ?, categoria_id = ?, imagem = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isdsssi', $cod, $item, $valor, $descricao, $categoria, $novoNomeImagem, $id);

if ($stmt->execute()) {
    header('Location: ../../public/index.php?action=listarProdutos&sucesso=Produto+atualizado+com+sucesso');
} else {
    header('Location: ../../public/index.php?action=listarProdutos&erro=Erro+ao+atualizar+produto');
}
exit;
?>