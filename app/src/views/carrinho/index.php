
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/conexao.php';

session_start();

/*
|--------------------------------------------------------------------------
| DADOS DA SESSÃO
|--------------------------------------------------------------------------
*/

$id_usuario = $_SESSION['id_usuario'] ?? null;
$rua = $_SESSION['rua'] ?? null;
$numero = $_SESSION['numero'] ?? null;
$bairro = $_SESSION['bairro'] ?? null;
$cidade = $_SESSION['cidade'] ?? null;
$ponto_de_referencia = $_SESSION['ponto_de_referencia'] ?? null;
$telefone = $_SESSION['telefone'] ?? null;


/*
|--------------------------------------------------------------------------
| BUSCA ENDEREÇO
|--------------------------------------------------------------------------
*/

$endereco = null;

if ($id_usuario) {

    $stmt = $conn->prepare("
        SELECT rua, numero, bairro, cidade, ponto_de_referencia, telefone
        FROM endereco
        WHERE id = ?
    ");

    if ($stmt) {

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $enderecoUsuario = $stmt->get_result();

        $endereco = $enderecoUsuario->fetch_assoc();

        $stmt->close();
    }
}


/*
|--------------------------------------------------------------------------
| BUSCA USUÁRIO
|--------------------------------------------------------------------------
*/

$user = null;

if ($id_usuario) {

    $stmt = $conn->prepare("
        SELECT nome
        FROM usuarios
        WHERE id = ?
    ");

    if ($stmt) {

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $ResultadoNomeDoUsuario = $stmt->get_result();

        $user = $ResultadoNomeDoUsuario->fetch_assoc();

        $stmt->close();
    }
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

    <title>Carrinho de Compras</title>


    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>


    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        principal: '#1500ff',

                        verde: '#00ff00'

                    },

                    boxShadow: {

                        neonGreen:
                            '0 0 15px rgba(0,255,0,.25)',

                        neonBlue:
                            '0 0 15px rgba(21,0,255,.30)'

                    }

                }

            }

        };

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
                    #7832b433,
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

            padding: 0;

        }


        /*
        |--------------------------------------------------------------------------
        | SCROLLBAR
        |--------------------------------------------------------------------------
        */

        ::-webkit-scrollbar {

            width: 7px;

        }


        ::-webkit-scrollbar-track {

            background: #080808;

        }


        ::-webkit-scrollbar-thumb {

            background: #1500ff;

            border-radius: 10px;

        }


        ::-webkit-scrollbar-thumb:hover {

            background: #321fff;

        }


        /*
        |--------------------------------------------------------------------------
        | GLASS
        |--------------------------------------------------------------------------
        */

        .glass {

            background: rgba(0, 0, 0, 0.78);

            backdrop-filter: blur(10px);

            -webkit-backdrop-filter: blur(10px);

        }


        /*
        |--------------------------------------------------------------------------
        | PIX
        |--------------------------------------------------------------------------
        */

        #pix-container {

            display: none;

        }


        /*
        |--------------------------------------------------------------------------
        | ANIMAÇÃO PIX
        |--------------------------------------------------------------------------
        */

        .pix-animation {

            animation: pixAnimation .3s ease;

        }


        @keyframes pixAnimation {

            from {

                opacity: 0;

                transform: translateY(-8px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SPINNER
        |--------------------------------------------------------------------------
        */

        .spinner {

            animation: spin 1s linear infinite;

        }


        @keyframes spin {

            from {

                transform: rotate(0deg);

            }

            to {

                transform: rotate(360deg);

            }

        }

    </style>

</head>


<body>


<!--
|--------------------------------------------------------------------------
| NAVBAR
|--------------------------------------------------------------------------
-->

<nav
    class="sticky top-0 z-50 border-b border-white/10 bg-black/90 backdrop-blur-xl"
>

    <div
        class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3"
    >


        <!-- Voltar -->

        <a
            href="?action=login"
            class="flex items-center gap-2 font-semibold text-white transition duration-300 hover:text-blue-500"
        >

            <i class="bi bi-arrow-left text-lg"></i>

            <span class="hidden sm:inline">
                Voltar
            </span>

        </a>


        <!-- Menu -->

        <div
            class="hidden items-center gap-6 md:flex"
        >

            <a
                href="index.php?action=categoria"
                class="text-sm text-gray-300 transition hover:text-white"
            >

                Página inicial

            </a>


            <a
                href="?action=perfilCliente"
                class="text-sm text-gray-300 transition hover:text-white"
            >

                Pedidos

            </a>


            <a
                href="#"
                class="text-sm text-gray-300 transition hover:text-white"
            >

                Suporte

            </a>

        </div>


        <!-- Carrinho -->

        <div
            class="flex h-9 w-9 items-center justify-center rounded-full border border-blue-500/40 bg-blue-500/10"
        >

            <i
                class="bi bi-cart3 text-lg text-blue-500"
            ></i>

        </div>

    </div>

</nav>



<!--
|--------------------------------------------------------------------------
| CONTAINER
|--------------------------------------------------------------------------
-->

<main
    class="mx-auto w-full max-w-4xl px-4 py-6 sm:py-8"
>


    <!--
    |--------------------------------------------------------------------------
    | CABEÇALHO
    |--------------------------------------------------------------------------
    -->

    <div class="mb-7 text-center">


        <div
            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-white/10 bg-black/50 shadow-lg"
        >

            <i
                class="bi bi-cart3 text-2xl text-white"
            ></i>

        </div>


        <h1
            class="text-2xl font-bold text-white sm:text-3xl"
        >

            🛒 Carrinho de Compras

        </h1>


        <p
            class="mt-2 text-sm text-gray-400"
        >

            Confira seus produtos antes de finalizar.

        </p>

    </div>



    <!--
    |--------------------------------------------------------------------------
    | CARRINHO VAZIO
    |--------------------------------------------------------------------------
    -->

    <div
        id="carrinho-vazio"
        class="hidden rounded-xl border border-white/20 bg-black/75 p-8 text-center shadow-xl"
    >

        <div
            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/5"
        >

            <i
                class="bi bi-cart-x text-3xl text-gray-500"
            ></i>

        </div>


        <h3
            class="text-xl font-bold text-white"
        >

            Seu carrinho está vazio

        </h3>


        <p
            class="mt-2 text-sm text-gray-400"
        >

            Adicione algum produto para continuar.

        </p>


        <a
            href="?action=categoria"
            class="mx-auto mt-5 inline-flex items-center gap-2 rounded-lg border border-blue-600 bg-black/80 px-5 py-2.5 text-sm font-bold text-white transition duration-300 hover:bg-blue-600 hover:shadow-neonBlue"
        >

            <i class="bi bi-plus-lg"></i>

            Adicionar produto

        </a>

    </div>



    <!--
    |--------------------------------------------------------------------------
    | ITENS DO CARRINHO
    |--------------------------------------------------------------------------
    -->

    <div
        id="carrinho-itens"
        class="space-y-4"
    >

    </div>



    <!--
    |--------------------------------------------------------------------------
    | FORMA DE PAGAMENTO
    |--------------------------------------------------------------------------
    -->

    <div
        id="carrinho-pagamento"
        class="mt-5 hidden"
    >

        <div
            class="glass rounded-xl border border-white/20 p-4 shadow-lg sm:p-5"
        >


            <div
                class="mb-4 flex items-center gap-3"
            >

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-white/5"
                >

                    <i
                        class="bi bi-credit-card text-lg text-white"
                    ></i>

                </div>


                <div>

                    <h3
                        class="font-bold text-white"
                    >

                        Forma de pagamento

                    </h3>


                    <p
                        class="text-xs text-gray-400"
                    >

                        Escolha uma opção para continuar.

                    </p>

                </div>

            </div>


            <select
                id="forma_pagamento"
                class="w-full rounded-lg border border-white/30 bg-black/85 px-4 py-3 text-sm text-white outline-none transition duration-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30"
            >

                <option
                    value=""
                    selected
                >

                    Forma de pagamento

                </option>


                <option value="Dinheiro">

                    💵 Dinheiro

                </option>


                <option value="Pix">

                    ⚡ Pix

                </option>


                <option value="Promissoria">

                    📄 Promissória

                </option>

            </select>


            <!--
            |--------------------------------------------------------------------------
            | PIX
            |--------------------------------------------------------------------------
            -->

            <div
                id="pix-container"
                class="mt-4 rounded-xl border border-green-500/40 bg-black/80 p-4 shadow-neonGreen pix-animation"
            >

                <div
                    class="mb-3 flex items-center gap-2"
                >

                    <i
                        class="bi bi-qr-code text-xl text-green-400"
                    ></i>


                    <span
                        class="font-bold text-green-400"
                    >

                        Pagamento via PIX

                    </span>

                </div>


                <div
                    class="flex flex-col gap-2 sm:flex-row"
                >

                    <input
                        type="text"
                        id="pix-chave"
                        value="88997443499"
                        readonly
                        aria-label="Chave PIX"
                        class="min-w-0 flex-1 rounded-lg border border-green-500/50 bg-black px-3 py-2.5 text-sm text-white outline-none"
                    >


                    <button
                        type="button"
                        id="btn-copiar-pix"
                        class="!m-0 flex items-center justify-center gap-2 rounded-lg border border-green-500 bg-green-500 px-4 py-2.5 font-bold text-black transition duration-300 hover:bg-green-400 hover:shadow-neonGreen"
                    >

                        <i class="bi bi-copy"></i>

                        Copiar

                    </button>

                </div>


                <p
                    class="mt-3 text-xs text-gray-400"
                >

                    Após realizar o pagamento favor enviar o comprovante!

                </p>

            </div>

        </div>

    </div>



    <!--
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    -->

    <div
        id="carrinho-total"
        class="mt-5 hidden"
    >

    </div>



    <!--
    |--------------------------------------------------------------------------
    | ADICIONAR PRODUTO
    |--------------------------------------------------------------------------
    -->

    <div
        class="mt-5 flex justify-center"
    >

        <a
            href="?action=categoria"
            class="flex h-10 items-center gap-2 rounded-full border border-blue-600 bg-black/70 px-4 text-sm font-semibold text-white transition duration-300 hover:bg-blue-600 hover:shadow-neonBlue"
        >

            <i class="bi bi-plus-lg"></i>

            Adicionar

        </a>

    </div>



    <!--
    |--------------------------------------------------------------------------
    | FINALIZAR
    |--------------------------------------------------------------------------
    -->

    <button
        type="button"
        onclick="enviarWhatsApp()"
        class="mx-auto mt-5 flex w-full items-center justify-center gap-2 rounded-xl border border-green-500 bg-black/80 px-5 py-3.5 font-bold text-white transition duration-300 hover:bg-green-500 hover:text-black hover:shadow-neonGreen sm:w-auto sm:min-w-[250px]"
    >

        <i
            class="bi bi-check-circle-fill"
        ></i>

        Finalizar Compra

    </button>


</main>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"
></script>



<script>

/*
|--------------------------------------------------------------------------
| DADOS PHP → JAVASCRIPT
|--------------------------------------------------------------------------
*/

const idUsuario =
    <?= json_encode($id_usuario); ?>;

const rua =
    <?= json_encode($rua); ?>;

const numero =
    <?= json_encode($numero); ?>;

const bairro =
    <?= json_encode($bairro); ?>;

const cidade =
    <?= json_encode($cidade); ?>;

const ponto_de_referencia =
    <?= json_encode($ponto_de_referencia); ?>;

const user =
    <?= json_encode($user); ?>;

const telefone =
    <?= json_encode($telefone); ?>;



const chavePix = "88997443499";



/*
|--------------------------------------------------------------------------
| CONTROLE DO PIX
|--------------------------------------------------------------------------
*/

function controlarPix() {

    const formaPagamento =
        document.getElementById('forma_pagamento');

    const pixContainer =
        document.getElementById('pix-container');

    const pixChave =
        document.getElementById('pix-chave');


    if (
        !formaPagamento ||
        !pixContainer ||
        !pixChave
    ) {

        return;

    }


    if (
        formaPagamento.value === 'Pix'
    ) {

        pixContainer.style.display = 'block';

        pixChave.value = chavePix;

    } else {

        pixContainer.style.display = 'none';

    }

}



/*
|--------------------------------------------------------------------------
| COPIAR PIX
|--------------------------------------------------------------------------
*/

async function copiarPix() {

    const pixChave =
        document.getElementById('pix-chave');

    const botao =
        document.getElementById('btn-copiar-pix');


    if (!pixChave) {

        return;

    }


    try {

        await navigator.clipboard.writeText(
            pixChave.value
        );


        if (botao) {

            const textoOriginal =
                botao.innerHTML;


            botao.innerHTML =

                '<i class="bi bi-check-lg"></i> Copiado!';


            setTimeout(() => {

                botao.innerHTML =
                    textoOriginal;

            }, 2000);

        }

    }

    catch (error) {

        pixChave.select();

        pixChave.setSelectionRange(
            0,
            99999
        );


        document.execCommand('copy');

        alert('Chave PIX copiada!');

    }

}



/*
|--------------------------------------------------------------------------
| DOM READY
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    function() {


        const formaPagamento =
            document.getElementById(
                'forma_pagamento'
            );


        const botaoCopiarPix =
            document.getElementById(
                'btn-copiar-pix'
            );


        if (formaPagamento) {

            formaPagamento.addEventListener(
                'change',
                controlarPix
            );

        }


        if (botaoCopiarPix) {

            botaoCopiarPix.addEventListener(
                'click',
                copiarPix
            );

        }


        controlarPix();


        carregarCarrinho();

    }
);



/*
|--------------------------------------------------------------------------
| CARREGAR CARRINHO
|--------------------------------------------------------------------------
*/

function carregarCarrinho() {

    const carrinho =
        JSON.parse(
            localStorage.getItem(
                'carrinho'
            ) || '[]'
        );


    const carrinhoVazio =
        document.getElementById(
            'carrinho-vazio'
        );


    const carrinhoItens =
        document.getElementById(
            'carrinho-itens'
        );


    const carrinhoTotal =
        document.getElementById(
            'carrinho-total'
        );


    const carrinhoPagamento =
        document.getElementById(
            'carrinho-pagamento'
        );



    /*
    |--------------------------------------------------------------------------
    | CARRINHO VAZIO
    |--------------------------------------------------------------------------
    */

    if (
        carrinho.length === 0
    ) {

        carrinhoVazio.style.display =
            'block';


        carrinhoItens.style.display =
            'none';


        carrinhoTotal.style.display =
            'none';


        carrinhoPagamento.style.display =
            'none';


        return;

    }



    /*
    |--------------------------------------------------------------------------
    | CARRINHO COM PRODUTOS
    |--------------------------------------------------------------------------
    */

    carrinhoVazio.style.display =
        'none';


    carrinhoItens.style.display =
        'block';


    carrinhoTotal.style.display =
        'block';


    carrinhoPagamento.style.display =
        'block';



    carrinhoItens.innerHTML = '';


    let totalGeral = 0;



    /*
    |--------------------------------------------------------------------------
    | PRODUTOS
    |--------------------------------------------------------------------------
    */

    carrinho.forEach(
        (item, index) => {


            const precoUnitario =
                Number(
                    item.precoUnitario || 0
                );


            const quantidade =
                Number(
                    item.quantidade || 1
                );


            const precoFinal =
                Number(
                    item.precoFinal || 0
                );


            totalGeral += precoFinal;



            /*
            |--------------------------------------------------------------------------
            | PRODUTO
            |--------------------------------------------------------------------------
            */

            let produtoHTML = `

                <div
                    class="mt-2 flex items-center gap-2 text-sm text-gray-300"
                >

                    <i
                        class="bi bi-check-circle-fill text-green-400"
                    ></i>

                    <span>

                        ${quantidade}x

                        R$
                        ${precoUnitario.toFixed(2).replace('.', ',')}

                    </span>

                </div>

            `;



            /*
            |--------------------------------------------------------------------------
            | ADICIONAIS
            |--------------------------------------------------------------------------
            */

            let adicionaisHTML = '';


            if (
                item.adicionais &&
                item.adicionais.length > 0
            ) {


                adicionaisHTML = `

                    <div
                        class="mt-3 border-t border-white/10 pt-3"
                    >

                        <p
                            class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500"
                        >

                            Adicionais

                        </p>

                `;


                item.adicionais.forEach(
                    ad => {


                        const valorAdicional =

                            Number(ad.valor || 0) *
                            quantidade;


                        adicionaisHTML += `

                            <div
                                class="flex items-center justify-between gap-3 py-1 text-sm"
                            >

                                <span
                                    class="text-gray-300"
                                >

                                    <span
                                        class="text-green-400"
                                    >

                                        ✓

                                    </span>

                                    ${quantidade}x
                                    ${ad.nome}

                                </span>


                                <span
                                    class="whitespace-nowrap text-gray-400"
                                >

                                    R$
                                    ${valorAdicional.toFixed(2).replace('.', ',')}

                                </span>

                            </div>

                        `;

                    }
                );


                adicionaisHTML += `

                    </div>

                `;

            }



            /*
            |--------------------------------------------------------------------------
            | CARD DO PRODUTO
            |--------------------------------------------------------------------------
            */

            const itemHTML = `

                <div
                    class="relative overflow-hidden rounded-xl border border-white/20 bg-black/80 p-4 shadow-lg transition duration-300 hover:border-white/40 sm:p-5"
                >


                    <!-- Barra lateral -->

                    <div
                        class="absolute left-0 top-0 h-full w-1 bg-white"
                    ></div>


                    <div
                        class="flex items-start justify-between gap-4"
                    >


                        <div
                            class="min-w-0 flex-1 pl-2"
                        >


                            <div
                                class="flex items-center gap-3"
                            >


                                <div
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white/5"
                                >

                                    <i
                                        class="bi bi-bag text-xl text-white"
                                    ></i>

                                </div>


                                <div
                                    class="min-w-0"
                                >

                                    <h6
                                        class="truncate text-base font-bold text-white sm:text-lg"
                                    >

                                        ${item.nome}

                                    </h6>


                                    ${produtoHTML}

                                </div>

                            </div>


                            ${adicionaisHTML}


                            <div
                                class="mt-4"
                            >

                                <span
                                    class="text-xl font-bold text-white"
                                >

                                    R$

                                    ${precoFinal.toFixed(2).replace('.', ',')}

                                </span>

                            </div>

                        </div>



                        <!-- REMOVER -->

                        <button
                            type="button"
                            class="!m-0 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-red-500/40 bg-black text-red-500 transition duration-300 hover:bg-red-500 hover:text-white"
                            onclick="removerDoCarrinho(${index})"
                            title="Remover produto"
                        >

                            <i
                                class="bi bi-trash3"
                            ></i>

                        </button>


                    </div>

                </div>

            `;


            carrinhoItens.innerHTML +=
                itemHTML;

        }
    );



    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    const totalHTML = `

        <div
            class="rounded-xl border border-white/30 bg-black/85 p-5 shadow-lg"
        >

            <div
                class="flex items-center justify-between gap-4"
            >

                <div>

                    <p
                        class="text-sm text-gray-400"
                    >

                        Valor Total

                    </p>


                    <p
                        class="mt-1 text-xs text-gray-600"
                    >

                        ${carrinho.length}

                        ${carrinho.length === 1
                            ? 'produto'
                            : 'produtos'}

                    </p>

                </div>


                <h3
                    class="text-xl font-bold text-white sm:text-2xl"
                >

                    R$

                    ${totalGeral.toFixed(2).replace('.', ',')}

                </h3>

            </div>

        </div>

    `;


    carrinhoTotal.innerHTML =
        totalHTML;

}



/*
|--------------------------------------------------------------------------
| REMOVER PRODUTO
|--------------------------------------------------------------------------
*/

function removerDoCarrinho(index) {

    const carrinho =
        JSON.parse(
            localStorage.getItem(
                'carrinho'
            ) || '[]'
        );


    carrinho.splice(
        index,
        1
    );


    localStorage.setItem(
        'carrinho',
        JSON.stringify(carrinho)
    );


    carregarCarrinho();

}



/*
|--------------------------------------------------------------------------
| ENVIAR PEDIDO
|--------------------------------------------------------------------------
*/

function enviarWhatsApp() {


    const carrinho =
        JSON.parse(
            localStorage.getItem(
                'carrinho'
            ) || '[]'
        );


    /*
    |--------------------------------------------------------------------------
    | CARRINHO VAZIO
    |--------------------------------------------------------------------------
    */

    if (
        carrinho.length === 0
    ) {

        alert(
            "Carrinho vazio!"
        );

        return;

    }



    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    if (!idUsuario) {

        alert(
            "Você precisa fazer login para finalizar o pedido!"
        );


        window.location.href =
            "index.php?action=login&redirect=carrinho";


        return;

    }


    if(!rua){
        alert("Você precisa cadastrar um endereço para fazer o pedido!");

        window.location.href = "index.php?action=cadastroDeEndereco&redirect=carrinho";

        return;

    }



    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    let total = 0;


    carrinho.forEach(
        item => {

            total +=
                Number(
                    item.precoFinal || 0
                );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | PRODUTOS
    |--------------------------------------------------------------------------
    */

    const produtoNome =

        carrinho

            .map(
                item => {


                    let descricao =

                        `${item.quantidade}x ${item.nome} ` +

                        `(R$ ` +

                        `${(
                            Number(
                                item.precoUnitario || 0
                            ) *

                            Number(
                                item.quantidade || 1
                            )

                        ).toFixed(2).replace('.', ',')}` +

                        `)`;


                    if (
                        item.adicionais &&
                        item.adicionais.length > 0
                    ) {

                        descricao +=
                            '\nAdicionais:';


                        descricao +=

                            item.adicionais

                                .map(
                                    ad =>

                                        `\n - ` +

                                        `${item.quantidade}x ` +

                                        `${ad.nome} ` +

                                        `(R$ ` +

                                        `${(
                                            Number(ad.valor || 0) *

                                            Number(
                                                item.quantidade || 1
                                            )

                                        ).toFixed(2).replace('.', ',')}` +

                                        `)`

                                )

                                .join('');

                    }


                    return descricao;

                }
            )

            .join('\n\n');



    /*
    |--------------------------------------------------------------------------
    | PAGAMENTO
    |--------------------------------------------------------------------------
    */

    const formaPagamentoSelect =
        document.getElementById(
            'forma_pagamento'
        );


    const formaPagamento =
        formaPagamentoSelect
            ? formaPagamentoSelect.value
            : '';



    if (!formaPagamento) {

        alert(
            'Selecione uma forma de pagamento antes de finalizar o pedido.'
        );


        if (formaPagamentoSelect) {

            formaPagamentoSelect.focus();

        }


        return;

    }



    /*
    |--------------------------------------------------------------------------
    | FORM DATA
    |--------------------------------------------------------------------------
    */

    const formData =
        new FormData();


    formData.append(
        'id_usuario',
        idUsuario
    );


    formData.append(
        'produto',
        produtoNome
    );


    formData.append(
        'valor',
        total.toFixed(2)
    );


    formData.append(
        'pagamento',
        formaPagamento
    );


    formData.append(
        'rua',
        rua || ''
    );


    formData.append(
        'bairro',
        bairro || ''
    );


    formData.append(
        'numero',
        numero || ''
    );


    formData.append(
        'cidade',
        cidade || ''
    );


    formData.append(
        'ponto_de_referencia',
        ponto_de_referencia || ''
    );

    formData.append(
        'telefone',
        telefone || ''
    );


    formData.append(
        'nome',
        user && user.nome
            ? user.nome
            : ''
    );



    /*
    |--------------------------------------------------------------------------
    | BOTÃO FINALIZAR
    |--------------------------------------------------------------------------
    */

    const botaoFinalizar =
        document.querySelector(
            'button[onclick="enviarWhatsApp()"]'
        );


    if (botaoFinalizar) {

        botaoFinalizar.disabled =
            true;


        botaoFinalizar.classList.add(
            'cursor-not-allowed',
            'opacity-70'
        );


        botaoFinalizar.innerHTML = `

            <i
                class="bi bi-arrow-repeat spinner"
            ></i>

            Enviando pedido...

        `;

    }



    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */

    fetch(
        'index.php?action=enviarPedido',
        {

            method: 'POST',

            body: formData

        }
    )


    .then(
        response => {


            if (!response.ok) {

                throw new Error(
                    'Erro HTTP: ' +
                    response.status
                );

            }


            return response.json();

        }
    )


    .then(
        data => {


            console.log(
                'Pedido salvo:',
                data
            );


            if (data.success) {


                /*
                |--------------------------------------------------------------------------
                | LIMPA CARRINHO
                |--------------------------------------------------------------------------
                */

                localStorage.removeItem(
                    'carrinho'
                );


                alert(
                    'Pedido registrado com sucesso!'
                );


                /*
                |--------------------------------------------------------------------------
                | REDIRECIONA
                |--------------------------------------------------------------------------
                */

                window.location.href =
                    "index.php?action=perfilCliente&redirect=carrinho";


            } else {


                alert(
                    'Erro ao salvar pedido: ' +

                    (
                        data.message ||
                        'Tente novamente.'
                    )
                );


                restaurarBotao();

            }

        }
    )


    .catch(
        error => {


            console.error(
                'Erro ao enviar pedido:',
                error
            );


            alert(
                'Erro ao enviar pedido. ' +
                'Verifique sua conexão e tente novamente.'
            );


            restaurarBotao();

        }
    );

}



/*
|--------------------------------------------------------------------------
| RESTAURAR BOTÃO
|--------------------------------------------------------------------------
*/

function restaurarBotao() {

    const botaoFinalizar =
        document.querySelector(
            'button[onclick="enviarWhatsApp()"]'
        );


    if (botaoFinalizar) {

        botaoFinalizar.disabled =
            false;


        botaoFinalizar.classList.remove(
            'cursor-not-allowed',
            'opacity-70'
        );


        botaoFinalizar.innerHTML = `

            <i
                class="bi bi-check-circle-fill"
            ></i>

            Finalizar Compra

        `;

    }

}

</script>


</body>

</html>

