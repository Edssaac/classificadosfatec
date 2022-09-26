<?php

    namespace App\Models;

    use MF\Model\Model;
    use PDO;

    class Produto extends Anuncio {

        protected $fk_cod_anuncio;
		protected $foto_name;
		protected $foto_token;
		protected $estado;
		protected $operacao;
		protected $quantidade;

        // Cadastrar produto:
        public function anunciar() {
            $this->__set("status", 1);
            $this->__set("tipo", "P");

            $cod_anuncio = $this->inserirAnuncio();
            $this->__set("cod_anuncio", $cod_anuncio);

            $this->db->insert([
                "cod_anuncio"   => $this->cod_anuncio,
                "foto_name"     => $this->foto_name,
                "foto_token"    => $this->foto_token,
                "estado"        => $this->estado,
                "operacao"      => $this->operacao,
                "quantidade"    => $this->quantidade
            ]);

            return true;
        }

        // Atualizar produto:

        // Excluir produto:

        // Obter todos os produtos:

        // Obter um produto em especifico:

        // Obter os últimos 5 anúncios:

    }

?>