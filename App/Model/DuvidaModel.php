<?php

namespace App\Model;

use App\Model;
use PDO;

class DuvidaModel extends Model
{
    public function getDuvidas(int $cod_anuncio): array
    {
        $result = $this->query(
            "SELECT d.cod_duvida, d.cod_anuncio, a.cod_usuario AS anunciador, d.cod_usuario, u.nome, d.pergunta, DATE_FORMAT(d.data_pergunta, '%d/%m/%Y | %Hh%i') as data_pergunta, d.resposta, DATE_FORMAT(d.data_resposta, '%d/%m/%Y | %Hh%i') as data_resposta
                FROM duvida d
                INNER JOIN usuario u ON (d.cod_usuario = u.cod_usuario) 
                INNER JOIN anuncio a ON (d.cod_anuncio = a.cod_anuncio)
                WHERE d.cod_anuncio = :cod_anuncio
                ORDER BY d.cod_duvida DESC
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function perguntar(array $data): bool
    {
        $result = $this->query(
            "INSERT INTO duvida (
                cod_anuncio, cod_usuario, pergunta, data_pergunta
            ) VALUES (
                :cod_anuncio, :cod_usuario, :pergunta, NOW()
            )",
            $this->mapToBind([
                'cod_anuncio'   => $data['cod_anuncio'],
                'cod_usuario'   => $data['cod_usuario'],
                'pergunta'      => $data['pergunta']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function responder(int $cod_duvida, string $resposta): bool
    {
        $result = $this->query(
            "UPDATE duvida 
                SET resposta = :resposta,
                    data_resposta = NOW()
                WHERE cod_duvida = :cod_duvida
            ",
            $this->mapToBind([
                'resposta'      => $resposta,
                'cod_duvida'    => $cod_duvida
            ])
        );

        return (bool) $result->rowCount();
    }
}
