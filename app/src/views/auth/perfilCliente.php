<?php
require_once __DIR__ . '/../../config/conexao.php';

session_start();

$id = $_SESSION['id_usuario'] ?? null;

$usuario = null;
$pedidos = null;

/*
|--------------------------------------------------------------------------
| BUSCA DADOS DO USUÁRIO
|--------------------------------------------------------------------------
*/
if ($id) {
    $stmt = $conn->prepare("
        SELECT usuario, rua, numero, bairro, cidade, ponto_de_referencia 
        FROM endereco 
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultUsuario = $stmt->get_result();
    $usuario = $resultUsuario->fetch_assoc();
}

/*
|--------------------------------------------------------------------------
| BUSCA PEDIDOS DO USUÁRIO
|--------------------------------------------------------------------------
*/
if ($id) {
    $stmt = $conn->prepare("
        SELECT id, usuario, item, valor, data_pedido, status 
        FROM pedido 
        WHERE usuario = ? 
        ORDER BY id DESC
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultPedido = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Meus Pedidos</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap JS apenas para o offcanvas -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- Bootstrap Icons -->
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
                        dark: '#030604'
                    },
                    boxShadow: {
                        neon: '0 0 20px rgba(0,255,0,0.25)',
                        'neon-lg': '0 0 35px rgba(0,255,0,0.35)'
                    }
                }
            }
        }
    </script>

    <style>

        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */

        body {

            background:
                radial-gradient(
                    circle at top right,
                    rgba(0, 255, 0, 0.08),
                    transparent 30%
                ),
                radial-gradient(
                    circle at bottom left,
                    rgba(0, 255, 0, 0.05),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    #030604,
                    #071008,
                    #030604
                );

            min-height: 100vh;

            color: white;

            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                sans-serif;

            background-attachment: fixed;

        }


        /*
        |--------------------------------------------------------------------------
        | NAVBAR
        |--------------------------------------------------------------------------
        */

        .minha-navbar {

            background:
                rgba(0, 0, 0, 0.90);

            backdrop-filter: blur(12px);

            -webkit-backdrop-filter: blur(12px);

            border-bottom:
                1px solid rgba(0, 255, 0, 0.25);

            box-shadow:
                0 5px 25px rgba(0, 255, 0, 0.10);

        }


        /*
        |--------------------------------------------------------------------------
        | BOTÃO MENU
        |--------------------------------------------------------------------------
        */

        .navbar-toggler {

            border:
                1px solid rgba(0, 255, 0, 0.5);

        }

        .navbar-toggler:focus {

            box-shadow:
                0 0 10px rgba(0, 255, 0, 0.35);

        }


        /*
        |--------------------------------------------------------------------------
        | OFFCANVAS
        |--------------------------------------------------------------------------
        */

        .offcanvas {

            background:
                #050805;

            color: white;

            border-left:
                1px solid rgba(0, 255, 0, 0.25);

        }

        .offcanvas-title {

            color: #00ff00;

            font-weight: bold;

        }

        .offcanvas .nav-link {

            color: #ccc;

            padding: 12px 10px;

            border-radius: 8px;

            transition: 0.25s;

        }

        .offcanvas .nav-link:hover {

            color: #00ff00;

            background:
                rgba(0, 255, 0, 0.08);

        }


        /*
        |--------------------------------------------------------------------------
        | TITULO
        |--------------------------------------------------------------------------
        */

        .titulo {

            color: white;

            margin-top: 100px;

            margin-bottom: 30px;

            text-align: center;

            font-size: 1.5rem;

            font-weight: 700;

        }


        /*
        |--------------------------------------------------------------------------
        | CARD DO PEDIDO
        |--------------------------------------------------------------------------
        */

        .pedido-card {

            position: relative;

            background:
                linear-gradient(
                    145deg,
                    rgba(0, 0, 0, 0.90),
                    rgba(5, 15, 7, 0.90)
                );

            border:
                1px solid rgba(0, 255, 0, 0.35);

            border-radius: 18px;

            color: white;

            margin-bottom: 18px;

            overflow: hidden;

            box-shadow:
                0 5px 25px rgba(0, 0, 0, 0.35);

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;

        }

        .pedido-card:hover {

            transform:
                translateY(-3px);

            border-color:
                rgba(0, 255, 0, 0.75);

            box-shadow:
                0 10px 35px rgba(0, 255, 0, 0.12);

        }


        /*
        |--------------------------------------------------------------------------
        | CABEÇALHO DO PEDIDO
        |--------------------------------------------------------------------------
        */

        .pedido-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 15px;

            padding: 18px;

            border-bottom:
                1px solid rgba(255, 255, 255, 0.08);

        }

        .pedido-id {

            color: #00ff00;

            font-size: 1rem;

            font-weight: 800;

        }

        .pedido-data {

            color: #aaa;

            font-size: 0.85rem;

        }


        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO DO PEDIDO
        |--------------------------------------------------------------------------
        */

        .pedido-conteudo {

            padding: 18px;

        }

        .pedido-produtos {

            color: #ddd;

            font-size: 0.9rem;

            line-height: 1.8;

            white-space: normal;

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        .pedido-total {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 10px;

            margin-top: 15px;

            padding-top: 15px;

            border-top:
                1px solid rgba(255, 255, 255, 0.08);

        }

        .valor-total {

            color: white;

            font-size: 1.05rem;

            font-weight: 700;

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status {

            padding: 6px 12px;

            border-radius: 999px;

            background:
                rgba(0, 255, 0, 0.08);

            border:
                1px solid rgba(0, 255, 0, 0.30);

            color: #00ff00;

            font-size: 0.8rem;

            font-weight: 700;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | PEDIDO VAZIO
        |--------------------------------------------------------------------------
        */

        .sem-pedidos {

            text-align: center;

            padding: 50px 20px;

            background:
                rgba(0, 0, 0, 0.55);

            border:
                1px solid rgba(0, 255, 0, 0.20);

            border-radius: 18px;

            color: #aaa;

        }

        .sem-pedidos i {

            display: block;

            color: #00ff00;

            font-size: 45px;

            margin-bottom: 15px;

        }


        /*
        |--------------------------------------------------------------------------
        | BOTÃO VOLTAR
        |--------------------------------------------------------------------------
        */

        .btn-voltar {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            margin-top: 5px;

            margin-bottom: 20px;

            padding: 10px 18px;

            border-radius: 10px;

            border:
                1px solid rgba(0, 255, 0, 0.45);

            color: white;

            background:
                rgba(0, 0, 0, 0.60);

            text-decoration: none;

            transition: 0.25s;

        }

        .btn-voltar:hover {

            color: black;

            background: #00ff00;

            box-shadow:
                0 0 18px rgba(0, 255, 0, 0.35);

        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVIDADE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 576px) {

            .titulo {

                font-size: 1.25rem;

            }

            .pedido-header {

                align-items: flex-start;

                flex-direction: column;

            }

            .pedido-total {

                align-items: flex-start;

                flex-direction: column;

            }

            .status {

                align-self: flex-start;

            }

        }

    </style>

</head>


<body>

<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar fixed-top minha-navbar">

    <div class="container-fluid px-3">

        <a
            class="navbar-brand text-white fw-bold"
            href="index.php?action=categoria"
        >

            <i class="bi bi-bag-check text-success"></i>

            Olá <?= htmlspecialchars($usuario['usuario'] ?? 'Visitante'); ?>!

        </a>





        <div
            class="offcanvas offcanvas-end"
            tabindex="-1"
            id="offcanvasNavbar"
        >

            <div class="offcanvas-header">

                <h5 class="offcanvas-title">

                    <i class="bi bi-list"></i>

                    Menu

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="offcanvas"
                ></button>

            </div>


            <div class="offcanvas-body">

                <ul class="navbar-nav">

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="index.php?action=categoria"
                        >

                            <i class="bi bi-house"></i>

                            Página inicial

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="index.php?action=carrinho"
                        >

                            <i class="bi bi-cart"></i>

                            Carrinho

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="https://wa.me/5588997443499"
                            target="_blank"
                        >

                            <i class="bi bi-whatsapp"></i>

                            Suporte

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link text-danger"
                            href="php?action=login"
                        >

                            <i class="bi bi-box-arrow-right"></i>

                            Sair

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </div>

</nav>


<!-- =========================================================
     CONTEÚDO
========================================================= -->

<main class="max-w-3xl mx-auto px-4 pb-10">

    <h1 class="titulo">

        <i class="bi bi-clock-history text-green-400"></i>

        Acompanhe Seu Pedido

    </h1>


    <!-- Botão voltar -->

    <a
        href="index.php?action=categoria"
        class="btn-voltar"
    >

        <i class="bi bi-arrow-left"></i>

        Continuar comprando

    </a>


    <!-- =====================================================
         PEDIDOS
    ====================================================== -->

    <?php if (isset($resultPedido) && $resultPedido->num_rows > 0): ?>

        <?php while ($pedidos = $resultPedido->fetch_assoc()): ?>


            <div class="pedido-card">


                <!-- Cabeçalho -->

                <div class="pedido-header">

                    <div>

                        <div class="pedido-id">

                            <i class="bi bi-receipt"></i>

                            #ID-<?= htmlspecialchars($pedidos['id'] ?? 'vazio'); ?>

                        </div>

                    </div>


                    <div class="pedido-data">

                        <i class="bi bi-calendar3"></i>

                        <?= htmlspecialchars($pedidos['data_pedido'] ?? 'vazio'); ?>

                    </div>

                </div>


                <!-- Conteúdo -->

                <div class="pedido-conteudo">


                    <div class="mb-2 text-gray-400 text-sm">

                        <i class="bi bi-basket"></i>

                        Itens do pedido

                    </div>


                    <div class="pedido-produtos">

                        <?php

                        $item = $pedidos['item'] ?? 'vazio';


                        /*
                        |--------------------------------------------------------------------------
                        | FORMATA PRODUTOS
                        |--------------------------------------------------------------------------
                        */

                        $item = preg_replace_callback(

                            '/(\d+)x\s+(.+?)\s*\(R\$\s+([\d.,]+)\)/m',

                            function ($matches) {

                                return
                                    '✓ ' .
                                    $matches[1] .
                                    'x ' .
                                    $matches[2] .
                                    ' (R$ ' .
                                    $matches[3] .
                                    ')';

                            },

                            $item

                        );


                        /*
                        |--------------------------------------------------------------------------
                        | FORMATA ADICIONAIS
                        |--------------------------------------------------------------------------
                        */

                        $item = preg_replace_callback(

                            '/^\s*-\s+(\d+)x\s+(.+?)\s*\(R\$\s+([\d.,]+)\)/m',

                            function ($matches) {

                                return
                                    '✓ ' .
                                    $matches[1] .
                                    'x ' .
                                    $matches[2] .
                                    ' (R$ ' .
                                    $matches[3] .
                                    ')';

                            },

                            $item

                        );


                        echo nl2br(
                            htmlspecialchars($item)
                        );

                        ?>

                    </div>


                    <!-- Total + Status -->

                    <div class="pedido-total">


                        <div class="valor-total">

                            <span class="text-gray-400 text-sm block">

                                Valor total

                            </span>

                            R$

                            <?= number_format(
                                (float)($pedidos['valor'] ?? 0),
                                2,
                                ',',
                                '.'
                            ); ?>

                        </div>


                        <div class="status">

                            <i class="bi bi-circle-fill text-[7px]"></i>

                            <?= htmlspecialchars(
                                $pedidos['status'] ?? 'Aguardando....'
                            ); ?>

                        </div>


                    </div>

                </div>

            </div>


        <?php endwhile; ?>


    <?php else: ?>


        <!-- =================================================
             SEM PEDIDOS
        ================================================== -->

        <div class="sem-pedidos">

            <i class="bi bi-bag-x"></i>

            <h3 class="text-white text-lg font-bold mb-2">

                Nenhum pedido encontrado

            </h3>

            <p class="mb-5">

                Você ainda não realizou nenhum pedido.

            </p>


            <a
                href="index.php?action=categoria"
                class="btn-voltar"
            >

                <i class="bi bi-shop"></i>

                Fazer um pedido

            </a>

        </div>


    <?php endif; ?>


</main>


</body>

</html>