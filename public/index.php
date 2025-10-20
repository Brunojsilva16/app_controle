<?php
// Ativa exibição de erros (em desenvolvimento)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Garante que a sessão seja iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Define constantes de caminho para facilitar a inclusão de arquivos

$baseUrl = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define("BASE_URL", $baseUrl);

define('ROOT_PATH', dirname(__DIR__));
define('VIEWS_PATH', ROOT_PATH . '/app/Views');

// Carrega o autoloader do Composer e as variáveis de ambiente
require_once ROOT_PATH . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

// Instancia o roteador
$router = new App\Core\Router();

// Carrega as definições de rota
require_once ROOT_PATH . '/app/Routes/routes.php';

// Obtém a URL e o método da requisição atual
$url    = $_GET['url'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

// Despacha a rota para o controlador correspondente
$router->dispatch($url, $method);

