<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!--inicio bootstrap-->
    <link rel="stylesheet" href="./abrir_produto.css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <script src="./carrinho.js"></script>
    <!--final bootstrap-->
    <link rel="stylesheet" href="" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produto</title>
  </head>
  <body>
    <!--Inicio nav bar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
      <div class="container-fluid">
        <a class="navbar-brand" href="./carrinho.php">Logo</a>
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
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="../../Pagina_inicial/Pagina_inicial.html"
              >Pagina inicial</a
            >
            <a class="nav-link active" href="#">Pedidos</a>
            <a class="nav-link active" href="#">Suporte</a>
            <a class="nav-link active" aria-disabled="true">Teste</a>
          </div>
          <img src="img/seta.png" alt="">
        </div>
      </div>
    </nav>
    <?php

    include '../../conexao.php';  // faz a conexão com o banco
    
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT item, valor, descricao FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($row = $result->fetch_assoc()) {
            echo "<h1>" . htmlspecialchars($row['item']) . "</h1>";
            echo "<p>R$ " . number_format($row['valor'], 2, ",", ".") . "</p>";
            echo "<h3>" . htmlspecialchars($row['descricao']) . "</h3>";
        } else {
            echo "<h1>Produto não encontrado</h1>";
        }
        $stmt->close();
    } else {
        echo "<h1>Nenhum produto selecionado</h1>";
    }
    ?>
   <script src="script.js"></script>
  </body>
</html>
