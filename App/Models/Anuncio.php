<?php

    namespace App\Models;

    use MF\Model\Model;
    use PDO;

    class Anuncio extends Model {

        protected $cod_anuncio;
        protected $cod_usuario;
		protected $titulo;
		protected $descricao;
		protected $status;
		protected $data_anunciada;
		protected $valor;
		protected $desconto;
		protected $data_desconto;
		protected $tipo;

        // Cadastrar Anuncio:
        public function inserirAnuncio() {
            $table = $this->db->getTable();
            $this->db->setTable("tb_anuncios");

            $cod_anuncio = $this->db->insert([
                "cod_usuario"       => $this->cod_usuario,
                "titulo"            => $this->titulo,
                "descricao"         => $this->descricao,
                "valor"             => $this->valor,
                "desconto"          => $this->desconto,
                "data_desconto"     => $this->data_desconto,
                "status"            => $this->status,
                "data_anunciada"    => date("Y-m-d H:i:s"),
                "tipo"              => $this->tipo,
            ]);

            $this->db->setTable($table);

            return $cod_anuncio;
        }

        // Atualizar Anuncio:

        // Excluir um anuncio:

        // Obter todos os anuncios de produto:

        // Obter todos os anuncios de monitoria:

        // Obter um anuncio em especifico:

        // Obter os últimos 5 anúncios:
        public function getUltimos($quantidade) {
            $table = $this->db->getTable();
            $this->db->setTable("tb_anuncios");

            $fields = "a.cod_anuncio, a.titulo, a.descricao, p.foto_name";
            $join   = "a inner join tb_produtos p on a.cod_anuncio = p.cod_anuncio";
            $order  = "a.data_anunciada desc";

            $ultimos = $this->db->select($fields, null, $order, $quantidade, $join)->fetchAll(PDO::FETCH_ASSOC);

            $this->db->setTable($table);

            return $ultimos;
        }

    }

?>