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
        <a class="navbar-brand" href="./carrinho.php">voltar</a>
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
    <div class="container mt-3">
    <?php

    include '../../conexao.php';  // faz a conexão com o banco

    
    if(isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT item, valor, descricao, imagem, 
        adicional1, adicional2, 
        adicional3, adicional4,
        adicional5, adicional6,
        adicional7, adicional8,
        adicional9, adicional10,
        valoradicional1, valoradicional2,
        valoradicional3, valoradicional4,
        valoradicional5, valoradicional6,
        valoradicional7, valoradicional8,
        valoradicional9, valoradicional10
         FROM produtos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($row = $result->fetch_assoc()) {
          
               if(!empty($row['imagem'])) {
            echo "<img src='../../uploads/" . htmlspecialchars($row['imagem']) . "' alt='" . htmlspecialchars($row['item']) . "' class='img-fluid' style='max-width: 400px;'>";}
            echo "<h1 style='color: #ffffff;'>" . htmlspecialchars($row['item']) . "</h1>";
            echo "<h6 style='color: #ffffff;'>". htmlspecialchars($row['descricao']) . "</h6>";
            echo "<hr>";
            echo "<h2 style='color: #ffffff;'>". htmlspecialchars($row['adicional1']) . "</h2>";
            echo "<p id='preco' data-valor='" . $row['valor'] . "'>R$ " . number_format($row['valor'], 2, ",", ".") . "</p>";
        } else {
            echo "<h1 style='color: #ffffff;'>Produto não encontrado</h1>";
        }
        $stmt->close();
    } else {
        echo "<h1 style='color: #ffffff;'>Nenhum produto selecionado</h1>";
    }
    ?>
    <!-- contador de quanidade -->
    <div class="text-center mt-4">
        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <button type="button" class="btn btn-danger" id="btnMenos" onclick="diminuirQuantidade()">−</button>
            <h3 id="quantidade" style="color: #ffffff; margin: 0; min-width: 30px; text-align: center;">1</h3>
            <button type="button" class="btn btn-success" id="btnMais" onclick="aumentarQuantidade()">+</button>
        </div>
        <button type="submit" class="btn btn-success btn-lg">Add no carrinho</button>
    </div>
    </div>
   <script src="script.js"></script>
   <script>
       let quantidade = 1;
       let precoUnitario = parseFloat(document.getElementById('preco')?.getAttribute('data-valor') || 0);

       function atualizarPreco() {
           const precoTotal = (precoUnitario * quantidade).toFixed(2);
           const precoFormatado = precoTotal.replace('.', ',');
           document.getElementById('preco').textContent = 'R$ ' + precoFormatado;
       }

       function aumentarQuantidade() {
           quantidade++;
           document.getElementById('quantidade').textContent = quantidade;
           atualizarPreco();
       }

       function diminuirQuantidade() {
           if (quantidade > 1) {
               quantidade--;
               document.getElementById('quantidade').textContent = quantidade;
               atualizarPreco();
           }
       }
   </script>
   <!-- final contador de quantidade -->
  </body>
</html>
