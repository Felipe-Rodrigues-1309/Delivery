<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

    case 'login':
        require __DIR__ . '/../views/auth/paginaDeLogin.html';
        break;

    case 'cadastro':
        require __DIR__ . '/../views/auth/paginaDeCadastro.html';
        break;

    case 'logar':
        AuthController::login();
        break;

    case 'produto':
        require __DIR__ . '/../views/categorias/abrir_produto.php';
        break;

    case 'categoria':    
        require __DIR__ . '/../views/categorias/Categoria-01.php';
        break;

    case 'abrirProduto':    
        require __DIR__ . '/../views/categorias/abrir_produto.php';
        break;

    case 'paginaInicial':    
        require __DIR__ . '/../views/home/paginaInicial.html';
        break;

    case 'carrinho':    
        require __DIR__ . '/../views/carrinho/index.php';
        break;

    case 'cadastroDeCliente':
        require __DIR__ . '/../controllers/cadastroDeCliente.php';
        break;

    case 'loginCliente':
        require __DIR__ . '/../controllers/loginCliente.php';
        break;

    case 'cadastroDeProduto':
        require __DIR__ . '/../views/produtos/paginaDeCadastroDeProduto.php';
        break;

    case 'enviarProduto':
        require __DIR__ . '/../views/produtos/enviarProduto.php';
        break;




    default:
        echo "Página não encontrada";

}