<?php
require_once __DIR__ . '/../../config/conexao.php';
$sql = "SELECT * FROM categorias ORDER BY id DESC";
$resultadoCategorias = $conn->query($sql);
?> 


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Admin Delivery - Cadastro de Produto</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: #000000;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      height: 100vh;
      position: fixed;
      background: #190268;
      color: white;
      padding: 20px;
    }

    .sidebar h3 {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 10px;
      text-decoration: none;
      border-radius: 8px;
    }

    .sidebar a:hover {
      background: #1f2937;
      color: #fff;
    }

    /* Conteúdo */
    .content {
      margin-left: 260px;
      padding: 20px;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .grupo {
      display: none;
      margin-top: 15px;
    }

    .linha {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }

    input, select {
      border-radius: 8px !important;
    }

    .btn-save {
      background: #22c55e;
      color: white;
      padding: 10px 20px;
      border-radius: 10px;
      border: none;
    }

    .btn-save:hover {
      background: #16a34a;
    }
  </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h3>🍔 Delivery Admin</h3>
  <a href="?action=pedidos">Pedidos</a>  
  <a href="?action=cadastroDeProduto">Cadastro de Produtos</a>
  <a href="?action=dashboard">Dashboard</a>
  <a href="?action=listarProdutos">Produto</a>

</div>

<!-- CONTEÚDO -->
<div class="content">

  <div class="card p-4">
    <h2 class="mb-4">Cadastro de Produto</h2>

    <form action="index.php?action=enviarProduto" method="post" enctype="multipart/form-data">

      <div class="row">
        <div class="col-md-2">
          <label>Código</label>
          <input class="form-control" type="text" name="cod" required>
        </div>

        <div class="col-md-4">
          <label>Produto</label>
          <input class="form-control" type="text" name="produto" required>
        </div>

        <div class="col-md-3">
          <label>Categoria</label>

  <select class="form-select" name="categoria">
    <option selected disabled>Selecione uma categoria</option>

    <?php while($categoria = $resultadoCategorias->fetch_assoc()): ?>
      <option value="<?= $categoria['id'] ?>">
        <?= $categoria['nome'] ?>
      </option>
    <?php endwhile; ?>

  </select>
</div>

        <div class="col-md-3">
          <label>Valor</label>
          <input class="form-control" type="number" name="valor" step="0.01" required>
        </div>
      </div>

      <div class="mt-3">
        <label>Descrição</label>
        <input class="form-control" type="text" name="descricao"required>
      </div>

      <div class="mt-3">
        <label>Imagem do Produto</label>
        <input class="form-control" type="file" name="imagem">
      </div>

      <hr>

      <h5>Adicionais</h5>

      <select class="form-select" id="selectAdd" onchange="mostrar()">
        <option value="">Selecione...</option>
        <option value="adicionais">Adicionar complementos</option>
      </select>

      <div id="adicionais" class="grupo mt-3">

        <div class="linha">
          <input class="form-control" type="text" name="adicional_nome1" placeholder="Nome">
          <input class="form-control" type="number" name="adicional_valor1" placeholder="Valor">
        </div>

        <div class="linha">
          <input class="form-control" type="text" name="adicional_nome2" placeholder="Nome">
          <input class="form-control" type="number" name="adicional_valor2" placeholder="Valor">
        </div>

        <div class="linha">
          <input class="form-control" type="text" name="adicional_nome3" placeholder="Nome">
          <input class="form-control" type="number" name="adicional_valor3" placeholder="Valor">
        </div>

      </div>

      <div class="mt-4">
        <button class="btn-save" type="submit">Salvar Produto</button>
      </div>

    </form>
  </div>

</div>

<script>
function mostrar() {
  document.getElementById("adicionais").style.display =
    document.getElementById("selectAdd").value === "adicionais"
      ? "block"
      : "none";
}
</script>

</body>
</html>