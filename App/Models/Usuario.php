<?php

    namespace App\Models;

    use MF\Model\Model;
    use PDO;

    class Usuario extends Model {

        protected $cod_usuario;
		protected $nome;
		protected $data_nascimento;
		protected $telefone;
		protected $instituicao;
		protected $email;
		protected $senha;

        public function registrar() {
            $this->db->insert([
                "nome"               => $this->nome,
                "data_nascimento"    => $this->data_nascimento,
                "telefone"           => $this->telefone,
                "instituicao"        => $this->instituicao,
                "email"              => $this->email,
                "senha"              => password_hash($this->senha, PASSWORD_DEFAULT)
            ]);

            return true;
        }

        public function getUsuarioPorEmail() {
            $usuario = $this->db->select("email", "email = '{$this->email}'")->fetchAll(PDO::FETCH_ASSOC);

            return $usuario;
        }

        public function getPerfil() {
            $usuario = $this->db->select("nome, data_nascimento, telefone, instituicao, email")->fetch(PDO::FETCH_ASSOC);
            
            return $usuario;
        }

        public function autenticar() {
            $usuario = $this->db->select("cod_usuario, senha", "email = '{$this->email}'")->fetch(PDO::FETCH_ASSOC);

            if ( isset($usuario['cod_usuario']) && password_verify($this->senha, $usuario['senha']) ) {
                $this->__set("cod_usuario", $usuario["cod_usuario"]);

                return true;
            }

            return false;
        }

    }

?>