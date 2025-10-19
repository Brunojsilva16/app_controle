<?php

namespace App\Controllers;

use App\Core\Auth;

abstract class BaseController
{
    protected function render(string $view, array $data = [])
    {
        // Extrai os dados para variáveis locais que podem ser usadas na view
        extract($data);

        // Inicia o buffer de saída para capturar o conteúdo da view
        ob_start();

        $viewPath = VIEWS_PATH . "/pages/{$view}.phtml";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "Erro: View '{$view}' não encontrada.";
        }

        // Obtém o conteúdo da view e limpa o buffer
        $pageContent = ob_get_clean();

        // Inclui o layout principal, que usará a variável $pageContent
        require VIEWS_PATH . '/layout.phtml';
    }

    protected function redirect(string $url)
    {
        $baseUrl = $_ENV['BASE_URL'] ?? '';
        header("Location: {$baseUrl}/{$url}");
        exit();
    }
}

