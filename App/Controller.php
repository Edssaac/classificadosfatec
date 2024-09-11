<?php

namespace App;

use stdClass;

abstract class Controller
{
    protected $view;
    protected $erro;

    public function __construct()
    {
        $this->view = new stdClass();
    }

    protected function render(string $view, string $layout = 'layout'): void
    {
        $this->view->page = $view;
        $this->view->image_base_path = $_ENV["IMAGE_BASE_PATH"];

        if (!isset($this->view->pesquisar)) {
            $this->view->pesquisar = '';
        }

        if (!isset($this->view->filtrar)) {
            $this->view->filtrar = false;
        }

        $this->view->login = $this->validaSessao();
        $this->view->admin = $this->validaAdmin();

        if (file_exists("../App/View/{$layout}.php")) {
            require_once("../App/View/{$layout}.php");
        } else {
            $this->content();
        }
    }

    protected function content(): void
    {
        $class = get_class($this);
        $class = str_replace("App\\Controller\\", "", $class);
        $class = str_replace("Controller", "", $class);
        $class = strtolower($class);

        if (file_exists("../App/View/{$class}/{$this->view->page}.php")) {
            require_once("../App/View/{$class}/{$this->view->page}.php");
        }
    }

    public function formatarNumero(string $numero): string
    {
        return str_replace(",", ".", str_replace(".", "", $numero));
    }

    public function sessao(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function validaSessao(): bool
    {
        $this->sessao();

        if (!isset($_SESSION["cod_usuario"]) || empty($_SESSION["cod_usuario"])) {
            return false;
        }

        return true;
    }

    public function autenticarPagina(bool $index = false, bool $admin = false): void
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

    public function validaAdmin(): bool
    {
        $this->sessao();

        if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            return true;
        }

        return false;
    }

    public function output(array $content): void
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }
}
