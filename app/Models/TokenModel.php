<?php

namespace App\Models;

use App\Database\DataSource;
use PDO;

class TokenModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataSource::getInstance();
    }
    
    /**
     * Gera um novo token alfanumérico único.
     */
    public function generateToken(): string
    {
        do {
            $token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $stmt = $this->db->selectOne("SELECT COUNT(*) FROM tokens WHERE token = :token", ['token' => $token]);
        } while ($stmt && $stmt['COUNT(*)'] > 0);
        
        return $token;
    }

    /**
     * Insere um novo registro no banco de dados.
     */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO tokens (token, paciente, id_paciente, valorpag, tipopag, mes_ref, ano_ref, profissional_id, criado_por_id, data_criacao) 
                VALUES (:token, :paciente, :id_paciente, :valorpag, :tipopag, :mes_ref, :ano_ref, :profissional_id, :criado_por_id, NOW())";
        return $this->db->execute($sql, $data);
    }

    /**
     * Busca todos os registros com filtros opcionais.
     */
    public function findAllWithFilters(array $filters = []): array
    {
        $sql = "SELECT 
                    t.*, 
                    u.nome as criado_por_nome,
                    p.profissional as profissional_nome
                FROM tokens t 
                JOIN usuarios u ON t.criado_por_id = u.id 
                JOIN profissionais p ON t.profissional_id = p.id_prof
                WHERE 1=1"; // Cláusula base para facilitar a adição de filtros

        $params = [];

        if (!empty($filters['profissional'])) {
            $sql .= " AND t.profissional_id = :profissional";
            $params[':profissional'] = $filters['profissional'];
        }
        if (!empty($filters['mes'])) {
            $sql .= " AND t.mes_ref = :mes";
            $params[':mes'] = $filters['mes'];
        }
        if (!empty($filters['ano'])) {
            $sql .= " AND t.ano_ref = :ano";
            $params[':ano'] = $filters['ano'];
        }
        if (!empty($filters['termo'])) {
            $sql .= " AND (t.paciente LIKE :termo OR t.token LIKE :termo)";
            $params[':termo'] = '%' . $filters['termo'] . '%';
        }
        
        $sql .= " ORDER BY t.data_criacao DESC";

        return $this->db->select($sql, $params);
    }
}

