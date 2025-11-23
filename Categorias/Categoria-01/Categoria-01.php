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
    <link rel="stylesheet" href="../Categoria-01/Categoria-01.css" />
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
    <div class="produto1">
      <img
        class="imagemProduto1"
        src="../../uploads/<?php echo $row['imagem']; ?>"
        width="310px"
        alt="Hamburguer de carne de sol"
      />
      <h3><?php echo $row['item']; ?></h3>
      <p>Cod: <?php echo $row['cod']; ?></p>
      <p>Valor R$ <?php echo number_format($row['valor'],2,",","."); ?></p>
      <button class="butao" data-bs-toggle="modal" data-bs-target="#modal<?php echo $row['id']; ?>">
        Comprar
      </button>
    </div>
   <!--inicio conteudo modal-->
    <div class="modal fade" id="modal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="modalProduto1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-10" id="modalProduto1"><?php echo $row['item'];?></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <span>Descição</span><br>
          <span><?php echo $row['descricao'];?></span>  <!--pegar a descrição do banco-->
          <hr>
          <!---incio adicionais -->
          
          <label for="Adicionais">Adicionais ?</label><br>
          
          <?php
          // Array para mapear as colunas de adicionais
          $adicionais_map = [
              'adicional1' => 'valoradicional1',
              'adicional2' => 'valoradicional2', // Assumindo que existem mais colunas
              'adicional3' => 'valoradicional3',
              'adicional4' => 'valoradicional4',
              'adicional5' => 'valoradicional5',
              'adicional6' => 'valoradicional6',
              'adicional7' => 'valoradicional7',
              'adicional8' => 'valoradicional8',
              'adicional9' => 'valoradicional9',
              'adicional10' => 'valoradicionall0', // Assumindo que existem mais colunas
              // Adicione mais pares de colunas conforme a estrutura da sua tabela 'produtos'
          ];

          $produto_id = $row['id'];
          $tem_adicional = false;

          foreach ($adicionais_map as $nome_coluna => $valor_coluna) {
              // Verifica se o nome do adicional e o valor existem e não estão vazios
              if (!empty($row[$nome_coluna]) && isset($row[$valor_coluna])) {
                  $nome_adicional = $row[$nome_coluna];
                  $valor_adicional = $row[$valor_coluna];
                  $tem_adicional = true;
          ?>
                  <input 
                      type="checkbox" 
                      name="adicional_<?php echo $produto_id; ?>[]" 
                      id="adicional_<?php echo $nome_coluna; ?>_<?php echo $produto_id; ?>" 
                      data-valor="<?php echo $valor_adicional; ?>"
                      class="adicional-checkbox"
                      data-produto-id="<?php echo $produto_id; ?>"
                      value="<?php echo $nome_adicional; ?>"
                  >
                  <label for="adicional_<?php echo $nome_coluna; ?>_<?php echo $produto_id; ?>">
                      <?php echo $nome_adicional; ?> (+R$ <?php echo number_format($valor_adicional, 2, ",", "."); ?>)
                  </label><br>
          <?php
              }
          }

          if (!$tem_adicional) {
              echo "<p>Nenhum adicional disponível para este produto.</p>";
          }
          ?>
                <h2 id = "valorTotal<?php echo $row['id']; ?>" data-valor-base="<?php echo $row['valor'];?>">Total: R$ <?php echo number_format($row['valor'],2,",","."); ?></h2>
                <!---final adicionais -->

        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-add-carrinho"
            data-id = "<?php echo $row['id']; ?>"
            data-nome="<?php echo htmlspecialchars($row['item'], ENT_QUOTES); ?>">Adicionar ao Carrinho</button>
          </div>
        </div>
      </div>
    </div>
    <!--final conteudo modal -->
    <?php endwhile; ?>
  </div>
</div> 
<!--final container-->
   <script src="script.js"></script>
  </body>
</html>
