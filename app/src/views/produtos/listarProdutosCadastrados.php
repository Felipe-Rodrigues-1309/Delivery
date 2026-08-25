<?php

require_once __DIR__ . '/../../config/conexao.php';

/*
|--------------------------------------------------------------------------
| BUSCAR CATEGORIAS
|--------------------------------------------------------------------------
*/

$sql = "SELECT * FROM categorias ORDER BY id DESC";
$resultadoCategorias = $conn->query($sql);

if (!$resultadoCategorias) {
    die("Erro ao buscar categorias: " . $conn->error);
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Produtos</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    ></script>


    <style>

        :root {

            --primary-bg: #0f172a;

            --sidebar-bg: #0f172a;

            --sidebar-hover: #1e293b;

            --green: #00ff00;

        }


        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            background: #020617;

            color: white;

            min-height: 100vh;

        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

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
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
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

        /* =====================================================
           CONTEÚDO
        ===================================================== */

        .main-content {

            margin-left: 260px;

            padding: 30px;

        }


        .titulo {

            color: white;

            font-weight: bold;

            margin-bottom: 25px;

        }


        /* =====================================================
           ACCORDION
        ===================================================== */

        .accordion {

            --bs-accordion-bg: #111827;

            --bs-accordion-color: white;

            --bs-accordion-border-color: #334155;

        }


        .accordion-item {

            margin-bottom: 12px;

            border-radius: 12px !important;

            overflow: hidden;

            border: 1px solid #334155;

        }


        .accordion-button {

            background: #111827;

            color: white;

            font-weight: bold;

        }


        .accordion-button:not(.collapsed) {

            background: var(--green);

            color: black;

            box-shadow: none;

        }


        .accordion-button:focus {

            box-shadow: none;

        }


        /* =====================================================
           PRODUTO
        ===================================================== */

        .list-group-item {

            background: #1e293b;

            color: white;

            border-color: #334155;

            padding: 15px;

        }


        .list-group-item:hover {

            background: #263449;

        }


        .produto-nome {

            font-weight: bold;

            font-size: 16px;

        }


        .produto-descricao {

            color: #94a3b8;

        }


        .produto-preco {

            font-size: 15px;

            font-weight: bold;

        }


        .produto-promocao {

            color: #00ff88;

            font-weight: bold;

        }


        /* =====================================================
           MODAL
        ===================================================== */

        .modal-content {

            background: #111827;

            color: white;

            border: 1px solid #334155;

            border-radius: 15px;

        }


        .modal-header {

            border-bottom: 1px solid #334155;

        }


        .modal-footer {

            border-top: 1px solid #334155;

        }


        .form-control,
        .form-select {

            background: #1e293b;

            color: white;

            border: 1px solid #475569;

        }


        .form-control:focus,
        .form-select:focus {

            background: #1e293b;

            color: white;

            border-color: var(--green);

            box-shadow: 0 0 0 0.2rem rgba(0,255,0,0.15);

        }


        .form-control::placeholder {

            color: #94a3b8;

        }


        .form-select option {

            background: #1e293b;

            color: white;

        }


        .btn-close {

            filter: invert(1);

        }


        /* =====================================================
           RESPONSIVO
        ===================================================== */

        @media (max-width: 768px) {

            .sidebar {

                width: 100%;

                height: auto;

                position: relative;

            }


            .main-content {

                margin-left: 0;

                padding: 15px;

            }


            .sidebar a {

                display: inline-flex;

                margin-right: 5px;

            }


            .produto-acoes {

                margin-top: 10px;

            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
========================================================= -->

<div class="sidebar">

    <h3>
        🍔 Delivery Admin
    </h3>


    <a href="?action=pedidos">
        Pedidos
    </a>


    <a href="?action=cadastroDeProduto">
         Cadastro de Produtos
    </a>


    <a href="?action=dashboard">
        Dashboard
    </a>


    <a
        href="?action=listarProdutos"
        class="active"
    >
        Produtos
    </a>

</div>


<!-- =========================================================
     CONTEÚDO
========================================================= -->

<div class="main-content">


    <h4 class="titulo">
        🍔 Produtos cadastrados
    </h4>


    <!-- =====================================================
         MENSAGENS
    ===================================================== -->

    <?php if (isset($_GET['sucesso'])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            ✅

            <?= htmlspecialchars($_GET['sucesso']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <?php if (isset($_GET['erro'])): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            ❌

            <?= htmlspecialchars($_GET['erro']) ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         ACCORDION
    ===================================================== -->

    <div
        class="accordion"
        id="meuAccordion"
    >


        <?php

        $i = 0;

        while (
            $categoria =
            $resultadoCategorias->fetch_assoc()
        ):

            $i++;

            $idCategoria =
                intval($categoria['id']);


            /*
            |--------------------------------------------------------------------------
            | PRODUTOS DA CATEGORIA
            |--------------------------------------------------------------------------
            */

            $sqlProdutos = "
                SELECT *
                FROM produtos
                WHERE categoria_id = ?
                ORDER BY id DESC
            ";

            $stmtProdutos =
                $conn->prepare($sqlProdutos);

            $stmtProdutos->bind_param(
                "i",
                $idCategoria
            );

            $stmtProdutos->execute();

            $result =
                $stmtProdutos->get_result();


            /*
            |--------------------------------------------------------------------------
            | SE NÃO TIVER PRODUTOS
            |--------------------------------------------------------------------------
            */

            if ($result->num_rows === 0) {

                $stmtProdutos->close();

                continue;

            }


            $collapseId =
                "cat" . $i;

        ?>


            <div class="accordion-item">


                <!-- CABEÇALHO -->

                <h2 class="accordion-header">

                    <button
                        class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $collapseId ?>"
                    >

                        🍔

                        <?= htmlspecialchars(
                            $categoria['nome'] ?? ''
                        ) ?>

                        <span class="badge bg-dark ms-2">

                            <?= $result->num_rows ?>

                        </span>

                    </button>

                </h2>


                <!-- PRODUTOS -->

                <div
                    id="<?= $collapseId ?>"
                    class="accordion-collapse collapse"
                    data-bs-parent="#meuAccordion"
                >

                    <div class="accordion-body p-0">


                        <ul class="list-group list-group-flush">


                            <?php while (
                                $row =
                                $result->fetch_assoc()
                            ): ?>


                                <?php

                                $valor =
                                    floatval(
                                        $row['valor'] ?? 0
                                    );


                                $valorPromocional =
                                    floatval(
                                        $row['valor_promocional'] ?? 0
                                    );


                                $duracaoPromocao =
                                    $row['duracao_da_promocao']
                                    ?? '';

                                ?>


                                <li
                                    class="list-group-item"
                                >


                                    <div
                                        class="d-flex justify-content-between align-items-center flex-wrap"
                                    >


                                        <!-- INFORMAÇÕES -->

                                        <div>

                                            <div class="produto-nome">

                                                <?= htmlspecialchars(
                                                    $row['item'] ?? ''
                                                ) ?>

                                            </div>


                                            <small class="produto-descricao">

                                                <?= htmlspecialchars(
                                                    $row['descricao'] ?? ''
                                                ) ?>

                                            </small>


                                            <div class="mt-2">

                                                <span class="badge bg-success">

                                                    R$

                                                    <?= number_format(
                                                        $valor,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) ?>

                                                </span>


                                                <?php if (
                                                    $valorPromocional > 0
                                                ): ?>

                                                    <span class="badge bg-danger">

                                                        Promoção:

                                                        R$

                                                        <?= number_format(
                                                            $valorPromocional,
                                                            2,
                                                            ',',
                                                            '.'
                                                        ) ?>

                                                    </span>

                                                <?php endif; ?>


                                                <?php if (
                                                    !empty(
                                                        $duracaoPromocao
                                                    )
                                                ): ?>

                                                    <span class="badge bg-info">

                                                        Até:

                                                        <?= date(
                                                            'd/m/Y',
                                                            strtotime(
                                                                $duracaoPromocao
                                                            )
                                                        ) ?>

                                                    </span>

                                                <?php endif; ?>

                                            </div>

                                        </div>


                                        <!-- AÇÕES -->

                                        <div
                                            class="d-flex gap-2 align-items-center produto-acoes"
                                        >


                                            <!-- EDITAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editarModal"

                                                data-id="<?= $row['id'] ?>"

                                                data-cod="<?= htmlspecialchars(
                                                    $row['cod'] ?? ''
                                                ) ?>"

                                                data-item="<?= htmlspecialchars(
                                                    $row['item'] ?? ''
                                                ) ?>"

                                                data-valor="<?= htmlspecialchars(
                                                    $row['valor'] ?? ''
                                                ) ?>"

                                                data-valor-promocional="<?= htmlspecialchars(
                                                    $row['valor_promocional'] ?? ''
                                                ) ?>"

                                                data-duracao-promocao="<?= htmlspecialchars(
                                                    $row['duracao_da_promocao'] ?? ''
                                                ) ?>"

                                                data-descricao="<?= htmlspecialchars(
                                                    $row['descricao'] ?? ''
                                                ) ?>"

                                                data-categoria="<?= htmlspecialchars(
                                                    $row['categoria_id'] ?? ''
                                                ) ?>"

                                                data-imagem="<?= htmlspecialchars(
                                                    $row['imagem'] ?? ''
                                                ) ?>"
                                            >

                                                ✏️ Editar

                                            </button>


                                            <!-- EXCLUIR -->

                                            <form
                                                action="index.php?action=deletarProduto"
                                                method="post"
                                                class="m-0"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= $row['id'] ?>"
                                                >


                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('⚠️ Tem certeza que deseja excluir este produto?');"
                                                >

                                                    🗑️ Excluir

                                                </button>

                                            </form>


                                        </div>

                                    </div>

                                </li>


                            <?php endwhile; ?>


                        </ul>

                    </div>

                </div>

            </div>


        <?php

            $stmtProdutos->close();

        endwhile;

        ?>


    </div>

