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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <style>
        :root {
            --primary-bg: #13043a;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --card-border-radius: 16px;
        }
        
        body {
            background-color: var(--primary-bg);
            color: #334155;
        }

        .modal-header, .modal-body{
          color:white;
          background-color: #13043a;
          box-shadow: 0 1px 15px #00ff00;
          border: 2px solid #00ff00;

        }

        .inputModal{
    margin-bottom: 10px;
    width:100%;
    height:38px;
    padding:0 18px;
    border:2px solid rgba(255,255,255,.15);
    border-radius:14px;
    background:rgba(255,255,255,.08);
    color:#fff;
    font-size:18px;
    transition:.3s;
    backdrop-filter:blur(10px);
        }

        .inputModal:focus{
              border-color:#00ff66;
    box-shadow:0 0 15px rgba(0,255,102,.35);
    background:rgba(255,255,255,.12);
    outline:none;
        }

        /* Sidebar Moderna */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            color: #94a3b8;
            padding: 18px;
            box-shadow: 4px 0 10px #00ff00;
            z-index: 100;
        }

        .sidebar h3 {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 32px;
            padding-left: 8px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 12px 16px;
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar a.active {
            backdrop-filter: blur(15px);
            box-shadow: 0 1px 4px white;
            background: #00ff00;
            color: black;
        }

    /* Conteúdo */
    .content {
      margin-left: 260px;
      padding: 20px;
    }

    .card {
            color:white;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(15px);
            box-shadow: 0 1px 15px white;
            border: 2px solid white;
            margin-bottom: 30px;
    }

    .grupo {
      margin-top: 15px;
    }

    .linha {
      display:flex;
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
  <a href="?action=cadastroDeProduto"class="active">Cadastro de Produtos</a>
  <a href="?action=dashboard">Dashboard</a>
  <a href="?action=listarProdutos">Produto</a>

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastro de Categoria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="index.php?action=adicionarCategoria" method="post">
        <input type="text" name="categorias" class=inputModal>
              <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- CONTEÚDO -->
<div class="content">

  <div class="card p-4">
    <h2 class="mb-4">Cadastro de Produto</h2>

    <form action="index.php?action=enviarProduto" method="post" enctype="multipart/form-data" id="meuFormulario">

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
          <label>Categoria</label> <i class="bi bi-plus-circle-fill"data-bs-toggle="modal" data-bs-target="#exampleModal"></i>

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

 <h5><button class="btn btn-primary btn-sm"onclick="adicionarInput()" type="button"> Adicionais <i class="bi bi-plus-square-fill"></i></button></h5>


      <div id="adicionais" class="grupo mt-3">
        <!-- inputs vindos do script -->
      </div>

      <div class="mt-4">
        <button class="btn-save" type="submit">Salvar Produto</button>
      </div>

    </form>
  </div>

</div>


<script>
const form = document.getElementById("meuFormulario");

// Restaurar dados
window.addEventListener("load", () => {
    const dados = JSON.parse(localStorage.getItem("formulario")) || {};

    Object.keys(dados).forEach(nome => {
        const campo = form.elements[nome];

        if (!campo) return;

        if (campo.type === "checkbox") {
            campo.checked = dados[nome];
        } else if (campo.type === "radio") {
            const radio = form.querySelector(`[name="${nome}"][value="${dados[nome]}"]`);
            if (radio) radio.checked = true;
        } else {
            campo.value = dados[nome];
        }
    });
});

// Salvar automaticamente
form.addEventListener("input", salvar);
form.addEventListener("change", salvar);

function salvar() {
    const dados = {};

    Array.from(form.elements).forEach(campo => {

        if (!campo.name) return;

        if (campo.type === "checkbox") {
            dados[campo.name] = campo.checked;
        } else if (campo.type === "radio") {
            if (campo.checked) {
                dados[campo.name] = campo.value;
            }
        } else {
            dados[campo.name] = campo.value;
        }
    });

    localStorage.setItem("formulario", JSON.stringify(dados));
}

// Limpar após cadastrar com sucesso
function limparFormularioSalvo() {
    localStorage.removeItem("formulario");
}
</script>


<!--script adiconar novos inputs -->
<script>
let contador = 1;

function adicionarInput() {

    const linha = document.createElement("div");
    linha.className = "linha";

    const input1 = document.createElement("input");
    input1.type = "text";
    input1.name = "adicional_nome" + contador;
    input1.placeholder = "Nome";
    input1.className = "form-control";

    const input2 = document.createElement("input");
    input2.type = "number";
    input2.name = "adicional_valor" + contador;
    input2.placeholder = "Valor";
    input2.className = "form-control";

    linha.appendChild(input1);
    linha.appendChild(input2);

    document.getElementById("adicionais").appendChild(linha);

    contador++;
}
</script>

</body>
</html>