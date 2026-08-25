<?php
require_once __DIR__ . '/../../config/conexao.php';

/**
 * 1. BUSCA TODAS AS CATEGORIAS (1 QUERY)
 */
$sqlCategorias = "SELECT * FROM categorias ORDER BY id DESC";
$resultCategorias = $conn->query($sqlCategorias);

$categorias = [];

while ($cat = $resultCategorias->fetch_assoc()) {
    $cat['produtos'] = [];
    $categorias[$cat['id']] = $cat;
}

/**
 * 2. BUSCA TODOS OS PRODUTOS (1 QUERY)
 */
$sqlProdutos = "SELECT * FROM produtos ORDER BY id DESC";
$resultProdutos = $conn->query($sqlProdutos);

/**
 * 3. AGRUPA PRODUTOS POR CATEGORIA
 */
while ($prod = $resultProdutos->fetch_assoc()) {

    if (isset($categorias[$prod['categoria_id']])) {
        $categorias[$prod['categoria_id']]['produtos'][] = $prod;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Categoria 01</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Ícones -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

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

    <style>

        /* ==============================
           CONFIGURAÇÃO GERAL
        ============================== */

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 150px;
        }

        body {

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

            min-height: 100vh;

            color: white;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                'Segoe UI',
                Roboto,
                sans-serif;

            margin: 0;
        }


        /* ==============================
           SCROLLBAR
        ============================== */

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #080808;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 20px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }


        /* ==============================
           NAVBAR
        ============================== */

        .navbar-principal {

            background: rgba(0, 0, 0, 0.90);

            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);

            border-bottom: 1px solid rgba(255, 255, 255, 0.08);

            position: sticky;
            top: 0;

            z-index: 1000;
        }


        .logo-navbar {

            transition:
                transform 0.3s ease,
                color 0.3s ease;
        }

        .logo-navbar:hover {

            color: #00ff00;

            transform: scale(1.05);
        }


        .nav-link-custom {

            position: relative;

            transition:
                color 0.3s ease,
                transform 0.3s ease;
        }

        .nav-link-custom:hover {

            color: #00ff00;

            transform: translateY(-1px);
        }


        /* ==============================
           NAVEGAÇÃO DE CATEGORIAS
        ============================== */

        .container-categorias {

            position: sticky;

            top: 65px;

            z-index: 900;

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px 15px;

            background: rgba(5, 5, 5, 0.92);

            backdrop-filter: blur(12px);

            -webkit-backdrop-filter: blur(12px);

            border-bottom:
                1px solid
                rgba(255, 255, 255, 0.08);
        }


        .lista-categorias {

            display: flex;

            align-items: center;

            gap: 10px;

            overflow-x: auto;

            scroll-behavior: smooth;

            flex: 1;

            padding: 3px;

            scrollbar-width: none;
        }

        .lista-categorias::-webkit-scrollbar {
            display: none;
        }


        .navCategoria {

            flex-shrink: 0;

            text-decoration: none;

            color: #fff;

            background: rgba(0, 0, 0, 0.70);

            border:
                1px solid
                rgba(255, 255, 255, 0.25);

            border-radius: 999px;

            padding: 8px 16px;

            font-size: 14px;

            font-weight: 600;

            transition:
                all 0.3s ease;
        }


        .navCategoria:hover {

            color: #000;

            background: #00ff00;

            border-color: #00ff00;

            box-shadow:
                0 0 15px
                rgba(0, 255, 0, 0.35);

            transform: translateY(-2px);
        }


        .btn-seta {

            width: 38px;
            height: 38px;

            flex-shrink: 0;

            border-radius: 50%;

            border:
                1px solid
                rgba(255, 255, 255, 0.25);

            background: rgba(0, 0, 0, 0.85);

            color: white;

            cursor: pointer;

            display: flex;

            align-items: center;

            justify-content: center;

            transition: all 0.3s ease;
        }


        .btn-seta:hover {

            background: #1500ff;

            border-color: #1500ff;

            box-shadow:
                0 0 15px
                rgba(21, 0, 255, 0.45);

            transform: scale(1.08);
        }


        /* ==============================
           CATEGORIA
        ============================== */

        .categoria {

            margin:
                35px
                15px
                20px;

            padding-left: 12px;

            border-left:
                4px solid
                #00ff00;

            color: white;

            font-size: 24px;

            font-weight: 800;

            letter-spacing: 0.3px;

            text-shadow:
                0 0 10px
                rgba(0, 255, 0, 0.15);
        }


        /* ==============================
           PRODUTO
        ============================== */

        .produto-link {

            display: block;

            text-decoration: none;

            color: white;

            height: 100%;
        }


        .produto1 {

            height: 100%;

            overflow: hidden;

            background:
                linear-gradient(
                    145deg,
                    rgba(15, 15, 15, 0.95),
                    rgba(0, 0, 0, 0.90)
                );

            border:
                1px solid
                rgba(255, 255, 255, 0.16);

            border-radius: 14px;

            padding: 10px;

            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;

            position: relative;
        }


        .produto1:hover {

            transform:
                translateY(-5px);

            border-color:
                rgba(0, 255, 0, 0.65);

            box-shadow:
                0 8px 25px
                rgba(0, 0, 0, 0.45),

                0 0 15px
                rgba(0, 255, 0, 0.12);
        }


        /* ==============================
           IMAGEM
        ============================== */

        .imagemProduto {

            width: 100%;

            aspect-ratio: 1 / 1;

            overflow: hidden;

            border-radius: 10px;

            background: #111;

            margin-bottom: 10px;
        }


        .imagemProduto img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            display: block;

            transition:
                transform 0.4s ease;
        }


        .produto1:hover
        .imagemProduto img {

            transform: scale(1.06);
        }


        /* ==============================
           NOME DO PRODUTO
        ============================== */

        .item {

            color: white;

            font-size: 16px;

            font-weight: 700;

            line-height: 1.2;

            margin-top: 5px;

            display: -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;
        }


        /* ==============================
           DESCRIÇÃO
        ============================== */

        .descricao {

            color: #aaa;

            font-size: 12px;

            line-height: 1.4;

            margin-top: 6px;

            min-height: 34px;

            display: -webkit-box;

            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

            overflow: hidden;
        }


        /* ==============================
           PREÇO
        ============================== */

        .valor {

            margin-top: 10px;

            color: #00ff00;

            font-size: 18px;

            font-weight: 800;

            text-shadow:
                0 0 8px
                rgba(0, 255, 0, 0.15);
        }


        /* ==============================
           RESPONSIVIDADE
        ============================== */

        @media (max-width: 640px) {

            .categoria {

                font-size: 21px;

                margin-top: 28px;
            }

            .produto1 {

                padding: 8px;

                border-radius: 12px;
            }

            .item {

                font-size: 14px;
            }

            .descricao {

                font-size: 11px;
            }

            .valor {

                font-size: 16px;
            }

            .container-categorias {

                top: 61px;

                padding:
                    10px 8px;
            }

            .navCategoria {

                font-size: 13px;

                padding:
                    7px 13px;
            }

            .btn-seta {

                width: 34px;
                height: 34px;
            }
        }


        @media (min-width: 768px) {

            .produtos-container {

                padding-left: 30px;
                padding-right: 30px;
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

            <!-- LOGO -->

            <!--<a
                href="./carrinho.php"
                class="
                    logo-navbar
                    text-white
                    text-xl
                    font-bold
                    no-underline
                "
            >
                Logo
            </a> -->


            <!-- MENU DESKTOP -->

            <div
                class="
                    hidden
                    md:flex
                    items-center
                    gap-7
                "
            >

                <a
                    href="index.php?action=categoria"
                    class="
                        nav-link-custom
                        text-white
                        text-sm
                        font-medium
                        no-underline
                    "
                >
                    Página Inicial
                </a>

                <a
                    href="index.php?action=perfilCliente"
                    class="
                        nav-link-custom
                        text-white
                        text-sm
                        font-medium
                        no-underline
                    "
                >
                    Pedidos
                </a>

                <a
                    href="index.php?action=carrinho"
                    class="
                        nav-link-custom
                        text-white
                        text-sm
                        font-medium
                        no-underline
                    "
                >
                    <i class="bi bi-cart3 mr-1"></i>
                    Carrinho
                </a>

            </div>


            <!-- MENU MOBILE -->

            <button
                id="btnMenu"
                type="button"
                class="
                    md:hidden
                    text-white
                    text-2xl
                    p-2
                    bg-transparent
                    border-0
                    cursor-pointer
                "
                aria-label="Abrir menu"
            >
                <i class="bi bi-list"></i>
            </button>

        </div>


        <!-- MENU MOBILE ABERTO -->

        <div
            id="menuMobile"
            class="
                hidden
                md:hidden
                pb-4
            "
        >

            <div class="flex flex-col gap-2">

                <a
                    href="index.php?action=categoria"
                    class="
                        text-white
                        no-underline
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                        transition
                    "
                >
                    <i class="bi bi-house mr-2"></i>
                    Página Inicial
                </a>

                <a
                    href="index.php?action=perfilCliente"
                    class="
                        text-white
                        no-underline
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                        transition
                    "
                >
                    <i class="bi bi-receipt mr-2"></i>
                    Pedidos
                </a>

                <a
                    href="index.php?action=carrinho"
                    class="
                        text-white
                        no-underline
                        px-4
                        py-3
                        rounded-lg
                        hover:bg-white/10
                        transition
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
     NAVEGAÇÃO DE CATEGORIAS
========================================================= -->

<div class="container-categorias">

    <button
        class="btn-seta"
        onclick="scrollCategorias(-250)"
        aria-label="Categorias anteriores"
    >
        &#10094;
    </button>


    <div
        class="lista-categorias"
        id="navCategoria"
    >

        <?php foreach ($categorias as $cat): ?>

            <?php if (empty($cat['produtos'])) continue; ?>

            <a
                href="#categoria-<?= $cat['id'] ?>"
                class="navCategoria"
            >
                <?= htmlspecialchars($cat['nome']) ?>
            </a>

        <?php endforeach; ?>

    </div>


    <button
        class="btn-seta"
        onclick="scrollCategorias(250)"
        aria-label="Próximas categorias"
    >
        &#10095;
    </button>

</div>


<!-- =========================================================
     CATEGORIAS + PRODUTOS
========================================================= -->

<main
    class="
        produtos-container
        max-w-7xl
        mx-auto
        px-3
        sm:px-5
        pb-10
    "
>


<?php foreach ($categorias as $cat): ?>

    <?php if (empty($cat['produtos'])) continue; ?>


    <!-- TÍTULO DA CATEGORIA -->

    <div
        id="categoria-<?= $cat['id'] ?>"
        class="categoria"
    >
        <?= htmlspecialchars($cat['nome']) ?>
    </div>


    <!-- PRODUTOS -->

    <div
        class="
            grid
            grid-cols-2
            sm:grid-cols-3
            md:grid-cols-4
            lg:grid-cols-5
            xl:grid-cols-6
            gap-3
            md:gap-4
        "
    >

        <?php foreach ($cat['produtos'] as $row): ?>

            <a
                href="index.php?action=produto&id=<?= $row['id'] ?>"
                class="produto-link"
            >

                <div class="produto1">


                    <!-- IMAGEM -->

                    <div class="imagemProduto">

                        <img
                            loading="lazy"
                            src="uploads/<?= htmlspecialchars($row['imagem']) ?>"
                            alt="<?= htmlspecialchars($row['item']) ?>"
                        >

                    </div>


                    <!-- NOME -->

                    <div class="item">

                        <?= htmlspecialchars($row['item']) ?>

                    </div>


                    <!-- DESCRIÇÃO -->

                    <div class="descricao">

                        <?= htmlspecialchars($row['descricao']) ?>

                    </div>


                    <!-- PREÇO -->

                    <div class="valor">

                        R$
                        <?= number_format(
                            $row['valor'],
                            2,
                            ",",
                            "."
                        ) ?>

                    </div>


                </div>

            </a>

        <?php endforeach; ?>

    </div>


<?php endforeach; ?>


</main>


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
     * SCROLL DAS CATEGORIAS
     */

    function scrollCategorias(valor) {

        const nav =
            document.getElementById('navCategoria');

        if (!nav) {
            return;
        }

        nav.scrollBy({

            left: valor,

            behavior: 'smooth'

        });

    }


    /*
     * FECHA MENU MOBILE AO CLICAR
     * EM UMA OPÇÃO
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

</script>


</body>
</html>