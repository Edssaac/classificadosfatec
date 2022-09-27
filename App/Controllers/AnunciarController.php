<?php

    namespace App\Controllers;

    use MF\Controller\Action;
    use MF\Controller\Upload;
    use MF\Model\Container;

    class AnunciarController extends Action {

        public function produto() {
            $this->autenticarPagina();
            $this->render("produto");
        }

        public function monitoria() {
            $this->autenticarPagina();
            $this->render("monitoria");
        }

        public function cadastrar_monitoria() {
            $this->autenticarPagina();

            $sucesso = true;
            $monitoria = Container::getModel("Monitoria");
            $horarios = [];

            foreach ($_POST["dia_semana"] as $key => $dia) {
                $horarios[$key] = [
                    "dia"   => $dia,
                    "de"    => $_POST["de_horario"][$key],
                    "ate"   => $_POST["ate_horario"][$key]
                ];
            }

            $monitoria->__set("cod_usuario",    $_SESSION["cod_usuario"]);
            $monitoria->__set("titulo",         $_POST["titulo"]);
            $monitoria->__set("descricao",      $_POST["descricao"]);
            $monitoria->__set("valor",          $this->formatarNumero($_POST["valor"]));
            $monitoria->__set("desconto",       $this->formatarNumero($_POST["desconto"]));
            $monitoria->__set("data_desconto",  $_POST["data_desconto"]);
            $monitoria->__set("horarios",       json_encode($horarios));
            $monitoria->__set("materia",        $_POST["materia"]);

            if ( !$monitoria->anunciar() ) {
                $sucesso = false;
                $this->erro = "Não foi possível cadastrar anúncio!";
            }
            
            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        }

        public function cadastrar_produto() {
            $this->autenticarPagina();

            $sucesso = true;
            $produto = Container::getModel("Produto");
            $upload = new Upload();

            $foto = $upload->uploadImagem($_FILES);

            echo "<pre>";
            print_r($_FILES);
            var_dump($foto);
            echo "<pre>";
            die("--");

            if ( !$foto ) {
                $sucesso = false;
                $this->erro = "Não foi possível salvar as informações do anúncio!";
            } else {
                $produto->__set("cod_usuario",    $_SESSION["cod_usuario"]);
                $produto->__set("titulo",         $_POST["titulo"]);
                $produto->__set("descricao",      $_POST["descricao"]);
                $produto->__set("valor",          $this->formatarNumero($_POST["valor"]));
                $produto->__set("desconto",       $this->formatarNumero($_POST["desconto"]));
                $produto->__set("data_desconto",  $_POST["data_desconto"]);
                $produto->__set("foto_name",      $foto["name"]);
                $produto->__set("foto_token",     $foto["sha"]);
                $produto->__set("estado",         $_POST["estado"]);
                $produto->__set("operacao",       $_POST["operacao"]);
                $produto->__set("quantidade",     $_POST["quantidade"]);
    
                if ( !$produto->anunciar() ) {
                    $sucesso = false;
                    $this->erro = "Não foi possível cadastrar anúncio!";
                }
            }

            header('Content-Type: application/json');
            echo json_encode([
                "sucesso"   => $sucesso,
                "mensagem"  => $this->erro
            ]);
        }

        public function formatarNumero($numero) {
            return str_replace(",", ".", str_replace(".", "", $numero));
        }

    }

?>