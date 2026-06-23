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
  <title>Produtos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

    <style>

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
    .accordion, .alert {
      margin-left: 180px;
      padding: 20px;
    }
    </style>


<body>

<!-- SIDEBAR -->
<div class="sidebar">
  <h3>🍔 Delivery Admin</h3>
  <a href="?action=pedidos">Pedidos</a>  
  <a href="?action=cadastroDeProduto">Cadastro de Produtos</a>
  <a href="?action=dashboard">Dashboard</a>
  <a href="?action=listarProdutos">Produto</a>

</div>




<div class="container">

  <h4>Produtos</h4>

  <?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
  <?php endif; ?>
  <?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
  <?php endif; ?>

  <div class="accordion" id="meuAccordion">

    <?php $i = 0; while($categoria = $resultadoCategorias->fetch_assoc()): $i++; ?>

      <?php
        $idCategoria = $categoria['id'];

        $sqlProdutos = "SELECT * FROM produtos WHERE categoria_id = $idCategoria ORDER BY id DESC";
        $result = $conn->query($sqlProdutos);

        $collapseId = "cat" . $i;
      ?>

      <div class="accordion-item">

        <h2 class="accordion-header">
          <button class="accordion-button collapsed"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#<?= $collapseId ?>">

            <?= htmlspecialchars($categoria['nome'] ?? '') ?>

          </button>
        </h2>

        <div id="<?= $collapseId ?>"
             class="accordion-collapse collapse"
             data-bs-parent="#meuAccordion">

          <div class="accordion-body">

            <?php if ($result->num_rows > 0): ?>

              <ul class="list-group">

                <?php while($row = $result->fetch_assoc()): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong><?= htmlspecialchars($row['item'] ?? '') ?></strong><br>
                      <small class="text-muted"><?= htmlspecialchars($row['descricao'] ?? '') ?></small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                      <span class="badge bg-success">R$ <?= number_format($row['valor'] ?? 0, 2, ',', '.') ?></span>
                      <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editarModal" data-id="<?= $row['id'] ?>" data-cod="<?= htmlspecialchars($row['cod'] ?? '') ?>" data-item="<?= htmlspecialchars($row['item'] ?? '') ?>" data-valor="<?= $row['valor'] ?? 0 ?>" data-descricao="<?= htmlspecialchars($row['descricao'] ?? '') ?>" data-categoria="<?= $row['categoria_id'] ?? '' ?>" data-imagem="<?= htmlspecialchars($row['imagem'] ?? '') ?>">
                        Editar
                      </button>
                      <form action="index.php?action=deletarProduto" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este produto?');">Excluir</button>
                      </form>
                    </div>
                  </li>
                <?php endwhile; ?>

              </ul>

            <?php else: ?>
              <p class="text-muted">Nenhum produto nesta categoria.</p>
            <?php endif; ?>

          </div>

        </div>

      </div>

    <?php endwhile; ?>

  </div>

</div>

<!-- Modal de Edição -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="editarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarModalLabel">Editar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form action="index.php?action=atualizarProduto" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id" id="modalProdutoId">

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Código</label>
              <input type="text" class="form-control" name="cod" id="modalCod" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Produto</label>
              <input type="text" class="form-control" name="item" id="modalItem" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Valor</label>
              <input type="number" class="form-control" name="valor" id="modalValor" step="0.01" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Categoria</label>
              <select class="form-select" name="categoria" id="modalCategoria" required>
                <option value="">Selecione uma categoria</option>
                <?php
                  $resultadoCategorias->data_seek(0);
                  while($categoria = $resultadoCategorias->fetch_assoc()):
                ?>
                  <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Imagem</label>
              <input type="file" class="form-control" name="imagem" accept="image/*">
              <input type="hidden" id="modalImagemAtualHidden" name="imagem_atual">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" id="modalDescricao" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const editarModal = document.getElementById('editarModal');

  editarModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const cod = button.getAttribute('data-cod');
    const item = button.getAttribute('data-item');
    const valor = button.getAttribute('data-valor');
    const descricao = button.getAttribute('data-descricao');
    const categoria = button.getAttribute('data-categoria');
    const imagem = button.getAttribute('data-imagem');

    document.getElementById('modalProdutoId').value = id;
    document.getElementById('modalCod').value = cod;
    document.getElementById('modalItem').value = item;
    document.getElementById('modalValor').value = valor;
    document.getElementById('modalDescricao').value = descricao;
    document.getElementById('modalCategoria').value = categoria;
    document.getElementById('modalImagemAtualHidden').value = imagem;
  });
</script>

</body>
</html>