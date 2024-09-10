<?php

namespace App\Model;

use App\Model;
use PDO;

class SolicitacaoModel extends Model
{
    public function solicitar(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO solicitacao (
                cod_usuario, titulo, descricao, data, tipo, data_vencimento
            ) VALUES (
                :cod_usuario, :titulo, :descricao, NOW(), :tipo, :data_vencimento
            )",
            $this->mapToBind([
                'cod_usuario'       => $data['cod_usuario'],
                'titulo'            => $data['titulo'],
                'descricao'         => $data['descricao'],
                'tipo'              => $data['tipo'],
                'data_vencimento'   => $data['data_vencimento']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function atualizar(array $data): bool
    {
        $result = $this->query(
            "UPDATE solicitacao 
                SET titulo = :titulo,
                    descricao = :descricao,
                    tipo = :tipo,
                    data_vencimento = :data_vencimento
                WHERE cod_solicitacao = :cod_solicitacao
            ",
            $this->mapToBind([
                'cod_solicitacao'   => $data['cod_solicitacao'],
                'titulo'            => $data['titulo'],
                'descricao'         => $data['descricao'],
                'tipo'              => $data['tipo'],
                'data_vencimento'   => $data['data_vencimento']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function excluir(int $cod_solicitacao): bool
    {
        $result = $this->query(
            "DELETE FROM solicitacao 
                WHERE cod_solicitacao = :cod_solicitacao
            ",
            $this->mapToBind(['cod_solicitacao' => $cod_solicitacao])
        );

        return (bool) $result->rowCount();
    }

    public function getSolicitacoes(array $filters = []): array
    {
        $where[] = "(data_vencimento >= CURDATE() OR data_vencimento IS NULL)";

        if (isset($filters['titulo'])) {
            $where[] = "s.titulo LIKE CONCAT('%', :titulo, '%')";
        }

        if (isset($filters['tipo'])) {
            $where[] = "s.tipo IN ('" . implode("', '", $filters['tipo']) . "')";

            unset($filters['tipo']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT s.cod_solicitacao, s.cod_usuario, u.nome, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.data_vencimento, s.tipo 
                FROM solicitacao s
                INNER JOIN usuario u ON (s.cod_usuario = u.cod_usuario)
                WHERE $where
                ORDER BY s.cod_solicitacao DESC
            ",
            $this->mapToBind($filters)
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getSolicitacao(int $cod_solicitacao): array
    {
        $result = $this->query(
            "SELECT s.cod_solicitacao, s.cod_usuario, u.nome, u.telefone, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.data_vencimento, s.tipo 
                FROM solicitacao s
                INNER JOIN usuario u ON (s.cod_usuario = u.cod_usuario)
                WHERE s.cod_solicitacao = :cod_solicitacao AND (data_vencimento >= CURDATE() OR data_vencimento IS NULL)
                ORDER BY s.cod_solicitacao DESC
            ",
            $this->mapToBind(['cod_solicitacao' => $cod_solicitacao])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }

    public function getSolicitacoesPorUsuario(int $cod_usuario): array
    {
        $result = $this->query(
            "SELECT cod_solicitacao, titulo, tipo 
                FROM solicitacao
                WHERE cod_usuario = :cod_usuario AND (data_vencimento >= CURDATE() OR data_vencimento IS NULL) 
                ORDER BY data DESC
            ",
            $this->mapToBind(['cod_usuario' => $cod_usuario])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
