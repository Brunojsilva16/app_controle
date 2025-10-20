<?php

namespace App\Models;

use App\Database\DataSource;

class ProfissionalModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataSource::getInstance();
    }

    /**
     * Busca todos os profissionais ativos.
     * @return array
     */
    public function findAllActive(): array
    {
        $sql = "SELECT id_prof, profissional FROM profissionais WHERE id_status = 1 ORDER BY profissional ASC";
        return $this->db->select($sql);
    }
}
