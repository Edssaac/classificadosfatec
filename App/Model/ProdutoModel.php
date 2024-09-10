<?php

namespace App\Model;

use PDO;

class ProdutoModel extends AnuncioModel
{
    public function anunciar(array $data): bool
    {
        $data['status'] = 1;
        $data['tipo'] = 'produto';

        $cod_anuncio = $this->inserirAnuncio($data);

        if ($cod_anuncio) {
            $result = $this->query(
                "INSERT INTO produto (
                    cod_anuncio, foto_name, foto_token, estado, operacao, quantidade
                ) VALUES (
                    :cod_anuncio, :foto_name, :foto_token, :estado, :operacao, :quantidade
                )",
                $this->mapToBind([
                    'cod_anuncio'   => $cod_anuncio,
                    'foto_name'     => $data['foto_name'],
                    'foto_token'    => $data['foto_token'],
                    'estado'        => $data['estado'],
                    'operacao'      => $data['operacao'],
                    'quantidade'    => $data['quantidade']
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
                "UPDATE produto 
                    SET foto_name = :foto_name,
                        foto_token = :foto_token,
                        estado = :estado,
                        operacao = :operacao,
                        quantidade = :quantidade,
                    WHERE cod_anuncio = :cod_anuncio
                ",
                $this->mapToBind([
                    'foto_name'     => $data['foto_name'],
                    'foto_token'    => $data['foto_token'],
                    'estado'        => $data['estado'],
                    'operacao'      => $data['operacao'],
                    'quantidade'    => $data['quantidade'],
                    'cod_anuncio'   => $data['cod_anuncio']
                ])
            );

            return (bool) $result->rowCount();
        }

        return true;
    }

    public function excluir(int $cod_anuncio): bool
    {
        // ajustar para FK ser delete on cascade

        $result = $this->query(
            "DELETE FROM produto 
                WHERE cod_anuncio = :cod_anuncio
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $this->deletarAnuncio($cod_anuncio);

        return (bool) $result->rowCount();
    }

    public function getProdutos(array $filters = []): array
    {
        $where[] = "a.status = '1'";

        if (isset($filters['titulo'])) {
            $where[] = "a.titulo LIKE CONCAT('%', :titulo, '%')";
        }

        if (isset($filters['materia'])) {
            $where[] = "m.materia LIKE CONCAT('%', :materia, '%')";
        }

        if (isset($filters['estado'])) {
            $where[] = "p.estado IN ('" . implode("', '", $filters['estado']) . "')";

            unset($filters['estado']);
        }

        if (isset($filters['operacao'])) {
            $where[] = "p.operacao IN ('" . implode("', '", $filters['operacao']) . "')";

            unset($filters['operacao']);
        }

        $where = implode(" AND ", $where);

        $result = $this->query(
            "SELECT u.nome, a.cod_anuncio, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, p.foto_name
                FROM produto p
                INNER JOIN anuncio a ON (p.cod_anuncio = a.cod_anuncio) 
                INNER JOIN usuario u ON (a.cod_usuario = u.cod_usuario)
                WHERE $where
                ORDER BY a.data_anunciada DESC
            ",
            $this->mapToBind($filters)
        );

        $fetch = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }

    public function getProduto(int $cod_anuncio): array
    {
        $result = $this->query(
            "SELECT u.nome, u.telefone, a.cod_anuncio, a.cod_usuario, a.titulo, a.descricao, DATE_FORMAT(a.data_anunciada, '%d/%m/%Y | %Hh%i') as data_anunciada, a.data_vencimento, a.valor, a.desconto, a.data_desconto, p.foto_name, p.estado, p.operacao, p.quantidade
                FROM produto p
                INNER JOIN anuncio a ON (p.cod_anuncio = a.cod_anuncio) 
                INNER JOIN usuario u ON (a.cod_usuario = u.cod_usuario)
                WHERE a.status = '1' and a.cod_anuncio = $cod_anuncio AND (a.data_vencimento > CURDATE() OR a.data_vencimento IS NULL)
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $fetch = $result->fetch(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetch : [];
    }
}
