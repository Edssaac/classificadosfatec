<?php

namespace App\Model;

use PDO;

class MonitoriaModel extends AnuncioModel
{
    public function anunciar(array $data): bool
    {
        $data['status'] = 1;
        $data['tipo'] = 'monitoria';

        $cod_anuncio = $this->inserirAnuncio($data);

        if ($cod_anuncio) {
            $result = $this->query(
                "INSERT INTO monitoria (
                    cod_anuncio, materia, horarios
                ) VALUES (
                    :cod_anuncio, :materia, :horarios
                )",
                $this->mapToBind([
                    'cod_anuncio'   => $cod_anuncio,
                    'materia'       => $data['materia'],
                    'horarios'      => $data['horarios']
                ])
            );

            return (bool) $result->rowCount();
        }

        return false;
    }

    public function atualizar(array $data): bool
    {
        if ($this->atualizarAnuncio($data)) {
            $result = $this->query(
                "UPDATE monitoria 
                    SET materia = :materia,
                        horarios = :horarios
                    WHERE cod_anuncio = :cod_anuncio
                ",
                $this->mapToBind([
                    'materia'       => $data['materia'],
                    'horarios'      => $data['horarios'],
                    'cod_anuncio'   => $data['cod_anuncio']
                ])
            );

            return (bool) $result->rowCount();
        }

        return false;
    }

    public function excluir(int $cod_anuncio): bool
    {
        // ajustar para FK ser delete on cascade

        $result = $this->query(
            "DELETE FROM monitoria 
                WHERE cod_anuncio = :cod_anuncio
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $this->deletarAnuncio($cod_anuncio);

        return (bool) $result->rowCount();
    }

    public function getMonitorias(array $filters = []): array
    {
        $where[] = "a.status = '1'";

        if (isset($filters['titulo'])) {
            $where[] = "a.titulo LIKE CONCAT('%', :titulo, '%')";
        }

        if (isset($filters['materia'])) {
            $where[] = "m.materia LIKE CONCAT('%', :materia, '%')";
        }

        if (isset($filters['dias'])) {
            $days = [];

            foreach (array_map('intval', $filters['dias']) as $dia) {
                $days[] = "JSON_CONTAINS(m.horarios, '{\"dia\": \"$dia\"}', '$')";
            }

            if ($days) {
                $where[] = "(" . implode(" OR ", $days) . ")";
            }

            unset($filters['dias']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, m.materia
                FROM monitoria m
                INNER JOIN anuncio a ON (m.cod_anuncio = a.cod_anuncio) 
                INNER JOIN usuario u ON (a.cod_usuario = u.cod_usuario)
                WHERE $where
                ORDER BY a.data_anunciada DESC
            ",
            $this->mapToBind($filters)
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getMonitoria(int $cod_anuncio): array
    {
        $result = $this->query(
            "SELECT u.nome, u.telefone, a.cod_anuncio, a.cod_usuario, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, m.materia, m.horarios
                FROM monitoria m
                INNER JOIN anuncio a ON (m.cod_anuncio = a.cod_anuncio) 
                INNER JOIN usuario u ON (a.cod_usuario = u.cod_usuario)
                WHERE a.status = '1' AND a.cod_anuncio = :cod_anuncio AND (a.data_vencimento >= CURDATE() OR a.data_vencimento IS NULL)
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }
}
