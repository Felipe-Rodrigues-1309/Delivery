<?php
require_once __DIR__ . '/../../config/conexao.php';

// 🔒 garante que só roda via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acesso inválido');
}

// ---------------------
// RECEBENDO DADOS SEGUROS
// ---------------------
$cod = $_POST['cod'] ?? null;
$produto = $_POST['produto'] ?? '';
$valor = $_POST['valor'] ?? 0;
$descricao = $_POST['descricao'] ?? '';

// adicionais nomes
for ($i = 1; $i <= 10; $i++) {
    ${"adicional_nome$i"} = $_POST["adicional_nome$i"] ?? '';
    ${"adicional_valor$i"} = $_POST["adicional_valor$i"] ?? 0;
}

// ---------------------
// UPLOAD DA IMAGEM (SEGURO)
// ---------------------
$novoNomeImagem = null;
$novoNomeImagem = null;

if (isset($_FILES['imagem'])) {
    $error = $_FILES['imagem']['error'];
    if ($error === 0) {
        $imagem = $_FILES['imagem']['name'];
        $tmp = $_FILES['imagem']['tmp_name'];

        // criar pasta
        $pastaUpload = __DIR__ . '/../../public/uploads';

        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0777, true);
        }

        // nome único
        $novoNomeImagem = uniqid() . "_" . basename($imagem);

        if (move_uploaded_file($tmp, $pastaUpload . '/' . $novoNomeImagem)) {
            echo "Imagem enviada com sucesso: " . $novoNomeImagem . "<br>";
        } else {
            $novoNomeImagem = null;
            echo "Erro ao mover o arquivo da imagem.<br>";
        }
    } else {
        echo "Erro no upload da imagem: " . $error . "<br>";
    }
} else {
    echo "Nenhuma imagem foi enviada.<br>";
}

// ---------------------
// INSERT
// ---------------------
$sql = "INSERT INTO produtos (
    cod, item, valor, descricao,
    adicional1, adicional2, adicional3, adicional4, adicional5,
    adicional6, adicional7, adicional8, adicional9, adicional10,
    valoradicional1, valoradicional2, valoradicional3, valoradicional4,
    valoradicional5, valoradicional6, valoradicional7, valoradicional8,
    valoradicional9, valoradicional10,
    imagem
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isdsssssssssssdddddddddds",
    $cod, $produto, $valor, $descricao,
    $adicional_nome1, $adicional_nome2, $adicional_nome3, $adicional_nome4,
    $adicional_nome5, $adicional_nome6, $adicional_nome7, $adicional_nome8,
    $adicional_nome9, $adicional_nome10,
    $adicional_valor1, $adicional_valor2, $adicional_valor3, $adicional_valor4,
    $adicional_valor5, $adicional_valor6, $adicional_valor7, $adicional_valor8,
    $adicional_valor9, $adicional_valor10,
    $novoNomeImagem
);

// executar
if ($stmt->execute()) {
    echo "Dados salvos com sucesso!";
} else {
    echo "Erro ao salvar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>