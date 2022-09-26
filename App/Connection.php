<?php

    /* CLASSE RESPONSÁVEL POR REALIZAR A CONEXÃO COM O BANCO DE DADOS.*/

    namespace App;
    use \PDO;
    use \PDOException;

    // Host de conexão com o banco de dados:
    define('HOST', getenv('DB_HOST'));
    // Nome do banco de dados:
    define('NAME', getenv('DB_NAME'));
    // Usuário do banco de dados:
    define('USER', getenv('DB_USER'));
    // Senha de acesso ao banco de dados:
    define('PASS', getenv('DB_PASS'));

    class Connection {
        // Nome da tabela a ser manipulada:
        private $table;

        // Instância de conexão com o banco de dados:
        private $connection;

        // Define a tabela e instância a conexão:
        public function __construct( $table = null ) {
            $this->table = $table;
            $this->setConnection();
        }

        // Método responsável por alterar a tabela atual:
		public function setTable($table) {
			$this->table = $table;
		}

        // Método responsável por devolver a tabela atual:
		public function getTable() {
			return $this->table;
		}

        // Método responsável por criar uma conexão com o banco de dados:
        private function setConnection() {   
            try {
                // TENTANDO FAZER A CONEXÃO COM O BANCO DE DADOS:
                $this->connection = new PDO( 'mysql:host='.HOST.';dbname='.NAME, USER, PASS );
                $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } catch ( PDOException $e ) {
                die("EROR: ". $e->getMessage() );
            }
        }

        // Método responsável por executar as queries dentro do banco de dados:
        public function execute( $query, $params = [] ) {
            try {
                $statement = $this->connection->prepare($query);
                $statement->execute($params);
                return $statement;
            } catch ( PDOException $e ) {
                die("EROR: ". $e->getMessage() );
            }
        }

        // Método responsável por inserir dados no banco:
        public function insert( $values ) {
            // COLETANDO OS DADOS PARA A QUERY:
            $fields = array_keys($values);
            $binds = array_pad( [], count($fields), "?" );

            // QUERY BUILDER:
            $query = 'INSERT INTO '.$this->table.' ('.implode(",", $fields).') VALUES ('.implode(",", $binds).')';

            // EXECUTA O INSERT:
            $this->execute( $query, array_values($values) );

            // RETORNA O ID INSERIDO:
            return $this->connection->lastInsertId();
        }

        // Método responsável por executar uma consulta no banco de dados:
        public function select( $fields="*", $where=null, $order=null, $limit=null ) {
            // COLETANDO OS DADOS PARA A QUERY:
            $where = strlen($where) ? 'WHERE '.$where : '';
            $order = strlen($order) ? 'ORDER BY '.$order : '';
            $limit = strlen($limit) ? 'LIMIT '.$limit : '';

            // QUERY BUILDER:
            $query = 'SELECT '.$fields.' FROM '.$this->table.' '.$where.' '.$order.' '.$limit;

            // EXECUTA A QUERY:
            return $this->execute($query);
        }

        // Método responsável por executar atualizações no banco de dados:
        public function update( $where, $values ) {
            // COLETANDO OS DADOS PARA A QUERY:
            $fields = array_keys($values);

            // QUERY BUILDER:
            $query = 'UPDATE '.$this->table.' SET '.implode('=?, ', $fields).'=? WHERE '.$where;
            
            // EXECUTA A QUERY:
            $this->execute( $query, array_values($values) );

            // RETORNANDO SUCESSO:
            return true;
        }

        // Método responsável por deletar um registro do banco de dados:
        public function delete( $where ) {
            //QUERY BUILDER:
            $query = 'DELETE FROM '.$this->table.' WHERE '.$where;

            $this->execute($query);

            // RETORNANDO SUCESSO:
            return true;
        }

    }

?>