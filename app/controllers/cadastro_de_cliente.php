<?php
require_once __DIR__ . '/../../config/conexao.php';

$usuario = trim($_POST['usuario'] ?? '');
$email   = trim($_POST['email'] ?? '');
$senha   = $_POST['senha'] ?? '';

// validação
if ($usuario === "" || $email === "" || $senha === "") {
    echo "<script>alert('Usuário, e-mail e senha são obrigatórios.'); window.history.back();</script>";
    exit();
}

// valida e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('E-mail inválido.'); window.history.back();</script>";
    exit();
}

// criptografa senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// verifica se o e-mail já existe
$sqlCheck = "SELECT id FROM usuarios WHERE email = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $email);
$stmtCheck->execute();
$stmtCheck->store_result();

if ($stmtCheck->num_rows > 0) {
    echo "<script>alert('Este e-mail já está cadastrado.'); window.history.back();</script>";
    exit();
}

// insere usuário
$sql = "INSERT INTO usuarios (nome, senha, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $usuario, $senhaHash, $email);

if ($stmt->execute()) {
    echo "<script>alert('Usuário cadastrado com sucesso!'); window.location.href='../pagina_de_login.html';</script>";
} else {
    echo "<script>alert('Erro ao cadastrar usuário.'); window.history.back();</script>";
}

$stmtCheck->close();
$stmt->close();
$conn->close();
?>
