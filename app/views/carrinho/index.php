<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?action=loginCliente");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        .container {
            margin-top: 30px;
        }
        .item-carrinho {
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-info h6 {
            margin-bottom: 5px;
        }
        .item-info small {
            color: #aaa;
        }
        .item-preco {
            font-size: 18px;
            font-weight: bold;
            color: #1500ff;
        }
        .btn-remover {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-remover:hover {
            background-color: #c82333;
        }
        .total-section {
            background-color: #2a2a2a;
            border: 2px solid #1500ff;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            text-align: right;
        }
        .total-section h5 {
            color: #1500ff;
            font-weight: bold;
        }

        button {
        margin-top: 50px;
        display: block;
        margin: 25px auto;
        border-radius: 10px;
        padding:10px;
        border: solid 2px black;
        background-color: #00ff00;
        color: black;
        }

    </style>
</head>
<body>
    <!--Inicio nav bar-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="./Pagina_inicial/Pagina_inicial.html">voltar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="./Pagina_inicial/Pagina_inicial.html">Pagina inicial</a>
                    <a class="nav-link active" href="#">Pedidos</a>
                    <a class="nav-link active" href="#">Suporte</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 style="margin-bottom: 30px; color: #1500ff;">🛒 Carrinho de Compras</h1>

        <div id="carrinho-vazio" style="display: none; text-align: center;">
            <h3>Seu carrinho está vazio</h3>
            <a href="?action=categoria" class="btn btn-primary mt-3">Continuar Comprando</a>
        </div>

        <div id="carrinho-itens">
            <!-- Os itens serão carregados aqui pelo JavaScript -->
        </div>

        <div id="carrinho-total" style="display: none;">
            <div class="total-section">
                <h5 id="total-preco">Total da Compra: R$ 0,00</h5>
            </div>
        </div>
        <button onclick="enviarWhatsApp()">Finalizar Compra</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
        function carregarCarrinho() {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            const carrinhoVazio = document.getElementById('carrinho-vazio');
            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');

            if(carrinho.length === 0) {
                carrinhoVazio.style.display = 'block';
                carrinhoItens.style.display = 'none';
                carrinhoTotal.style.display = 'none';
                return;
            }

            carrinhoVazio.style.display = 'none';
            carrinhoItens.style.display = 'block';
            carrinhoTotal.style.display = 'block';

            // Limpar itens anteriores
            carrinhoItens.innerHTML = '';

            let totalGeral = 0;

            carrinho.forEach((item, index) => {
                totalGeral += item.precoFinal;

                let adicionaisHTML = '';
                if(item.adicionais && item.adicionais.length > 0) {
                    adicionaisHTML = '<small style="display: block; color: #aaa; margin-top: 5px;">';
                    item.adicionais.forEach(ad => {
                        adicionaisHTML += '✓ ' + ad.nome + ' (R$ ' + ad.valor.toFixed(2).replace('.', ',') + ')<br>';
                    });
                    adicionaisHTML += '</small>';
                }

                const itemHTML = `
                    <div class="item-carrinho">
                        <div class="item-info" style="flex: 1;">
                            <h6>${item.nome}</h6>
                            <small>Quantidade: ${item.quantidade} x R$ ${item.precoUnitario.toFixed(2).replace('.', ',')}</small>
                            ${adicionaisHTML}
                        </div>
                        <div style="text-align: right; margin-right: 20px;">
                            <div class="item-preco">R$ ${item.precoFinal.toFixed(2).replace('.', ',')}</div>
                        </div>
                        <button class="btn-remover" onclick="removerDoCarrinho(${index})">Remover</button>
                    </div>
                `;
                carrinhoItens.innerHTML += itemHTML;
            });

            // Criar seção de total
            const totalHTML = `
                <div class="total-section">
                    <h5>Total da Compra: R$ ${totalGeral.toFixed(2).replace('.', ',')}</h5>
                </div>
            `;

            carrinhoTotal.innerHTML = totalHTML;
        }

        function removerDoCarrinho(index) {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            carrinho.splice(index, 1);
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            carregarCarrinho();
        }

        // Carregar carrinho ao abrir a página
        document.addEventListener('DOMContentLoaded', function() {
            carregarCarrinho();
        });


// enviar para o whatsapp

function enviarWhatsApp(){

    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');

    if(carrinho.length === 0){
        alert("Carrinho vazio!");
        return;
    }

    let mensagem = "🛒 *NOVO PEDIDO*\n\n";

    let total = 0;

    carrinho.forEach(item => {

        mensagem += "🍔 " + item.nome + "\n";
        mensagem += "Quantidade: " + item.quantidade + "\n";
        mensagem += "Preço: R$ " + item.precoFinal.toFixed(2).replace('.', ',') + "\n";

        if(item.adicionais && item.adicionais.length > 0){
            mensagem += "Adicionais:\n";
            item.adicionais.forEach(ad => {
                mensagem += " - " + ad.nome + " (R$ " + ad.valor.toFixed(2).replace('.', ',') + ")\n";
            });
        }

        mensagem += "\n";

        total += item.precoFinal;

    });

    mensagem += " *Total: R$ " + total.toFixed(2).replace('.', ',') + "*";

    const numero = "5588988188728"; // coloque o número do whatsapp da loja

    const url = `https://wa.me/${numero}?text=${encodeURIComponent(mensagem)}`;

    window.open(url, '_blank');

}
    </script>
</body>
</html>
