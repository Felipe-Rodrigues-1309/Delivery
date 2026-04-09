<?php
require_once __DIR__ . '/../../config/conexao.php';

session_start();

$id = $_SESSION['id_usuario'] ?? null; // usado para pegar o uduario da seccão e colocar para aparrecer no front

$usuario = null;  // usado para criar uma varivael para ser possivel fazer os dados do banco virem para o front
$pedidos = null;

if ($id) {  // vaz a busca do usuario no banco para exibir oque for necessario no front
    $stmt = $conn->prepare("SELECT usuario, rua, numero, bairro, cidade, ponto_de_referencia FROM endereco WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultUsuario = $stmt->get_result(); // usado para dar o resultado da consulta executada
    $usuario = $resultUsuario->fetch_assoc();  // resultUsuario não pode se repetir em outro if
}


if ($id) {  // faz a busca do usuario no banco para exibir oque for necessario no front
    $stmt = $conn->prepare("SELECT id, usuario, item, valor, data_pedido, status FROM pedido WHERE usuario = ? ORDER BY id DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultPedido = $stmt->get_result();
    //$pedidos= $resultPedido->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
</head>
<body class="pt-5">
    <style>
    body{
      background-color:#030604;
    }
    .titulo{
      color:white;
      margin-top:25px;
      text-align: center;
    }
    .card{
      border:solid 2px #00ff00;
      border-radius: 15px;
      margin: 5px;
      background-color: rgba(0, 0, 0, 0.59);
      color:white;
    }

    .minha-navbar {
    background: linear-gradient(#00ff00);
    box-shadow: 0 10px 10px rgba(0, 255, 0, 0.57);
}
    </style>

<nav class="navbar bg-body-tertiary fixed-top minha-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><h5>Olá <?= $usuario['usuario'] ?? 'Visitante'; ?> !</h5> <!---usado para mostrar no fronto  o nome vindo do bancp--> </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="#">Alterar Endereço</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class = "nav-link" href="php?action=login">Sair</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
<h5 class="titulo">Acompanhe Seu Pedido</h5>


<!--inicio cards-->
<?php while($pedidos = $resultPedido->fetch_assoc()): ?>  <!-- while faz buscar todos os dados da tabela a criar em novos cards-->
 <div class="card">
  <div class="card-body">
    <div class="id">
      <h5>#ID-<?= $pedidos['id'] ?? 'vazio';?></h5>

      <h5><div class="data">Data: <?= $pedidos['data_pedido'] ?? 'vazio'; ?></div></h5>
      
      <h6><div class="endereco">
        Endereço: <?= $usuario['rua'] ?? 'vazio';?>,
        Nº<?= $usuario['numero'] ?? 'vazio';?>,
        <?= $usuario['ponto_de_referencia'] ?? 'vazio';?>
        <?= $usuario['bairro'] ?? 'vazio';?>,
        <?= $usuario['cidade'] ?? 'vazio';?></h6>
      </div>

      <div class="pedido">
        <?= $pedidos['item'] ?? 'vazio'; ?>
        <div class="total">
          Valor total: R$ <?= $pedidos['valor'] ?? 'vazio'; ?>
          <div class="status">Status: <?= $pedidos['status'] ?? 'vazio'; ?></div>
        </div>
      </div>
    </div>
  </div>
  </div>
<?php endwhile; ?>
</body>
</html>