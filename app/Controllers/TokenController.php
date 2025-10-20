<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\TokenModel;
use App\Models\ListagemModel; // Novo
use App\Models\ProfissionalModel; // Novo

class TokenController extends BaseController
{
    private $tokenModel;
    private $listagemModel; // Novo
    private $profissionalModel; // Novo

    public function __construct()
    {
        if (!Auth::isLogged()) {
            $this->redirect('login');
        }
        $this->tokenModel = new TokenModel();
        $this->listagemModel = new ListagemModel(); // Novo
        $this->profissionalModel = new ProfissionalModel(); // Novo
    }

    /**
     * Exibe o formulário para gerar token, agora com dados dinâmicos.
     */
    public function showGerarForm()
    {
        // Busca os dados para os selects do formulário
        $profissionais = $this->profissionalModel->findAllActive();
        $tiposPagamento = $this->listagemModel->findByType('tipopag');
        $meses = $this->listagemModel->findByType('mes');
        $anos = $this->listagemModel->findByType('ano');

        $this->render('gerar_token', [
            'title' => 'Gerar Pagamento / Token',
            'profissionais' => $profissionais,
            'tiposPagamento' => $tiposPagamento,
            'meses' => $meses,
            'anos' => $anos
        ]);
    }

    /**
     * Armazena o novo token/pagamento no banco.
     */
    public function gerar()
    {
        // Captura todos os dados do formulário
        $data = [
            'token' => $this->tokenModel->generateToken(),
            'paciente' => $_POST['paciente'] ?? null,
            'id_paciente' => $_POST['id_paciente'] ?? null,
            'valorpag' => $_POST['valorpag'] ?? 0,
            'tipopag' => $_POST['tipopag'] ?? null,
            'mes_ref' => $_POST['mes_ref'] ?? null,
            'ano_ref' => $_POST['ano_ref'] ?? null,
            'profissional_id' => $_POST['profissional_id'] ?? null,
            'criado_por_id' => Auth::userId()
        ];
        
        // Validação básica
        if (empty($data['paciente']) || empty($data['profissional_id']) || empty($data['valorpag'])) {
            $_SESSION['error_msg'] = "Paciente, Profissional e Valor são campos obrigatórios.";
            $this->redirect('token/gerar');
            return;
        }

        if ($this->tokenModel->create($data)) {
            $_SESSION['success_msg'] = "Registro de pagamento gerado com sucesso com o token " . $data['token'];
        } else {
            $_SESSION['error_msg'] = "Ocorreu um erro ao gerar o registro.";
        }

        $this->redirect('token/consulta');
    }
    
    /**
     * Exibe a página de consulta, agora com filtros.
     */
    public function showConsulta()
    {
        // Pega os filtros da URL (via GET)
        $filters = [
            'profissional' => $_GET['profissional'] ?? null,
            'mes' => $_GET['mes'] ?? null,
            'ano' => $_GET['ano'] ?? null,
            'termo' => $_GET['termo'] ?? null,
        ];

        // Busca os tokens com base nos filtros
        $tokens = $this->tokenModel->findAllWithFilters($filters);
        
        // Busca dados para popular os filtros da consulta
        $profissionais = $this->profissionalModel->findAllActive();
        $meses = $this->listagemModel->findByType('mes');
        $anos = $this->listagemModel->findByType('ano');

        $this->render('consulta_token', [
            'title' => 'Consultar Lançamentos',
            'tokens' => $tokens,
            'profissionais' => $profissionais,
            'meses' => $meses,
            'anos' => $anos,
            'filters' => $filters // Envia os filtros de volta para a view
        ]);
    }
}

