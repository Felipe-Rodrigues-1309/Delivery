<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!--inicio bootstrap-->
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
    <link rel="stylesheet" href="css/Categoria-01.css" />
    <!--css-->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>categoria 01</title>
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
        </div>
      </div>
    </nav>
    <!--final nav bar-->
  
    <!--inicio produtos-->

<?php
include '../../conexao.php';  // faz a conexão com o banco

$sql = "SELECT * FROM produtos ORDER BY id DESC"; //seleciona os itens da tabela produtos 
// se usar a opção de ORDER BY id DESC ira criar um card
//  para pada item da tabela - tambem e possivel criar um expecifico pelo id ou nome
$result = $conn->query($sql);
?>

<div class="container mt-3">  <!--inicio container-->
  <div class="row">

<?php while($row = $result->fetch_assoc()): ?>   
    <a href="./abrir_produto.php?id=<?php echo $row['id']; ?>"><div class="produto1">
      <div class="item"><?php echo $row['item']; ?></div>
      <!--Cod: <?php echo $row['cod']; ?>-->
      <div class="valor">R$ <?php echo number_format($row['valor'],2,",","."); ?></div>
      <!--<button class="butao btn-add-carrinho"
        data-id="<?php echo $row['id']; ?>"
        data-nome="<?php echo htmlspecialchars($row['item'], ENT_QUOTES); ?>"
        data-total="<?php echo $row['valor']; ?>">
        Adicionar ao Carrinho
      </button>-->
    </div></a>
    <?php endwhile; ?>
  </div>
</div>
<!--final container-->
   <script src="script.js"></script>
  </body>
</html>
