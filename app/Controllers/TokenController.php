<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\TokenModel; // ATUALIZADO

class TokenController extends BaseController
{
    public function __construct()
    {
        // Garante que apenas usuários logados acessem este controller
        if (!Auth::isLogged()) {
            $this->redirect('login');
        }
    }

    /**
     * Exibe o formulário para gerar um novo token.
     */
    public function createForm()
    {
        $this->render('pages/gerar_token', ['title' => 'Gerar Token']);
    }

    /**
     * Armazena o novo token no banco de dados.
     */
    public function store()
    {
        // Lógica para pegar os dados do POST
        $paciente = $_POST['paciente'] ?? 'Não informado';
        $codigo_paciente = $_POST['codigo_paciente'] ?? null;
        
        $tokenModel = new TokenModel(); // ATUALIZADO
        $novoToken = $tokenModel->generateToken();
        
        $data = [
            'token' => $novoToken,
            'paciente' => $paciente,
            'id_paciente' => $codigo_paciente,
            'criado_por' => Auth::userId()
        ];

        $tokenModel->create($data);

        // Redireciona para a página de consulta após criar
        $this->redirect('token/consulta');
    }

    /**
     * Lista todos os tokens existentes.
     */
    public function list()
    {
        $tokenModel = new TokenModel(); // ATUALIZADO
        $tokens = $tokenModel->findAll();

        $this->render('pages/consulta_token', [
            'title' => 'Consultar Tokens',
            'tokens' => $tokens
        ]);
    }
}

