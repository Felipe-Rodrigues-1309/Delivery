<?php
require_once __DIR__ . "/../config/conexao.php";

class AuthController
{
    public static function login()
    {
        // só aceita POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /");
            exit;
        }

        // pegar dados com segurança
        $usuario = $_POST['usuarios'] ?? '';
        $senha   = $_POST['senha'] ?? '';

        global $conn;

        $sql = "SELECT * FROM usuarios WHERE nome = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $user = $resultado->fetch_assoc();

        // 🔐 VERIFICAÇÃO DE SENHA
        if ($user && password_verify($senha, $user['senha'])) {

            // cria sessão
            session_start();
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];

            header("Location: /app/views/produtos/cadastro.php");
            exit;
        }

        // erro login
        header("Location: /app/views/auth/login_incorreto.html");
        exit;
    }
}