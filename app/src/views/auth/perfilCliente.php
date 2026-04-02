<?php
require_once __DIR__ . '/../../config/conexao.php';

session_start();

$id = $_SESSION['id_usuario'] ?? null; // usado para pegar o uduario da seccão e colocar para aparrecer no front

$dados = null;

if ($id) {  // vaz a busca do usuario no banco para exibir oque for necessario no front
    $stmt = $conn->prepare("SELECT usuario, rua FROM endereco WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $dados = $result->fetch_assoc();
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
<body>
    <style>
    .minha-navbar {
    background: linear-gradient(#0000000b);
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
    </style>

<nav class="navbar bg-body-tertiary fixed-top minha-navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><h5>Olá <?= $dados['usuario'] ?? 'Visitante'; ?> !</h5> <!---usado para mostrar no fronto  o nome vindo do bancp--> </a>
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
<!--inicio cards-->
 <div class="card">
  <div class="card-body">
    This is some text within a card body.
  </div>
</div>
</body>
</html>