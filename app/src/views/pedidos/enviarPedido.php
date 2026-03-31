<?php
// Conexão com o banco (mysqli)
// O arquivo conexao.php define $conn (mysqli) e já configura usuário, senha, hostname e database.
require_once __DIR__ . '/../../config/conexao.php';

// A resposta para a chamada AJAX espera JSON.
// Isso ajuda o front-end (JS) a ler facilmente se o pedido foi salvo com sucesso.
header('Content-Type: application/json; charset=utf-8');

// 🔒 Proteção: só aceitaremos chamadas via POST.
// Isso evita que alguém acesse a URL diretamente via GET e ainda assim gere um pedido.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Acesso inválido']);
    exit;
}

// ===============================
// 1) Validação dos dados recebidos
// ===============================
// A página carrinho/index.php envia estes campos:
// - id_usuario: ID do usuário logado (da sessão)
// - produto: nome ou descrição do(s) produto(s) pedidos
// - valor: total do pedido (string ou número)
//
// Aqui garantimos que eles existam e façamos uma conversão segura.
// intval() / floatval() evitam injeção direta e garantem tipos previsíveis.
$id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : null;
$produto = isset($_POST['produto']) ? trim($_POST['produto']) : null;
$valor = isset($_POST['valor']) ? floatval(str_replace(',', '.', $_POST['valor'])) : null;

// Checamos se todos os dados obrigatórios estão presentes.
// Se algo estiver faltando, retornamos erro imediatamente.
if (!$id_usuario || !$produto || $valor === null) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// ===============================
// 2) Preparar dados para inserir
// ===============================
// Geramos timestamp para saber quando o pedido foi feito.
$data_pedido = date("Y-m-d H:i:s");

// Query preparada para evitar injeção SQL.
// Aqui salvamos o nome/descrição do(s) produto(s) em vez da mensagem completa do WhatsApp.
$sql = "INSERT INTO pedido (usuario, item, valor, data_pedido) VALUES (?, ?, ?, ?)";

// Preparação do statement com binding de parâmetros:
// i = inteiro, s = string, d = número (double)
$stmt = $conn->prepare($sql);
$stmt->bind_param("isds", $id_usuario, $produto, $valor, $data_pedido);

// ===============================
// 3) Execução e resposta JSON
// ===============================
if ($stmt->execute()) {
    // Envia um JSON simples que o JS pode interpretar.
    echo json_encode(['success' => true, 'message' => 'Pedido salvo com sucesso']);
    exit;
} else {
    // Qualquer falha na inserção retorna mensagem de erro.
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar pedido']);
}



































