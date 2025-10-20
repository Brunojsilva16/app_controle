<?php

namespace App\Controllers;

use App\Core\Auth;

class HomeController extends BaseController
{
    public function __construct()
    {
        if (!Auth::isLogged()) {
            $this->redirect('login');
        }
    }

    public function index()
    {
        $this->render('home', [
            'title' => 'Página Inicial',
            'userName' => Auth::userName()
        ]);
    }
}

