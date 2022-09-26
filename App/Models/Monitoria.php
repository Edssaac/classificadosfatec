<?php

    namespace App\Models;

    use MF\Model\Model;
    use PDO;

    class Monitoria extends Anuncio {

        protected $fk_cod_anuncio;
		protected $materia;
		protected $horarios;

        // Cadastrar monitoria:
        public function anunciar() {
            $this->__set("status", 1);
            $this->__set("tipo", "M");

            $cod_anuncio = $this->inserirAnuncio();
            $this->__set("cod_anuncio", $cod_anuncio);

            $this->db->insert([
                "cod_anuncio"   => $this->cod_anuncio,
                "materia"       => $this->materia,
                "horarios"      => $this->horarios
            ]);

            return true;
        }

        // Atualizar monitoria:

        // Excluir monitoria:

        // Obter todas as monitorias:

        // Obter uma monitoria em especifico:

        // Obter os últimos 5 anúncios:

    }

?>