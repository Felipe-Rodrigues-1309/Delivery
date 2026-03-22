<?php
// Inicia a sessão do usuário. Isso garante que temos acesso ao ID do usuário logado.
// Sem sessão, não conseguimos saber quem está fazendo o pedido.
session_start();

// Se não houver usuário logado, redireciona para a página de login.
// Isso impede que pessoas acessem o carrinho diretamente sem se autenticar.

/*if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php?action=login");
    exit();
}*/


// Guardamos o ID do usuário para usar no JavaScript e no envio do pedido.
$id_usuario = $_SESSION['id_usuario'];
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <!-- Usamos Bootstrap para layout básico e componentes responsivos. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Estilos customizados para tornar o carrinho mais legível. -->
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
    <!-- Navegação superior: links de volta à página inicial e página de pedidos -->
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

        <!-- Mensagem exibida quando não há produtos no carrinho -->
        <div id="carrinho-vazio" style="display: none; text-align: center;">
            <h3>Seu carrinho está vazio</h3>
            <a href="?action=categoria" class="btn btn-primary mt-3">Continuar Comprando</a>
        </div>

        <!-- Aqui o JavaScript injeta os cards dos itens que estiverem no carrinho -->
        <div id="carrinho-itens">
            <!-- Os itens serão carregados aqui pelo JavaScript -->
        </div>

        <!-- Exibe o total do pedido quando houver itens no carrinho -->
        <div id="carrinho-total" style="display: none;">
            <div class="total-section">
                <h5 id="total-preco">Total da Compra: R$ 0,00</h5>
            </div>
        </div>
        <button onclick="enviarWhatsApp()">Finalizar Compra</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
        // ### Variáveis/passagem de dados do PHP para JS
        // O PHP passa o ID do usuário logado para o JavaScript usando json_encode.
        // Isso permite enviar o ID ao servidor quando o pedido for salvo.
        const idUsuario = <?= json_encode($id_usuario); ?>;

        // ### Função principal que renderiza o carrinho na página
        function carregarCarrinho() {
            // Busca o carrinho salvo no localStorage.
            // O carrinho é um array de objetos, por exemplo: [{ nome, quantidade, precoUnitario, precoFinal, adicionais }]
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');

            // Elementos do DOM usados para renderizar a UI.
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

        // Remove um item do carrinho (por índice) e atualiza o localStorage.
        // Esse índice corresponde à posição do item na lista exibida.
        function removerDoCarrinho(index) {
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
            carrinho.splice(index, 1);
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            carregarCarrinho();
        }

        // Carrega os dados do carrinho assim que a página termina de carregar.
        // Isso garante que, ao abrir a página, o usuário veja sempre o estado atual.
        document.addEventListener('DOMContentLoaded', function() {
            carregarCarrinho();
        });


// enviar para o whatsapp e salvar pedido no servidor
function enviarWhatsApp(){

    // 1) Pega os itens atuais do carrinho (armazenados no localStorage do navegador)
    //    Esse JSON é escrito pela aplicação quando o usuário adiciona itens ao carrinho.
    const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');

    // Se o carrinho estiver vazio, não faz nada e avisa o usuário.
    if(carrinho.length === 0){
        alert("Carrinho vazio!");
        return;
    }

        // 🔥 NOVO: verifica login aqui
    if (!idUsuario) {
        alert("Você precisa fazer login para finalizar o pedido!");
        window.location.href = "index.php?action=login&redirect=carrinho";
        return;
    }

    // 2) Monta a mensagem que será enviada para o WhatsApp
    //    A ideia é deixar a mensagem legível para o atendente ou para quem receber.
    let mensagem = "🛒 *NOVO PEDIDO*\n\n";

    let total = 0;

    // Cada item do carrinho é convertido em linhas de texto.
    // Exemplo:
    // 🍔 Hambúrguer
    // Quantidade: 2
    // Preço: R$ 20,00
    // (com adicionais, se houver)
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

    // Número de telefone da loja (WhatsApp). Ajuste para o número real.
    const numero = "5588988188728";

    // URL do WhatsApp Web/mobile que abre uma conversa com a mensagem pré-preenchida.
    const url = `https://wa.me/${numero}?text=${encodeURIComponent(mensagem)}`;

    // 3) Salvamos o pedido no servidor (banco de dados)
    //    Aqui NÃO enviamos a mensagem completa, apenas os dados importantes:
    //    - produto: string com os nomes/resumos dos itens
    //    - valor: total do pedido
    //    - id_usuario: para identificar quem fez
    //    - data (é gerada no servidor)
    const produtoNome = carrinho
        .map(item => `${item.quantidade}x ${item.nome}`)
        .join(', ');

    const formData = new FormData();
    formData.append('id_usuario', idUsuario);
    formData.append('produto', produtoNome);
    formData.append('valor', total.toFixed(2));

    // Chamada AJAX para endpoint que grava o pedido em DB.
    // A resposta é JSON contendo success/message.
    fetch('index.php?action=enviarPedido', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log('Pedido salvo:', data);

        if (data.success) {
            // Pedido gravado com sucesso -> esvazia o carrinho local e atualiza interface.
            localStorage.removeItem('carrinho');
            alert('Pedido registrado com sucesso! O carrinho foi limpo.');
            carregarCarrinho();
        } else {
            // Caso o backend retorne erro, mostramos para o usuário.
            alert('Erro ao salvar pedido: ' + (data.message || 'Tente novamente.'));
        }
    })
    .catch(error => {
        // Qualquer falha de rede é capturada aqui.
        console.error('Erro ao enviar pedido:', error);
        alert('Erro ao enviar pedido. Verifique sua conexão e tente novamente.');
    });

    // 4) Abre o WhatsApp em nova aba/janela com a mensagem montada.
    //    Isso permite que o cliente finalize o envio e veja os detalhes.
    window.open(url, '_blank');
}
   </script>
</body>
</html>
