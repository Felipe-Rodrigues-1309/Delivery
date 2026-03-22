<?php
require_once __DIR__ . '/../../config/conexao.php';

session_start();
    // aceita somente POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    
    // PEGA O ID E USUARIO LOGADO
    $id_usuario = $_SESSION['id_usuario'] ?? null;
    $usuario = $_SESSION['usuario'] ?? '';

    // verifica se o usuario esta logado se não tiver da erro
    if(!$id_usuario){
        die ("usuario não esta logado");
    }


    // pega os dados do front
    $rua = $_POST['rua'] ?? '';   // ?? ''usado para verificar se a variavel existe 
    $numero = $_POST['numero'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
    $ponto_de_referencia = $_POST['ponto_de_referencia'] ?? '';

    // validação dos campos obrigatorios
    if (empty($rua) || empty($numero) || empty($cidade)) {
        die("Preencha os campos obrigatórios!");
    }
    //insere no banco
    $sql = "INSERT INTO endereco (id, usuario, rua, numero, bairro, cidade, ponto_de_referencia)
            VALUES (?,?,?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("issssss", 
        $id_usuario,
        $usuario,
        $rua, 
        $numero, 
        $bairro, 
        $cidade, 
        $ponto_de_referencia
    );

    if ($stmt->execute()) {
        echo "Dados salvos com sucesso!";
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>