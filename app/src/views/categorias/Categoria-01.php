<?php
require_once __DIR__ . '/../../config/conexao.php';

/**
 * 1. BUSCA TODAS AS CATEGORIAS (1 QUERY)
 */
$sqlCategorias = "SELECT * FROM categorias ORDER BY id DESC";
$resultCategorias = $conn->query($sqlCategorias);

$categorias = [];
while ($cat = $resultCategorias->fetch_assoc()) {
    $cat['produtos'] = []; // espaço para produtos
    $categorias[$cat['id']] = $cat;
}

/**
 * 2. BUSCA TODOS OS PRODUTOS (1 QUERY)
 */
$sqlProdutos = "SELECT * FROM produtos ORDER BY id DESC";
$resultProdutos = $conn->query($sqlProdutos);

/**
 * 3. AGRUPA PRODUTOS POR CATEGORIA
 */
while ($prod = $resultProdutos->fetch_assoc()) {
    $categorias[$prod['categoria_id']]['produtos'][] = $prod;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Categoria 01</title>

  <!-- Bootstrap (pode manter CDN ou baixar local depois) -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />

  <link rel="stylesheet" href="css/Categoria-01.css" />
</head>

<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
  <div class="container-fluid">
    <a class="navbar-brand" href="./carrinho.php">Logo</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="navbar-nav">
        <a class="nav-link active" href="../../Pagina_inicial/Pagina_inicial.html">Página Inicial</a>
        <a class="nav-link active" href="?action=perfilCliente">Pedidos</a>
        <a class="nav-link active" href="?action=carinho">Carrinho</a>
      </div>
    </div>
  </div>
</nav>

<!-- ===== NAVEGAÇÃO DE CATEGORIAS ===== -->
<div class="container-categorias">
  <button class="btn-seta" onclick="scrollCategorias(-250)">&#10094;</button>

  <div class="lista-categorias" id="navCategoria">
    <?php foreach ($categorias as $cat): ?>
      <a href="#categoria-<?= $cat['id'] ?>" class="navCategoria">
        <?= htmlspecialchars($cat['nome']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <button class="btn-seta" onclick="scrollCategorias(250)">&#10095;</button>
</div>

<!-- ===== CATEGORIAS + PRODUTOS ===== -->
<?php foreach ($categorias as $cat): ?>

  <div id="categoria-<?= $cat['id'] ?>" class="categoria">
    <?= htmlspecialchars($cat['nome']) ?>
  </div>

  <div class="row g-3">

    <?php foreach ($cat['produtos'] as $row): ?>
      <div class="col-5 col-md-2">
        <a href="index.php?action=produto&id=<?= $row['id'] ?>" class="produto-link">

          <div class="produto1">

            <div class="imagemProduto">
              <img 
                loading="lazy"
                src="uploads/<?= htmlspecialchars($row['imagem']) ?>"
                alt="<?= htmlspecialchars($row['item']) ?>"
              >
            </div>

            <div class="item">
              <?= htmlspecialchars($row['item']) ?>
            </div>

            <div class="valor">
              R$ <?= number_format($row['valor'], 2, ",", ".") ?>
            </div>

          </div>

        </a>
      </div>
    <?php endforeach; ?>

  </div>

<?php endforeach; ?>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
function scrollCategorias(valor) {
  document.getElementById('navCategoria').scrollBy({
    left: valor,
    behavior: 'smooth'
  });
}
</script>

</body>
</html>