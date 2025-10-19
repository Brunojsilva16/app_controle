<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\TokenController;
use App\Core\Auth;

/**
 * @var \App\Core\Router $router Instância do Router, vinda do index.php
 */

// --- ROTAS PÚBLICAS (Acessíveis sem login) ---

// Rota principal: redireciona para o home se estiver logado, caso contrário para o login.
$router->get('', function () {
    if (Auth::isLogged()) {
        header('Location: ' . $_ENV['BASE_URL'] . '/home');
    } else {
        header('Location: ' . $_ENV['BASE_URL'] . '/login');
    }
    exit();
});

$router->get('login', [AuthController::class, 'showLoginForm']);
$router->post('login', [AuthController::class, 'login']);


// --- ROTAS PROTEGIDAS (Exigem autenticação) ---

$router->get('home', [HomeController::class, 'index']);
$router->get('logout', [AuthController::class, 'logout']);

// Rotas para a gestão de Tokens
$router->get('token/gerar', [TokenController::class, 'showGerarForm']);
$router->post('token/gerar', [TokenController::class, 'gerar']);
$router->get('token/consulta', [TokenController::class, 'showConsulta']);
$router->post('token/consulta', [TokenController::class, 'consultar']); // Para o caso de a consulta ser via POST

