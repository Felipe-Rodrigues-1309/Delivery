<?php
session_start();

require_once __DIR__ . '/../config/conexao.php';

// salva pra onde voltar
if (!empty($_POST['redirect'])) {
    $_SESSION['redirect_after_login'] = $_POST['redirect'];
}

if (!isset($_POST['email'], $_POST['senha'])) {
    die("Dados incompletos.");
}

$email = trim($_POST['email']);
$senhaDigitada = $_POST['senha'];

$sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();
$user = $resultado->fetch_assoc();

if ($user && password_verify($senhaDigitada, $user['senha'])) {

    $_SESSION['id_usuario'] = $user['id'];
    $_SESSION['usuario']    = $user['nome'];

if (isset($_SESSION['redirect_after_login'])) {

    $redirect = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);

    header("Location: index.php?action=$redirect");
    exit();

}   else {
    header("Location: ?action=paginaInicial");
    exit();
}

}   else {
    echo "<script>alert('Usuário ou senha incorretos!'); window.location.href='?action=login';</script>";
}

$stmt->close();
$conn->close();
