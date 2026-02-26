<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!--inicio bootstrap-->
    <link rel="stylesheet" href="/css/abrir_produto.css">
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
        <a class="navbar-brand" href="../../carrinho.php">carrinho</a>
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

   require_once __DIR__ . '/../../config/conexao.php';

    $produtoId = null;
    $nomeProduto = null;
    $precoProduto = null;
    
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
            $produtoId = $id;
            $nomeProduto = htmlspecialchars($row['item']);
            $precoProduto = $row['valor'];
          
            if(!empty($row['imagem'])) {
                echo "<img src='/uploads/" . htmlspecialchars($row['imagem']) . "' alt='" . htmlspecialchars($row['item']) . "' class='img-fluid' style='max-width: 400px;'>";
            }
            echo "<h1 style='color: #ffffff;'>" . $nomeProduto . "</h1>";
            echo "<h6 style='color: #ffffff;'>". htmlspecialchars($row['descricao']) . "</h6>";
            echo "<hr>";
            echo "<p id='preco' data-valor='" . $precoProduto . "' style='color: #1500ff; font-size: 24px; font-weight: bold;'>R$ " . number_format($precoProduto, 2, ",", ".") . "</p>";
            
            // Mostrar adicionais como checkboxes
            echo "<h5 style='color: #ffffff; margin-top: 20px;'>Adicionais:</h5>";
            echo "<div id='adicionaisContainer'>";
            
            for($i = 1; $i <= 10; $i++) {
                $adicionalKey = 'adicional' . $i;
                $valorAdicionalKey = 'valoradicional' . $i;
                
                if(!empty($row[$adicionalKey])) {
                    $valorAd = floatval($row[$valorAdicionalKey]);
                    echo "<div class='form-check' style='margin-bottom: 10px;'>";
                    echo "<input class='form-check-input adicional-checkbox' type='checkbox' id='adicional" . $i . "' data-valor='" . $valorAd . "' onchange='atualizarPreco()'>";
                    echo "<label class='form-check-label' for='adicional" . $i . "' style='color: #ffffff;'>";
                    echo htmlspecialchars($row[$adicionalKey]) . " - R$ " . number_format($valorAd, 2, ",", ".");
                    echo "</label>";
                    echo "</div>";
                }
            }
            echo "</div>";
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
        <button type="button" class="btn btn-success btn-lg" onclick="adicionarNoCarrinho()">Add no carrinho</button>
    </div>
    </div>
   <script src="script.js"></script>
   <script>
       let quantidade = 1;
       let precoUnitario = parseFloat(document.getElementById('preco')?.getAttribute('data-valor') || 0);
       const produtoId = <?php echo $produtoId; ?>;
       const nomeProduto = "<?php echo $nomeProduto ?? ''; ?>";

       function atualizarPreco() {
           // Pegar preço base
           let precoTotal = precoUnitario;
           
           // Somar adicionais selecionados
           const checkboxes = document.querySelectorAll('.adicional-checkbox:checked');
           checkboxes.forEach(checkbox => {
               precoTotal += parseFloat(checkbox.getAttribute('data-valor'));
           });
           
           // Multiplicar pela quantidade
           precoTotal = (precoTotal * quantidade).toFixed(2);
           const precoFormatado = precoTotal.toString().replace('.', ',');
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

       function adicionarNoCarrinho() {
           // Coletar os adicionais selecionados
           const adicionais = [];
           const checkboxes = document.querySelectorAll('.adicional-checkbox');
           checkboxes.forEach((checkbox, index) => {
               if(checkbox.checked) {
                   const label = document.querySelector('label[for="' + checkbox.id + '"]');
                   const valor = parseFloat(checkbox.getAttribute('data-valor'));
                   adicionais.push({
                       nome: label.textContent.split(' - R$')[0].trim(),
                       valor: valor
                   });
               }
           });

           // Calcular preço final
           let precoFinal = precoUnitario;
           adicionais.forEach(adic => {
               precoFinal += adic.valor;
           });
           precoFinal = precoFinal * quantidade;

           // Criar objeto do item
           const item = {
               id: produtoId,
               nome: nomeProduto,
               quantidade: quantidade,
               precoUnitario: precoUnitario,
               adicionais: adicionais,
               precoFinal: precoFinal
           };

           // Adicionar ao localStorage
           let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
           carrinho.push(item);
           localStorage.setItem('carrinho', JSON.stringify(carrinho));

           // Redirecionar para carrinho
           window.location.href = '?action=carrinho';
       }
   </script>
   <!-- final contador de quantidade -->
  </body>
</html>
