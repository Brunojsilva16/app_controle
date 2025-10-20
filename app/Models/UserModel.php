<?php

namespace App\Models;

use App\Database\DataSource;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataSource::getInstance();
    }

    /**
     * Encontra um usuário pelo seu e-mail.
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        // Tabela corrigida para 'usuarios'
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        return $this->db->selectOne($sql, ['email' => $email]);
    }

    /**
     * Encontra um usuário pelo seu ID.
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        // Tabela corrigida para 'usuarios'
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        return $this->db->selectOne($sql, ['id' => $id]);
    }
}
