<?php
include '../conexao.php';

// Recebe dados comuns
$cod = $_POST['cod'];
$produto = $_POST['produto'];
$valor = $_POST['valor'];
$descricao = $_POST['descricao'];

$adicional_nome1 = $_POST['adicional_nome1'];
$adicional_nome2 = $_POST['adicional_nome2'];
$adicional_nome3 = $_POST['adicional_nome3'];
$adicional_nome4 = $_POST['adicional_nome4'];
$adicional_nome5 = $_POST['adicional_nome5'];
$adicional_nome6 = $_POST['adicional_nome6'];
$adicional_nome7 = $_POST['adicional_nome7'];
$adicional_nome8 = $_POST['adicional_nome8'];
$adicional_nome9 = $_POST['adicional_nome9'];
$adicional_nome10 = $_POST['adicional_nome10'];

$adicional_valor1 = $_POST['adicional_valor1'];
$adicional_valor2 = $_POST['adicional_valor2'];
$adicional_valor3 = $_POST['adicional_valor3'];
$adicional_valor4 = $_POST['adicional_valor4'];
$adicional_valor5 = $_POST['adicional_valor5'];
$adicional_valor6 = $_POST['adicional_valor6'];
$adicional_valor7 = $_POST['adicional_valor7'];
$adicional_valor8 = $_POST['adicional_valor8'];
$adicional_valor9 = $_POST['adicional_valor9'];
$adicional_valor10 = $_POST['adicional_valor10'];


// ---------------------
// UPLOAD DA IMAGEM
// ---------------------
$imagem = $_FILES['imagem']['name'];
$tmp = $_FILES['imagem']['tmp_name'];

// Criar pasta caso não exista
if (!is_dir("../uploads")) {
    mkdir("../uploads", 0777, true);
}

// Gerar nome único
$novoNomeImagem = uniqid() . "_" . $imagem;

// Mover arquivo
move_uploaded_file($tmp, "../uploads/" . $novoNomeImagem);


// ---------------------
// QUERY COM CAMPO IMAGEM
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


// Executa
if ($stmt->execute()) {
    echo "Dados salvos com sucesso!";
} else {
    echo "Erro ao salvar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
