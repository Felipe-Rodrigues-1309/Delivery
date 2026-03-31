<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <title>Cadastro</title>

    <style>
        .grupo { 
            display: none; 
            margin-top: 20px; 
            padding: 1px; 
            background: #f1f1f1; 
            border-radius: 5px; 
        }
        .linha {
            display: flex;
            gap: 5px;
            margin-bottom: 5px;
        }
        input {
            padding: 1px;
        }
    </style>
</head>
<body>

<form action="index.php?action=enviarProduto" method="post" enctype="multipart/form-data">
  <h1>Cadastro de Produtos</h1>

  <label for="Codigo">Código:</label>
  <input type="text" name="cod"><br>

  <label for="nome">Produto:</label>
  <input type="text" name="produto"><br>

  <label for="Descricao">Descrição:</label>
  <input type="text" name="descricao"><br>

  <label for="valor">Valor:</label>
  <input type="number" name="valor" step="0.01"><br><br>

  <!-- Upload de imagem -->
  <label>Imagem do Produto:</label><br>
  <input type="file" name="imagem" accept="image/*"><br><br>


  <!-- inicio drop adicionais -->
  <h3>Cadastro de Adicionais</h3>

  <select id="selectAdd" onchange="mostrar()">
    <option value="">Selecione...</option>
    <option value="adicionais">Adicionais</option>
  </select>

  <div id="adicionais" class="grupo">

    <!-- 10 LINHAS DE INPUTS -->
    <div class="linha">
      <input type="text" name="adicional_nome1" placeholder="Nome 1">
      <input type="number" name="adicional_valor1" step="0.01" placeholder="Valor 1">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome2" placeholder="Nome 2">
      <input type="number" name="adicional_valor2" step="0.01" placeholder="Valor 2">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome3" placeholder="Nome 3">
      <input type="number" name="adicional_valor3" step="0.01" placeholder="Valor 3">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome4" placeholder="Nome 4">
      <input type="number" name="adicional_valor4" step="0.01" placeholder="Valor 4">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome5" placeholder="Nome 5">
      <input type="number" name="adicional_valor5" step="0.01" placeholder="Valor 5">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome6" placeholder="Nome 6">
      <input type="number" name="adicional_valor6" step="0.01" placeholder="Valor 6">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome7" placeholder="Nome 7">
      <input type="number" name="adicional_valor7" step="0.01" placeholder="Valor 7">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome8" placeholder="Nome 8">
      <input type="number" name="adicional_valor8" step="0.01" placeholder="Valor 8">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome9" placeholder="Nome 9">
      <input type="number" name="adicional_valor9" step="0.01" placeholder="Valor 9">
    </div>

    <div class="linha">
      <input type="text" name="adicional_nome10" placeholder="Nome 10">
      <input type="number" name="adicional_valor10" step="0.01" placeholder="Valor 10">
    </div>

  </div>

  <script>
  function mostrar() {
    document.getElementById("adicionais").style.display =
      document.getElementById("selectAdd").value === "adicionais" ? "block" : "none";
  }
  </script>
  <!-- final drop adicionais -->

  <button type="submit">Salvar</button>
</form>

</body>
</html>
