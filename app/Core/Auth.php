<?php
namespace App\Core;

class Auth
{
    public static function isLogged(): bool
    {
        return !empty($_SESSION['usuario_id']);
    }

    public static function userId(): ?int
    {
        return $_SESSION['usuario_id'] ?? null;
    }

    public static function userName(): ?string
    {
        return $_SESSION['usuario_nome'] ?? null;
    }

    public static function login(int $id, string $name): void
    {
        session_regenerate_id(true); 
        $_SESSION['usuario_id'] = $id;
        $_SESSION['usuario_nome'] = $name;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}

