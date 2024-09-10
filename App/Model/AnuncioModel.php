<?php

namespace App\Model;

use App\Model;
use PDO;

class AnuncioModel extends Model
{
    public function inserirAnuncio(array $data): int
    {
        $accepted_fields = [
            'cod_usuario',
            'titulo',
            'descricao',
            'valor',
            'desconto',
            'data_desconto',
            'status',
            'tipo',
            'data_vencimento'
        ];

        foreach ($data as $name => $value) {
            if (!in_array($name, $accepted_fields) || empty($value)) {
                unset($data[$name]);
            }
        }

        $fields = implode(', ', array_keys($data));
        $data = $this->mapToBind($data);

        $this->query(
            "INSERT INTO anuncio (
                $fields
            ) VALUES (
                " . implode(', ', array_keys($data)) . "
            )",
            $data
        );

        return $this->lastInsertId();
    }

    public function atualizarAnuncio(array $data): bool
    {
        $result = $this->query(
            "UPDATE anuncio 
                SET titulo = :titulo,
                    descricao = :descricao,
                    valor = :valor,
                    desconto = :desconto,
                    data_desconto = :data_desconto,
                    data_vencimento = :data_vencimento
                WHERE cod_anuncio = :cod_anuncio
            ",
            $this->mapToBind([
                'titulo'            => $data['titulo'],
                'descricao'         => $data['descricao'],
                'valor'             => $data['valor'],
                'desconto'          => $data['desconto'],
                'data_desconto'     => $data['data_desconto'],
                'data_vencimento'   => $data['data_vencimento'],
                'cod_anuncio'       => $data['cod_anuncio']
            ])
        );

        return (bool) $result->rowCount();
    }

    public function deletarAnuncio(int $cod_anuncio): bool
    {
        $result = $this->query(
            "DELETE FROM anuncio 
                WHERE cod_anuncio = :cod_anuncio
            ",
            $this->mapToBind(['cod_anuncio' => $cod_anuncio])
        );

        return (bool) $result->rowCount();
    }

    public function getAnunciosPorUsuario(int $cod_usuario): array
    {
        $result = $this->query(
            "SELECT cod_anuncio, titulo, tipo 
                FROM anuncio
                WHERE cod_usuario = :cod_usuario AND (data_vencimento >= CURDATE() OR data_vencimento IS NULL) 
                ORDER BY data_anunciada DESC
            ",
            $this->mapToBind(['cod_usuario' => $cod_usuario])
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }

    public function getUltimos(int $quantidade): array
    {
        $result = $this->query(
            "SELECT a.cod_anuncio, a.titulo, a.descricao, p.foto_name 
                FROM anuncio a
                INNER JOIN produto p ON (a.cod_anuncio = p.cod_anuncio)
                WHERE (a.data_vencimento > CURDATE() OR a.data_vencimento IS NULL) 
                ORDER BY a.data_anunciada DESC
                LIMIT $quantidade
            "
        );

        $fetches = $result->fetchAll(PDO::FETCH_ASSOC);

        return ($result->rowCount()) ? $fetches : [];
    }
}
