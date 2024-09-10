<?php

namespace App\Model;

use App\Model;
use PDO;

class ComentarioModel extends Model
{
    public function getComentarios(int $cod_solicitacao): array
    {
        $result = $this->query(
            "SELECT c.cod_usuario, u.nome, c.comentario, DATE_FORMAT(c.data, '%d/%m/%Y | %Hh%i') as data
                FROM comentario c
                INNER JOIN usuario u ON (c.cod_usuario = u.cod_usuario)
                WHERE c.cod_solicitacao = :cod_solicitacao
                ORDER BY c.cod_comentario DESC
            ",
            $this->mapToBind(['cod_solicitacao' => $cod_solicitacao])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function comentar(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO comentario (
                cod_solicitacao, cod_usuario, comentario, data
            ) VALUES (
                :cod_solicitacao, :cod_usuario, :comentario, NOW()
            )",
            $this->mapToBind([
                'cod_solicitacao'   => $data['cod_solicitacao'],
                'cod_usuario'       => $data['cod_usuario'],
                'comentario'        => $data['comentario']
            ])
        );

        return (bool) $result->rowCount();
    }
}
