<?php

namespace App;

use PDO;
use PDOStatement;
use PDOException;

/**
 * Classe base para conexões com o banco de dados.
 */
class Model
{
    private $connection;

    /**
     * Método responsável por realizar a conexão com o banco de dados.
     *  
     * @throws PDOException
     */
    public function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    /**
     * Método responsável por realizar uma consulta no banco de dados.
     *  
     * @param string $query
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
    protected function query(string $query, array $params = []): PDOStatement
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage());
        }
    }

    /**
     * Método responsável retornar o último ID inserido.
     *  
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * Método responsável criar um array associativo prefixado.
     *  
     * @param array $data
     * @return array
     */
    protected function mapToBind(array $data): array
    {
        $map = [];

        foreach ($data as $key => $value) {
            $map[':' . $key] = filter_var($value);
        }

        return $map;
    }
}
