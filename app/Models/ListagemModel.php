<?php

namespace App\Models;

use App\Database\DataSource;

class ListagemModel
{
    private $db;

    public function __construct()
    {
        $this->db = DataSource::getInstance();
    }

    /**
     * Busca itens da listagem por um tipo específico.
     * Ex: 'mes', 'ano', 'tipopag'
     * @param string $tipo
     * @return array
     */
    public function findByType(string $tipo): array
    {
        $sql = "SELECT nome_li, valor_li FROM listagem WHERE tipo_li = :tipo AND status_li = 1 ORDER BY id_list ASC";
        return $this->db->select($sql, ['tipo' => $tipo]);
    }
}
