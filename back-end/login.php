<?php
include '../conexao.php'; // acessa o arquivo que faz a conexão com o banco 

$usuario = $_POST['usuarios']; // pega o valor do campo 'usuario' enviado pelo formulário via POST
$senha = $_POST['senha']; // pega o valor do campo 'senha' enviado pelo formulário via POST

$sql = "SELECT * FROM usuarios WHERE nome = ?"; // Prepara a consulta SQL para buscar o usuário no banco de dados (? evita SQL Injection)
$stmt = $conn->prepare($sql); // Prepara a declaração SQL para execução segura
$stmt->bind_param("s", $usuario); // Vincula o parâmetro $usuario ao placeholder "?" como string
$stmt->execute(); // Executa a consulta preparada com o valor do usuário
$resultado = $stmt->get_result(); // Obtém o resultado da consulta
$user = $resultado->fetch_assoc(); // Busca o primeiro registro do resultado como um array associativo

if ($user && $senha == $user['senha']) { // Verifica se o usuário foi encontrado no banco E se a senha digitada é igual à senha armazenada
    header("Location: ../Cadastro_de_produto/cadastro.php"); // Redireciona para a página inicial após login bem-sucedido
    exit(); // Encerra o script após o redirecionamento
} else {
    header("Location: ../login_incorreto.html");  // Se o usuário não existir ou a senha estiver incorreta, exibe mensagem de erro
}

$stmt->close(); // Fecha o statement/declaração preparada, liberando os recursos
$conn->close(); // Fecha a conexão com o banco de dados
?>