</div>


<!-- =========================================================
     MODAL EDITAR PRODUTO
========================================================= -->

<div
    class="modal fade"
    id="editarModal"
    tabindex="-1"
    aria-labelledby="editarModalLabel"
    aria-hidden="true"
>


    <div class="modal-dialog modal-lg modal-dialog-centered">


        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="editarModalLabel"
                >

                    ✏️ Editar Produto

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Fechar"
                ></button>

            </div>


            <!-- FORM -->

            <form
                action="index.php?action=atualizarProduto"
                method="POST"
                enctype="multipart/form-data"
            >


                <div class="modal-body">


                    <!-- ID -->

                    <input
                        type="hidden"
                        name="id"
                        id="modalProdutoId"
                    >


                    <!-- =================================================
                         DADOS PRINCIPAIS
                    ================================================= -->

                    <div class="row g-3 mb-3">


                        <!-- CÓDIGO -->

                        <div class="col-md-4">

                            <label class="form-label">
                                Código
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="cod"
                                id="modalCod"
                                required
                            >

                        </div>


                        <!-- PRODUTO -->

                        <div class="col-md-8">

                            <label class="form-label">
                                Produto
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="item"
                                id="modalItem"
                                required
                            >

                        </div>


                    </div>


                    <!-- =================================================
                         PREÇOS
                    ================================================= -->

                    <div class="row g-3 mb-3">


                        <!-- VALOR NORMAL -->

                        <div class="col-md-4">

                            <label class="form-label">

                                Valor

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    R$
                                </span>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="valor"
                                    id="modalValor"
                                    step="0.01"
                                    min="0"
                                    required
                                >

                            </div>

                        </div>


                        <!-- VALOR PROMOCIONAL -->

                        <div class="col-md-4">

                            <label class="form-label">

                                Valor Promocional

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    R$
                                </span>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="valorPromocional"
                                    id="modalValorPromocional"
                                    step="0.01"
                                    min="0"
                                >

                            </div>

                        </div>


                        <!-- DATA PROMOÇÃO -->

                        <div class="col-md-4">

                            <label class="form-label">

                                Duração da Promoção

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                name="duracaoDaPromocao"
                                id="modalDuracao"
                            >

                        </div>


                    </div>


                    <!-- =================================================
                         CATEGORIA / IMAGEM
                    ================================================= -->

                    <div class="row g-3 mb-3">


                        <!-- CATEGORIA -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Categoria

                            </label>

                            <select
                                class="form-select"
                                name="categoria"
                                id="modalCategoria"
                                required
                            >

                                <option value="">

                                    Selecione uma categoria

                                </option>


                                <?php

                                /*
                                |--------------------------------------------------------------------------
                                | NOVA CONSULTA DE CATEGORIAS
                                |--------------------------------------------------------------------------
                                */

                                $sqlCat =
                                    "SELECT * FROM categorias ORDER BY id DESC";

                                $resultCat =
                                    $conn->query($sqlCat);


                                while (
                                    $categoriaModal =
                                    $resultCat->fetch_assoc()
                                ):

                                ?>

                                    <option
                                        value="<?= $categoriaModal['id'] ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $categoriaModal['nome']
                                        ) ?>

                                    </option>

                                <?php endwhile; ?>


                            </select>

                        </div>


                        <!-- IMAGEM -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Nova imagem

                            </label>

                            <input
                                type="file"
                                class="form-control"
                                name="imagem"
                                accept="image/*"
                            >


                            <input
                                type="hidden"
                                id="modalImagemAtualHidden"
                                name="imagem_atual"
                            >

                            <small
                                class="text-secondary"
                            >

                                Deixe vazio para manter a imagem atual.

                            </small>

                        </div>


                    </div>


                    <!-- =================================================
                         DESCRIÇÃO
                    ================================================= -->

                    <div class="mb-3">

                        <label class="form-label">

                            Descrição

                        </label>

                        <textarea
                            class="form-control"
                            name="descricao"
                            id="modalDescricao"
                            rows="4"
                            placeholder="Digite a descrição do produto..."
                        ></textarea>

                    </div>


                </div>


                <!-- =================================================
                     FOOTER
                ================================================= -->

                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        💾 Salvar alterações

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

