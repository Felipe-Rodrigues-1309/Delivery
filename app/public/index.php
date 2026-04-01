<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../src/controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

    case 'login':
        require __DIR__ . '/../src/views/auth/paginaDeLogin.html';
        break;

    case 'cadastro':
        require __DIR__ . '/../src/views/auth/paginaDeCadastro.html';
        break;

    case 'logar':
        AuthController::login();
        break;

    case 'produto':
        require __DIR__ . '/../src/views/categorias/abrir_produto.php';
        break;

    case 'categoria':    
        require __DIR__ . '/../src/views/categorias/Categoria-01.php';
        break;

    case 'abrirProduto':    
        require __DIR__ . '/../src/views/categorias/abrir_produto.php';
        break;

    case 'paginaInicial':    
        require __DIR__ . '/../src/views/home/paginaInicial.html';
        break;

    case 'carrinho':    
        require __DIR__ . '/../src/views/carrinho/index.php';
        break;

    case 'cadastroDeCliente':
        require __DIR__ . '/../src/controllers/cadastroDeCliente.php';
        break;

    case 'loginCliente':
        require __DIR__ . '/../src/controllers/loginCliente.php';
        break;

    case 'cadastroDeProduto':
        require __DIR__ . '/../src/views/produtos/paginaDeCadastroDeProduto.php';
        break;

    case 'enviarProduto':
        require __DIR__ . '/../src/views/produtos/enviarProduto.php';
        break;

    case 'enviarPedido':
        require __DIR__ . '/../src/views/pedidos/enviarPedido.php';
        break;

    case 'cadastroDeEndereco':
        require __DIR__ . '/../src/views/auth/cadastroDeEndereco.html';
        break;

    case 'enviarEndereco':
        require __DIR__ . '/../src/views/produtos/cadastroDeEndereco.php';
        break;


    case 'perfilCliente':
        require __DIR__ . '/../src/views/auth/perfilCliente.php';
        break;

        default:
        echo "Página não encontrada";

}