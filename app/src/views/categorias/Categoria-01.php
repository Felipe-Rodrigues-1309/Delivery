<?php
require_once __DIR__ . '/../../config/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <!-- Meta Tags -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Categoria 01</title>

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous"
  />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/Categoria-01.css" />
</head>

<body>
  <!-- ===== NAVBAR ===== -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
      <!-- Brand Logo -->
      <a class="navbar-brand" href="./carrinho.php">Logo</a>

      <!-- Toggle Button -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav Links -->
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" href="../../Pagina_inicial/Pagina_inicial.html">
            Página Inicial
          </a>
          <a class="nav-link active" href="?action=perfilCliente">
            Pedidos
          </a>
          <a class="nav-link active" href="?action=carinho">
            Carrinho
          </a>
          <a class="nav-link active" aria-disabled="true">
            Teste
          </a>
        </div>
      </div>
    </div>
  </nav>
  <!-- ===== FIM NAVBAR ===== -->
  <!-- ===== NAVEGAÇÃO DE CATEGORIAS ===== -->
  <?php
    $sql = "SELECT * FROM categorias ORDER BY id DESC";
    $resultadoNavCategorias = $conn->query($sql);
  ?>

  <div class="container-categorias">
    <!-- Botão Esquerda -->
    <button class="btn-seta" onclick="scrollCategorias(-250)">
      &#10094;
    </button>

    <!-- Lista de Categorias -->
    <div class="lista-categorias" id="navCategoria">
      <?php while($navCategoria = $resultadoNavCategorias->fetch_assoc()): ?>
        <a href="#categoria-<?php echo $navCategoria['id']; ?>" class="navCategoria">
          <?php echo $navCategoria['nome']; ?>
        </a>
      <?php endwhile; ?>
    </div>

    <!-- Botão Direita -->
    <button class="btn-seta" onclick="scrollCategorias(250)">
      &#10095;
    </button>
  </div>
  <!-- ===== FIM NAVEGAÇÃO DE CATEGORIAS ===== -->  <!-- ===== SEÇÃO DE PRODUTOS POR CATEGORIA ===== -->
  <?php
    $sql = "SELECT * FROM categorias ORDER BY id DESC";
    $resultadoCategorias = $conn->query($sql);
  ?>

  <?php while($categoria = $resultadoCategorias->fetch_assoc()): ?>
    <!-- Busca produtos da categoria -->
    <?php
      $idCategoria = $categoria['id'];
      $sql = "SELECT * FROM produtos WHERE categoria_id = $idCategoria ORDER BY id DESC";
      $result = $conn->query($sql);
    ?>

    <!-- Título da Categoria -->
    <div id="categoria-<?php echo $categoria['id']; ?>" class="categoria">
      <?php echo $categoria['nome']; ?>
    </div>

    <!-- Grid de Produtos -->
    <div class="row g-3">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-md-4">
          <a href="index.php?action=produto&id=<?php echo $row['id']; ?>" class="produto-link">
            <div class="produto1">
              <!-- Imagem do Produto -->
              <div class="imagemProduto">
                <img src="uploads/<?php echo $row['imagem']; ?>" alt="<?php echo $row['item']; ?>">
              </div>

              <!-- Nome do Produto -->
              <div class="item">
                <?php echo $row['item']; ?>
              </div>

              <!-- Descrição -->
              <div class="descricao">
                <?php echo $row['descricao']; ?>
              </div>

              <!-- Preço -->
              <div class="valor">
                R$ <?php echo number_format($row['valor'], 2, ",", "."); ?>
              </div>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
    <!-- ===== FIM GRID DE PRODUTOS ===== -->
  <?php endwhile; ?>
  <!-- ===== FIM SEÇÃO DE PRODUTOS ===== -->
  <!-- ===== SCRIPTS ===== -->
  <!-- Bootstrap JS -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
  ></script>

  <!-- Carrinho JS -->
  <script src="./carrinho.js"></script>

  <!-- Script Personalizado -->
  <script src="script.js"></script>

  <!-- Função de Scroll de Categorias -->
  <script>
    function scrollCategorias(valor) {
      document.getElementById('navCategoria').scrollBy({
        left: valor,
        behavior: 'smooth'
      });
    }
  </script>
  <!-- ===== FIM SCRIPTS ===== -->
</body>
</html>