<?php

namespace App\Model;

use App\Model;
use PDO;

class AvaliacaoModel extends Model
{
    public function getAvaliacoes(int $cod_anuncio): array
    {
        $result = $this->query(
            "SELECT av.cod_usuario, u.nome, av.comentario, av.avaliacao, DATE_FORMAT(av.data, '%d/%m/%Y | %Hh%i') as data
                FROM avaliacao av
                INNER JOIN usuario u ON (av.cod_usuario = u.cod_usuario)
                WHERE av.cod_anuncio = :cod_anuncio
                ORDER BY av.cod_avaliacao DESC
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function avaliar(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO avaliacao (
                cod_anuncio, cod_usuario, comentario, avaliacao, data
            ) VALUES (
                :cod_anuncio, :cod_usuario, :comentario, :avaliacao, NOW()
            )",
            $this->mapToBind([
                'cod_anuncio'   => $data['cod_anuncio'],
                'cod_usuario'   => $data['cod_usuario'],
                'comentario'    => $data['comentario'],
                'avaliacao'     => $data['avaliacao']
            ])
        );

        return (bool) $result->rowCount();
    }
}
