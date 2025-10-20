<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\UserModel;

class AuthController extends BaseController
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        if (Auth::isLogged()) {
            $this->redirect('home');
        }
        // A página de login não usará o layout principal com sidebar
        $this->render('login', ['title' => 'Login'], false);
    }

    /**
     * Processa a tentativa de login.
     */
    public function login()
    {
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (!$email || !$senha) {
            $_SESSION['error_msg'] = "E-mail e senha são obrigatórios.";
            $this->redirect('login');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            Auth::login($user['id'], $user['nome']);
            $this->redirect('home');
        } else {
            // Falha no login
            $_SESSION['error_msg'] = "Credenciais inválidas. Tente novamente.";
            $this->redirect('login');
        }
    }

    /**
     * Efetua o logout do usuário.
     */
    public function logout()
    {
        Auth::logout();
        $this->redirect('login');
    }
}
