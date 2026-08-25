<?php
require_once __DIR__ . '/../../config/conexao.php';

$produtoId = null;
$nomeProduto = null;
$precoProduto = null;
$descricaoProduto = null;
$imagemProduto = null;
$adicionais = [];

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "SELECT 
                item,
                valor,
                descricao,
                imagem,
                adicional1,
                adicional2,
                adicional3,
                adicional4,
                adicional5,
                adicional6,
                adicional7,
                adicional8,
                adicional9,
                adicional10,
                valoradicional1,
                valoradicional2,
                valoradicional3,
                valoradicional4,
                valoradicional5,
                valoradicional6,
                valoradicional7,
                valoradicional8,
                valoradicional9,
                valoradicional10
            FROM produtos
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $produtoId = $id;

        $nomeProduto = htmlspecialchars($row['item']);

        $precoProduto = floatval($row['valor']);

        $descricaoProduto = htmlspecialchars($row['descricao']);

        $imagemProduto = htmlspecialchars($row['imagem']);

        for ($i = 1; $i <= 10; $i++) {

            $adicionalKey = 'adicional' . $i;

            $valorAdicionalKey = 'valoradicional' . $i;

            if (!empty($row[$adicionalKey])) {

                $adicionais[] = [
                    'id' => $i,
                    'nome' => $row[$adicionalKey],
                    'valor' => floatval($row[$valorAdicionalKey])
                ];
            }
        }
    }

    $stmt->close();
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

    <title>
        <?= $nomeProduto ? $nomeProduto : 'Produto' ?>
    </title>


    <!-- =====================================================
         TAILWIND CSS
    ====================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>


    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        neon: '#00ff00',

                        azul: '#1500ff'

                    }

                }

            }

        }

    </script>


    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Seu carrinho continua sendo carregado -->
    <script src="./carrinho.js"></script>


    <style>

        /* ==================================================
           CONFIGURAÇÃO GERAL
        ================================================== */

        html {

            scroll-behavior: smooth;

        }


        body {

            min-height: 100vh;

            margin: 0;

            color: white;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                'Segoe UI',
                Roboto,
                sans-serif;

            background:

                radial-gradient(
                    circle at top right,
                    rgba(120, 50, 180, 0.20),
                    transparent 35%
                ),

                radial-gradient(
                    circle at bottom left,
                    rgba(80, 30, 130, 0.16),
                    transparent 35%
                ),

                linear-gradient(
                    135deg,
                    #0a0a0a,
                    #151515,
                    #202020
                );

        }


        /* ==================================================
           NAVBAR
        ================================================== */

        .navbar-principal {

            background: rgba(0, 0, 0, 0.90);

            backdrop-filter: blur(12px);

            -webkit-backdrop-filter: blur(12px);

            border-bottom:
                1px solid
                rgba(255, 255, 255, 0.08);

            position: sticky;

            top: 0;

            z-index: 1000;

        }


        .nav-link-custom {

            color: white;

            text-decoration: none;

            transition:
                color 0.3s ease,
                transform 0.3s ease;

        }


        .nav-link-custom:hover {

            color: #00ff00;

            transform: translateY(-1px);

        }


        /* ==================================================
           CARD PRINCIPAL
        ================================================== */

        .produto-container {

            width: 100%;

            max-width: 900px;

            margin:
                30px auto
                50px;

            padding:
                20px;

        }


        .produto-card {

            background:
                linear-gradient(
                    145deg,
                    rgba(15, 15, 15, 0.95),
                    rgba(0, 0, 0, 0.88)
                );

            border:
                1px solid
                rgba(255, 255, 255, 0.14);

            border-radius: 20px;

            padding: 25px;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.45);

        }


        /* ==================================================
           IMAGEM DO PRODUTO
        ================================================== */

        .imagem-produto {

            width: 100%;

            max-width: 365px;

            aspect-ratio: 1 / 1;

            object-fit: cover;

            display: block;

            margin:
                0 auto
                25px;

            border-radius: 18px;

            border:
                1px solid
                rgba(255, 255, 255, 0.15);

            box-shadow:
                0 10px 30px
                rgba(0, 0, 0, 0.50);

            transition:
                transform 0.4s ease,
                box-shadow 0.4s ease;

        }


        .imagem-produto:hover {

            transform: scale(1.02);

            box-shadow:
                0 0 25px
                rgba(0, 255, 0, 0.15);

        }


        /* ==================================================
           NOME
        ================================================== */

        .nome-produto {

            color: white;

            font-size: 30px;

            font-weight: 800;

            line-height: 1.2;

            margin-bottom: 10px;

        }


        /* ==================================================
           DESCRIÇÃO
        ================================================== */

        .descricao-produto {

            color: #a8a8a8;

            font-size: 15px;

            line-height: 1.6;

            margin-bottom: 20px;

        }


        /* ==================================================
           PREÇO
        ================================================== */

        .preco-produto {

            color: #00ff00;

            font-size: 28px;

            font-weight: 800;

            margin:
                15px 0
                25px;

            text-shadow:
                0 0 10px
                rgba(0, 255, 0, 0.15);

        }


        /* ==================================================
           SEPARADOR
        ================================================== */

        .linha {

            border: 0;

            border-top:
                1px solid
                rgba(255, 255, 255, 0.10);

            margin:
                20px 0;

        }


        /* ==================================================
           ADICIONAIS
        ================================================== */

        .titulo-adicionais {

            color: white;

            font-size: 19px;

            font-weight: 700;

            margin-bottom: 15px;

        }


        .adicional-item {

            display: flex;

            align-items: center;

            gap: 12px;

            padding:
                13px 15px;

            margin-bottom: 10px;

            background:
                rgba(255, 255, 255, 0.035);

            border:
                1px solid
                rgba(255, 255, 255, 0.10);

            border-radius: 12px;

            transition:
                all 0.3s ease;

            cursor: pointer;

        }


        .adicional-item:hover {

            background:
                rgba(0, 255, 0, 0.06);

            border-color:
                rgba(0, 255, 0, 0.35);

        }


        .adicional-checkbox {

            width: 20px;

            height: 20px;

            accent-color: #00ff00;

            cursor: pointer;

            flex-shrink: 0;

        }


        .adicional-label {

            color: #eee;

            font-size: 14px;

            cursor: pointer;

            flex: 1;

        }


        .adicional-valor {

            color: #00ff00;

            font-weight: 700;

            white-space: nowrap;

        }


        /* ==================================================
           CONTADOR
        ================================================== */

        .contador {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 18px;

            margin:
                30px 0
                20px;

        }


        .btn-quantidade {

            width: 44px;

            height: 44px;

            border-radius: 50%;

            border:
                1px solid
                rgba(255, 255, 255, 0.20);

            background: #080808;

            color: white;

            font-size: 24px;

            font-weight: bold;

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .btn-quantidade:hover {

            transform: scale(1.08);

        }


        .btn-menos:hover {

            background: #dc2626;

            border-color: #dc2626;

            box-shadow:
                0 0 15px
                rgba(220, 38, 38, 0.35);

        }


        .btn-mais:hover {

            background: #00ff00;

            color: black;

            border-color: #00ff00;

            box-shadow:
                0 0 15px
                rgba(0, 255, 0, 0.35);

        }


        .quantidade {

            min-width: 40px;

            text-align: center;

            color: white;

            font-size: 22px;

            font-weight: 700;

        }


        /* ==================================================
           BOTÃO CARRINHO
        ================================================== */

        .btn-carrinho {

            width: 100%;

            max-width: 400px;

            margin:
                0 auto;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            padding:
                14px 25px;

            border:
                1px solid
                #00ff00;

            border-radius: 12px;

            background:
                rgba(0, 0, 0, 0.85);

            color: white;

            font-size: 16px;

            font-weight: 700;

            cursor: pointer;

            transition:
                all 0.3s ease;

        }


        .btn-carrinho:hover {

            background: #00ff00;

            color: black;

            transform:
                translateY(-2px);

            box-shadow:
                0 0 20px
                rgba(0, 255, 0, 0.35);

        }


        /* ==================================================
           PRODUTO NÃO ENCONTRADO
        ================================================== */

        .produto-erro {

            text-align: center;

            padding: 50px 20px;

            color: white;

        }


        .produto-erro i {

            font-size: 55px;

            color: #dc2626;

            margin-bottom: 20px;

        }


        /* ==================================================
           RESPONSIVO
        ================================================== */

        @media (max-width: 640px) {

            .produto-container {

                padding:
                    10px;

                margin-top:
                    20px;

            }


            .produto-card {

                padding:
                    18px;

                border-radius:
                    16px;

            }


            .nome-produto {

                font-size:
                    25px;

            }


            .descricao-produto {

                font-size:
                    14px;

            }


            .preco-produto {

                font-size:
                    25px;

            }


            .adicional-item {

                padding:
                    12px;

            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar-principal">

    <div
        class="
            max-w-7xl
            mx-auto
            px-4
            sm:px-6
            lg:px-8
        "
    >

        <div
            class="
                flex
                items-center
                justify-between
                h-16
            "
        >

            <!-- BOTÃO MOBILE -->

            <button
                id="btnMenu"
                type="button"
                class="
                    md:hidden
                    text-white
                    text-2xl
                    bg-transparent
                    border-0
                    cursor-pointer
                    p-2
                "
                aria-label="Abrir menu"
            >

                <i class="bi bi-list"></i>

            </button>


            <!-- MENU DESKTOP -->

            <div
                class="
                    hidden
                    md:flex
                    items-center
                    gap-8
                "
            >

                <a
                    class="nav-link-custom"
                    href="index.php?action=categoria"
                >
                    <i class="bi bi-house mr-1"></i>
                    Página inicial
                </a>


                <a
                    class="nav-link-custom"
                    href="index.php?action=perfilCliente"
                >
                    <i class="bi bi-receipt mr-1"></i>
                    Pedidos
                </a>


                <a
                    class="nav-link-custom"
                    href="index.php?action=carrinho"
                >
                    <i class="bi bi-cart3 mr-1"></i>
                    Carrinho
                </a>

            </div>


            <!-- CARRINHO -->

            <a
                href="index.php?action=carrinho"
                class="
                    text-white
                    text-xl
                    no-underline
                    hover:text-green-400
                    transition
                "
                title="Carrinho"
            >

                <i class="bi bi-cart3"></i>

            </a>

        </div>


        <!-- MENU MOBILE -->

        <div
            id="menuMobile"
            class="
                hidden
                md:hidden
                pb-4
            "
        >

            <div class="flex flex-col gap-1">

                <a
                    href="index.php?action=categoria"
                    class="
                        nav-link-custom
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                    "
                >

                    <i class="bi bi-house mr-2"></i>

                    Página inicial

                </a>


                <a
                    href="index.php?action=perfilCliente"
                    class="
                        nav-link-custom
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                    "
                >

                    <i class="bi bi-receipt mr-2"></i>

                    Pedidos

                </a>


                <a
                    href="index.php?action=carrinho"
                    class="
                        nav-link-custom
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                    "
                >

                    <i class="bi bi-cart3 mr-2"></i>

                    Carrinho

                </a>

            </div>

        </div>

    </div>

</nav>


<!-- =========================================================
     PRODUTO
========================================================= -->

<div class="produto-container">

    <div class="produto-card">


<?php if ($produtoId !== null): ?>


    <!-- =====================================================
         IMAGEM
    ====================================================== -->

    <?php if (!empty($imagemProduto)): ?>

        <img
            src="uploads/<?= $imagemProduto ?>"
            alt="<?= $nomeProduto ?>"
            class="imagem-produto"
        >

    <?php endif; ?>


    <!-- =====================================================
         NOME
    ====================================================== -->

    <h1 class="nome-produto">

        <?= $nomeProduto ?>

    </h1>


    <!-- =====================================================
         DESCRIÇÃO
    ====================================================== -->

    <?php if (!empty($descricaoProduto)): ?>

        <p class="descricao-produto">

            <?= $descricaoProduto ?>

        </p>

    <?php endif; ?>


    <hr class="linha">


    <!-- =====================================================
         PREÇO
    ====================================================== -->

    <div
        id="preco"
        class="preco-produto"
        data-valor="<?= $precoProduto ?>"
    >

        R$
        <?= number_format(
            $precoProduto,
            2,
            ",",
            "."
        ) ?>

    </div>


    <!-- =====================================================
         ADICIONAIS
    ====================================================== -->

    <?php if (!empty($adicionais)): ?>

        <h2 class="titulo-adicionais">

            <i class="bi bi-plus-circle mr-1"></i>

            Adicionais

        </h2>


        <div id="adicionaisContainer">

            <?php foreach ($adicionais as $ad): ?>

                <label
                    class="adicional-item"
                    for="adicional<?= $ad['id'] ?>"
                >

                    <input
                        class="adicional-checkbox"
                        type="checkbox"
                        id="adicional<?= $ad['id'] ?>"
                        data-valor="<?= $ad['valor'] ?>"
                        onchange="atualizarPreco()"
                    >


                    <span class="adicional-label">

                        <?= htmlspecialchars($ad['nome']) ?>

                    </span>


                    <span class="adicional-valor">

                        + R$
                        <?= number_format(
                            $ad['valor'],
                            2,
                            ",",
                            "."
                        ) ?>

                    </span>

                </label>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         CONTADOR
    ====================================================== -->

    <div class="contador">


        <button
            type="button"
            class="btn-quantidade btn-menos"
            id="btnMenos"
            onclick="diminuirQuantidade()"
            aria-label="Diminuir quantidade"
        >

            −

        </button>


        <span
            id="quantidade"
            class="quantidade"
        >
            1
        </span>


        <button
            type="button"
            class="btn-quantidade btn-mais"
            id="btnMais"
            onclick="aumentarQuantidade()"
            aria-label="Aumentar quantidade"
        >

            +

        </button>


    </div>


    <!-- =====================================================
         ADICIONAR AO CARRINHO
    ====================================================== -->

    <button
        type="button"
        class="btn-carrinho"
        onclick="adicionarNoCarrinho()"
    >

        <i class="bi bi-cart-plus text-lg"></i>

        Adicionar ao carrinho

    </button>


<?php else: ?>


    <!-- =====================================================
         ERRO
    ====================================================== -->

    <div class="produto-erro">

        <i class="bi bi-exclamation-circle block"></i>

        <h1 class="text-2xl font-bold mb-3">

            Produto não encontrado

        </h1>

        <p class="text-gray-400">

            O produto selecionado não existe ou foi removido.

        </p>


        <a
            href="index.php?action=categoria"
            class="
                inline-flex
                items-center
                gap-2
                mt-6
                px-5
                py-3
                rounded-xl
                border
                border-green-500
                text-green-400
                no-underline
                hover:bg-green-500
                hover:text-black
                transition
            "
        >

            <i class="bi bi-arrow-left"></i>

            Voltar para produtos

        </a>

    </div>


<?php endif; ?>


    </div>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

    /*
     * MENU MOBILE
     */

    const btnMenu =
        document.getElementById('btnMenu');

    const menuMobile =
        document.getElementById('menuMobile');


    if (btnMenu && menuMobile) {

        btnMenu.addEventListener(
            'click',
            function () {

                menuMobile.classList.toggle('hidden');

            }
        );

    }


    /*
     * FECHAR MENU AO CLICAR
     */

    document
        .querySelectorAll('#menuMobile a')
        .forEach(link => {

            link.addEventListener(
                'click',
                function () {

                    menuMobile.classList.add('hidden');

                }
            );

        });


    /*
     * ======================================================
     * CONTROLE DO PRODUTO
     * ======================================================
     */

    let quantidade = 1;


    let precoUnitario =
        parseFloat(
            document
                .getElementById('preco')
                ?.getAttribute('data-valor')
                || 0
        );


    const produtoId =
        <?= json_encode($produtoId) ?>;


    const nomeProduto =
        <?= json_encode($nomeProduto ?? '') ?>;


    /*
     * ATUALIZA PREÇO
     */

    function atualizarPreco() {

        let precoTotal =
            precoUnitario;


        const checkboxes =
            document.querySelectorAll(
                '.adicional-checkbox:checked'
            );


        checkboxes.forEach(
            checkbox => {

                precoTotal +=
                    parseFloat(
                        checkbox.getAttribute(
                            'data-valor'
                        )
                    );

            }
        );


        precoTotal =
            (
                precoTotal *
                quantidade
            ).toFixed(2);


        const precoFormatado =
            precoTotal
                .toString()
                .replace('.', ',');


        const elementoPreco =
            document.getElementById('preco');


        if (elementoPreco) {

            elementoPreco.textContent =
                'R$ ' +
                precoFormatado;

        }

    }


    /*
     * AUMENTAR QUANTIDADE
     */

    function aumentarQuantidade() {

        quantidade++;


        const elementoQuantidade =
            document.getElementById(
                'quantidade'
            );


        if (elementoQuantidade) {

            elementoQuantidade.textContent =
                quantidade;

        }


        atualizarPreco();

    }


    /*
     * DIMINUIR QUANTIDADE
     */

    function diminuirQuantidade() {

        if (quantidade > 1) {

            quantidade--;

            const elementoQuantidade =
                document.getElementById(
                    'quantidade'
                );


            if (elementoQuantidade) {

                elementoQuantidade.textContent =
                    quantidade;

            }


            atualizarPreco();

        }

    }


    /*
     * ======================================================
     * ADICIONAR AO CARRINHO
     * ======================================================
     */

    function adicionarNoCarrinho() {


        if (!produtoId) {

            alert(
                'Produto inválido.'
            );

            return;

        }


        /*
         * Coletar adicionais
         */

        const adicionais = [];


        const checkboxes =
            document.querySelectorAll(
                '.adicional-checkbox'
            );


        checkboxes.forEach(
            checkbox => {

                if (checkbox.checked) {

                    const label =
                        document.querySelector(
                            'label[for="' +
                            checkbox.id +
                            '"]'
                        );


                    const valor =
                        parseFloat(
                            checkbox.getAttribute(
                                'data-valor'
                            )
                        );


                    adicionais.push({

                        nome:
                            label
                                .querySelector(
                                    '.adicional-label'
                                )
                                ?.textContent
                                .trim()
                                ||
                                label.textContent
                                    .split(' + R$')[0]
                                    .trim(),

                        valor:
                            valor

                    });

                }

            }
        );


        /*
         * Calcular preço final
         */

        let precoFinal =
            precoUnitario;


        adicionais.forEach(
            adicional => {

                precoFinal +=
                    adicional.valor;

            }
        );


        precoFinal =
            precoFinal *
            quantidade;


        /*
         * Criar objeto do produto
         */

        const item = {

            id:
                produtoId,

            nome:
                nomeProduto,

            quantidade:
                quantidade,

            precoUnitario:
                precoUnitario,

            adicionais:
                adicionais,

            precoFinal:
                precoFinal

        };


        /*
         * Recuperar carrinho
         */

        let carrinho =
            JSON.parse(
                localStorage.getItem(
                    'carrinho'
                )
                ||
                '[]'
            );


        /*
         * Adicionar produto
         */

        carrinho.push(item);


        /*
         * Salvar
         */

        localStorage.setItem(
            'carrinho',
            JSON.stringify(carrinho)
        );


        /*
         * Redirecionar
         */

        window.location.href =
            '?action=carrinho';

    }

</script>


</body>

</html>