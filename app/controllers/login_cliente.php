
<?php
session_start();

require_once __DIR__ . '/../../config/conexao.php';

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

    header("Location: ../Pagina_inicial/Pagina_inicial.html");
    exit();

} else {
    echo "<script>alert('Usuário ou senha incorretos!'); window.location.href='../pagina_de_login.html';</script>";
}

$stmt->close();
$conn->close();
