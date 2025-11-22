<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>

    <!-- Importa o Bootstrap para estilização -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <style>
        /* Estilo visual do bloco de cada item do carrinho */
        .item-carrinho {
            background: #bddcdfff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        /* Estilo do botão vermelho de remover item */
        .btn-remover {
            background: red;
            color: white;
        }
    </style>
</head>
<body class="container mt-4">

    <h2>Carrinho de Compras 🛒</h2>
    <hr>

    <!-- Aqui serão listados os itens do carrinho -->
    <div id="listaCarrinho"></div>

    <!-- Exibe o total geral do carrinho -->
    <h3 id="totalGeral">Total Geral: R$ 0,00</h3>

    <!-- Botões do carrinho -->
    <button class="btn btn-danger mt-3" onclick="limparCarrinho()">Limpar Carrinho</button>
    <button class="btn btn-success mt-3" onclick="finalizarPedido()">Finalizar Pedido</button>

    <script>

        /*
         * Função que carrega todos os itens salvos no localStorage
         * e exibe no HTML
         */
        function carregarCarrinho() {

            // Recupera o carrinho salvo; se não existir, cria lista vazia
            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

            let lista = document.getElementById("listaCarrinho");

            lista.innerHTML = ""; // limpa o HTML antes de atualizar

            let totalGeral = 0; // variável para somar o valor total final

            // Percorre todos os itens do carrinho
            carrinho.forEach((item, index) => {

                // Soma cada item ao total geral
                totalGeral += item.total;

                // Monta os adicionais
                let adicionaisHTML = "";
                if (item.adicionais.length > 0) {
                    adicionaisHTML = "<b>Adicionais:</b><br>";

                    // Para cada adicional, cria uma linha dentro do HTML
                    item.adicionais.forEach(a => {
                        adicionaisHTML +=
                            "- " + a.nome + " (R$ " + a.valor.toFixed(2).replace(".", ",") + ")<br>";
                    });
                }

                // Insere o item dentro da lista HTML
                lista.innerHTML += `
                    <div class="item-carrinho">
                        <h4>${item.nome}</h4>
                        <p><b>Quantidade:</b> ${item.quantidade}</p>

                        ${adicionaisHTML} <!-- Lista de adicionais se houver -->

                        <p><b>Total:</b> R$ ${item.total.toFixed(2).replace(".", ",")}</p>

                        <!-- Botão de remover item -->
                        <button class="btn btn-remover" onclick="removerItem(${index})">
                            Remover
                        </button>
                    </div>
                `;
            });

            // Atualiza o total geral na tela
            document.getElementById("totalGeral").innerHTML =
                "Total Geral: R$ " + totalGeral.toFixed(2).replace(".", ",");
        }


        /*
         * Função para remover um item do carrinho pelo índice
         */
        function removerItem(indice) {

            // Recupera carrinho atual
            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

            // Remove 1 item na posição "indice"
            carrinho.splice(indice, 1);

            // Atualiza no localStorage
            localStorage.setItem("carrinho", JSON.stringify(carrinho));

            // Recarrega a lista na tela
            carregarCarrinho();
        }


        /*
         * Remove TODOS os itens do carrinho
         */
        function limparCarrinho() {
            localStorage.removeItem("carrinho"); // apaga o carrinho inteiro
            carregarCarrinho(); // atualiza a tela
        }


        /*
         * Monta a mensagem e envia para o WhatsApp
         */
        function finalizarPedido() {

            // Pega o carrinho salvo
            let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

            // Se estiver vazio, avisa
            if (carrinho.length === 0) {
                alert("Seu carrinho está vazio!");
                return;
            }

            // Mensagem inicial do pedido
            let mensagem = "Olá, quero fazer um pedido:%0A%0A";

            // Monta a mensagem item por item
            carrinho.forEach(item => {

                mensagem += `🍔 *${item.nome}*%0A`;
                mensagem += `Quantidade: ${item.quantidade}%0A`;

                // Se houver adicionais
                if (item.adicionais.length > 0) {
                    mensagem += "Adicionais:%0A";

                    item.adicionais.forEach(a => {
                        mensagem += `- ${a.nome} (R$ ${a.valor.toFixed(2)})%0A`;
                    });

                    // ⚠️ Aqui você está apagando o carrinho dentro do loop
                    // Isso significa que ele é apagado várias vezes
                    localStorage.removeItem("carrinho");
                }

                // Total do item
                mensagem += `Total: R$ ${item.total.toFixed(2)}%0A%0A`;
            });

            // Pega o total geral da tela
            let totalGeral = document
                .getElementById("totalGeral")
                .textContent.replace("Total Geral: R$ ", "");

            // Adiciona ao texto
            mensagem += `*TOTAL GERAL:* R$ ${totalGeral}%0A%0A`;

            // Número do WhatsApp que receberá o pedido
            let numero = "5588988188728";

            // Abre o WhatsApp com o pedido montado
            window.open(`https://wa.me/${numero}?text=${mensagem}`, "_blank");
        }

        // Carrega o carrinho automaticamente ao abrir a página
        carregarCarrinho();

    </script>

</body>
</html>
