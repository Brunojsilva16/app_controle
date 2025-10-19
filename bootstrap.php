<?php

// bootstrap.php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Carrega as variáveis de ambiente do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Define a constante BASE_URL para ser usada em toda a aplicação
define('BASE_URL', $_ENV['BASE_URL']);

// Configuração de exibição de erros
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Define o caminho para a pasta de Views
define('VIEWS_PATH', __DIR__ . '/app/Views');
