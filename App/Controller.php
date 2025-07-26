<?php

namespace App;

use stdClass;
use Library\Session;

abstract class Controller
{
    protected $view;
    protected $error;
    protected $user_id;

    public function __construct()
    {
        $this->view = new stdClass();

        $this->user_id = Session::getLoggedUser()['user_id'] ?? '';
    }

    protected function render(string $view, string $layout = 'layout'): void
    {
        $this->view->page = $view;
        $this->view->image_base_path = $_ENV['IMAGE_BASE_PATH'];

        if (!isset($this->view->search)) {
            $this->view->search = '';
        }

        if (!isset($this->view->filter)) {
            $this->view->filter = false;
        }

        $this->view->login = Session::isLogged();
        $this->view->admin = Session::isAdminstrator();

        if (file_exists("../App/View/{$layout}.php")) {
            require_once("../App/View/{$layout}.php");
        } else {
            $this->content();
        }
    }

    protected function content(): void
    {
        $class = get_class($this);
        $class = str_replace('App\\Controller\\', '', $class);
        $class = str_replace('Controller', '', $class);
        $class = strtolower($class);

        if (file_exists("../App/View/{$class}/{$this->view->page}.php")) {
            require_once("../App/View/{$class}/{$this->view->page}.php");
        }
    }

    protected function numberFormat(string $number): string
    {
        return str_replace(',', '.', str_replace('.', '', $number));
    }

    protected function authenticatePage(bool $index = false, bool $admin = false): void
    {
        if ($admin && !Session::isAdminstrator()) {
            header('Location: /');
            exit;
        } else if (Session::isLogged() && $index) {
            header('Location: /');
            exit;
        } else if (!Session::isLogged() && !$index) {
            $_SESSION['access_attempt'] = true;
            header('Location: /');
            exit;
        }
    }

    protected function output(array $content): void
    {
        header('Content-Type: application/json');
        echo json_encode($content);
        exit;
    }
}
