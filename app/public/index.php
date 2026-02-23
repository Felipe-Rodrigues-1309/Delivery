<?php

require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {

    case 'login':
        require __DIR__ . '/../views/auth/pagina_de_login.html';
        break;

    case 'cadastro':
        require __DIR__ . '/../views/auth/pagina_de_cadastro.html';
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

    case 'carrinho':    
        require __DIR__ . '/../views/carrinho/index.php';
        break;

    default:
        echo "Página não encontrada";

}