const editarModal =
    document.getElementById('editarModal');


editarModal.addEventListener(
    'show.bs.modal',
    function (event) {


        const button =
            event.relatedTarget;


        /*
        |--------------------------------------------------------------------------
        | PEGAR DADOS
        |--------------------------------------------------------------------------
        */

        const id =
            button.getAttribute('data-id');

        const cod =
            button.getAttribute('data-cod');

        const item =
            button.getAttribute('data-item');

        const valor =
            button.getAttribute('data-valor');

        const valorPromocional =
            button.getAttribute(
                'data-valor-promocional'
            );

        const duracaoPromocao =
            button.getAttribute(
                'data-duracao-promocao'
            );

        const descricao =
            button.getAttribute(
                'data-descricao'
            );

        const categoria =
            button.getAttribute(
                'data-categoria'
            );

        const imagem =
            button.getAttribute(
                'data-imagem'
            );


        /*
        |--------------------------------------------------------------------------
        | PREENCHER FORMULÁRIO
        |--------------------------------------------------------------------------
        */

        document.getElementById(
            'modalProdutoId'
        ).value = id;


        document.getElementById(
            'modalCod'
        ).value = cod;


        document.getElementById(
            'modalItem'
        ).value = item;


        document.getElementById(
            'modalValor'
        ).value = valor;


        document.getElementById(
            'modalValorPromocional'
        ).value =
            valorPromocional || '';


        document.getElementById(
            'modalDuracao'
        ).value =
            duracaoPromocao || '';


        document.getElementById(
            'modalDescricao'
        ).value = descricao;


        document.getElementById(
            'modalCategoria'
        ).value = categoria;


        document.getElementById(
            'modalImagemAtualHidden'
        ).value = imagem || '';

    }

);

</script>


</body>

</html>