<?php

namespace App\Models;

use App\Database\DataSource;

class TokenModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataSource::getInstance();
    }

    /**
     * Gera um novo token alfanumérico.
     * @return string O token gerado.
     */
    public function generateToken(): string
    {
        // Lógica para gerar um token único, por exemplo.
        return strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Insere um novo token no banco de dados.
     * @param array $data Os dados a serem inseridos.
     * @return bool
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tokens (token, paciente, id_paciente, criado_por, data_criacao) VALUES (:token, :paciente, :id_paciente, :criado_por, NOW())";
        return $this->db->execute($sql, $data);
    }

    /**
     * Busca todos os tokens no banco de dados.
     * @return array
     */
    public function findAll(): array
    {
        // Exemplo de SQL para buscar tokens com o nome do criador
        $sql = "SELECT t.*, u.nome as criado_por_nome 
                FROM tokens t 
                JOIN usuarios u ON t.criado_por = u.id 
                ORDER BY t.data_criacao DESC";
        return $this->db->select($sql);
    }
}
