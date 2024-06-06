<?php

namespace MF\Controller;

use stdClass;

abstract class Action
{
    protected $view;
    protected $erro;

    public function __construct()
    {
        $this->view = new stdClass();
    }

    protected function render($view, $layout = "layout")
    {
        $this->view->page = $view;

        $this->view->login = $this->validaSessao();
        $this->view->admin = $this->validaAdmin();

        if (file_exists("../App/Views/{$layout}.phtml")) {
            require_once("../App/Views/{$layout}.phtml");
        } else {
            $this->content();
        }
    }

    protected function content()
    {
        $class = get_class($this);
        $class = str_replace("App\\Controllers\\", "", $class);
        $class = str_replace("Controller", "", $class);
        $class = strtolower($class);

        if (file_exists("../App/Views/{$class}/{$this->view->page}.phtml")) {
            require_once("../App/Views/{$class}/{$this->view->page}.phtml");
        }
    }

    public function formatarNumero($numero)
    {
        return str_replace(",", ".", str_replace(".", "", $numero));
    }

    public function sessao()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function validaSessao()
    {
        $this->sessao();

        if (!isset($_SESSION["cod_usuario"]) || empty($_SESSION["cod_usuario"])) {
            return false;
        }

        return true;
    }

    public function autenticarPagina($index = false, $admin = false)
    {
        if ($admin && !$this->validaAdmin()) {
            header("Location: /");
            exit;
        } else if ($this->validaSessao() && $index) {
            header("Location: /");
            exit;
        } else if (!$this->validaSessao() && !$index) {
            $_SESSION["tentativa_acesso"] = true;
            header("Location: /");
            exit;
        }
    }

    public function validaAdmin()
    {
        $this->sessao();

        if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            return true;
        }

        return false;
    }
}

?>