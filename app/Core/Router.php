<?php

namespace App\Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Adiciona uma rota GET.
     * @param string $uri
     * @param callable|array $action A ação a ser executada. Pode ser um array [Controller::class, 'method'] ou uma Closure.
     */
    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Adiciona uma rota POST.
     * @param string $uri
     * @param callable|array $action A ação a ser executada. Pode ser um array [Controller::class, 'method'] ou uma Closure.
     */
    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Despacha a requisição para a ação correta.
     * @param string $url A URL solicitada.
     * @param string $method O método HTTP (GET, POST).
     */
    public function dispatch($url, $method)
    {
        $uri = trim($url, '/');
        
        // Tenta encontrar uma correspondência exata para a URI
        if (array_key_exists($uri, $this->routes[$method])) {
            $action = $this->routes[$method][$uri];

            // **INÍCIO DA CORREÇÃO**
            // Verifica se a ação é uma função anónima (Closure)
            if (is_callable($action)) {
                // Se for, simplesmente executa a função
                call_user_func($action);
                return;
            }

            // Se for um array, continua com a lógica do controlador
            if (is_array($action)) {
                [$controllerName, $methodName] = $action;

                if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                    $controllerInstance = new $controllerName();
                    $controllerInstance->$methodName();
                    return;
                }
            }
            // **FIM DA CORREÇÃO**
        }

        // Se não houver correspondência exata, procura por rotas com parâmetros
        foreach ($this->routes[$method] as $route => $action) {
            $regex = "#^" . preg_replace('/\\{\\w+\\}/', '([^/]+)', $route) . "$#";

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);

                // A lógica para rotas com parâmetros continua a esperar um array [Controller, method]
                if (is_array($action)) {
                    [$controllerName, $methodName] = $action;
                    if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                        $controllerInstance = new $controllerName();
                        call_user_func_array([$controllerInstance, $methodName], $matches);
                        return;
                    }
                }
            }
        }
        
        // Se nenhuma rota for encontrada, exibe um erro 404
        $this->abort();
    }

    /**
     * Exibe uma página de erro.
     * @param int $code O código HTTP do erro (padrão 404).
     */
    protected function abort($code = 404)
    {
        http_response_code($code);
        // Pode criar uma view de erro mais elaborada aqui se desejar
        echo "<h1>{$code}</h1><p>Página não encontrada.</p>";
        exit();
    }
}

