<?php

    namespace App\Models;

    use MF\Model\Model;
    use PDO;

    class Solicitacao extends Model {

        protected $cod_solicitacao;
        protected $cod_usuario;
		protected $titulo;
		protected $descricao;
		protected $data;
		protected $tipo;

        public function solicitar() {
            $this->db->insert([
                "cod_usuario"   => $this->cod_usuario,
                "titulo"        => $this->titulo,
                "descricao"     => $this->descricao,
                "data"          => date("Y-m-d H:i:s"),
                "tipo"          => $this->tipo
            ]);

            return true;
        }

        public function atualizar() {
            $this->db->update("cod_solicitacao = $this->cod_solicitacao", [
                "titulo"        => $this->titulo,
                "descricao"     => $this->descricao,
                //"data"          => date("Y-m-d H:i:s"),
                "tipo"          => $this->tipo
            ]);

            return true;
        }

        public function getSolicitacoes() {
            $fields = "s.cod_solicitacao, s.cod_usuario, u.nome, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.tipo";
            $join   = "s INNER JOIN tb_usuarios u ON s.cod_usuario = u.cod_usuario";
            $order  = "s.cod_solicitacao DESC";

            $solicitacoes = $this->db->select($fields, null, $order, null, $join)->fetchAll(PDO::FETCH_ASSOC);

            return $solicitacoes;
        }

        public function getSolicitacao($cod_solicitacao) {
            $fields = "s.cod_solicitacao, s.cod_usuario, u.nome, u.telefone, s.titulo, s.descricao, DATE_FORMAT(s.data, '%d/%m/%Y | %Hh%i') as data, s.tipo";
            $join   = "s INNER JOIN tb_usuarios u ON s.cod_usuario = u.cod_usuario";
            $where  = "s.cod_solicitacao = $cod_solicitacao";

            $solicitacao = $this->db->select($fields, $where, null, null, $join)->fetch(PDO::FETCH_ASSOC);

            return $solicitacao;
        }

        public function getSolicitacoesPorUsuario() {
            $fields = "cod_solicitacao, titulo, tipo";
            $where  = "cod_usuario = $this->cod_usuario";
            $order  = "data desc";

            $solicitacoes = $this->db->select($fields, $where, $order)->fetchAll(PDO::FETCH_ASSOC);

            return $solicitacoes;
        }

    }

?>