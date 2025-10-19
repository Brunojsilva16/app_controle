<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\UserModel; // ATUALIZADO

class AuthController extends BaseController
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        // Se já estiver logado, redireciona para a home
        if (Auth::isLogged()) {
            $this->redirect('home');
        }
        $this->render('pages/login', ['title' => 'Login']);
    }

    /**
     * Processa a tentativa de login.
     */
    public function login()
    {
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (!$email || !$senha) {
            // Lógica de erro (ex: redirecionar de volta com mensagem)
            $this->redirect('login');
        }

        $userModel = new UserModel(); // ATUALIZADO
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($senha, $user['senha'])) {
            // Login bem-sucedido
            Auth::login($user['id'], $user['nome']);
            $this->redirect('home');
        } else {
            // Falha no login
            // Adicionar uma mensagem de erro na sessão seria ideal aqui
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

