<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../../config/conexao.php';
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
$id_usuario = $_SESSION['id_usuario'] ?? null;
$rua = $_SESSION['rua'] ?? null;
$numero = $_SESSION['numero'] ?? null;
$bairro = $_SESSION['bairro'] ?? null;
$cidade = $_SESSION['cidade'] ?? null;
$ponto_de_referencia = $_SESSION['ponto_de_referencia'] ?? null;





// busca para mostrar o endereço no front 
if($id_usuario){  // o $id_usuario venda do start da secão sempre e usada para buscar pelo id da seção 
$stmt = $conn->prepare(" SELECT rua, numero, bairro, cidade, ponto_de_referencia FROM endereco WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$enderecoUsuario = $stmt->get_result();
$endereco = $enderecoUsuario->fetch_assoc();
}


// busca para encontrar o usuario pelo nome na seção 
if($id_usuario){

    $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $ResultadoNomeDoUsuario = $stmt->get_result();
    $user = $ResultadoNomeDoUsuario->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <!-- Usamos Bootstrap para layout básico e componentes responsivos. -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Estilos customizados para tornar o carrinho mais legível. -->
    <style>
      
        body {
            background-image: url(/img/background.png);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 5px; 
            backdrop-filter: blur(5px);
            padding: 1px;
            margin-top: 25px;
            margin-bottom: 20px;
            border-radius: 25px;
        }
        .item-carrinho {
            position: relative;
            color:white;
            background-color: rgba(0, 0, 0, 0.85);
            margin-top: 20px;
            border: 1px solid #0004ff;
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
        .item-preco {
            margin-top: 10px;
            font-size: 20px;
            font-weight: bold;
            color: #1500ff;
        }
        .btn-remover {
            position: absolute;
            top: 8px;
            right: 8px;
            background: transparent;
            border: none;
            color: #dc3545;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            margin: 0;
}
        

            .btn-remover:hover {
            color: #ff0000;
        }
        .total-section {
            color:white;
            background-color: rgba(0, 0, 0, 0.85);
            margin-top: 20px;
            border: 1px solid #ff0000;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;

            justify-content: space-between;
            align-items: center;
        }
        .total-section h3 {
            text-align: center;
            color: #ff0000;
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

        .buttonNavBar{
        margin: 0px;
        padding: 5px;
        }

        .cardEndereco{
        color: #00ff00;
        font-style: italic;
        background-color: rgba(0, 0, 0, 0.85);
        border: 1px solid #00ff00;
        padding: 1px;
        margin-bottom: 2px;
        border-radius: 8px;
        }



        .form-select {
        background-color: rgba(0, 0, 0, 0.85);
        color: white;
        border: 1px solid #1500ff;
        border-radius: 8px;
        }

        .form-select:focus {
        background-color: rgba(0, 0, 0, 0.95);
        color: white;
        border-color: #1500ff;
        box-shadow: 0 0 10px rgba(21, 0, 255, 0.5);
        }

        .form-select option {
        background-color: #000;
        color: white;
        }

    button {
    display: block;
    margin: 25px auto;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid #00ff00;
    background-color: rgba(0, 0, 0, 0.85);
    color: white;
    font-weight: bold;
    transition: all 0.4s ease;
}

button:hover {
    background-color: #000000;
    color: white;
    box-shadow: 0 0 15px #00ff00;
}

.btn-finalizar {
    border: 1px solid #00ff00;
}

.btn-finalizar:hover {
    background-color: #00ff00;
    color: black;
    box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
}


.btn-add-produto {
    width: 55px;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;

    background-color: rgba(0, 0, 0, 0.85);
    border: 2px solid #1500ff;
    border-radius: 50%;

    color: white;
    font-size: 24px;

    transition: all 0.3s ease;
}

.btn-add-produto:hover {
    background-color: #1500ff;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(21, 0, 255, 0.5);
}

    </style>
</head>
<body>
    <!-- Navegação superior: links de volta à página inicial e página de pedidos -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container-fluid">
            <a class="navbar-brand" href="?action=login">voltar</a>
            <button class="navbar-toggler buttonNavBar" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="./Pagina_inicial/Pagina_inicial.html">Pagina inicial</a>
                    <a class="nav-link active" href="?action=perfilCliente">pedidos</a>
                    <a class="nav-link active" href="#">Suporte</a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <h2 style="margin-bottom: 30px; color: #1500ff;">🛒 Carrinho de Compras</h2>

            <div class="card cardEndereco">
                <div class="card-body">
                    <div class="endereco">
                        Endereço:
                        <?= $endereco['rua'] ?? 'Endereço Não Cadastrado';?>,
                        Nº <?= $endereco['numero'] ?? '';?> -
                        <?= $endereco['ponto_de_referencia'] ?? '';?> -
                        <?= $endereco['cidade'] ?? '';?>
                        <?= $user['nome'] ?? '';?>,
                    </div>
                </div>
            </div>

        <!-- Mensagem exibida quando não há produtos no carrinho -->
        <div id="carrinho-vazio" style="display: none; text-align: center;">
            <h3>Seu carrinho está vazio</h3>
        </div>

        <!-- Aqui o JavaScript injeta os cards dos itens que estiverem no carrinho -->
        <div id="carrinho-itens">
            <!-- Os itens serão carregados aqui pelo JavaScript -->
        </div>

        <div id="carrinho-pagamento" style="display: none; margin-top: 20px;"> <!--usado para nao aparecer a forma de pagamento se o carrinho estiver vazio busca pelo id-->
            <select id="forma_pagamento" class="form-select" aria-label="Default select example">
                <option value="" selected>Forma de pagamento</option>
                <option value="Dinheiro">Dinheiro</option>
                <option value="Cartão">Cartão</option>
                <option value="Vale Alimentação">Vale Alimentação</option>
            </select>
        </div>

        <!-- Exibe o total do pedido quando houver itens no carrinho -->
        <div id="carrinho-total" style="display: none;">
            <div class="total-section">
                <h5 id="total-preco">Total da Compra: R$ 0,00</h5>
            </div>
        </div>

            <div class="d-flex justify-content-center">
                <a href="?action=categoria"
                    class="btn-add-produto">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>


        <button onclick="enviarWhatsApp()">Finalizar Compra</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
        // ### Variáveis/passagem de dados do PHP para JS
        // O PHP passa o ID do usuário logado para o JavaScript usando json_encode.
        // Isso permite enviar o ID ao servidor quando o pedido for salvo.
        const idUsuario = <?= json_encode($id_usuario); ?>;
        const rua = <?=json_encode($rua);?>;
        const numero = <?=json_encode($numero);?>;
        const bairro = <?=json_encode($bairro);?>;
        const cidade = <?=json_encode($cidade);?>;
        const ponto_de_referencia = <?=json_encode($ponto_de_referencia);?>;
        const user = <?=json_encode($user);?>;

        // ### Função principal que renderiza o carrinho na página
        function carregarCarrinho() {
            // Busca o carrinho salvo no localStorage.
            // O carrinho é um array de objetos, por exemplo: [{ nome, quantidade, precoUnitario, precoFinal, adicionais }]
            const carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');

            // Elementos do DOM usados para renderizar a UI.
            const carrinhoVazio = document.getElementById('carrinho-vazio');
            const carrinhoItens = document.getElementById('carrinho-itens');
            const carrinhoTotal = document.getElementById('carrinho-total');
            const carrinhoPagamento = document.getElementById('carrinho-pagamento'); //usado para nao aparecer a forma de pagamento se o carrinho estiver vazio

            if(carrinho.length === 0) {
                carrinhoVazio.style.display = 'block';
                carrinhoItens.style.display = 'none';
                carrinhoTotal.style.display = 'none';
                carrinhoPagamento.style.display = 'none'; //usado para nao aparecer a forma de pagamento se o carrinho estiver vazio
                return;
            }

            carrinhoVazio.style.display = 'none';
            carrinhoItens.style.display = 'block';
            carrinhoTotal.style.display = 'block';
            carrinhoPagamento.style.display = 'block'; //usado para nao aparecer a forma de pagamento se o carrinho estiver com itens 

            // Limpar itens anteriores
            carrinhoItens.innerHTML = '';

            let totalGeral = 0;

            carrinho.forEach((item, index) => {
                totalGeral += item.precoFinal;

                let produtoHTML = `<small style="display: flex; color: #ffffff; margin-top: 5px;">✓(R$ ${(item.precoUnitario * item.quantidade).toFixed(2).replace('.', ',')}) x ${item.quantidade}</small>`;
                
                let adicionaisHTML = '';
                if(item.adicionais && item.adicionais.length > 0) {
                    adicionaisHTML = '<small style="display: flex; color: #ffffff;">';
                    item.adicionais.forEach(ad => {
                        adicionaisHTML += '✓ ' + item.quantidade + 'x ' + ad.nome + ' (R$ ' + (ad.valor * item.quantidade).toFixed(2).replace('.', ',') + ')<br>';
                    });
                    adicionaisHTML += '</small>';
                }

                const itemHTML = `
                    <div class="item-carrinho">
                        <div class="item-info">
                            <h6>${item.nome}</h6>
                            ${produtoHTML}
                            ${adicionaisHTML}
                            <div class="item-preco">R$ ${item.precoFinal.toFixed(2).replace('.', ',')}</div>
                        </div>
                         <button class="btn-remover" onclick="removerDoCarrinho(${index})">X</button>
                    </div>
                `;
                carrinhoItens.innerHTML += itemHTML;
            });

            // Criar seção de total
            const totalHTML = `
                <div class="total-section">
                    <h3>Valor Total : R$ ${totalGeral.toFixed(2).replace('.', ',')}</h3>
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

    if(!rua){
        alert("Você precisa cadastrar um endereço!");
        window.location.href = "index.php?action=cadastroDeEndereco&redirect=carrinho";
        return;
    }

    // 2) Salva o pedido no servidor (banco de dados)
    let total = 0;

    // Calcula o total
    carrinho.forEach(item => {
        total += item.precoFinal;
    });

    const produtoNome = carrinho
        .map(item => {
            let descricao = `${item.quantidade}x ${item.nome} (R$ ${(item.precoUnitario * item.quantidade).toFixed(2).replace('.', ',')})`;
            if (item.adicionais && item.adicionais.length > 0) {
                descricao += '\nAdicionais:';
                descricao += item.adicionais
                    .map(ad => `\n - ${item.quantidade}x ${ad.nome} (R$ ${(ad.valor * item.quantidade).toFixed(2).replace('.', ',')})`)
                    .join('');
            }
            return descricao;
        })
        .join('\n\n');

    const formaPagamentoSelect = document.getElementById('forma_pagamento');
    const formaPagamento = formaPagamentoSelect ? formaPagamentoSelect.value : '';

    if (!formaPagamento) {
        alert('Selecione uma forma de pagamento antes de finalizar o pedido.');
        return;
    }

    const formData = new FormData();
    formData.append('id_usuario', idUsuario);
    formData.append('produto', produtoNome);
    formData.append('valor', total.toFixed(2));
    formData.append('pagamento', formaPagamento);
    formData.append('rua', rua);
    formData.append('bairro', bairro);
    formData.append('numero', numero);
    formData.append('cidade', cidade);
    formData.append('ponto_de_referencia', ponto_de_referencia);
    formData.append('nome', user.nome); 

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
            alert('Pedido registrado com sucesso!');
            window.location.href = "index.php?action=perfilCliente&redirect=carrinho";
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
}
   </script>
</body>
</html>
